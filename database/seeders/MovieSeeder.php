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
                'description' => '“Mai” là câu chuyện đầy cảm xúc về hành trình đi tìm lại chính mình của một người phụ nữ mang nhiều vết thương trong tâm hồn. Giữa những tổn thương, dằn vặt và ký ức đau đớn, cô học cách tha thứ, yêu thương và bắt đầu lại. Bộ phim khắc họa sâu sắc bản lĩnh và sự kiên cường của người phụ nữ Việt Nam trong hành trình đi qua bóng tối để tìm thấy ánh sáng.',
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
                'description' => '“Mưa Đỏ” tái hiện một giai đoạn khốc liệt trong lịch sử, nơi con người đối mặt với chiến tranh, mất mát và lòng dũng cảm. Dưới cơn mưa nhuộm màu máu, những số phận tưởng chừng nhỏ bé lại toả sáng bởi tinh thần yêu nước và khát vọng tự do. Tác phẩm là bản hùng ca bi tráng, lay động trái tim người xem bằng những hình ảnh chân thực và cảm xúc mãnh liệt.',
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
                'description' => '“Cuộc Chiến Trên Không” là một bức tranh dữ dội về những trận không chiến khốc liệt giữa bầu trời. Phim đưa khán giả theo chân những phi công quả cảm, nơi từng giây từng phút là ranh giới mong manh giữa sự sống và cái chết. Với kỹ xảo hoành tráng và kịch bản giàu cảm xúc, tác phẩm tôn vinh lòng dũng cảm, tình đồng đội và niềm tin vào lý tưởng lớn lao.',
                'status'      => 'active',   
                'is_banner'   => false,
            ],
        ], ['movieID'], [
            // Các cột sẽ được cập nhật nếu movieID đã tồn tại
            'title','poster','durationMin','genre','rating','releaseDate','description','status','is_banner'
        ]);
    }
}
