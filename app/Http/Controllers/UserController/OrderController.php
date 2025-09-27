<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;

use App\Models\UserModels\Order;
use App\Models\UserModels\Customer;
use App\Models\ProductModels\SeatHold;

class OrderController extends Controller
{
    /**
     * API tạo đơn hàng mới
     */
    public function createOrder(Request $request)
{
    try {
        // ⚡ kiểm tra bắt buộc phải có showtimeID
        if (!$request->has('showtimeID')) {
            return response()->json([
                'error' => 'Thiếu showtimeID trong request'
            ], 422);
        }

        $order = Order::create([
            'order_code' => strtoupper(uniqid('MB')),
            'seats'      => json_encode($request->seats),
            'amount'     => $request->amount,
            'status'     => 'pending',
        ]);

        $userId   = auth()->id();
        $customer = Customer::firstOrCreate(
            ['user_id' => $userId],
            ['customer_name' => auth()->user()->name ?? '']
        );

        // 👉 giữ ghế ở trạng thái held trong bảng seat_holds
        foreach ($request->seats as $seatId) {
            SeatHold::updateOrCreate(
                [
                    'showtimeID' => $request->showtimeID, // ✅ lấy từ request chứ không từ seat
                    'seatID'     => $seatId,
                ],
                [
                    'user_id'    => $userId,
                    'orderID'    => $order->id,
                    'status'     => 'held',
                    'expires_at' => now()->addMinutes(10), // ghế sẽ auto hết hạn sau 10p
                ]
            );
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



    /**
     * API kiểm tra trạng thái thanh toán
     */
    public function checkPayment($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json(['status' => $order->status]);
    }

    /**
     * API hủy đơn khi hết hạn
     */
    public function expire($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'paid') {
            $order->status = 'cancelled';
            $order->save();

            $seats = json_decode($order->seats, true) ?? [];
            foreach ($seats as $seatId) {
                SeatHold::where('orderID', $order->id)
                    ->where('seatID', $seatId)
                    ->where('status', 'held')
                    ->update(['status' => 'available', 'expires_at' => null]);
            }
        }

        return response()->json(['message' => 'Order expired, seats released']);
    }

    /**
     * API đồng bộ giao dịch từ Google Sheet
     */
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
                \Log::error("❌ Sai format dữ liệu từ Google Sheet", $transactions);
                return response()->json(['error' => 'Sai dữ liệu từ Google Sheet'], 500);
            }

            foreach ($transactions as $tx) {
                $note = $tx['Nội dung thanh toán'] ?? null;
                if (!$note) continue;

                $orders = Order::where('status', 'pending')->get();

                foreach ($orders as $order) {
                    if (str_contains($note, $order->order_code)) {
                        $order->update(['status' => 'paid', 'paid_at' => now()]);

                        $seats = json_decode($order->seats, true);
                        if (is_array($seats)) {
                            foreach ($seats as $seatId) {
                                SeatHold::where('orderID', $order->id)
                                    ->where('seatID', $seatId)
                                    ->update([
                                        'status'     => 'unavailable',
                                        'expires_at' => null
                                    ]);
                            }
                        }

                        \Log::info("✅ Đổi trạng thái: {$order->order_code} thành PAID (note: $note)");
                    }
                }
            }

            return response()->json(['message' => 'Đã đồng bộ giao dịch từ Google Sheet']);
        } catch (\Exception $e) {
            \Log::error("❌ Lỗi syncPayments: ".$e->getMessage());
            return response()->json(['error' => 'Lỗi xử lý dữ liệu'], 500);
        }
    }
}
