<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $banners = [
            [
                'img'   => Storage::url('pictures/fastfurious.jpg'),
                'url'   => url('/promo/1'),
                'title' => 'Khuyến mãi 1',
                'desc'  => 'Giảm 20%',
            ],
            [
                'img'   => Storage::url('pictures/giamcamquydu.jpg'),
                'url'   => url('/promo/2'),
                'title' => 'Khuyến mãi 2',
                'desc'  => 'Tặng combo',
            ],
            [
                'img'   => Storage::url('pictures/hocduongnoiloan.jpg'),
                'url'   => url('/promo/3'),
                'title' => 'Khuyến mãi 3',
                'desc'  => 'Tặng người yêu',
            ],
        ];

        return view('home', compact('banners'));
    }
}