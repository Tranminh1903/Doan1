<?php


use App\Http\Controllers\StaffController\ReportController;
use App\Http\Controllers\StaffController\AdminController;
use App\Http\Controllers\UserController\AuthController;
use App\Http\Controllers\UserController\HomeController;
use App\Http\Controllers\UserController\MovieController;
use App\Http\Controllers\UserController\OrderController;
use App\Http\Controllers\UserController\TicketController;
use App\Http\Controllers\UserController\BookingController;
use App\Http\Controllers\UserController\CustomerController;
use App\Http\Controllers\UserController\ResetPasswordController;
use App\Http\Controllers\UserController\ForgetPasswordController;
use App\Http\Controllers\UserController\PromotionController;
use App\Http\Controllers\UserController\MovieTheaterController;
use App\Http\Controllers\UserController\GoogleController;
use App\Http\Controllers\UserController\ProfileController;
use App\Http\Controllers\UserController\NewsController;
use App\Http\Controllers\UserController\ContactController;
use App\Http\Controllers\UserController\PolicyController;
use App\Http\Controllers\UserController\AboutController;
use Illuminate\Support\Facades\Route;


// ==== Trang chủ ==== //
    Route::get('/', [HomeController::class,'index'])->name('home');
    // ==== Tìm kiếm phim ==== //
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');

// Trang chủ và chi tiết phim — công khai và tin tức
    Route::get('/movies/{movieID}', [MovieController::class, 'show'])->name('movies.show');
    Route::post('/movies/{movieID}/rate', [MovieController::class, 'rate'])->name('movies.rate');
    Route::get('/news', [NewsController::class, 'index'])->name('news.news');
    Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.news_detail');
