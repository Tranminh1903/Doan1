<!doctype html>
  <html lang="vi">
  <head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        prefix: 'tw-',
        corePlugins: { preflight: false },
        important: '#payment-root'
      }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Movie Tickets')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <style>
    </style>
    @stack('head')
    @stack('styles')
  </head>
  <body>

  @guest
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('storage/pictures/logo-dmm.png') }}" alt="DMM  Logo" class="navbar-logo">
      </a>

      <div class="nav-menu-left d-flex align-items-center ms-3">
        <a href="/" class="nav-link-custom">Trang chủ</a>
      </div>

      <div class="dmm-search flex-grow-1 ms-3">
        <div class="input-group">
          <input type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm phim..." aria-label="Tìm kiếm phim">
        </div>
      </div>

      <div class="nav-menu-right d-flex align-items-center gap-4 me-4">
        <a href="{{ route('news.news') }}" class="nav-link-custom">Tin tức</a>
      </div>
      <div class="d-flex gap-2">
          <a class="btn auth-btn auth-btn--ghost" href="{{ route('register.form') }}">Đăng ký</a>
          <a class="btn auth-btn auth-btn--solid" href="{{ route('login.form') }}">Đăng nhập</a>
      </div>
    </div>
  </nav>
  @endguest

  {{-- Giao diện khi đăng nhập thành công --}}
  @auth
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          <img src="{{ asset('storage/pictures/logo-dmm.png') }}" alt="DMM Logo" class="navbar-logo">
        </a>

        <div class="nav-menu-left d-flex align-items-center ms-3">
          <a href="/" class="nav-link-custom">Trang chủ</a>
        </div>

        <div class="dmm-search flex-grow-1 ms-3">
          <div class="input-group">
            <input type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm phim..." aria-label="Tìm kiếm phim">
          </div>
        </div>

        <div class="nav-menu-right d-flex align-items-center gap-4 me-4">
          <a href="/news" class="nav-link-custom">Tin tức</a>
        </div>

        <div class="dropdown">
            <button class="user-dropdown-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <span class="user-avatar">
                    <img src="{{ asset('storage/pictures/dogavatar.jpg') }}" alt="avatar">
                </span>
                <span>{{ auth()->user()->username }}</span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="dropdown-menu user-dropdown-menu mt-2 p-2 shadow">
                <a class="dropdown-item user-dropdown-item" href="{{ route('profile') }}">Thông tin cá nhân</a>
                @if (auth()->user()->isAdmin())
                <a class="dropdown-item user-dropdown-item" href="{{ route('admin.form') }}">Chuyển sang AdminDashboard</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item user-dropdown-item text-danger">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
  </nav>
  @endauth

  <main class="container my-4">
      @if($errors->any())
          <div class="alert alert-danger small mb-2">
            <strong>Vui lòng kiểm tra lại:</strong>
          <ul class="mb-0">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
          </ul>
      </div>
      @endif
    @yield('content')
  </main>

  <footer class="footer-dmm" role="contentinfo">
      <div class="footer-dmm__wrap">
          <nav class="footer-dmm__nav">
              <a href="/">Trang chủ</a>
              <a href="/about">Giới thiệu</a>
              <a href="/terms">Điều khoản</a>
              <a href="/payment-policy">Thanh toán</a>
              <a href="/contact">Liên hệ</a>
          </nav>

          <div class="footer-dmm__center">

              <div class="footer-dmm__logo-row">
                  <img
                      src="{{ asset('storage/pictures/logo-dmm.png') }}"
                      alt="DMM CINEMA"
                      class="footer-dmm__logo"
                  >
              </div>

              <p class="footer-dmm__intro">
                  DMM CINEMA là hệ thống rạp chiếu phim được thành lập bởi ba thành viên cốt lõi — những người có chung niềm đam mê với nghệ thuật điện ảnh và công nghệ giải trí hiện đại.
                  Chúng tôi mang đến trải nghiệm xem phim chân thực, tiện nghi và đầy cảm xúc.
              </p>

              <ul class="footer-dmm__info">
                  <li>Hotline: <strong>0978140521</strong></li>
                  <li>Giờ làm việc: <strong>8:00 - 22:00</strong> (Tất cả các ngày, gồm Lễ Tết)</li>
                  <li>Email hỗ trợ: <a href="mailto:hotro@dumanminh.vn">hotro@dumanminh.vn</a></li>
              </ul>

              <div class="footer-dmm__socials">
                  <a class="social-btn fb" href="#" aria-label="Facebook">
                      <svg viewBox="0 0 24 24" width="18" height="18">
                          <path fill="currentColor" d="M22 12a10 10 0 10-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.57 1.53-3.99 3.87-3.99 1.12 0 2.3.2 2.3.2v2.53h-1.29c-1.27 0-1.66.79-1.66 1.6V12h2.83l-.45 2.91h-2.38v7.04A10 10 0 0022 12z"/>
                      </svg>
                  </a>
                  <a class="social-btn yt" href="#" aria-label="YouTube">
                      <svg viewBox="0 0 24 24" width="18" height="18">
                          <path fill="currentColor" d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31 31 0 000 12a31 31 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31 31 0 0024 12a31 31 0 00-.5-5.8zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                      </svg>
                  </a>
                  <a class="social-btn ig" href="#" aria-label="Instagram">
                      <svg viewBox="0 0 24 24" width="18" height="18">
                          <path fill="currentColor" d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm10 2H7a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3zm-5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm6-1a1 1 0 110 2 1 1 0 010-2z"/>
                      </svg>
                  </a>
                  <a class="social-btn zalo" href="#" aria-label="Zalo">
                      <svg viewBox="0 0 24 24" width="18" height="18">
                          <path fill="currentColor" d="M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H8.5l-3.3 1.7a.8.8 0 01-1.2-.7V4a1 1 0 011-1zm4 4v2h5.6L8 16v2h8v-2H10.4L16 9V7H8z"/>
                      </svg>
                  </a>
              </div>
          </div>

          <div class="footer-dmm__copy">
              Copyright {{ now()->year }} © DMM CINEMA. All rights reserved.
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

    @if(session('error'))
        toastr.error("{{ session('error') }}", "Lỗi", {
            positionClass: "toast-bottom-right",
            timeOut: 3000,
            progressBar: true,
        });
    @endif

    document.addEventListener('DOMContentLoaded', function () {
        // ===== navbar đổi màu khi scroll =====
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            const toggleNavbarBg = () => {
                if (window.scrollY > 10) {
                    navbar.classList.add('nav-scrolled');
                } else {
                    navbar.classList.remove('nav-scrolled');
                }
            };
            toggleNavbarBg();
            window.addEventListener('scroll', toggleNavbarBg);
        }

        // ===== search phim =====
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return; // trang nào không có ô search thì thôi

        const baseUrl = "{{ route('movies.search') }}";

        const nowContainer    = document.getElementById('movieListContainer');   
        const comingContainer = document.getElementById('comingSoonContainer');  

        function updateSection(container, type, q) {
            if (!container) return;

            const url = new URL(baseUrl, window.location.origin);
            if (q && q.trim() !== '') {
                url.searchParams.set('q', q.trim());
            }
            url.searchParams.set('type', type);

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(err => console.error(err));
        }

        function triggerSearch() {
            const q = searchInput.value || '';

            // Nếu là trang home: có 2 block
            if (nowContainer || comingContainer) {
                updateSection(nowContainer,    'now_showing',  q);
                updateSection(comingContainer, 'coming_soon',  q);
            } else {
                // Trang khác mà vẫn muốn search: redirect sang URL search full
                window.location.href = baseUrl + '?q=' + encodeURIComponent(q);
            }
        }

        // Bấm Enter trong ô search
        searchInput.addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                triggerSearch();
            }
        });
    });

  </script>
  @stack('scripts')
  </body>
  </html>
