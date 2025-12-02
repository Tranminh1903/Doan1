<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowtimeSeeder extends Seeder
{
    public function run(): void
    {
        $showtime =[
            // ===== Các suất chiếu lấy từ Showtime.sql gốc =====
            [
                'movieID'    => 7,
                'theaterID'  => 1,
                'startTime'  => '2025-12-02 16:10:00',
                'endTime'    => '2025-12-02 19:25:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:07:06',
                'updated_at' => '2025-12-02 12:07:06',
            ],
            [
                'movieID'    => 6,
                'theaterID'  => 1,
                'startTime'  => '2025-12-05 10:10:00',
                'endTime'    => '2025-12-05 11:40:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:07:31',
                'updated_at' => '2025-12-02 12:07:31',
            ],
            [
                'movieID'    => 5,
                'theaterID'  => 2,
                'startTime'  => '2025-12-04 22:10:00',
                'endTime'    => '2025-12-04 23:35:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:07:55',
                'updated_at' => '2025-12-02 12:07:55',
            ],
            [
                'movieID'    => 2,
                'theaterID'  => 3,
                'startTime'  => '2025-12-10 19:10:00',
                'endTime'    => '2025-12-10 21:00:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:08:16',
                'updated_at' => '2025-12-02 12:08:16',
            ],
            [
                'movieID'    => 8,
                'theaterID'  => 4,
                'startTime'  => '2025-12-08 19:10:00',
                'endTime'    => '2025-12-08 21:00:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:08:50',
                'updated_at' => '2025-12-02 12:08:50',
            ],
            [
                'movieID'    => 10,
                'theaterID'  => 5,
                'startTime'  => '2025-12-05 22:10:00',
                'endTime'    => '2025-12-06 00:25:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:09:03',
                'updated_at' => '2025-12-02 12:09:03',
            ],
            [
                'movieID'    => 10,
                'theaterID'  => 2,
                'startTime'  => '2025-12-12 19:10:00',
                'endTime'    => '2025-12-12 21:25:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:09:15',
                'updated_at' => '2025-12-02 12:09:15',
            ],
            [
                'movieID'    => 10,
                'theaterID'  => 1,
                'startTime'  => '2025-12-15 22:10:00',
                'endTime'    => '2025-12-16 00:25:00',
                'price'      => null,
                'created_at' => '2025-12-02 12:09:28',
                'updated_at' => '2025-12-02 12:09:28',
            ],

            // ===== Thêm suất chiếu để đủ 12 phim & > 30 showtime =====
            // MovieID 1
            [
                'movieID'    => 1,
                'theaterID'  => 1,
                'startTime'  => '2025-12-03 09:00:00',
                'endTime'    => '2025-12-03 10:50:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 1,
                'theaterID'  => 2,
                'startTime'  => '2025-12-03 13:30:00',
                'endTime'    => '2025-12-03 15:20:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 1,
                'theaterID'  => 3,
                'startTime'  => '2025-12-04 19:30:00',
                'endTime'    => '2025-12-04 21:20:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 2 (thêm cho đủ nhiều suất)
            [
                'movieID'    => 2,
                'theaterID'  => 4,
                'startTime'  => '2025-12-11 15:00:00',
                'endTime'    => '2025-12-11 16:50:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 2,
                'theaterID'  => 5,
                'startTime'  => '2025-12-12 09:30:00',
                'endTime'    => '2025-12-12 11:20:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 3
            [
                'movieID'    => 3,
                'theaterID'  => 1,
                'startTime'  => '2025-12-06 14:00:00',
                'endTime'    => '2025-12-06 16:00:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 3,
                'theaterID'  => 2,
                'startTime'  => '2025-12-07 19:30:00',
                'endTime'    => '2025-12-07 21:30:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 3,
                'theaterID'  => 4,
                'startTime'  => '2025-12-09 10:00:00',
                'endTime'    => '2025-12-09 12:00:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 4
            [
                'movieID'    => 4,
                'theaterID'  => 3,
                'startTime'  => '2025-12-05 16:00:00',
                'endTime'    => '2025-12-05 18:10:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 4,
                'theaterID'  => 5,
                'startTime'  => '2025-12-06 20:00:00',
                'endTime'    => '2025-12-06 22:10:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 4,
                'theaterID'  => 1,
                'startTime'  => '2025-12-08 11:00:00',
                'endTime'    => '2025-12-08 13:10:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 5 (đã có 1, thêm nữa)
            [
                'movieID'    => 5,
                'theaterID'  => 3,
                'startTime'  => '2025-12-06 09:00:00',
                'endTime'    => '2025-12-06 10:25:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 5,
                'theaterID'  => 4,
                'startTime'  => '2025-12-07 18:30:00',
                'endTime'    => '2025-12-07 19:55:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 6 (đã có 1)
            [
                'movieID'    => 6,
                'theaterID'  => 2,
                'startTime'  => '2025-12-06 13:30:00',
                'endTime'    => '2025-12-06 15:00:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 6,
                'theaterID'  => 5,
                'startTime'  => '2025-12-07 21:00:00',
                'endTime'    => '2025-12-07 22:30:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 7 (đã có 1)
            [
                'movieID'    => 7,
                'theaterID'  => 2,
                'startTime'  => '2025-12-03 11:00:00',
                'endTime'    => '2025-12-03 13:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 7,
                'theaterID'  => 5,
                'startTime'  => '2025-12-04 20:00:00',
                'endTime'    => '2025-12-04 22:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 8 (đã có 1)
            [
                'movieID'    => 8,
                'theaterID'  => 1,
                'startTime'  => '2025-12-09 16:00:00',
                'endTime'    => '2025-12-09 18:00:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 8,
                'theaterID'  => 3,
                'startTime'  => '2025-12-10 14:00:00',
                'endTime'    => '2025-12-10 16:00:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 9
            [
                'movieID'    => 9,
                'theaterID'  => 2,
                'startTime'  => '2025-12-11 18:00:00',
                'endTime'    => '2025-12-11 20:05:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 9,
                'theaterID'  => 4,
                'startTime'  => '2025-12-12 20:30:00',
                'endTime'    => '2025-12-12 22:35:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 9,
                'theaterID'  => 5,
                'startTime'  => '2025-12-13 10:00:00',
                'endTime'    => '2025-12-13 12:05:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 10 (đã có 3)
            [
                'movieID'    => 10,
                'theaterID'  => 3,
                'startTime'  => '2025-12-18 19:00:00',
                'endTime'    => '2025-12-18 21:15:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 11
            [
                'movieID'    => 11,
                'theaterID'  => 1,
                'startTime'  => '2025-12-14 09:00:00',
                'endTime'    => '2025-12-14 11:10:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 11,
                'theaterID'  => 2,
                'startTime'  => '2025-12-15 14:00:00',
                'endTime'    => '2025-12-15 16:10:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 11,
                'theaterID'  => 4,
                'startTime'  => '2025-12-16 19:30:00',
                'endTime'    => '2025-12-16 21:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MovieID 12
            [
                'movieID'    => 12,
                'theaterID'  => 3,
                'startTime'  => '2025-12-17 15:00:00',
                'endTime'    => '2025-12-17 16:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 12,
                'theaterID'  => 5,
                'startTime'  => '2025-12-18 10:00:00',
                'endTime'    => '2025-12-18 11:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movieID'    => 12,
                'theaterID'  => 1,
                'startTime'  => '2025-12-19 20:00:00',
                'endTime'    => '2025-12-19 21:40:00',
                'price'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('showtime')->upsert(
            $showtime,
            ['showtimeID'], // khoá để check trùng
            [
                'movieID',
                'theaterID',
                'startTime',
                'endTime',
                'created_at',
                'updated_at',
            ]
        );
    }
}
