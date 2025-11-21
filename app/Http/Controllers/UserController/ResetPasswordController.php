<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showReset_Password(Request $request, string $token): View
    {
        return view(
            'Authentication.reset_password',
            [
                'token' => $token,
                'email' => $request->query('email')
            ]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ], [], [
            'email' => 'Email',
            'password' => 'Mật khẩu',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(attributes: [
                    'password' => Hash::make($password),
                ]);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))->with('changePasswordSuccess', 'Bạn đã thay đổi mật khẩu thành công!')
            : back()->withErrors(['email' => __($status)]);
    }
}
