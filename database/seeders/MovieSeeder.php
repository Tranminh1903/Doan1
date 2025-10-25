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
            [
                'movieID'     => 1,
                'title'       => 'Mai',
                'poster'      => 'storage/pictures/mai.jpg',
                'durationMin' => 120,
                'genre'       => 'Drama',
                'rating'      => 'PG-13',
                'releaseDate' => '2024-02-10',
                'description' => 'Phim Việt tâm lý gia đình.',
                'status'      => 'active',   
                'is_banner'   => false,       
            ],
            [
                'movieID'     => 2,
                'title'       => 'Mưa Đỏ',
                'poster'      => 'storage/pictures/muado.jpg',
                'durationMin' => 132,
                'genre'       => 'Sci-Fi',
                'rating'      => 'PG-13',
                'releaseDate' => '2025-07-01',
                'description' => 'Viễn tưởng hành động.',
                'status'      => 'active',
                'is_banner'   => false,
            ],
            [
                'movieID'     => 3,
                'title'       => 'Từ Chiến Trên Không',
                'poster'      => 'storage/pictures/tuchientrenkhong.jpg',
                'durationMin' => 98,
                'genre'       => 'Comedy',
                'rating'      => 'PG',
                'releaseDate' => '2025-04-20',
                'description' => 'Hài hước, giải trí.',
                'status'      => 'active',   
                'is_banner'   => false,
            ],
        ], ['movieID'], [
            // Các cột sẽ được cập nhật nếu movieID đã tồn tại
            'title','poster','durationMin','genre','rating','releaseDate','description','status','is_banner'
        ]);
    }
}
