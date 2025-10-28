<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\UserController\Controller;
use App\Models\UserModels\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * 1️⃣ Lấy danh sách mã khuyến mãi còn hiệu lực
     */
    public function getActivePromotions()
    {
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('used_count', '<', 'limit_count')
            ->get(['id', 'code', 'description', 'type', 'value']); // chỉ lấy trường cần thiết

        return response()->json($promotions);
    }

    /**
     * 2️⃣ Áp dụng mã khuyến mãi (AJAX)
     */
    public function applyPromotion(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0'
        ]);

        $promotion = Promotion::where('code', $request->code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại.'
            ]);
        }

        if (!$promotion->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không còn hiệu lực.'
            ]);
        }

        $discount = $promotion->calculateDiscount($request->total);
        $final = $request->total - $discount;

        return response()->json([
            'success' => true,
            'discount' => round($discount, 0),
            'final' => round($final, 0),
            'message' => 'Áp dụng khuyến mãi thành công!'
        ]);
    }

    /**
     * 3️⃣ Cập nhật số lượt dùng sau khi thanh toán thành công
     */
    public function markAsUsed($promotionCode)
    {
        $promotion = Promotion::where('code', $promotionCode)->first();

        if ($promotion && $promotion->isValid()) {
            $promotion->increment('used_count');
        }

        return response()->json(['message' => 'Đã ghi nhận lượt sử dụng mã.']);
    }
}
