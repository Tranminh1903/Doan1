<?php

namespace App\Http\Controllers\UserController;

use App\Models\UserModels\Customer;
use App\Models\UserModels\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\UserController\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductModels\Ticket;
use App\Models\UserModels\Promotion;

class CustomerController extends Controller
{
    // =============== Profile User ============= //
    public function showProfile(Request $request): View
    {
        $user = Auth::user();
        // Lấy customer kèm quan hệ user
        $customer = Customer::with('user')->where('user_id', $request->user()->id)->firstOrFail();

        // Lịch sử vé đã mua
        $tickets = Ticket::query()
            ->join('orders', 'ticket.order_code', '=', 'orders.order_code')
            ->where('orders.username', $user->username)
            ->select('ticket.*')
            ->with([
                'showtime:showtimeID,movieID,startTime',
                'showtime.movie:movieID,title',
                'seat:seatID',
            ])
            ->orderByDesc('ticket.created_at')
            ->get();
        $total_order_amount = $tickets->pluck('order_code')->unique()->count();
        $totalAmount = $tickets->sum('price');

        // Lấy danh sách khuyến mãi đang hoạt động
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('user.profile', compact('customer', 'totalAmount', 'tickets', 'total_order_amount', 'totalAmount', 'promotions'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate(
            [
                'customer_name' => 'string|max:255',
                'phone'         => 'nullable|string|max:20',
                'sex'           => 'nullable|in:Nam,Nữ,Khác',
            ]
        );
        // Lưu thông tin vào bảng Customers
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();
        $customer->fill($request->only(
            [
                'customer_name'
            ]
        ));
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
