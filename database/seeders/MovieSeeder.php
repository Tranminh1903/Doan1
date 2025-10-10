<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Carbon; 

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movies')->upsert([
            ['movieID'=>1, 'title'=>'Mai',               'poster'=>'pictures/mai.jpg',     'durationMin'=>120, 'genre'=>'Drama',            'rating'=>'PG-13', 'releaseDate'=>'2024-02-10'],
            ['movieID'=>2, 'title'=>'Mưa Đỏ',            'poster'=>'pictures/mua-do.jpg',  'durationMin'=>132, 'genre'=>'Sci-Fi',           'rating'=>'PG-13', 'releaseDate'=>'2025-07-01'],
            ['movieID'=>3, 'title'=>'Từ Chiến Trên Không','poster'=>'pictures/tu-chien.jpg','durationMin'=>98,  'genre'=>'Comedy',           'rating'=>'PG',    'releaseDate'=>'2025-04-20'],
        ], ['movieID'], ['title','poster','durationMin','genre','rating','releaseDate']);
    }
}
