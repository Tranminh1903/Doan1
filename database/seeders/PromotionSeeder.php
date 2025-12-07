<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $promotions = [
            // 1. GIAM15 - giảm 15% tối đa 30k, đơn từ 120k, tối thiểu 2 vé
            [
                'code'               => 'GIAM15',
                'type'               => 'percent',
                'value'              => 15.00,
                'limit_count'        => 500,
                'used_count'         => 0,
                'min_order_value'    => 120000.00,
                'min_ticket_quantity'=> 2,
                'start_date'         => $now->copy()->startOfDay(),
                'end_date'           => $now->copy()->addDays(30)->endOfDay(),
                'status'             => 'active',
                'description'        => 'Giảm 15% tối đa 30.000đ cho đơn từ 120.000đ, áp dụng từ 2 vé trở lên.',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 2. TUANMOI50K - giảm thẳng 50k, đơn từ 200k, 3 vé trở lên
            [
                'code'               => 'TUANMOI50K',
                'type'               => 'fixed',
                'value'              => 50000.00,
                'limit_count'        => 300,
                'used_count'         => 0,
                'min_order_value'    => 200000.00,
                'min_ticket_quantity'=> 3,
                'start_date'         => $now->copy()->startOfWeek()->startOfDay(),
                'end_date'           => $now->copy()->addWeeks(2)->endOfWeek()->endOfDay(),
                'status'             => 'active',
                'description'        => 'Giảm thẳng 50.000đ cho hóa đơn từ 200.000đ, áp dụng từ 3 vé trở lên.',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 3. THUSINHVIEN - 20% tối đa 40k, đơn từ 70k, 1 vé
            [
                'code'               => 'THUSINHVIEN',
                'type'               => 'percent',
                'value'              => 20.00,
                'limit_count'        => 1000,
                'used_count'         => 0,
                'min_order_value'    => 70000.00,
                'min_ticket_quantity'=> 1,
                'start_date'         => $now->copy()->startOfDay(),
                'end_date'           => $now->copy()->addMonths(2)->endOfDay(),
                'status'             => 'active',
                'description'        => 'Ưu đãi 20% tối đa 40.000đ cho hóa đơn từ 70.000đ. Dành cho khách hàng là học sinh, sinh viên (kiểm tra tại quầy).',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 4. COUPLE30 - 30% tối đa 60k, tối thiểu 2 vé
            [
                'code'               => 'COUPLE30',
                'type'               => 'percent',
                'value'              => 30.00,
                'limit_count'        => 400,
                'used_count'         => 0,
                'min_order_value'    => 0,
                'min_ticket_quantity'=> 2,
                'start_date'         => $now->copy()->startOfDay(),
                'end_date'           => $now->copy()->addMonths(1)->endOfDay(),
                'status'             => 'active',
                'description'        => 'Giảm 30% tối đa 60.000đ cho đơn có từ 2 vé trở lên. Rất hợp đi xem phim đôi.',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 5. MIDNIGHT40 - 40% tối đa 70k cho suất chiếu sau 22h, đơn từ 100k
            [
                'code'               => 'MIDNIGHT40',
                'type'               => 'percent',
                'value'              => 40.00,
                'limit_count'        => 200,
                'used_count'         => 0,
                'min_order_value'    => 100000.00,
                'min_ticket_quantity'=> 1,
                'start_date'         => $now->copy()->startOfDay(),
                'end_date'           => $now->copy()->addMonths(1)->endOfDay(),
                'status'             => 'active',
                'description'        => 'Giảm 40% tối đa 70.000đ cho đơn từ 100.000đ, áp dụng cho các suất chiếu sau 22:00.',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ];
        DB::table('promotion')->upsert(
            $promotions,
            ['code'], // cột dùng để xác định trùng
            [
                'type',
                'value',
                'limit_count',
                'used_count',
                'min_order_value',
                'min_ticket_quantity',
                'start_date',
                'end_date',
                'status',
                'description',
                'created_at',
                'updated_at',
            ]
        );
    }
}