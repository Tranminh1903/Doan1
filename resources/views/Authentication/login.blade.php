@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="login-wrapper">
  <div class="glass-card">
    <h3 class="mb-4 text-center">Đăng nhập</h3>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="Nhập địa chỉ email" autofocus>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-2">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3 auth-utility">
        <div class="form-check m-0">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>
        <button type="button"
                class="btn btn-link p-0 border-0 align-baseline text-decoration-none text-white-50"
                data-bs-toggle="modal"
                data-bs-target="#forgotPasswordModal">
          Quên mật khẩu?
        </button>
      </div>

      <button class="btn btn-primary w-100">Đăng nhập</button>

      <div class="divider my-3 text-center text-light">
        <span>hoặc</span>
      </div>

      <div class="google-btn-wrapper text-center">
        <a href="{{ route('login.google') }}" class="google-btn">
          <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo">
          <span>Đăng nhập bằng Google</span>
        </a>
      </div>
    </form>

    <div class="text-center mt-3 auth-utility">
      Chưa có tài khoản?
      <a href="{{ route('register.form') }}">Đăng ký</a>
      · <a href="{{ url('/') }}">Trang chủ</a>
    </div>
  </div>
</div>

{{-- Modal quên mật khẩu --}}
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content forgot-modal">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title">Quên mật khẩu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body pt-3">
        @if (session('status'))
          <div class="alert alert-success py-2 px-3 small mb-3">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->hasBag('forgot'))
          <div class="alert alert-danger py-2 px-3 small mb-3">
            <ul class="mb-0">
              @foreach ($errors->forgot->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('forget_password.link') }}" class="vstack gap-3">
          @csrf
          <div>
            <label class="form-label">Email dùng để khôi phục</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="nhapemail@domain.com"
                   required>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            Gửi liên kết đặt lại mật khẩu
          </button>
        </form>
      </div>

      <div class="modal-footer border-0 pt-0 small text-muted justify-content-center">
        Nhớ mật khẩu rồi?
        <button type="button" class="btn btn-link p-0 ms-1 text-decoration-none"
                data-bs-dismiss="modal">
          Đăng nhập lại
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* ======================= */
  /*  NỀN CINEMATIC FULL BODY */
  /* ======================= */
  body {
    min-height: 100vh;
    margin: 0;
    padding: 0;

    background-image:
      linear-gradient(
        135deg,
        rgba(10,10,25,0.25),
        rgba(0,0,0,0.60)
      ),
      url('{{ asset('storage/pictures/background-auth2.png') }}');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-color: #060812;
  }

  /* glow + cinematic highlight */
  body::before {
    content: "";
    position: fixed;
    inset: 0;
    pointer-events: none;
    background:
      radial-gradient(circle at 20% 15%, rgba(255,255,255,0.12), transparent 55%),
      radial-gradient(circle at 80% 85%, rgba(255,255,255,0.10), transparent 55%),
      radial-gradient(circle at 50% 40%, rgba(255,255,255,0.06), transparent 45%);
    mix-blend-mode: screen;
    opacity: .75;
    z-index: -1;
  }

  /* cinematic vignette */
  body::after {
    content: "";
    position: fixed;
    inset: -20px;
    pointer-events: none;
    background: radial-gradient(circle, transparent 45%, rgba(0,0,0,0.85) 100%);
    mix-blend-mode: multiply;
    opacity: .9;
    z-index: -1;
  }

  /* ======================= */
  /*  LOGIN WRAPPER          */
  /* ======================= */
  .login-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    min-height: calc(100vh - 56px);
    padding: 80px 1rem 40px;
  }

  /* ======================= */
  /*  GLASS DARK CARD        */
  /* ======================= */
  .glass-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,0.35);
    box-shadow:
        0 25px 50px rgba(0,0,0,0.55),
        0 0 25px rgba(255,255,255,0.05);
    padding: 34px 28px;
    border-radius: 14px;
    color: #fff;
  }

  .glass-card h3 {
    letter-spacing: .06em;
    color: #fff;
  }

  /* ===== INPUT ===== */
  .glass-card .form-control {
      background: rgba(255,255,255,0.18);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff;
  }

  .glass-card .form-control::placeholder {
    color: #b8b8b8;
  }

  .glass-card .form-control:focus {
      background: rgba(255,255,255,0.22);
      border-color: rgba(255,255,255,0.55);
  }

  /* ===== LOGIN BUTTON ===== */
  .glass-card .btn.btn-primary {
    background: #e50914;
    border: none;
    font-weight: 600;
  }

  .glass-card .btn.btn-primary:hover {
    background:#f6121d;
  }

  .auth-utility {
    color: #b3b3b3;
    font-size: .9rem;
  }

  .glass-card a {
    color:#fff;
    text-decoration:none;
  }

  .glass-card a:hover {
    text-decoration:underline;
  }

  /* ======================= */
  /*  GOOGLE BUTTON          */
  /* ======================= */
  .google-btn-wrapper {
    margin-top: 12px;
  }

  .google-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 9px 20px;
    border-radius: 999px;
    border: 1px solid #dadce0;
    background-color: #fff;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.18);
    transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
    white-space: nowrap;
  }

  .google-btn img {
    width: 20px;
    height: 20px;
    display: block;
  }

  .google-btn span {
    font-family: "Roboto", sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #3c4043;
  }

  .google-btn:hover {
    background-color: #f7f8f8;
    box-shadow: 0 6px 18px rgba(0,0,0,0.24);
    transform: translateY(-1px);
  }

  .google-btn:active {
    background-color: #eeeeee;
    box-shadow: 0 2px 6px rgba(0,0,0,0.18);
    transform: translateY(0);
  }

  /* ======================= */
  /*  FORGOT PASSWORD MODAL  */
  /* ======================= */
  .forgot-modal {
    background: rgba(15, 23, 42, 0.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 18px;
    border: 1px solid rgba(148, 163, 184, 0.6);
    color: #e5e7eb;
    box-shadow:
      0 24px 60px rgba(0,0,0,0.85),
      0 0 24px rgba(148,163,184,0.35);
  }

  .forgot-modal .modal-title {
    font-weight: 600;
    letter-spacing: .04em;
  }

  .forgot-modal .form-control {
    background: rgba(15, 23, 42, 0.9);
    border: 1px solid rgba(148,163,184,0.7);
    color: #e5e7eb;
  }

  .forgot-modal .form-control::placeholder {
    color: #9ca3af;
  }

  .forgot-modal .form-control:focus {
    background: rgba(15, 23, 42, 0.95);
    border-color: #e5e7eb;
    box-shadow: 0 0 0 .2rem rgba(37,99,235,.45);
  }

  .forgot-modal .btn.btn-primary {
    background: #2563eb;
    border: none;
    font-weight: 600;
  }

  .forgot-modal .btn.btn-primary:hover {
    background: #1d4ed8;
  }

  .modal-backdrop.show {
    opacity: 0.65;
  }
</style>
@endpush

@push('scripts')
@if (session('open_forgot_modal'))
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('forgotPasswordModal');
    if (modalEl) {
      var modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  });
</script>
@endif
@endpush
