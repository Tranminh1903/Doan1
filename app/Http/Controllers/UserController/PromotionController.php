<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\UserController\Controller;
use App\Models\UserModels\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // ==== Lấy danh sách mã khuyến mãi còn hiệu lực ==== //
    public function getActivePromotions()
    {
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('used_count', '<', 'limit_count')
            ->get([
                'id',
                'code',
                'description',
                'type',
                'value',
                'min_order_value',
                'min_ticket_quantity'
            ]);

        return response()->json($promotions);
    }
    // ==== Áp dụng mã khuyến mãi (AJAX) ==== //

    public function applyPromotion(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0',
            'seat_count' => 'nullable|integer|min:0'
        ]);

        $promotion = Promotion::where('code', $request->code)->first();

        // ❌ Không tìm thấy mã
        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại.'
            ]);
        }

        // ❌ Hết hạn hoặc ngưng hoạt động
        if (!$promotion->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không còn hiệu lực hoặc đã đạt giới hạn sử dụng.'
            ]);
        }

        // 🔹 Kiểm tra điều kiện tối thiểu
        if ($promotion->min_order_value && $request->total < $promotion->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải có giá trị tối thiểu ' .
                    number_format($promotion->min_order_value, 0, ',', '.') . ' VND để sử dụng mã này.'
            ]);
        }

        if ($promotion->min_ticket_quantity && $request->seat_count < $promotion->min_ticket_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đặt ít nhất ' .
                    $promotion->min_ticket_quantity . ' ghế để áp dụng mã này.'
            ]);
        }

        // ✅ Tính giảm giá
        $discount = $promotion->calculateDiscount($request->total);
        $final = $request->total - $discount;

        return response()->json([
            'success' => true,
            'discount' => round($discount, 0),
            'final' => round($final, 0),
            'message' => 'Áp dụng khuyến mãi thành công!'
        ]);
    }

    // ==== Cập nhật số lượt dùng sau khi thanh toán thành công ==== //
    public function markAsUsed($promotionCode)
    {
        $promotion = Promotion::where('code', $promotionCode)->first();

        if ($promotion && $promotion->isValid()) {
            $promotion->increment('used_count');
        }

        return response()->json(['message' => 'Đã ghi nhận lượt sử dụng mã.']);
    }
}
