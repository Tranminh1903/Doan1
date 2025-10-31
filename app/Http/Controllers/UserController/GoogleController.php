<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\UserModels\User;
use App\Models\UserModels\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GuzzleClient;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
    [
        'name' => $googleUser->getName(),
        'username' => Str::slug($googleUser->getName()) . rand(100,999),
        'google_id' => $googleUser->getId(),
        'avatar' => $googleUser->getAvatar(),
        'password' => bcrypt(Str::random(16)),
    ]
        );

        Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'customer_name' => $user->name,
                'customer_point' => 0,
                'tier' => 'bronze',
                'total_order_amount' => 0,
                'total_promotions_unused' => 0,
            ]
        );

        Auth::login($user);

        return redirect('/')->with('success', 'Đăng nhập Google thành công!');
    }
}