// ==== Guest only (chưa đăng nhập) ==== //
Route::middleware('guest')->group(function () {
    // ==== Login ====//
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
  
    // ==== Register Account ==== //
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
  
    // ==== Hạn chế spam, tối đa là 5 lần cho 1 phút - Forget Password ==== //
    Route::post('/forgot_password', [ForgetPasswordController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('forget_password.link');
    Route::get('/forget_password', [ForgetPasswordController::class, 'showForget_Password'])->name('forget_password.form');

    // ==== Reset Password ==== //
    Route::post('/reset_password', action: [ResetPasswordController::class, 'resetPassword'])->name('password.update');
    Route::get('/reset_password/{token}', [ResetPasswordController::class, 'showReset_Password'])->name('password.reset');

    //footer
    Route::get('/contact', [ContactController::class, 'index'])->name('contact'); // Liên hệ
    Route::get('/terms', [PolicyController::class, 'index'])->name('terms'); // Chính sách và quy định
    Route::get('/about', [AboutController::class, 'index'])->name('about'); // Giới thiệu
});

// ==== Auth only (đã đăng nhập)  ==== //
Route::middleware('auth')->group(function () {
    // ==== Logout ==== //
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==== Profile account  ==== //
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/user/avatar', [CustomerController::class], 'updateAvatar')->name('avatar.update');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');

    // ==== Order - Đặt hàng mới ==== //
    Route::post('/create-order', [OrderController::class, 'createOrder'])->name('orders.create');

    // ==== Kiểm tra trạng thái thanh toán (frontend gọi để reload) ==== //
    Route::get('/check-payment/{orderCode}', [OrderController::class, 'checkPayment'])->name('orders.check');

    // ==== Hủy đơn khi hết hạn (frontend gọi khi timeLeft = 0)  ==== //
    Route::post('/orders/{orderCode}/expire', [OrderController::class, 'expire'])->name('orders.expire');
    
    // ==== Đồng bộ thanh toán từ Google Sheet  ==== //
    Route::get('/sync-payments', [OrderController::class, 'syncPayments'])->name('orders.sync');
    
    // ==== Kiểm tra và đồng bộ thanh toán cho đơn hàng cụ thể ==== //
    Route::get('/orders/check-sync/{orderCode}', [OrderController::class, 'checkAndSyncPayment']);

    // ==== Trang chọn suất chiếu ==== //
    Route::get('/select-showtimes/{movieID}', [BookingController::class, 'selectShowtime'])->name('select.showtime');
    Route::get('/booking/start/{showtimeID}', [BookingController::class, 'start'])->name('booking.start');
    Route::get('/booking/{showtimeID}', [BookingController::class, 'booking'])->name('booking.time');

    // ==== Giữ ghế chỉ 1 người được chọn ==== //
    Route::post('/booking/hold', [BookingController::class, 'holdSeat'])->name('booking.hold');

    // ==== Kiểm tra và giải phóng ghế hết hạn ==== //
    Route::get('/check-expired-seats/{showtimeID}', [BookingController::class, 'checkExpiredSeats']);
    // ==== Promotion  ==== //
    Route::prefix('promotion')->group(function () {
        Route::get('/active', [PromotionController::class, 'getActivePromotions']);
        Route::post('/apply', [PromotionController::class, 'applyPromotion']);
        Route::post('/mark-used/{code}', [PromotionController::class, 'markAsUsed']);
    });
    // ==== Movie Theater  ==== //
    Route::post('/user/avatar', [ProfileController::class, 'updateAvatar'])
    ->middleware('auth')
    ->name('avatar.update');
});

Route::middleware('auth', 'admin')->group(function () {

    // ====  Admin Dashboard - Index ==== //
    Route::get('/adminDashboard', [AdminController::class, 'showAdminDashboard'])->name('admin.form');

    // ==== Admin Dashboard - User Management ==== //
    Route::get('/adminDashboard/userManagement/main', [AdminController::class, 'showMainManagementUser'])->name('admin.userManagement_main.form');
    
    // ==== User CSV ==== //
    Route::get('/adminDashboard/users/template/csv', [AdminController::class, 'userTemplateCsv'])->name('usersManage.template_csv');
    Route::get('/adminDashboard/users/export/csv', [AdminController::class, 'userExportCsv'])->name('usersManage.export_csv');
    Route::post('/adminDashboard/users/import/csv', [AdminController::class, 'userImportCsv'])->name('usersManage.import_csv');
    // ==== Admin Dashboard - Button for _updateUser  ==== //
    Route::put('/adminDashboard/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/adminDashboard/users/{user}', [AdminController::class, 'delete'])->name('users.delete');
    Route::post('/adminDashboard/users', [AdminController::class, 'store'])->name('users.store');
    Route::post('/adminDashboard/users/upload-avatar', [AdminController::class, 'uploadAvatar'])->name('users.upload_avatar');
    Route::patch('/adminDashboard/users/{user}/toggle-lock', [AdminController::class, 'toggleStatus'])->name('users.toggleStatus');

    // ==== Admin Dashboard - Manage Movies ==== //
    Route::get('/adminDashboard/moviesManagement/main', [AdminController::class, 'showMain'])->name('admin.moviesManagement_main.form');
    Route::post('/adminDashboard/movies', [AdminController::class, 'movieStore'])->name('moviesManage.store');
    Route::put('/adminDashboard/movies/{movie}', [AdminController::class, 'movieUpdate'])->name('moviesManage.update');
    Route::delete('/adminDashboard/movies/{movie}', [AdminController::class, 'movieDestroy'])->name('moviesManage.delete');
    Route::post('/adminDashboard/movies/upload-poster', [AdminController::class, 'uploadPoster'])->name('moviesManage.upload_poster');

    // ==== CSV ==== //
    Route::get('/adminDashboard/movies/export/csv',  [AdminController::class, 'movieExportCsv'])->name('moviesManage.export_csv');
    Route::get('/adminDashboard/movies/template/csv', [AdminController::class, 'movieTemplateCsv'])->name('moviesManage.template_csv');
    Route::post('/adminDashboard/movies/import/csv', [AdminController::class, 'movieImportCsv'])->name('moviesManage.export_csv');

    // ==== Banner ==== //
    Route::post('adminDashboard/movies/{movie}/banner',  [AdminController::class, 'setBanner'])->name('moviesManage.banner_set');
    Route::delete('adminDashboard/movies/{movie}/banner', [AdminController::class, 'unsetBanner'])->name('moviesManage.banner_unset');
    Route::get('/banners.json', [MovieController::class, 'bannersJson'])->name('banners.json');

    // ==== Report ==== //
    Route::get('adminDashboard/reports/revenue', [ReportController::class, 'index'])->name('admin.reports.revenue');
    Route::get('adminDashboard/reports/revenue-data', [ReportController::class, 'ajaxData'])->name('reports.revenue.ajax');
    Route::get('adminDashboard/reports/revenue-movie', [ReportController::class, 'revenueByMovie'])->name('reports.revenue.movie');
    Route::get('adminDashboard/reports/revenue-movie-data', [ReportController::class, 'ajaxRevenueByMovie'])->name('reports.revenue.movie.ajax');

    // ==== Promotion ==== //
    Route::get('adminDashboard/promotion', [AdminController::class, 'showPromotion'])->name('admin.promotionManagement.form');
    Route::post('adminDashboard/promotion/store', [AdminController::class, 'PromotionStore'])->name('admin.promotionManagement.store');
    Route::put('adminDashboard/promotion/update/{id}', [AdminController::class, 'PromotionUpdate'])->name('admin.promotionManagement.update');
    Route::delete('adminDashboard/promotion/delete/{id}', [AdminController::class, 'PromotionDestroy'])->name('admin.promotionManagement.delete');

    // ==== Showtime ==== //
    Route::get('adminDashboard/showtime', [AdminController::class, 'showShowtime'])->name('admin.showtimeManagement.form');
    Route::post('adminDashboard/showtime/store', [AdminController::class, 'showtimeStore'])->name('admin.showtime.store');
    Route::put('adminDashboard/showtime/update/{id}', [AdminController::class, 'showtimeUpdate'])->name('admin.showtime.update');
    Route::delete('adminDashboard/showtime/delete/{showtime}', [AdminController::class, 'showtimeDestroy'])->name('admin.showtime.delete');

    // ==== Movie Theater ==== //
    Route::get('adminDashboard/movieTheater', [AdminController::class, 'showMovieTheater'])->name('admin.movietheaterManagement.form');
    Route::post('adminDashboard/movieTheater/store', [AdminController::class, 'theaterStore'])->name('admin.movietheaterManagement.store');
    Route::delete('adminDashboard/movieTheater/destroy/{movieTheater}', [AdminController::class, 'theaterDestroy'])->name('admin.movietheaterManagement.delete');
    Route::put('adminDashboard/movieTheater/update/{id}', [AdminController::class, 'theaterUpdate'])->name('admin.movietheaterManagement.update');
    Route::get('adminDashboard/movieTheater/{id}/seats', [AdminController::class, 'showSeats'])->name('admin.movietheaterManagement.seats');

    // ==== News ==== //
    Route::get('adminDashboard/news', [AdminController::class, 'showNews'])->name('admin.newsManagement.form');
    Route::post('adminDashboard/news', [AdminController::class, 'newsStore'])->name('admin.newsManage.store');
    Route::put('adminDashboard/news/{news}', [AdminController::class, 'newsUpdate'])->name('admin.newsManage.update');
    Route::delete('adminDashboard/news/{news}', [AdminController::class, 'newsDestroy'])->name('admin.newsManage.delete');
    Route::post('adminDashboard/news/upload-image', [AdminController::class, 'uploadImage'])->name('admin.newsManage.upload_image');
});
