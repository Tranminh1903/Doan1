@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="auth-bg">
  <span class="mountain"></span>

  <div class="glass-card">
    <h3 class="mb-4 text-center">Login</h3>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="Username or Email">
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-2">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required placeholder="Password">
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3 auth-utility">
        <div class="form-check m-0">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>
        <a href="{{ route('forget_password.form') }}">Quên mật khẩu?</a>
      </div>

      <button class="btn btn-primary w-100">Đăng nhập</button>
      <div class="divider my-3 text-center text-light">
  <span>hoặc</span>
</div>
      <div class="google-btn-wrapper text-center">
  <a href="{{ route('login.google') }}" class="google-btn">
    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo">
    <span>Đăng nhập bằng   Google</span>
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
@endsection

@push('styles')
<style>
  .google-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  border: 1px solid #dadce0;
  border-radius: 20px;
  padding: 8px 16px;
  background-color: #fff;
  color: #3c4043;
  font-family: "Roboto", sans-serif;
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
  transition: box-shadow 0.2s ease, background-color 0.2s ease;
}

.google-btn img {
  width: 18px;
  height: 18px;
}

.google-btn:hover {
  background-color: #f7f8f8;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.google-btn:active {
  background-color: #eee;
}

.google-btn-wrapper {
  margin-top: 10px;
}
  /* ==== BACKGROUND ==== */
  .auth-bg {
    position: relative;
    min-height: calc(100vh - 56px); /* trừ header nếu có, tùy layout */
    display: grid;
    place-items: center;
    padding: 2rem;
    background:
      radial-gradient(1200px 600px at 50% -200px, #7b2cff 0%, #4a1fb7 35%, #2c136d 60%, #1a0f46 100%);
    overflow: hidden;
  }
  /* sao lấp lánh */
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
  /* núi chân trời */
  .mountain, .mountain::before, .mountain::after {
    position: absolute; bottom: -2vw; width: 140%; left: -20%;
    height: 26vh; content: "";
    background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,.15));
    filter: blur(1px);
  }
  .mountain { clip-path: polygon(0 70%, 15% 45%, 28% 60%, 44% 30%, 60% 55%, 74% 40%, 88% 58%, 100% 46%, 100% 100%, 0 100%); }
  .mountain::before { bottom: -2vh; opacity:.85; clip-path: polygon(0 78%, 12% 56%, 26% 66%, 40% 38%, 58% 64%, 72% 50%, 86% 70%, 100% 56%, 100% 100%, 0 100%); }
  .mountain::after  { bottom: -4vh; opacity:.95; clip-path: polygon(0 86%, 10% 66%, 24% 76%, 38% 50%, 56% 74%, 70% 60%, 84% 80%, 100% 66%, 100% 100%, 0 100%); }

  /* ==== CARD GLASS ==== */
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
  /* input trong suốt, chữ trắng – vẫn giữ class .form-control */
  .glass-card .form-control {
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
  }
  .glass-card .form-control::placeholder { color: rgba(255,255,255,.75); }
  .glass-card .form-label { color: #f6f4ff; }
  .glass-card .form-control:focus {
    background: rgba(255,255,255,0.14);
    border-color: rgba(255,255,255,0.55);
    box-shadow: 0 0 0 .25rem rgba(124,58,237,.25);
    color: #fff;
  }
  /* nút primary (giữ .btn.btn-primary) đổi style cục bộ */
  .glass-card .btn.btn-primary {
    background: linear-gradient(180deg, #cbb6ff, #a78bfa);
    border: none;
    color:#2b1a5a;
    font-weight: 700;
    border-radius: 999px;
    padding: .75rem 1rem;
  }
  .glass-card .btn.btn-primary:hover { filter: brightness(1.05); }
  .glass-card a { color:#715959; text-decoration: none; }
  .glass-card a:hover { text-decoration: underline; }

  /* bố cục Remember / Forgot */
  .auth-utility { font-size: .9rem; color:#e9e1ff; }
</style>
@endpush