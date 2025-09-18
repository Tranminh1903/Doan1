<?php

namespace App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;

use App\Models\UserModels\Order;
use App\Models\UserModels\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;

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
                'amount' => $request->amount,
                'status'     => 'pending'
            ]);
            $userId   = auth()->id();    
            $customer = Customer::firstOrCreate(
                ['user_id' => $userId],
                ['customer_name' => auth()->user()->name ?? '']
            );
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
    public function expire($orderCode)
    {
    $order = Order::where('order_code', $orderCode)->first();

    if ($order && $order->status === 'pending') {
        $order->update(['status' => 'cancelled']);
    }

    return response()->json(['success' => true]);
    }
 
    /**
     * API đồng bộ giao dịch từ Google Sheet
     */
    public function syncPayments()
{
    $url = "https://script.google.com/macros/s/AKfycbzaD9M8fnGXLQnNTKdr4ubPAixSI8_6cj-Z-eP4TaMPgusZ-K8_c2reSGDUalhyQJ0u/exec";
    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json(['error' => 'Không kết nối được Google Sheet'], 500);
    }

    $transactions = $response->json();

    foreach ($transactions as $tx) {
        // lấy đúng key cột F
        $note = $tx['Nội dung thanh toán'] ?? null;

        if (!$note) {
            continue;
        }

        // lấy danh sách order pending
        $orders = Order::where('status', 'pending')->get();

        foreach ($orders as $order) {
            // nếu order_code nằm trong nội dung thanh toán
            if (str_contains($note, $order->order_code)) {
                $order->status = 'paid';
                $order->save();

                \Log::info("✅ Đổi trạng thái: {$order->order_code} thành PAID (note: $note)");
            }
        }
    }

    return response()->json(['message' => 'Đã đồng bộ giao dịch từ Google Sheet']);
}
}
