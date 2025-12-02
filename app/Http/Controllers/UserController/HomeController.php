<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Storage;
use App\Models\ProductModels\Movie;

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

        return view('layouts.home', compact('nowShowingMovies', 'comingSoonMovies', 'bannerMovies'));
    }
}
