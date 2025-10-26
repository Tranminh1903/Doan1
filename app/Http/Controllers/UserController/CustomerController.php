<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\Customer;
use App\Models\UserModels\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\UserController\Controller;

class CustomerController extends Controller
{
public function showProfile(Request $request): View
    {
        // Lấy customer kèm quan hệ user
        $customer = Customer::with('user')->where('user_id', $request->user()->id)->firstOrFail();
        //Tổng tiêu dùng của 1 khách hàng
        $totalAmount = Order::where('username', $request->user()->id)->sum('amount');
        return view('user.profile', compact('customer', 'totalAmount'));
    }

public function updateProfile(Request $request)
    {
        $request->validate(
        [
            'customer_name' => 'string|max:255',
            'phone'         => 'nullable|string|max:20',
            'sex'           => 'nullable|in:Nam,Nữ,Khác',
        ]);
        // Lưu thông tin vào bảng Customers
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();
        $customer->fill($request->only(
        [
            'customer_name'
        ]));
        $customer->save(); 
        //Lưu thông tin vào bảng Users
        $user = $request->user();
        $user->fill($request->only([
            'phone',
            'sex'
        ]));
        $user->save();
        return redirect()->route('profile', ['tab' => 'account'])->with('updateProfileSuccess', 'Bạn đã cập nhật thông tin thành công!');
    }
}