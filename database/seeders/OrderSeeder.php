<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Xoá dữ liệu cũ THEO ĐÚNG THỨ TỰ: con -> cha
        DB::table('seat_holds')->delete();
        DB::table('orders')->delete();

        // Lấy danh sách user_id HỢP LỆ từ bảng customers
        $userIds = DB::table('customers')->pluck('user_id')->all();

        // Nếu chưa có khách hàng nào thì thôi, không seed orders
        if (empty($userIds)) {
            return;
        }

        $cutoffDate = '2025-12-03';

        $nowShowingMovies = DB::table('movies')
            ->where('status', 'active')
            ->whereDate('releaseDate', '<', $cutoffDate)
            ->get();

        if ($nowShowingMovies->isEmpty()) {
            return;
        }

        foreach ($nowShowingMovies as $movie) {
            // Lấy các suất chiếu của phim
            $showtimes = DB::table('showtime')
                ->where('movieID', $movie->movieID)
                ->get();

            if ($showtimes->isEmpty()) {
                continue; // không có suất chiếu thì bỏ qua
            }

            // Target doanh thu cho phim này: 50–500 triệu
            $targetRevenue   = rand(50_000_000, 500_000_000);
            $currentRevenue  = 0;

            // Tạo order ngẫu nhiên cho từng suất chiếu
            foreach ($showtimes as $st) {
                // Số order cho 1 suất chiếu: 5–20
                $numOrders = rand(5, 20);

                for ($i = 0; $i < $numOrders; $i++) {
                    // Nếu đã gần chạm target thì dừng, lát nữa cộng thêm 1 đơn cuối
                    if ($currentRevenue >= $targetRevenue * 0.9) {
                        break;
                    }

                    $userId = $userIds[array_rand($userIds)];

                    // Số tiền cho 1 đơn: 150k – 2 triệu
                    $amount         = rand(150_000, 2_000_000);
                    $currentRevenue += $amount;

                    DB::table('orders')->insert([
                        'showtimeID'     => $st->id ?? $st->showtimeID,
                        'user_id'        => $userId,
                        'order_code'     => 'ORD' . strtoupper(Str::random(8)),
                        'promotion_code' => null,
                        'seats'          => $this->randomSeats(),     // vd: "A1,A2,A3"
                        'status'         => 'paid',
                        'amount'         => $amount,
                        'created_at'     => $this->randomOrderTime($st->startTime),
                        'updated_at'     => now(),
                    ]);
                }
            }

            // Nếu doanh thu vẫn < target thì tạo thêm 1 order để bù cho đủ
            if ($currentRevenue < $targetRevenue) {
                $extra   = $targetRevenue - $currentRevenue;
                $st      = $showtimes->random();
                $userId  = $userIds[array_rand($userIds)];

                DB::table('orders')->insert([
                    'showtimeID'     => $st->id ?? $st->showtimeID,
                    'user_id'        => $userId,
                    'order_code'     => 'ORD' . strtoupper(Str::random(8)),
                    'promotion_code' => null,
                    'seats'          => $this->randomSeats(),
                    'status'         => 'paid',
                    'amount'         => $extra,
                    'created_at'     => $this->randomOrderTime($st->startTime),
                    'updated_at'     => now(),
                ]);

                $currentRevenue += $extra;
            }
        }
    }

    /**
     * Random danh sách ghế dạng "A1,A2,A3"
     */
    protected function randomSeats(): string
    {
        $rows      = range('A', 'J');
        $seatCount = rand(2, 6);
        $picked    = [];

        while (count($picked) < $seatCount) {
            $row  = $rows[array_rand($rows)];
            $num  = rand(1, 20);
            $seat = $row . $num;

            if (!in_array($seat, $picked, true)) {
                $picked[] = $seat;
            }
        }

        return implode(',', $picked);
    }

    /**
     * Random thời gian tạo đơn: trước giờ chiếu 0–10 ngày
     */
    protected function randomOrderTime(string $startTime): string
    {
        $start         = Carbon::parse($startTime);
        $daysBefore    = rand(0, 10);
        $minutesBefore = rand(10, 60 * 6); // từ 10 phút tới 6h trước chiếu

        return $start
            ->copy()
            ->subDays($daysBefore)
            ->subMinutes($minutesBefore)
            ->toDateTimeString();
    }
}
