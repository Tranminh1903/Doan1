<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Models\ProductModels\Movie;
use Symfony\Component\HttpFoundation\Response;

class SetupBanner
{
    public function handle(Request $request, Closure $next): Response
    {
        $bannerMovies = Movie::where('is_banner', true)
            ->orderByDesc('updated_at')
            ->get();

        View::share('bannerMovie', $bannerMovies->first()); 
        View::share('bannerMovies', $bannerMovies);
        
        return $next($request);
    }
}
