<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Models\ProductModels\Movie;

class MovieController
{
    public function show($movieID)
    {
        $movie = Movie::with(['showtimes.theater'])->findOrFail($movieID);
        return view('movie_detail', compact('movie'));
    }
}
