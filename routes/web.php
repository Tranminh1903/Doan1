<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\AdminController;
use App\Http\Controllers\UserController\MovieController;
use App\Http\Controllers\UserController\OrderController;
use App\Http\Controllers\UserController\ReportController;
use App\Http\Controllers\UserController\TicketController;
use App\Http\Controllers\UserController\BookingController;
use App\Http\Controllers\UserController\CustomerController;
use App\Http\Controllers\UserController\ResetPasswordController;
use App\Http\Controllers\UserController\ForgetPasswordController;
use App\Http\Controllers\UserController\PromotionController;
use App\Http\Controllers\UserController\GoogleController;

//Trang chủ
    Route::get('/', [HomeController::class,'index'])->name('home');
// Trang chủ và chi tiết phim — công khai
    Route::get('/movies/{movieID}', [MovieController::class, 'show'])->name('movies.show');
    Route::post('/movies/{movieID}/rate', [App\Http\Controllers\UserController\MovieController::class, 'rate'])->name('movies.rate');


// Guest only (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    //Google Login
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

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
    Route::post('/user/avatar', [CustomerController::class], 'updateAvatar')->name('avatar.update');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');

    //Order
    // Đặt hàng mới
    Route::post('/create-order', [OrderController::class, 'createOrder'])->name('orders.create');
    // Kiểm tra trạng thái thanh toán (frontend gọi để reload)
    Route::get('/check-payment/{orderCode}', [OrderController::class, 'checkPayment'])->name('orders.check');
    // Hủy đơn khi hết hạn (frontend gọi khi timeLeft = 0)
    Route::post('/orders/{orderCode}/expire', [OrderController::class, 'expire'])->name('orders.expire');
    // Đồng bộ thanh toán từ Google Sheet
    Route::get('/sync-payments', [OrderController::class, 'syncPayments'])->name('orders.sync');
    // Trang chọn suất chiếu

    Route::get('/select-showtimes/{movieID}', [BookingController::class, 'selectShowtime'])->name('select.showtime');
    Route::get('/booking/{showtimeID}', [BookingController::class, 'booking'])->name('booking.time');
    Route::get('/booking/start/{showtimeID}', [BookingController::class, 'start'])->name('booking.start');
    //Booking
    Route::get('/booking/{showtimeID}', [BookingController::class, 'booking'])->name('booking.time');
    // giữ ghế chỉ 1 người được chọn
    Route::post('/booking/hold', [BookingController::class, 'holdSeat'])->name('booking.hold');
    // Kiểm tra và giải phóng ghế hết hạn
    Route::get('/check-expired-seats/{showtimeID}', [\App\Http\Controllers\UserController\BookingController::class, 'checkExpiredSeats']);
    // Promotion 
    Route::prefix('promotion')->group(function () {
        Route::get('/active', [PromotionController::class, 'getActivePromotions']);
        Route::post('/apply', [PromotionController::class, 'applyPromotion']);
        Route::post('/mark-used/{code}', [PromotionController::class, 'markAsUsed']);
        });
    }); 

Route::middleware('auth','admin')->group(function() {
    //Admin Dashboard - Manage User
    Route::get('/adminDashboard', [AdminController::class, 'showAdminDashboard'])->name('admin.form');

    //Admin Dashboard - User Management
    Route::get('/adminDashboard/userManagement/main', [AdminController::class, 'showMainManagementUser'])->name('admin.userManagement_main.form');

    //Admin Dashboard - Button for _updateUser 
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'delete'])->name('users.delete');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('users.store');
    Route::post('/admin/users/upload-avatar', [AdminController::class, 'uploadAvatar'])->name('users.upload_avatar');
    //Admin Dashboard - Manage Movies
    Route::get('/adminDashboard/moviesManagement/main',[AdminController::class,'showMain'])->name('admin.moviesManagement_main.form');

    Route::post('/admin/movies', [AdminController::class, 'movieStore'])->name('moviesManage.store');
    Route::put('/admin/movies/{movie}', [AdminController::class, 'movieUpdate'])->name('moviesManage.update');
    Route::delete('/admin/movies/{movie}', [AdminController::class, 'movieDestroy'])->name('moviesManage.delete');
    Route::post('/admin/movies/upload-poster', [MovieController::class, 'uploadPoster'])->name('moviesManage.upload_poster');

    // CSV
    Route::get('/admin/movies/export/csv',  [AdminController::class,'movieExportCsv'])->name('moviesManage.export_csv');
    Route::get('/admin/movies/template/csv',[AdminController::class,'movieTemplateCsv'])->name('moviesManage.template_csv');
    Route::post('/admin/movies/import/csv', [AdminController::class,'movieImportCsv'])->name('moviesManage.export_csv');

    //Set Banner
    Route::post('admin/movies/{movie}/banner',  [AdminController::class, 'setBanner'])->name('moviesManage.banner_set');
    Route::delete('admin/movies/{movie}/banner', [AdminController::class, 'unsetBanner'])->name('moviesManage.banner_unset');
    
    Route::get('reports/revenue', [ReportController::class, 'index'])->name('admin.reports.revenue');
    Route::get('reports/revenue-data', [ReportController::class, 'ajaxData'])->name('reports.revenue.ajax');
    Route::get('reports/revenue-movie', [ReportController::class, 'revenueByMovie'])->name('reports.revenue.movie');
    Route::get('reports/revenue-movie-data', [ReportController::class, 'ajaxRevenueByMovie'])->name('reports.revenue.movie.ajax');
});