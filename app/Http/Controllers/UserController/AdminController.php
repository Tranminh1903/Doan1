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
    public function showAdminDashboard(): View
    {
        return view('adminDashboard');
    }

    public function showUserManagement(): View
    {
        return view('adminDashboard_userManagement');
    }

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
    // public function index(): View
    // {
    //     $admins = Admin::with('user')->paginate(10);
    //     return view('admin.admins.index', compact('admins'));
    // }

    // public function create(): View
    // {
    //     return view('admin.admins.create');
    // }

    // public function store(Request $r)
    // {
    //     $user = User::create([
    //         'username' => $r->username,
    //         'email'    => $r->email,
    //         'password' => bcrypt($r->password),
    //         'role'     => 'admin'
    //     ]);

    //     Admin::create([
    //         'user_id'  => $user->id,
    //         'position' => $r->position
    //     ]);

    //     return redirect()->route('admin.admins.index')->with('success','Thêm admin thành công');
    // }
