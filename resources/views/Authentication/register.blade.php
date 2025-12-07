@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="register-wrapper">
  <div class="glass-card">
    <h3 class="mb-4 text-center">Đăng ký</h3>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Họ và tên đăng nhập</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required placeholder="Nhập tên đăng nhập">
        @error('username') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Ngày sinh</label>
        <div class="d-flex gap-2">
          <select name="day" class="form-select form-control" required>
            <option value="">Ngày</option>
            @for ($d = 1; $d <= 31; $d++)
              <option value="{{ $d }}">{{ $d }}</option>
            @endfor
          </select>

          <select name="month" class="form-select form-control" required>
            <option value="">Tháng</option>
            @for ($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}">{{ $m }}</option>
            @endfor
          </select>

          <select name="year" class="form-select form-control" required>
            <option value="">Năm</option>
            @for ($y = now()->year - 12; $y >= 1900; $y--)
              <option value="{{ $y }}">{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="Nhập địa chỉ email">
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu">
      </div>

      <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    </form>

    <div class="text-center mt-3 auth-utility">
      Đã có tài khoản?
      <a href="{{ route('login.form') }}">Đăng nhập</a> ·
      <a href="{{ url('/') }}">Trang chủ</a>
    </div>
  </div>
</div>
@endsection


@push('styles')
<style>
  /* ======================= */
  /*  NỀN CINEMATIC FULL BODY (giữ giống login) */
  /* ======================= */
  body {
    min-height: 100vh;
    margin: 0;
    padding: 0;

    background-image:
      linear-gradient(135deg, rgba(10,10,25,0.25), rgba(0,0,0,0.60)),
      url('{{ asset('storage/pictures/background-auth1.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-color: #060812;
  }

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
  /*  REGISTER WRAPPER       */
  /* ======================= */
  .register-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    min-height: calc(100vh - 56px);
    padding: 80px 1rem 40px;
  }

  /* ======================= */
  /*  GLASS CARD             */
  /* ======================= */
  .glass-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
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
  }

  /* ===== FORM INPUTS ===== */
  .glass-card .form-control,
  .glass-card .form-select {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
  }

  .glass-card .form-select option {
    color: #000;
    background: #fff;
  }

  .glass-card .form-control::placeholder {
    color: #b8b8b8;
  }

  .glass-card .form-control:focus,
  .glass-card .form-select:focus {
    background: rgba(255,255,255,0.22);
    border-color: rgba(255,255,255,0.55);
  }

  /* ===== BUTTON ===== */
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

</style>
@endpush
