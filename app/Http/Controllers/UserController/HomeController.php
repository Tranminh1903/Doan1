<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Storage;
use App\Models\ProductModels\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $banners = [
            [
                'img'   => Storage::url('pictures/mai.jpg'),
                'url'   => url('/promo/1'),
                'title' => 'Khuyến mãi 1',
                'desc'  => 'Giảm 20%',
            ],
            [
                'img'   => Storage::url('pictures/muado.jpg'),
                'url'   => url('/promo/2'),
                'title' => 'Khuyến mãi 2',
                'desc'  => 'Tặng combo',
            ],
            [
                'img'   => Storage::url('pictures/tuchientrenkhong.jpg'),
                'url'   => url('/promo/3'),
                'title' => 'Khuyến mãi 3',
                'desc'  => 'Tặng người yêu',
            ],
        ];

        $movies = Movie::with(['showtimes.theater'])->get();


        return view('home', compact('banners', 'movies'));
    }
}