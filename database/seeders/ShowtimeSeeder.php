<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Carbon; 

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movieMai  = DB::table('movies')->where('title','Mai')->value('movieID');
        $movieMua  = DB::table('movies')->where('title','Mưa Đỏ')->value('movieID');
        $roomA     = DB::table('movie_theaters')->where('roomName','Room A')->value('theaterID');
        $roomB     = DB::table('movie_theaters')->where('roomName','Room B')->value('theaterID');

        $rows = [
            ['movieID'=>$movieMai,'theaterID'=>$roomA,'startTime'=>'2025-10-10 10:00:00','endTime'=>'2025-10-10 12:00:00','price'=>80000],
            ['movieID'=>$movieMai,'theaterID'=>$roomB,'startTime'=>'2025-10-10 15:30:00','endTime'=>'2025-10-10 17:30:00','price'=>85000],
            // Mưa Đỏ
            ['movieID'=>$movieMua,'theaterID'=>$roomA,'startTime'=>'2025-10-11 19:00:00','endTime'=>'2025-10-11 21:12:00','price'=>95000],
        ];
        DB::table('showtime')->upsert(
            $rows,
            ['movieID','theaterID','startTime'],
            ['endTime','price','updated_at']
        );
    }
}
