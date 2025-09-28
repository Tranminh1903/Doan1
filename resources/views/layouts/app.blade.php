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

<footer class="border-top py-4">
  <div class="container small text-muted">&copy; {{ date('Y') }} DuManMinh Cinema</div>
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
