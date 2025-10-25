<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\UserController\Controller; // ✅ sửa ở đây
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModels\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

       $tickets = Ticket::query()
    ->with([
        'showtime:showtimeID,movieID,startTime',
        'showtime.movie:movieID,title',
        'seat:seatID',
        'showtime.orders' => function ($q) use ($user) {
            $q->where('username', $user->username)
              ->select('order_code', 'showtimeID', 'username');
        },
    ])
    ->whereHas('showtime.orders', function ($q) use ($user) {
        $q->where('username', $user->username);
    })
    ->orderByDesc('created_at')
    ->get();


        return view('ticket_history', compact('tickets'));
    }
}
