<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movies')->upsert([
            [
                'movieID' => 1,
                'title' => 'Phi Vụ Động Trời 2',
                'poster' => 'https://image.tmdb.org/t/p/original/dAJAUq3WO5w5QUK2YLJTqxTfxio.jpg',
                'background' => 'https://image.tmdb.org/t/p/original/kPmE7vEwQWSvhQt5P0ZR8NIwNRN.jpg',
                'durationMin' => 107,
                'rating' => 'P',
                'genre' => 'Adventure',
                'releaseDate' => '2025-11-28',
                'description' => "“Zootopia 2” đưa khán giả trở lại thành phố động vật náo nhiệt, nơi mọi giống loài cùng chung sống dưới một hệ thống trật tự mong manh. Lần này, sĩ quan Judy Hopps và người đồng hành tinh quái Nick Wilde phải đối mặt với một chuỗi sự kiện bất thường lan rộng khắp thành phố — những dấu hiệu rời rạc nhưng ẩn chứa một âm mưu lớn hơn bất kỳ vụ án nào họ từng đối đầu.\r\n\r\nHành trình lần này không chỉ là cuộc chạy đua với thời gian để ngăn chặn thảm họa, mà còn là phép thử của lòng tin, sự can đảm và tình bạn…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 06:12:11',
                'updated_at' => '2025-12-02 02:59:22',
            ],
            [
                'movieID' => 2,
                'title' => 'Quái Thú Vô Hình: Vùng Đất Chết Chóc',
                'poster' => 'https://media.themoviedb.org/t/p/w220_and_h330_face/tFoWkywjcmU8wWFJeGHvL9ljAoV.jpg',
                'background' => 'https://image.tmdb.org/t/p/original/yCykKtIKHMpwwdMAtgQgZO0ioo9.jpg',
                'durationMin' => 107,
                'rating' => 'T13',
                'genre' => 'Adventure',
                'releaseDate' => '2025-07-11',
                'description' => "“Quái Thú Vô Hình: Vùng Đất Chết Chóc” đưa người xem trở lại thời điểm hỗn loạn nhất — ngày mà những sinh vật bí ẩn từ ngoài không gian giáng xuống Trái Đất…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 06:32:56',
                'updated_at' => '2025-12-02 03:01:18',
            ],
            [
                'movieID' => 3,
                'title' => 'Wicked: Phần 2',
                'poster' => 'https://media.themoviedb.org/t/p/w220_and_h330_face/iFpGz29so4IX8CHAcFZQYk0zsAs.jpg',
                'background' => 'https://image.tmdb.org/t/p/original/vwVHteFFEcOEg5BiU2T5ymdB0C2.jpg',
                'durationMin' => 137,
                'rating' => 'P',
                'genre' => 'Adventure',
                'releaseDate' => '2025-11-21',
                'description' => "“Wicked” mở ra câu chuyện kỳ vĩ bên trong xứ Oz – nhưng không phải theo cách mà khán giả từng biết…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 07:14:30',
                'updated_at' => '2025-12-02 02:59:35',
            ],
            [
                'movieID' => 4,
                'title' => 'Wildcat',
                'poster' => 'https://media.themoviedb.org/t/p/w220_and_h330_face/h893ImjM6Fsv5DFhKJdlZFZIJno.jpg',
                'background' => 'https://media.themoviedb.org/t/p/w533_and_h300_face/4kjPMklaHZCklHumSLmJld2GgXW.jpg',
                'durationMin' => 99,
                'rating' => 'K',
                'genre' => 'Adventure',
                'releaseDate' => '2025-11-25',
                'description' => "“Wildcat” là chuyến hành trình nội tâm dữ dội và đầy cảm xúc, theo chân nữ nhà văn Flannery O’Connor…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 08:32:57',
                'updated_at' => '2025-12-02 03:00:39',
            ],
            [
                'movieID' => 5,
                'title' => 'Border Hunters',
                'poster' => 'https://media.themoviedb.org/t/p/w220_and_h330_face/6TNFZnJ5CU0uFQxGaO9dbqriiI7.jpg',
                'background' => 'https://media.themoviedb.org/t/p/w533_and_h300_face/tRdorrIjlybrGsebXOzgF7vRyYy.jpg',
                'durationMin' => 82,
                'rating' => 'T16',
                'genre' => 'Adventure',
                'releaseDate' => '2025-10-20',
                'description' => "“Border Hunters” đưa khán giả vào thế giới đầy bạo lực và căng thẳng nơi ranh giới sống – chết chỉ cách nhau một lần bóp cò…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 08:36:26',
                'updated_at' => '2025-12-02 03:00:57',
            ],
            [
                'movieID' => 6,
                'title' => 'Chú Thuật Hồi Chiến: -Biến Cố Shibuya x Tử Diệt Hồi Du',
                'poster' => 'https://media.themoviedb.org/t/p/w220_and_h330_face/sBffPvE9Kau726nnU14cTOEj3Pq.jpg',
                'background' => 'https://image.tmdb.org/t/p/original/9hK3rxEwCAX4DrLvOOBggJzkjHk.jpg',
                'durationMin' => 88,
                'rating' => 'T13',
                'genre' => 'Adventure',
                'releaseDate' => '2025-12-05',
                'description' => "“Chú Thuật Hồi Chiến: Biến Cố Shibuya × Tử Diệt Hồi Du” đưa khán giả vào giai đoạn hỗn loạn nhất trong series…",
                'is_banner' => 1,
                'status' => 'active',
                'created_at' => '2025-12-01 08:39:34',
                'updated_at' => '2025-12-02 02:43:25',
            ],
        ], ['movieID'], [
            'title', 'poster', 'background', 'durationMin', 'rating',
            'genre', 'releaseDate', 'description', 'is_banner', 'status',
            'created_at', 'updated_at'
        ]);
    }
}
