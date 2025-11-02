@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="auth-bg">
  <span class="mountain"></span>

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
        <label for="birthday" class="form-label">Ngày sinh</label>
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
            @for ($y = now()->year-12; $y >= 1900; $y--)
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

      <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    </form>

    <div class="text-center mt-3 auth-utility">
      Đã có tài khoản?
      <a href="{{ route('login.form') }}">Đăng nhập</a> ·
      <a href="{{ route('forget_password.form') }}">Quên mật khẩu</a> ·
      <a href="{{ url('/') }}">Trang chủ</a>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* ==== NỀN & GLASS CARD (giống login) ==== */
  .auth-bg {
    overflow: visible;
    position: relative;
    min-height: calc(100vh - 56px);
    display: grid;
    place-items: center;
    padding: 2rem;
    background:
      radial-gradient(1200px 600px at 50% -200px, #7b2cff 0%, #4a1fb7 35%, #2c136d 60%, #1a0f46 100%);
    overflow: hidden;
  }
  .auth-bg::after {
    content: "";
    position: absolute; inset: 0;
    background-image:
      radial-gradient(2px 2px at 10% 20%, rgba(255,255,255,.7) 50%, transparent 51%),
      radial-gradient(2px 2px at 30% 80%, rgba(255,255,255,.6) 50%, transparent 51%),
      radial-gradient(2px 2px at 60% 30%, rgba(255,255,255,.6) 50%, transparent 51%),
      radial-gradient(2px 2px at 80% 60%, rgba(255,255,255,.5) 50%, transparent 51%),
      radial-gradient(1.5px 1.5px at 50% 50%, rgba(255,255,255,.5) 50%, transparent 51%),
      radial-gradient(1.5px 1.5px at 70% 15%, rgba(255,255,255,.5) 50%, transparent 51%);
    background-repeat: repeat;
    pointer-events: none;
    opacity: .6;
  }

  .mountain, .mountain::before, .mountain::after {
    position: absolute; bottom: -2vw; width: 140%; left: -20%;
    height: 26vh; content: "";
    background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,.15));
    filter: blur(1px);
  }
  .mountain { clip-path: polygon(0 70%, 15% 45%, 28% 60%, 44% 30%, 60% 55%, 74% 40%, 88% 58%, 100% 46%, 100% 100%, 0 100%); }
  .mountain::before { bottom: -2vh; opacity:.85; clip-path: polygon(0 78%, 12% 56%, 26% 66%, 40% 38%, 58% 64%, 72% 50%, 86% 70%, 100% 56%, 100% 100%, 0 100%); }
  .mountain::after  { bottom: -4vh; opacity:.95; clip-path: polygon(0 86%, 10% 66%, 24% 76%, 38% 50%, 56% 74%, 70% 60%, 84% 80%, 100% 66%, 100% 100%, 0 100%); }

  .glass-card {
    width: 100%;
    max-width: 480px;
    border-radius: 16px;
    padding: 32px 28px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 20px 50px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.15);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
  }

  .glass-card h3 { color:#fff; letter-spacing:.06em; }

  .glass-card .form-control, 
  .glass-card .form-select {
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
  }

  .glass-card .form-control::placeholder { color: rgba(255,255,255,.75); }
  .glass-card .form-label { color: #f6f4ff; }

  .glass-card .form-control:focus,
  .glass-card .form-select:focus {
    background: rgba(255,255,255,0.14);
    border-color: rgba(255,255,255,0.55);
    box-shadow: 0 0 0 .25rem rgba(124,58,237,.25);
    color: #fff;
  }

  .glass-card .btn.btn-primary {
    background: linear-gradient(180deg, #cbb6ff, #a78bfa);
    border: none;
    color:#2b1a5a;
    font-weight: 700;
    border-radius: 999px;
    padding: .75rem 1rem;
  }

  .glass-card .btn.btn-primary:hover { filter: brightness(1.05); }

  .auth-utility { font-size: .9rem; color:#e9e1ff; }
  .auth-utility a { color:#cdb8ff; text-decoration: none; }
  .auth-utility a:hover { text-decoration: underline; }
  .glass-card select option {
  color: #000;           
  background-color: #fff; 
}
.glass-card select {
  color: #fff;
}
.glass-card select:focus {
  color: #fff;
  background: rgba(255,255,255,0.14);
}
</style>
@endpush
