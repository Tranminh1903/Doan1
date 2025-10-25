<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\User;
use App\Models\UserModels\Admin;
use App\Models\UserModels\Customer;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    ////////////////////////// Register //////////////////////////
    public function showRegister()
    {
        return view('Authentication.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username'  => ['required','string','max:20','unique:users,username'],
            'email'     => ['required','email','max:60','unique:users,email'],
            'password'  => ['required', Password::min(5)->numbers()],
        ], [
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique'    => 'Email này đã được sử dụng.',
        ]);
            $day   = $request->input('day');
            $month = $request->input('month');
            $year  = $request->input('year');
            $birthday = sprintf('%04d-%02d-%02d', $year, $month, $day); 

        $user = User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => 'customers', 
            'birthday'  => $birthday,
        ]); 

        if ($user->role === 'customers') {
            Customer::create(['user_id' => $user->id, 'customer_name' => $user->username,'customer_point' => 0]);
        } else {
            Admin::create(['user_id' => $user->id]);
        }
        
        Auth::login($user);
        $request->session()->regenerate(); 
        return redirect()->route('home')->with('RegisterSuccess', 'Đăng ký tài khoản thành công!');
    }

    ////////////////////////// Login //////////////////////////
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
                'admin'     => redirect()->route('home')->with('LoginSuccess', 'Đăng nhập thành công!'),
                'customers' => redirect()->route('home')->with('LoginSuccess', 'Đăng nhập thành công!'),
                default     => redirect()->route('home')->with('LoginSuccess', 'Đăng nhập thành công!'),
            };
        }
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
    }

    public function showLogin(): View
    {
        return view('Authentication.login');
    }

    ////////////////////////// Logout //////////////////////////
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('LogoutSuccess', 'Khỏi trang web thành công!');
    }
}
