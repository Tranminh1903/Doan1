<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Models\ProductModels\Movie;
use App\Models\ProductModels\MovieRating; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovieController
{
    public function show($movieID)
    {
        $movie = Movie::with('showtimes')->findOrFail($movieID);
        $averageRating = MovieRating::where('movieID', $movieID)->avg('stars') ?? 0;

        return view('movies.movie_detail', compact('movie', 'averageRating'));
    }

    public function rate(Request $request, $movieID)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5'
        ]);

        // Nếu chưa đăng nhập thì chặn luôn
        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đánh giá phim.');
        }

        $userID = Auth::id();

        // Kiểm tra nếu user đã từng đánh giá phim này -> cập nhật
        $existing = DB::table('movie_ratings')
            ->where('movieID', $movieID)
            ->where('userID', $userID)
            ->first();

        if ($existing) {
            DB::table('movie_ratings')
                ->where('ratingID', $existing->ratingID)
                ->update([
                    'stars' => $request->stars,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('movie_ratings')->insert([
                'movieID' => $movieID,
                'userID' => $userID,
                'stars' => $request->stars,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Đánh giá của bạn đã được ghi nhận!');
    }
}
