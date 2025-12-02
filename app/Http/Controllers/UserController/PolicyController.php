<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index()
    {
        return view('footer.policy');
    }
}