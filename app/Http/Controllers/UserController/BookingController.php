<?php
namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function showByTime($time)
    {
        // Kiểm tra giờ chiếu hợp lệ nếu cần
        // Truy vấn suất chiếu, danh sách ghế, v.v.

        return view('booking', compact('time'));
    }
}