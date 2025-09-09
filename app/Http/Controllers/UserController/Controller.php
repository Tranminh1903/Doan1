<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{  
     public function showProfile(): View
    {
        return view('profile');
    }
}
