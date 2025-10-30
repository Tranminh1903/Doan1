<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Storage;
use App\Models\ProductModels\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::with(['showtimes.theater'])
            ->where('status', 'active')
            ->get();

        $bannerMovies = Movie::where('is_banner', true)
            ->where('status', 'active')
            ->get();

        return view('layouts.home', compact('movies', 'bannerMovies'));
    }
}