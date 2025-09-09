<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;

//Trang chủ
    Route::get('/', [HomeController::class,'index'])->name('home');
// Guest only (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
// Auth only (đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::post('/', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});