<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\UserController\Controller;

use Illuminate\Support\Str;
use App\Mail\TicketPaidMail;
use Illuminate\Http\Request;
use App\Models\UserModels\Customer;
use App\Models\UserModels\User;
use App\Models\UserModels\Order;
use App\Models\ProductModels\SeatHold;
use App\Models\ProductModels\Showtime;
use App\Models\ProductModels\MovieTheater;
use App\Models\ProductModels\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Events\SeatStatusChanged;

class OrderController extends Controller
{
    // ==== API tạo đơn hàng mới ==== //

    public function createOrder(Request $request)
    {
        try {
            if (!$request->has('showtimeID')) {
                return response()->json([
                    'error' => 'Thiếu showtimeID trong request'
                ], 422);
            }
            $user = auth()->user();

            // ==== Tạo đơn hàng ==== //

            $order = Order::create([
                'showtimeID' => $request->showtimeID,
                'order_code' => strtoupper(uniqid('MB')),
                'username'   => $user->username ?? $user->name ?? 'unknown',
                'seats'      => json_encode($request->seats),
                'amount'     => $request->amount,
                'status'     => 'pending',
            ]);



            $userId = auth()->id();
            $customer = Customer::firstOrCreate(
                ['user_id' => $userId],
                ['customer_name' => auth()->user()->name ?? '']
            );
            // ==== Giữ ghế trong 5 phút ==== // 
            foreach ($request->seats as $seatId) {
                SeatHold::updateOrCreate(
                    [
                        'showtimeID' => $request->showtimeID,
                        'seatID'     => $seatId,
                    ],
                    [
                        'user_id'    => $userId,
                        'orderID'    => $order->id,
                        'status'     => 'held',
                        'expires_at' => now()->addMinutes(5),
                    ]
                );
                // ==== Phát realtime event cho frontend ==== //
                broadcast(new \App\Events\SeatStatusUpdated(
                    $request->showtimeID,
                    $request->seats,
                    'held'
                ));


                \Log::info(" SeatHeldEvent fired for seat $seatId showtime {$request->showtimeID}");
            }

            return response()->json([
                'order_code' => $order->order_code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // ==== API kiểm tra trạng thái thanh toán ==== //
    public function checkPayment($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json(['status' => $order->status]);
    }
    // ==== API hủy đơn khi hết hạn ==== //

    public function expire($orderCode)
    {
        try {
            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            // ==== Chỉ xử lý nếu đơn chưa thanh toán ==== //
            if ($order->status !== 'paid') {
                $order->update(['status' => 'cancelled']);

                $seats = json_decode($order->seats, true) ?? [];

                foreach ($seats as $seatId) {
                    SeatHold::where('orderID', $order->id)
                        ->where('seatID', $seatId)
                        ->update([
                            'status'     => 'available',
                            'expires_at' => null
                        ]);
                }
                // ==== Phát realtime event cho tất cả client khác ==== // 
                $seatHold = SeatHold::where('orderID', $order->id)->first();

                if ($seatHold) {
                    $showtimeID = $seatHold->showtimeID;
                    broadcast(new \App\Events\SeatStatusUpdated(
                        $showtimeID,
                        $seats,
                        'available'
                    ))->toOthers();
                }



                \Log::info("Order {$order->order_code} hết hạn, ghế đã được release realtime", [
                    'released_seats' => $seats
                ]);

                return response()->json([
                    'message' => 'Order expired and seats released successfully',
                    'released_seats' => $seats
                ]);
            }

            return response()->json(['message' => 'Order already paid, no action needed']);
        } catch (\Exception $e) {
            \Log::error("Lỗi expire order {$orderCode}: " . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
    // ==== API đồng bộ giao dịch từ Google Sheet ==== //
    public function syncPayments()
    {
        try {
            $url = "https://script.google.com/macros/s/AKfycbzaD9M8fnGXLQnNTKdr4ubPAixSI8_6cj-Z-eP4TaMPgusZ-K8_c2reSGDUalhyQJ0u/exec";
            $response = Http::get($url);

            if ($response->failed()) {
                return response()->json(['error' => 'Không kết nối được Google Sheet'], 500);
            }

            $transactions = $response->json();

            if (!is_array($transactions)) {
                Log::error(" Sai format dữ liệu từ Google Sheet", $transactions);
                return response()->json(['error' => 'Sai dữ liệu từ Google Sheet'], 500);
            }

            foreach ($transactions as $tx) {
                $note = $tx['Nội dung thanh toán'] ?? null;
                $paidAmount = isset($tx['Số tiền'])
                    ? floatval(str_replace([',', '.'], '', preg_replace('/[^\d,\.]/', '', $tx['Số tiền'])))
                    : 0;

                if (!$note || $paidAmount <= 0) continue;

                $orders = Order::where('status', 'pending')->get();

                foreach ($orders as $order) {
                    // ==== So khớp order_code trong nội dung và số tiền khớp chính xác ==== //
                    if (str_contains($note, $order->order_code) && intval($order->amount) == intval($paidAmount)) {

                        $order->update(['status' => 'paid']);

                        $seats = json_decode($order->seats, true);
                        if (is_array($seats)) {
                            foreach ($seats as $seatId) {
                                SeatHold::where('orderID', $order->id)
                                    ->where('seatID', $seatId)
                                    ->update([
                                        'status' => 'unavailable',
                                        'expires_at' => null
                                    ]);

                                Ticket::updateOrCreate(
                                    [
                                        'showtimeID' => $order->showtimeID,
                                        'seatID' => $seatId,
                                    ],
                                    [
                                        'price' => $order->amount / count($seats),
                                        'status' => 'issued',
                                        'qr_token' => (string) Str::uuid(),
                                        'order_code' => $order->order_code,
                                        'issueAt' => now(),
                                        'refund_reason' => null,
                                    ]
                                );
                            }

                            $seatHold = SeatHold::where('orderID', $order->id)->first();
                            $showtimeID = $seatHold ? $seatHold->showtimeID : null;

                            if ($showtimeID) {
                                broadcast(new \App\Events\SeatStatusUpdated(
                                    $showtimeID,
                                    $seats,
                                    'unavailable'
                                ));
                            }

                            // ==== Gửi email ==== //
                            $showtime = Showtime::with('movie')->find($order->showtimeID ?? null);
                            $cinema = $showtime ? MovieTheater::find($showtime->theaterID ?? null) : null;

                            if ($showtime && $cinema) {
                                try {
                                    $seatIDs = json_decode($order->seats, true);
                                    $seatsFormatted = \App\Models\ProductModels\Seat::whereIn('seatID', $seatIDs)
                                        ->select('verticalRow', 'seatID')
                                        ->get()
                                        ->map(fn($s) => $s->verticalRow . $s->seatID)
                                        ->toArray();

                                    Mail::to(auth()->user()->email)->send(new TicketPaidMail(
                                        $order,
                                        $showtime,
                                        $cinema,
                                        $seatsFormatted
                                    ));

                                    \Log::info("Đã gửi mail vé cho đơn {$order->order_code}");
                                } catch (\Exception $mailError) {
                                    \Log::error("Gửi mail lỗi: " . $mailError->getMessage());
                                }
                            }
                        }

                        \Log::info(" Đổi trạng thái: {$order->order_code} thành PAID (note: $note, amount: $paidAmount)");
                    }
                }
            }


            return response()->json(['message' => 'Đã đồng bộ giao dịch từ Google Sheet']);
        } catch (\Exception $e) {
            \Log::error(" Lỗi syncPayments: " . $e->getMessage());
            return response()->json(['error' => 'Lỗi xử lý dữ liệu'], 500);
        }
    }
}
