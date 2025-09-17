<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\CustomerController;
use App\Http\Controllers\UserController\AdminController;
use App\Http\Controllers\UserController\ForgetPasswordController;
use App\Http\Controllers\UserController\ResetPasswordController;

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

});
// Auth only (đã đăng nhập)
Route::middleware('auth')->group(function () {
    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Profile account
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');

    //Admin Dashboard - Manage
    Route::post('/adminDashboard/userManagement/_createUser', [AdminController::class, 'createUser'])->name('admin_userManagement.Create');
    Route::get('/adminDashboard', [AdminController::class, 'showAdminDashboard'])->name('admin.form');
    Route::get('/adminDashboard/userManagement/main', [AdminController::class, 'showMainManagementUser'])->name('userManagement_main.form');
    Route::get('/adminDashboard/userManagement/_managerUser', [AdminController::class, 'showManagerUser'])->name('userManagement_managerUser.form');
    Route::get('/adminDashboard/userManagement/_createUser', [AdminController::class, 'showCreateUser'])->name('userManagement_createUser.form');
    Route::get('/adminDashboard/userManagement/_updateUser', [AdminController::class, 'showUpdateUser'])->name('userManagement_updateUser.form');
    Route::get('/booking', function () {return view('booking');})->name('booking');
    Route::get('/booking/{time}', [BookingController::class, 'showByTime'])->name('booking.time');
}); 

