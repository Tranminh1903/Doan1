<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeatsSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('seat_holds')->delete();
        DB::table('orders')->delete();

        $layout = [
            1 => 9,
            2 => 8,
            3 => 5,
            4 => 6,
            5 => 8,
        ];

        $now = Carbon::now();
        $seats = [];

        foreach ($layout as $theaterID => $rowCount) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                $verticalRow = chr(ord('A') + $rowIndex);

                // Hàng A là VIP, còn lại normal
                $seatType = ($verticalRow === 'A') ? 'vip' : 'normal';
                $price    = ($seatType === 'vip') ? 3000.00 : 2000.00;

                // 10 ghế mỗi hàng
                for ($col = 1; $col <= 10; $col++) {
                    $seats[] = [
                        'theaterID'     => $theaterID,
                        'verticalRow'   => $verticalRow,
                        'horizontalRow' => $col,
                        'seatType'      => $seatType,
                        'status'        => 'available',
                        'price'         => $price,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
            }
        }

        DB::table('seats')->insert($seats);
    }
}
