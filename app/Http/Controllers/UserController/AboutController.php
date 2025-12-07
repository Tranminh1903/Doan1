<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('footer.about');
    }
}