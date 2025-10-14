<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\CustomerController;
use App\Http\Controllers\UserController\AdminController;
use App\Http\Controllers\UserController\ForgetPasswordController;
use App\Http\Controllers\UserController\ResetPasswordController;
use App\Http\Controllers\UserController\OrderController;
use App\Http\Controllers\UserController\BookingController;
use App\Http\Controllers\UserController\MovieController;

//Trang chủ
    Route::get('/', [HomeController::class,'index'])->name('home');
// Guest only (chưa đăng nhập)
Route::middleware('guest')->group(function () {

    //Login Account
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');

    //Register Account
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');

    //Hạn chế spam, tối đa là 5 lần cho 1 phút - Forget Password
    Route::post('/forgot_password', [ForgetPasswordController::class, 'sendResetLink'])->middleware('throttle:5,1') ->name('forget_password.link');
    Route::get('/forget_password', [ForgetPasswordController::class,'showForget_Password'])->name('forget_password.form');
    
    //Reset Password
    Route::post('/reset_password', action: [ResetPasswordController::class, 'resetPassword'])->name('password.update');
    Route::get('/reset_password/{token}', [ResetPasswordController::class, 'showReset_Password'])->name('password.reset');

    // Trang chủ và chi tiết phim — công khai
    Route::get('/movies/{movieID}', [MovieController::class, 'show'])->name('movies.show');

});

// Auth only (đã đăng nhập)
Route::middleware('auth')->group(function () {
    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Profile account
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/user/avatar', [CustomerController::class], 'updateAvatar')->name('avatar.update');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');
    //Booking
    Route::get('/booking/{showtime}', [BookingController::class, 'booking'])->name('booking.time');

    //Order
    // Đặt hàng mới
    Route::post('/create-order', [OrderController::class, 'createOrder'])->name('orders.create');
    // Kiểm tra trạng thái thanh toán (frontend gọi để reload)
    Route::get('/check-payment/{orderCode}', [OrderController::class, 'checkPayment'])->name('orders.check');
    // Hủy đơn khi hết hạn (frontend gọi khi timeLeft = 0)
    Route::post('/orders/{orderCode}/expire', [OrderController::class, 'expire'])->name('orders.expire');
    // Đồng bộ thanh toán từ Google Sheet
    Route::get('/sync-payments', [OrderController::class, 'syncPayments'])->name('orders.sync');
    }); 

Route::middleware('auth','admin')->group(function() {
     //Admin Dashboard - Manage User
    Route::post('/adminDashboard/userManagement/_createUser', [AdminController::class, 'createUser'])->name('admin_userManagement.Create');
    Route::get('/adminDashboard', [AdminController::class, 'showAdminDashboard'])->name('admin.form');
    Route::get('/adminDashboard/userManagement/main', [AdminController::class, 'showMainManagementUser'])->name('userManagement_main.form');
    Route::get('/adminDashboard/userManagement/_updateUser', [AdminController::class, 'showUpdateUser'])->name('userManagement_updateUser.form');
    Route::get('/adminDashboard/userManagement/_createUser', [AdminController::class, 'showCreateUser'])->name('userManagement_createUser.form');

    //Admin Dashboard - Button for _updateUser 
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'delete'])->name('users.delete');

    //Admin Dashboard - Manage Movies
    Route::get('/adminDashboard/moviesManagement/_createMovies',[AdminController::class, 'showCreateMovies'])->name('moviesManagement_createMovies.form');
    Route::get('/adminDashboard/moviesManagement/_updateMovies',[AdminController::class,'showUpdateMovies'])->name('moviesManagement_updateMovies.form');
    Route::get('/adminDashboard/moviesManagement/main',[AdminController::class,'showMain'])->name('moviesManagement_main.form');
});