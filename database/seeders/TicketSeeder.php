<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt FK để truncate an toàn (MySQL)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tickets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Lấy orders + theaterID của suất chiếu
        $orders = DB::table('orders')
            ->join('showtimes', 'orders.showtimeID', '=', 'showtimes.showtimeID')
            ->select(
                'orders.*',
                'showtimes.theaterID'
            )
            ->where('orders.status', 'paid')      // chỉ tạo vé cho order đã thanh toán
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        // Lấy toàn bộ seats, group theo theaterID để tra nhanh
        $seatsByTheater = DB::table('seats')
            ->select('seatID', 'seatNumber', 'theaterID')
            ->get()
            ->groupBy('theaterID');

        $now  = Carbon::now();
        $rows = [];

        foreach ($orders as $order) {
            // Tách danh sách ghế từ cột `seats` (có thể là seatID hoặc seatNumber)
            $tokens = array_filter(array_map('trim', explode(',', (string) $order->seats)));
            if (empty($tokens)) {
                continue;
            }

            $theaterSeats = $seatsByTheater[$order->theaterID] ?? collect();
            if ($theaterSeats->isEmpty()) {
                continue;
            }

            $resolvedSeatIds = [];

            foreach ($tokens as $tk) {
                if ($tk === '') {
                    continue;
                }

                $seat = null;

                // Nếu là số nguyên -> thử coi như seatID
                if (ctype_digit($tk)) {
                    $seat = $theaterSeats->firstWhere('seatID', (int) $tk);
                }

                // Không tìm được theo ID thì thử theo seatNumber (A1, B5,…)
                if (!$seat) {
                    $seat = $theaterSeats->firstWhere('seatNumber', $tk);
                }

                if ($seat) {
                    $resolvedSeatIds[] = $seat->seatID;
                }
            }

            $seatCount = count($resolvedSeatIds);
            if ($seatCount === 0) {
                continue;
            }

            // Chia tiền vé trên mỗi ghế (làm tròn xuống cho an toàn)
            $orderAmount = (int) $order->amount;
            $basePrice   = $seatCount > 0 ? (int) floor($orderAmount / $seatCount) : 0;
            if ($basePrice <= 0) {
                $basePrice = 50000; // fallback nếu amount = 0
            }

            $createdAt = $order->created_at ?? $now;
            $updatedAt = $order->updated_at ?? $createdAt;

            foreach ($resolvedSeatIds as $seatID) {
                $rows[] = [
                    'price'        => $basePrice,
                    'status'       => 'paid',
                    'qr_token'     => Str::uuid()->toString(),
                    'issueAt'      => $createdAt,
                    'refund_reason'=> null,
                    'order_code'   => $order->order_code,
                    'showtimeID'   => $order->showtimeID,
                    'seatID'       => $seatID,
                    'created_at'   => $createdAt,
                    'updated_at'   => $updatedAt,
                ];
            }
        }

        // Insert theo chunk để tránh quá nặng
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('tickets')->insert($chunk);
        }
    }
}
