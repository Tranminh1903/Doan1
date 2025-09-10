<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
class CustomerController extends Controller
{
public function showProfile(Request $request): View
    {
        // Lấy customer kèm quan hệ user
        $customer = Customer::with('user')->where('user_id', $request->user()->id)->firstOrFail();

        // Demo dữ liệu phụ để render UI (tùy dự án của bạn thay bằng query thực)
        $member = [
            'tier'   => $customer->tier ?? 'Member',
            'points' => 1540,
            'next_tier_name' => 'Gold',
            'next_tier_need' => 460, // còn 460 điểm lên Gold
            'benefits' => [
                'Ưu tiên đặt vé sớm',
                'Giảm 10% bắp nước',
                'Miễn phí vé sinh nhật (1 vé)'
            ]
        ];

        $vouchers = [
            ['code' => 'CGV-SN-2025', 'desc' => 'Vé sinh nhật 2D', 'exp' => '2025-12-31', 'status' => 'active'],
            ['code' => 'POP-10', 'desc' => 'Giảm 10% combo bắp nước', 'exp' => '2025-10-15', 'status' => 'active'],
            ['code' => 'WEEKEND50', 'desc' => 'Giảm 50k cuối tuần', 'exp' => '2025-09-30', 'status' => 'used'],
        ];

        $bookings = [
            [
                'code' => 'ORD20250910-0001',
                'movie' => 'Dune: Part Two',
                'format' => '2D | Atmos',
                'seats' => 'H7, H8',
                'cinema' => 'CGV Vincom Đồng Khởi',
                'showtime' => '2025-09-12 19:30',
                'status' => 'paid',
                'total' => 260000,
            ],
            [
                'code' => 'ORD20250822-0023',
                'movie' => 'Inside Out 2',
                'format' => '2D | Lồng tiếng',
                'seats' => 'C5',
                'cinema' => 'CGV Crescent Mall',
                'showtime' => '2025-08-24 15:10',
                'status' => 'paid',
                'total' => 120000,
            ],
        ];

        return view('customers.profile', compact('customer', 'member', 'vouchers', 'bookings'));
    }
}