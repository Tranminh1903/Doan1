<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModels\Seat;
use Illuminate\Support\Facades\DB;
use App\Models\ProductModels\Movie;
use Illuminate\Support\Facades\Redis;
use App\Models\ProductModels\Showtime;
use App\Models\UserModels\Promotion;
use App\Events\SeatStatusUpdated;
use App\Http\Controllers\UserController\Controller;

class BookingController extends Controller
{
    public function selectShowtime($movieID)
    {
        $movie = Movie::with(['showtimes.theater'])
            ->findOrFail($movieID);

        // Lấy danh sách ngày có suất chiếu
        $availableDates = $movie->showtimes
            ->pluck('startTime')
            ->map(fn($t) => Carbon::parse($t)->startOfDay())
            ->unique()
            ->sort();

        // Nhóm suất chiếu theo rạp
        $groupedShowtimes = $movie->showtimes->groupBy(fn($st) => $st->theater->name);

        return view('payment.select_showtimes', compact('movie', 'availableDates', 'groupedShowtimes'));
    }

    public function start($showtimeID)
    {
        $showtime = Showtime::findOrFail($showtimeID);
        return $this->booking($showtimeID);
    }

    public function booking($showtimeID)
    {
        $showtime = Showtime::with('movie')->findOrFail($showtimeID);

        $seats = Seat::where('seats.theaterID', $showtime->theaterID)
            ->leftJoin('seat_holds as sh', function ($join) use ($showtimeID) {
                $join->on('seats.seatID', '=', 'sh.seatID')
                    ->where('sh.showtimeID', '=', $showtimeID)
                    ->where(function ($q) {
                        $q->where('sh.status', 'unavailable')
                            ->orWhere(function ($q2) {
                                $q2->where('sh.status', 'held')
                                    ->where('sh.expires_at', '>', now());
                            });
                    });
            })
            ->select(
                'seats.seatID',
                'seats.theaterID',
                'seats.verticalRow',
                'seats.horizontalRow',
                DB::raw("
                    CASE 
                        WHEN SUM(CASE WHEN sh.status = 'unavailable' THEN 1 ELSE 0 END) > 0 THEN 'unavailable'
                        WHEN SUM(CASE WHEN sh.status = 'held' AND sh.expires_at > NOW() THEN 1 ELSE 0 END) > 0 THEN 'held'
                        ELSE 'available'
                    END as status
                ")
            )
            ->groupBy(
                'seats.seatID',
                'seats.theaterID',
                'seats.verticalRow',
                'seats.horizontalRow'
            )
            ->orderBy('seats.verticalRow')
            ->orderBy('seats.horizontalRow')
            ->get()
            ->groupBy('verticalRow');
        
        // Lọc khuyến mãi hợp lệ
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('used_count', '<', 'limit_count')
            ->get();

        return view('payment.booking', [
            'seats'      => $seats,
            'showtimeID' => $showtimeID,
            'showtime'   => $showtime,
            'movie'      => $showtime->movie,
        ]);
    }

    public function holdSeat(Request $request)
    {
        $request->validate([
            'seatID' => 'required|integer',
            'showtimeID' => 'required|integer',
        ]);

        $seatID = $request->seatID;
        $showtimeID = $request->showtimeID;
        $userID = auth()->id();
        $expiresAt = Carbon::now()->addMinutes(5);

        try {
            DB::transaction(function () use ($seatID, $showtimeID, $userID, $expiresAt) {
                $exists = DB::table('seat_holds')
                    ->where('seatID', $seatID)
                    ->where('showtimeID', $showtimeID)
                    ->where(function ($q) {
                        $q->where('status', 'unavailable')
                            ->orWhere(function ($q2) {
                                $q2->where('status', 'held')
                                    ->where('expires_at', '>', now());
                            });
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    throw new \Exception('Ghế này đã có người giữ hoặc đặt.');
                }

                DB::table('seat_holds')->insert([
                    'seatID'     => $seatID,
                    'showtimeID' => $showtimeID,
                    'user_id'    => $userID,
                    'status'     => 'held',
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Đã giữ ghế trong 5 phút.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
        }
    }
    public function checkExpiredSeats($showtimeID)
{
    $now = now();

    // Tìm ghế đang held nhưng đã hết hạn
    $expiredSeats = DB::table('seat_holds')
        ->where('showtimeID', $showtimeID)
        ->where('status', 'held')
        ->where('expires_at', '<', $now)
        ->pluck('seatID')
        ->toArray();

    if (!empty($expiredSeats)) {
        // Xóa ghế hết hạn khỏi bảng seat_holds
        DB::table('seat_holds')
            ->whereIn('seatID', $expiredSeats)
            ->where('showtimeID', $showtimeID)
            ->delete();

        // Tạo dữ liệu broadcast chuẩn
        $seatObjects = collect($expiredSeats)->map(fn($seatId) => [
            'seatID' => $seatId,
            'status' => 'available'
        ])->toArray();

        // Phát event realtime để tất cả client khác cập nhật
        broadcast(new SeatStatusUpdated(
            $showtimeID,
            $seatObjects,
            'available'
        ))->toOthers();
    }

    return response()->json(['expiredSeats' => $expiredSeats]);
}

}
