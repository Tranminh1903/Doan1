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
        $query = trim($request->input('q'));

        if (!$query) {
            $movies = Movie::with('showtimes')->get();
        } else {
            $map = [
                'á'=>'a','à'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ấ'=>'a','ầ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ắ'=>'a','ằ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
                'é'=>'e','è'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ế'=>'e','ề'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
                'í'=>'i','ì'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
                'ó'=>'o','ò'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ố'=>'o','ồ'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ớ'=>'o','ờ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
                'ú'=>'u','ù'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ứ'=>'u','ừ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
                'ý'=>'y','ỳ'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
                'đ'=>'d'
            ];

            $normalized = mb_strtolower($query, 'UTF-8');
            $normalized = str_replace(array_keys($map), array_values($map), $normalized);

            $tokens = array_values(array_filter(preg_split('/\s+/', $normalized), function($t){ return $t !== ''; }));

            if (count($tokens) === 0) {
                $movies = Movie::with('showtimes')->get();
            } else {
                $sqlNormalize = 'LOWER(title)';
                foreach ($map as $accent => $base) {
                    $sqlNormalize = "REPLACE({$sqlNormalize},'{$accent}','{$base}')";
                }

                $moviesQuery = Movie::with('showtimes')->where(function($q) use ($tokens, $sqlNormalize) {
                    $first = true;
                    foreach ($tokens as $token) {
                        if ($first) {
                            $q->whereRaw("{$sqlNormalize} LIKE ?", ["%{$token}%"]);
                            $first = false;
                        } else {
                            $q->orWhereRaw("{$sqlNormalize} LIKE ?", ["%{$token}%"]);
                        }
                    }
                });

                $orderParts = [];
                foreach ($tokens as $t) {
                    $escaped = str_replace("'", "\\'", $t);
                    $orderParts[] = "(CASE WHEN {$sqlNormalize} LIKE '%{$escaped}%' THEN 1 ELSE 0 END)";
                }
                if (count($orderParts) > 0) {
                    $moviesQuery->orderByRaw('(' . implode(' + ', $orderParts) . ') DESC');
                }

                $movies = $moviesQuery->get();
            }
        }

        return view('layouts.movie_list', compact('movies'))->render();
    }


}
