<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\Customer;
use App\Models\UserModels\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\UserController\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgetPassword extends Controller
{
        //Forget_password
    public function showForget_Password(Request $request) : View
        {
            return view('forget_password');
        }

    public function sendResetLink(Request $request)
        {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink($request->only('email'));
            $generic = 'Đã gửi liên kết mật khẩu, xin hãy kiểm tra email của bạn!';

            // Throttle notification
            if (in_array($status, [
                Password::RESET_LINK_SENT,
                Password::INVALID_USER,     
                Password::RESET_THROTTLED,    
            ], true)) {
                return back()->with('status', $generic);
            }
            return back()->withErrors(['email' => 'Hiện không thể gửi email, xin vui lòng hãy chờ đợi!']);
        }
    public function showReset_Password(Request $request,string $token) : View
        {
            return view('reset_password',
            ['token' => $token,
            'email' => $request->query('email')]);
        }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ],[],[
            'email' => 'Email',
            'password' => 'Mật khẩu',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

}