<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;

use App\Models\UserModels\Order;
use App\Models\UserModels\Customer;
use App\Models\ProductModels\Seat;

class OrderController extends Controller
{
    /**
     * API tạo đơn hàng mới
     */
    public function createOrder(Request $request)
    {
        try {
            $order = Order::create([
                'order_code' => strtoupper(uniqid('MB')),
                'seats'      => json_encode($request->seats),
                'amount'     => $request->amount,
                'status'     => 'pending'
            ]);

            $userId   = auth()->id();    
            $customer = Customer::firstOrCreate(
                ['user_id' => $userId],
                ['customer_name' => auth()->user()->name ?? '']
            );

            // 👉 cập nhật ghế sang trạng thái held
            foreach ($request->seats as $seatId) {
                Seat::where('seatID', $seatId)->update(['status' => 'held']);
            }

            return response()->json([
                'order_code' => $order->order_code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
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

        // decode danh sách ghế
        $seats = json_decode($order->seats, true) ?? [];

        foreach ($seats as $seatId) {
            $seat = Seat::find($seatId);
            if ($seat && $seat->status === 'held') {
                $seat->status = 'available';
                $seat->save();
            }
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
                    $order->update(['status' => 'paid']);

                    $seats = json_decode($order->seats, true);
                    if (is_array($seats)) {
                        foreach ($seats as $seatId) {
                            Seat::where('seatID', $seatId)->update(['status' => 'unavailable']);
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
