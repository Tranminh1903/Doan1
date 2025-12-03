<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserModels\User;
use App\Models\UserModels\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()
                ->route('login.form')
                ->with('error', 'Bạn đã hủy đăng nhập bằng Google.');
        }

        if (!$request->has('code')) {
            return redirect()
                ->route('login.form')
                ->with('error', 'Thiếu mã xác thực từ Google, vui lòng thử lại.');
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            report($e);

            return redirect()
                ->route('login.form')
                ->with('error', 'Không thể đăng nhập bằng Google, vui lòng thử lại sau.');
        }
        $avatarUrl  = $googleUser->getAvatar();
        $savedImagePath = null;

        if ($avatarUrl) {
            try {
                //Tải nội dung ảnh từ URL của Google
                $imgGoogle = Http::get($avatarUrl)->body();

                if ($imgGoogle) {
                    // => Sẽ lưu vào storage\app\public\avatars
                    $imgName = 'avatars/' . Str::random(40) . '.jpg';
                    Storage::disk('public')->put($imgName,$imgGoogle);
                    $savedImagePath = $imgName; 
                }
            } catch (\Exception $e) {

            }
        }
        $user = User::where('email',$googleUser->getEmail())->first();
        if($user) { // Nếu user đã tồn tại thì update thêm google_id và cập nhật avatar 

            $user->google_id = $googleUser->getId();
            if ($savedImagePath) {
            $user->avatar = $savedImagePath;
            }
    $user->save();
        } else { //Nếu user không tồn tại
            $user = User::create([
                'username'  => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'avatar'    => $savedImagePath,
                'status'    => 'active',
                'google_id' => $googleUser->getId(),
                'role'      => 'customers',
                'password'  => null,
            ]);
            Customer::create([ // Đồng thời kéo dữ liệu qua bảng Customer
                'user_id'        => $user->id,
                'customer_name'  => $user->username,
            ]);
        }
        Auth::login($user);

        return redirect('/')->with('success', 'Đăng nhập Google thành công!');
    }
}
