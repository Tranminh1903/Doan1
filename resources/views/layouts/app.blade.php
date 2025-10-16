<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Movie Tickets')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  
  <style>
  </style>
  @stack('head')
</head>
<body>

{{-- Giao diện khi chưa đăng nhập --}}
@guest
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">🎬 DuManMinh Cinema</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ url('/#phimdangchieu') }}">Phim đang chiếu</a></li>
      </ul>
      @if (!request()->routeIs('login.form') && !request()->routeIs('register.form'))
        <div class="d-flex gap-2">
          <a class="btn btn-outline-primary" href="{{ route('login.form') }}">Đăng nhập</a>
          <a class="btn btn-outline-primary" href="{{ route('register.form') }}">Đăng ký</a>
        </div>
      @endif
    </div>
  </div>
</nav>
@endguest

{{-- Giao diện khi đăng nhập thành công --}}
@auth
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">🎬 DuManMinh Cinema</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ url('/#phimdangchieu') }}">Phim đang chiếu</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
          <p class="mb-0">Xin chào, <b>{{ auth()->user()->username }}</b></p>
        @if (auth()->user()->isAdmin())
            <div class="d-flex gap-2">
              <a class="btn btn-outline-primary" href="{{ route('admin.form') }}">Admin Dashboard</a>
            </div>
        @else
            <div class="d-flex gap-2">
              <a class="btn btn-outline-primary" href="{{ route('profile') }}">Xem hồ sơ</a>
            </div>
        @endif
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-danger">Đăng xuất</button>
        </form>
      </div>
    </div>
  </div>
</nav>
@endauth

<main class="container my-4">
  @yield('content')
</main>

<footer class="footer-dmm" role="contentinfo">
  <div class="footer-dmm__wrap">

    <div class="footer-dmm__col">
      <h4 class="footer-dmm__title">DuManMinh Cinema</h4>
      <ul class="footer-dmm__list">
        <li><a href="/about">Giới Thiệu</a></li>
        <li><a href="/online">Tiện Ích Online</a></li>
        <li><a href="/gift-card">Thẻ Quà Tặng</a></li>
        <li><a href="/jobs">Tuyển Dụng</a></li>
        <li><a href="/ads">Liên Hệ Quảng Cáo</a></li>
        <li><a href="/partners">Dành cho đối tác</a></li>
      </ul>
    </div>


    <div class="footer-dmm__col">
      <h4 class="footer-dmm__title">Điều khoản sử dụng</h4>
      <ul class="footer-dmm__list">
        <li><a href="/terms">Điều Khoản Chung</a></li>
        <li><a href="/trade-terms">Điều Khoản Giao Dịch</a></li>
        <li><a href="/payment-policy">Chính Sách Thanh Toán</a></li>
        <li><a href="/privacy">Chính Sách Bảo Mật</a></li>
        <li><a href="/faq">Câu Hỏi Thường Gặp</a></li>
      </ul>
    </div>

    <div class="footer-dmm__col">
      <h4 class="footer-dmm__title">Kết nối với chúng tôi</h4>
      <div class="footer-dmm__socials">
        <a class="social-btn fb" href="#" aria-label="Facebook">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M22 12a10 10 0 10-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.57 1.53-3.99 3.87-3.99 1.12 0 2.3.2 2.3.2v2.53h-1.29c-1.27 0-1.66.79-1.66 1.6V12h2.83l-.45 2.91h-2.38v7.04A10 10 0 0022 12z"/></svg>
        </a>
        <a class="social-btn yt" href="#" aria-label="YouTube">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31 31 0 000 12a31 31 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31 31 0 0024 12a31 31 0 00-.5-5.8zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
        </a>
        <a class="social-btn ig" href="#" aria-label="Instagram">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm10 2H7a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3zm-5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm6-1a1 1 0 110 2 1 1 0 010-2z"/></svg>
        </a>
        <a class="social-btn zalo" href="#" aria-label="Zalo">
          <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H8.5l-3.3 1.7a.8.8 0 01-1.2-.7V4a1 1 0 011-1zm4 4v2h5.6L8 16v2h8v-2H10.4L16 9V7H8z"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-dmm_col">
      <h4 class="footer-dmm__title">Chăm sóc khách hàng</h4>
      <ul class="footer-dmm__info">
        <li>Hotline: <strong>0978140521</strong></li>
        <li>Giờ làm việc: <strong>8:00 - 22:00</strong> (Tất cả các ngày, gồm Lễ Tết)</li>
        <li>Email hỗ trợ: <a href="mailto:hotro@dumanminh.vn">hotro@dumanminh.vn</a></li>
      </ul>
    </div>
  </div>
</footer>



{{-- Import thư viện --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

{{-- Thông báo đăng nhập thành công --}}
<script>
    @if(session('LoginSuccess'))
        toastr.success("{{ session('LoginSuccess') }}", "Thành công", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,  
            progressBar: true,
        });
        toastr.success("{{ session('LoginSuccess') }}", "Chào mừng khách hàng đã trở lại", {
            positionClass: "toast-bottom-right",
            timeOut: 3000, 
            progressBar: true,
        });
    @endif

    @if(session('RegisterSuccess'))
        toastr.success("{{ session('RegisterSuccess') }}", "Thành công", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,  
            progressBar: true,
        });
    @endif
    
    @if(session('adminCreateSuccess'))
        toastr.success("{{ session('adminCreateSuccess') }}", "Thành công", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,  
            progressBar: true,
        });
    @endif

    @if(session('LogoutSuccess'))
        toastr.success("{{ session('LogoutSuccess') }}", "Bạn đã đăng xuất", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,
            progressBar: true,
        });
    @endif 
    
    @if(session('updateProfileSuccess'))
        toastr.success("{{ session('updateProfileSuccess') }}", "Bạn đã cập nhật thông tin thành công!", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,
            progressBar: true,
        });
    @endif
  
    @if (session('status'))
      toastr.success(@json(session('status')),{
        positionClass: 'toast-bottom-right',
        timeOut: 3000,
        progressBar: true,
      });
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger small mb-2">
          <strong>Vui lòng kiểm tra lại:</strong>
        <ul class="mb-0">
          @foreach($errors->all() as $e)
          < li>{{ $e }}</li>
          @endforeach
        </ul>
    </div>
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}", "Lỗi", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,
            progressBar: true,
        });
    @endif
</script>
</body>
</html>
