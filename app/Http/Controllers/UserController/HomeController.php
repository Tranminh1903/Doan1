<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Storage;
use App\Models\ProductModels\Movie;
use App\Models\ProductModels\News;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $nowShowingMovies = Movie::with(['showtimes.theater','ratings'])
            ->withAvg('ratings', 'stars')
            ->where('status', 'active')
            ->whereDate('releaseDate', '<=', $today)
            ->orderBy('releaseDate', 'desc')
            ->get();

        $comingSoonMovies = Movie::with(['showtimes.theater','ratings'])
            ->withAvg('ratings', 'stars')
            ->where('status', 'active')
            ->whereDate('releaseDate', '>', $today)
            ->orderBy('releaseDate', 'asc')
            ->get();
            
        $bannerMovies = Movie::where('is_banner', true)
            ->where('status', 'active')
            ->get();

        $news = News::latest()->take(4)->get();

        $topRaw = DB::table('orders')
            ->join('showtime', 'orders.showtimeID', '=', 'showtime.showtimeID')
            ->join('movies', 'showtime.movieID', '=', 'movies.movieID')
            ->where('orders.status', 'paid')
            ->where('movies.status', 'active')
            ->whereDate('movies.releaseDate', '<=', $today)
            ->groupBy('movies.movieID')
            ->select(
                'movies.movieID',
                DB::raw('SUM(orders.amount) as total_revenue'),
                DB::raw('COUNT(orders.id) as orders_count')
            )
            ->orderByDesc('total_revenue')
            ->limit(4)
            ->get();

        $topSellingMovies = collect();

        if ($topRaw->isNotEmpty()) {
            $movieIds = $topRaw->pluck('movieID')->all();

            // Lấy lại Movie model đầy đủ để dùng movie_list (có ratings, showtimes)
            $movies = Movie::with(['showtimes.theater','ratings'])
                ->withAvg('ratings', 'stars')
                ->whereIn('movieID', $movieIds)
                ->get();

            // Gắn thêm thuộc tính total_revenue & orders_count
            $topSellingMovies = $movies->map(function ($movie) use ($topRaw) {
                $stat = $topRaw->firstWhere('movieID', $movie->movieID);
                $movie->total_revenue = $stat->total_revenue ?? 0;
                $movie->orders_count  = $stat->orders_count ?? 0;
                return $movie;
            })
            ->sortByDesc('total_revenue')
            ->values();
        }

        return view('layouts.home', compact('nowShowingMovies', 'comingSoonMovies', 'bannerMovies', 'news', 'topSellingMovies'));
    }
}
