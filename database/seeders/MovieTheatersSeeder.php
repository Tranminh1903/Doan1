<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieTheatersSeeder extends Seeder
{
    public function run()
    {
        DB::table('movie_theaters')->insert([
            ['theaterID' => 1, 'roomName' => 'Phòng chiếu 1', 'created_at' => now()],
            ['theaterID' => 2, 'roomName' => 'Phòng chiếu 2', 'created_at' => now()],
            ['theaterID' => 3, 'roomName' => 'Phòng chiếu 3', 'created_at' => now()],
            ['theaterID' => 4, 'roomName' => 'Phòng chiếu 4', 'created_at' => now()],
            ['theaterID' => 5, 'roomName' => 'Phòng chiếu 5', 'created_at' => now()],
        ]);
    }
}
