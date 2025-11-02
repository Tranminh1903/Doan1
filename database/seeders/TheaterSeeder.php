<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Carbon; 

class TheaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movie_theaters')->upsert([
            ['theaterID'=>1, 'roomName'=>'Phòng 1', 'capacity'=>120],
            ['theaterID'=>2, 'roomName'=>'Phòng 2', 'capacity'=> 90],
            ['theaterID'=>3, 'roomName'=>'Phòng 3', 'capacity'=>150],
        ], ['theaterID'], ['roomName','capacity']);
    }
}
