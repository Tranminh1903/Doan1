<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;
use App\Models\ProductModels\Seat;
use App\Models\ProductModels\Showtime;

class BookingController extends Controller
{
    public function booking($showtimeID)
    {
        $showtime = Showtime::findOrFail($showtimeID);
        $theaterId = $showtime->theaterID;

        $seats = Seat::where('theaterID', $theaterId)
                    ->orderBy('verticalRow')
                    ->orderBy('horizontalRow')
                    ->get()
                    ->groupBy('verticalRow');

        return view('booking', compact('seats', 'showtimeID'));
    }
}
