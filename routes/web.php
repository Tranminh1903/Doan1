<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\CustomerController;
use App\Http\Controllers\UserController\AdminController;
use App\Http\Controllers\UserController\ForgetPassword;

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
    Route::post('/forgot_password', [ForgetPassword::class, 'sendResetLink'])->middleware('throttle:5,1') ->name('forget_password.link');
    Route::get('/forget_password', [ForgetPassword::class,'showForget_Password'])->name('forget_password.form');
    
    //Reset Password
    Route::post('/reset_password', [ForgetPassword::class, 'resetPassword'])->name('password.update');
    Route::get('/reset_password/{token}', [ForgetPassword::class, 'showReset_Password'])->name('password.reset');

});
// Auth only (đã đăng nhập)
Route::middleware('auth')->group(function () {
    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Profile account
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');

    //Admin Dashboard - Manage
    Route::post('/adminDashboard_userManagement', [AdminController::class, 'createUser'])->name('admin_userManagement.Create');
    Route::get('/adminDashboard', [AdminController::class, 'showAdminDashboard'])->name('admin.form');
    Route::get('/adminDashboard_userManagement', [AdminController::class, 'showUserManagement'])->name('admin_userManagement.form');
}); 