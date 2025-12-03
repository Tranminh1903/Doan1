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
        // Reset bảng ticket
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('ticket')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Lấy orders đã thanh toán
        $orders = DB::table('orders')
            ->join('showtime', 'orders.showtimeID', '=', 'showtime.showtimeID')
            ->select(
                'orders.*',
                'showtime.theaterID'
            )
            ->where('orders.status', 'paid')
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        // Load GHẾ THEO RẠP
        $seatsByTheater = DB::table('seats')
            ->select('seatID', 'verticalRow', 'horizontalRow', 'theaterID')
            ->get()
            ->groupBy('theaterID');

        $now  = Carbon::now();
        $rows = [];

        foreach ($orders as $order) {

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
                $seat = null;

                // Nếu là số thì thử seatID
                if (ctype_digit($tk)) {
                    $seat = $theaterSeats->firstWhere('seatID', (int)$tk);
                }

                // Nếu chưa tìm thấy -> ghép verticalRow + horizontalRow
                if (!$seat) {
                    $seat = $theaterSeats->first(function ($s) use ($tk) {
                        return ($s->verticalRow . $s->horizontalRow) == $tk;
                    });
                }

                if ($seat) {
                    $resolvedSeatIds[] = $seat->seatID;
                }
            }

            $resolvedSeatIds = array_unique($resolvedSeatIds); // tránh trùng trong order

            if (empty($resolvedSeatIds)) {
                continue;
            }

            // Lấy giá
            $seatCount   = count($resolvedSeatIds);
            $orderAmount = (int)$order->amount;
            $basePrice   = $seatCount > 0 ? (int)floor($orderAmount / $seatCount) : 0;
            if ($basePrice <= 0) $basePrice = 50000;

            $createdAt = $order->created_at ?? $now;
            $updatedAt = $order->updated_at ?? $createdAt;

            foreach ($resolvedSeatIds as $seatID) {

                // ❗ FIX 1: kiểm tra trùng trong DB
                $exists = DB::table('ticket')
                    ->where('showtimeID', $order->showtimeID)
                    ->where('seatID', $seatID)
                    ->exists();

                if ($exists) {
                    continue; // bỏ qua nếu ghế đã tồn tại
                }

                // ❗ FIX 2: kiểm tra trùng trong mảng rows (chưa insert DB)
                $key = $order->showtimeID . '-' . $seatID;
                static $added = [];
                if (isset($added[$key])) {
                    continue;
                }
                $added[$key] = true;

                $rows[] = [
                    'price'        => min($basePrice, 99999999), // tránh overflow
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

        // Insert chunk
        foreach (array_chunk($rows, 300) as $chunk) {
            DB::table('ticket')->insert($chunk);
        }
    }
}
