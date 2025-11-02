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
    public function search(Request $request)
{
    $query = $request->input('q');

    if (!$query) {
        // Nếu query trống, trả tất cả phim
        $movies = Movie::with('showtimes')->get();
    } else {
        // Tìm kiếm bằng LIKE, có thể match tiếng Việt
        $movies = Movie::with('showtimes')
        ->whereRaw('title COLLATE utf8mb4_unicode_ci LIKE ?', ["%{$query}%"])
        ->get();

    }

    // Nếu muốn AJAX trả view partial (HTML movie_list)
    return view('layouts.movie_list', compact('movies'))->render();
}


}
