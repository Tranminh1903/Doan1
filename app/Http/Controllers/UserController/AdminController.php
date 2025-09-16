<?php
namespace App\Http\Controllers\UserController;

use App\Models\UserModels\User;
use App\Models\UserModels\Admin;
use App\Models\UserModels\Customer;
use App\Http\Controllers\UserController\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    // View
    public function showAdminDashboard(): View
    {
        return view('adminDashboard.index');
    }

    public function showMainManagementUser(Request $request): View
    {
        return view('adminDashboard.userManagement.main');
    }
    public function showManagerUser(Request $request): View
    {
        return view('adminDashboard.userManagement._managerUser');
    }
    public function showCreateUser(Request $request): View
    {
        return view('adminDashboard.userManagement._createUser');
    }
    public function showUpdateUser(Request $request): View
    {
        return view('adminDashboard.userManagement._updateUser');
    }

    // Button
    public function createUser(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:20|unique:users,username',
            'email'    => 'required|email|max:60|unique:users,email',
            'password' => ['required', Password::min(5)->numbers()],
            'role'     => 'required|in:customers,admin',
        ], [
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique'    => 'Email này đã được sử dụng.',
        ]);
            $day   = $request->input('day');
            $month = $request->input('month');
            $year  = $request->input('year');
            $birthday = sprintf('%04d-%02d-%02d', $year, $month, $day); 

        User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'], 
            'birthday'  => $birthday,
        ]); 
        return redirect()->route('admin_userManagement.form')->with('adminCreateSuccess', 'Tạo tài khoản thành công!');
    }
}

