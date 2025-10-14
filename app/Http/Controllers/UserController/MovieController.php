<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Models\ProductModels\Movie;

class MovieController extends Controller
{
    /**
     * Hiển thị danh sách phim (trang chủ)
     */
    public function index()
    {
        // Lấy tất cả phim đang chiếu kèm suất chiếu
        $movies = Movie::with('showtimes')->get();

        return view('home', compact('movies'));
    }

    /**
     * Hiển thị chi tiết một phim cụ thể
     */
    public function show($movieID)
    {
        $movie = \App\Models\ProductModels\Movie::with(['showtimes.theater'])->findOrFail($movieID);
        return view('movie_detail', compact('movie'));
    }


}
