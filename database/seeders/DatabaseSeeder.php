<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Carbon; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MovieSeeder::class,
            CustomerSeeder::class,
            MovieTheatersSeeder::class,
            SeatsSeeder::class,
            PromotionSeeder::class,
            ShowtimeSeeder::class,
            OrderSeeder::class,
            ShowtimeSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
