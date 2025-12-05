<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\UserController\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModels\Movie;
use App\Models\ProductModels\MovieRating;

class MovieController extends Controller
{
    public function show($movieID)
    {
        $movie = Movie::with('showtimes')
            ->findOrFail($movieID);

        $averageRating = (float) ($movie->ratings()->avg('stars') ?? 0);

        $bannerMovies = Movie::where('is_banner', true)
            ->whereNotNull('background')
            ->get(['movieID', 'background']);

        return view('movies.movie_detail', compact('movie', 'averageRating', 'bannerMovies'));
    }

    public function rate(Request $request, $movieID)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:10',
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đánh giá phim.');
        }

        $userID = Auth::id();

        DB::transaction(function () use ($request, $movieID, $userID) {
            MovieRating::create([
                'movieID' => $movieID,
                'userID'  => $userID,
                'stars'   => $request->stars,
            ]);
        });

        return back()->with('success', 'Đánh giá của bạn đã được ghi nhận!');
    }

public function search(Request $request)
{
    $queryRaw = trim((string) $request->input('q', ''));

    // chuẩn hóa type: now_showing, coming_soon, all
    $type  = str_replace('-', '_', strtolower((string) $request->input('type', 'all')));
    $today = now()->toDateString();

    // base query cho movie_list: đã load showtime + ratings
    $moviesQuery = Movie::with(['showtimes.theater', 'ratings'])
        ->withAvg('ratings', 'stars');

    // ============ NẾU CÓ TỪ KHÓA THÌ MỚI FILTER ============ //
    if ($queryRaw !== '') {

        // map bỏ dấu
        $map = [
            'á'=>'a','à'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ấ'=>'a','ầ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ắ'=>'a','ằ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
            'é'=>'e','è'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ế'=>'e','ề'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
            'í'=>'i','ì'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
            'ó'=>'o','ò'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ố'=>'o','ồ'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ớ'=>'o','ờ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
            'ú'=>'u','ù'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ứ'=>'u','ừ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
            'ý'=>'y','ỳ'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
            'đ'=>'d'
        ];

        // chuẩn hóa từ khóa: lower + bỏ dấu
        $normalized = mb_strtolower($queryRaw, 'UTF-8');
        $normalized = str_replace(array_keys($map), array_values($map), $normalized);

        // build expression normalize cho cột title
        $sqlNormalize = 'LOWER(title)';
        foreach ($map as $accent => $base) {
            $sqlNormalize = "REPLACE({$sqlNormalize},'{$accent}','{$base}')";
        }

        // lọc: title đã bỏ dấu chứa nguyên cụm normalized
        // kèm thêm genre/rating dạng like bình thường
        $moviesQuery->where(function ($qr) use ($sqlNormalize, $normalized, $queryRaw) {
            // title: bỏ dấu, search theo cả cụm
            $qr->whereRaw("{$sqlNormalize} LIKE ?", ["%{$normalized}%"])
               // bonus: genre + rating (không bỏ dấu)
               ->orWhere('genre', 'like', "%{$queryRaw}%")
               ->orWhere('rating', 'like', "%{$queryRaw}%");
        });
    }

    // ============ FILTER THEO TYPE (ĐANG CHIẾU / SẮP CHIẾU) ============ //
    switch ($type) {
        case 'coming_soon':
            $moviesQuery
                ->where('status', 'active')
                ->whereDate('releaseDate', '>', $today)
                ->orderBy('releaseDate', 'asc');
            break;

        case 'now_showing':
        case 'now_playing':
            $moviesQuery
                ->where('status', 'active')
                ->whereDate('releaseDate', '<=', $today)
                ->orderBy('releaseDate', 'desc');
            break;

        case 'all':
        default:
            $moviesQuery
                ->where('status', 'active')
                ->orderBy('releaseDate', 'desc');
            break;
    }

    $movies = $moviesQuery->get();

    return view('layouts.movie_list', compact('movies'))->render();
}
}