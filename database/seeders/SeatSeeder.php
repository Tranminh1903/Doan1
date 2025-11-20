<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $now  = Carbon::now();
        $rows = [];

        // Giá ghế
        $vipPrice     = 3000;
        $normalPrice  = 2000;
        $couplePrice  = 5000; // nếu bạn muốn giá couple khác, sửa tại đây

        /* ===== THEATER 1 ===== */
        // VIP row A
        foreach (range(1,10) as $col) {
            $rows[] = [
                'theaterID'     => 1,
                'verticalRow'   => 'A',
                'horizontalRow' => $col,
                'seatType'      => 'vip',
                'price'         => $vipPrice,
                'status'        => 'available',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        // NORMAL rows B, C
        foreach (['B','C'] as $r) {
            foreach (range(1,10) as $col) {
                $rows[] = [
                    'theaterID'     => 1,
                    'verticalRow'   => $r,
                    'horizontalRow' => $col,
                    'seatType'      => 'normal',
                    'price'         => $normalPrice,
                    'status'        => 'available',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }

        // COUPLE row D
        foreach (range(1,10) as $col) {
            $rows[] = [
                'theaterID'     => 1,
                'verticalRow'   => 'D',
                'horizontalRow' => $col,
                'seatType'      => 'couple',
                'price'         => $couplePrice,
                'status'        => 'available',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        /* ===== THEATER 2 ===== */
        // VIP row A
        foreach (range(1,10) as $col) {
            $rows[] = [
                'theaterID'     => 2,
                'verticalRow'   => 'A',
                'horizontalRow' => $col,
                'seatType'      => 'vip',
                'price'         => $vipPrice,
                'status'        => 'available',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        // NORMAL row B
        foreach (range(1,10) as $col) {
            $rows[] = [
                'theaterID'     => 2,
                'verticalRow'   => 'B',
                'horizontalRow' => $col,
                'seatType'      => 'normal',
                'price'         => $normalPrice,
                'status'        => 'available',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        /* ===== UPSERT ===== */
        DB::table('seats')->upsert(
            $rows,
            ['theaterID','verticalRow','horizontalRow'], // khóa
            ['seatType','price','status','updated_at']    // update khi trùng
        );
    }
}
