<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('footer.contact');
    }
}