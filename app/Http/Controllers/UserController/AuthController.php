<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /** GET /register */
    public function showRegister()
    {
        return view('register');
    }
    /** POST /register */
    public function register(Request $request)
    {
        $data = $request->validate([
            'username'  => ['required','string','max:20'],
            'email'     => ['required','email','max:60','unique:users,email'],
            'phone'     => ['nullable','string','max:50'],
            'password'  => ['required', Password::min(5)->numbers(), 'confirmed'],
        ]);
        $user = User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'role'      => 'customers', 
        ]);
        Auth::login($user);
        $request->session()->regenerate(); 
        return redirect()->route('home')->with('register_success', 'Đăng ký tài khoản thành công!');
    }
    public function showLogin(): View
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => ['required','email'],
            'password'  => ['required','string'],
        ]);
        $remember = $request->boolean('remember');
        if (Auth::attempt($credentials, $remember))
        {
            $request->session()->regenerate();
            return match (Auth::user()->role) 
            {
                'admin'     => redirect()->route('home')->with('success', 'Đăng nhập thành công!'),
                'customers' => redirect()->route('home')->with('success', 'Đăng nhập thành công!'),
                default     => redirect()->route('home')->with('success', 'Đăng nhập thành công!'),
            };
        }
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
                ->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
