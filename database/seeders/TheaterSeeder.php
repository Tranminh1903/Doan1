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
            ['theaterID'=>1, 'roomName'=>'Room A', 'capacity'=>120],
            ['theaterID'=>2, 'roomName'=>'Room B', 'capacity'=> 90],
            ['theaterID'=>3, 'roomName'=>'Room C', 'capacity'=>150],
        ], ['theaterID'], ['roomName','capacity']);
    }
}
