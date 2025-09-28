<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;
use App\Models\ProductModels\Seat;
use App\Models\ProductModels\Showtime;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function booking($showtimeID)
    {
        $showtime = Showtime::findOrFail($showtimeID);

        $seats = Seat::where('seats.theaterID', $showtime->theaterID)
            ->leftJoin('seat_holds as sh', function($join) use ($showtimeID) {
    $join->on('seats.seatID', '=', 'sh.seatID')
         ->where('sh.showtimeID', '=', $showtimeID)
         ->where(function($q){
             $q->where('sh.status', 'unavailable')
               ->orWhere(function($q2){
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
        IF(SUM(sh.status='unavailable')>0, 'unavailable',
        IF(SUM(sh.status='held' AND sh.expires_at>NOW())>0, 'held', 'available')) as status
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

        return view('booking', [
            'seats'      => $seats,
            'showtimeID' => $showtimeID,
            'showtime'   => $showtime
        ]);
    }
}
