<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;

//Trang chủ
    Route::get('/', [HomeController::class,'index'])->name('home');
// Guest only (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
});
// Auth only (đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::post('/', [AuthController::class, 'logout'])->name('logout');
});