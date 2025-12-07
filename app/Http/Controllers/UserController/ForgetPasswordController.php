<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    // =============== Forget Passwod ============= //
    public function showForget_Password(Request $request): View
    {
        return view('authentication.forget_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [], [], 'forgot'); 

        $status = Password::sendResetLink($request->only('email'));

        $generic = 'Đã gửi liên kết đặt lại mật khẩu, vui lòng kiểm tra email!';

        if (in_array($status, [
            Password::RESET_LINK_SENT,
            Password::INVALID_USER,
            Password::RESET_THROTTLED,
        ], true)) {

            return back()
                ->with('status', $generic)
                ->with('open_forgot_modal', true);
        }

        return back()
            ->withErrors(['email' => 'Hiện không thể gửi email, vui lòng thử lại sau!'], 'forgot')
            ->with('open_forgot_modal', true);
    }
}
