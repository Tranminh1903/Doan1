<?php
namespace App\Http\Controllers\UserController;

use App\Models\UserModels\User;
use Illuminate\Http\RedirectResponse;
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
    public function showCheckUser(Request $request): View
    {
    $query = User::query();

    // Tìm kiếm theo từ khóa (username hoặc email)
    if ($request->filled('q')) {
        $query->where(function($q) use ($request) {
            $q->where('username', 'like', '%' . $request->q . '%')
              ->orWhere('email', 'like', '%' . $request->q . '%');
        });
    }
    // Lọc theo role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }
    // Lọc theo status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    // Sắp xếp mới nhất + phân trang
    $users = $query->latest()->paginate(10)->withQueryString();

    return view('adminDashboard.userManagement._checkUser', compact('users'));
    }
    
    public function showCreateUser(Request $request): View
    {
        return view('adminDashboard.userManagement._createUser');
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
        return redirect()->route('userManagement_checkUser.form')->with('adminCreateSuccess', 'Tạo tài khoản thành công!');
    }



    public function edit(User $user)
    {
        // Trả về view có form edit
        return view('adminDashboard.userManagement._checkUser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,customers',
            'status'   => 'required|in:active,blocked',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = 'storage/' . $path;
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Không cho tự xóa mình
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của mình.');
        }
        try {
            $user->forceDelete();
            return back()->with('success', 'Đã xóa người dùng.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể xóa người dùng. Kiểm tra ràng buộc dữ liệu.');
        }
    }
}
