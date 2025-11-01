<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductModels\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModels\MovieRating;

class MovieController
{
    public function show($movieID)
    {
        $movie = Movie::with('showtimes')->findOrFail($movieID);
        $averageRating = MovieRating::where('movieID', $movieID)->avg('stars') ?? 0;
        $bannerMovies = Movie::where('is_banner', true)->whereNotNull('poster')->get(['movieID','poster']);
        return view('movies.movie_detail', compact('movie', 'averageRating','bannerMovies'));
    }

    public function rate(Request $request, $movieID)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5'
        ]);

        // ==== Bắt buộc đăng nhập ==== //
        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đánh giá phim.');
        }

        $userID = Auth::id();
        // ==== Kiểm tra nếu user đã từng đánh giá phim này -> cập nhật ==== //
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
