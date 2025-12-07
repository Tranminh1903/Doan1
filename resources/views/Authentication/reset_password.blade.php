@extends('layouts.app')
@section('title','Đặt lại mật khẩu')

@section('content')
<div class="reset-wrapper">
  <div class="glass-card">
    <h3 class="mb-4 text-center">Đặt lại mật khẩu</h3>

    @if (session('status'))
      <div class="alert alert-success py-2 px-3 small mb-3">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small mb-3">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div>
        <label class="form-label">Email</label>
        <input type="email"
               name="email"
               class="form-control"
               placeholder="nhapemail@domain.com"
               value="{{ request('email') }}"
               autocomplete="email"
               required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="form-label">Mật khẩu mới</label>
        <input type="password"
               name="password"
               class="form-control"
               autocomplete="new-password"
               minlength="5"
               required>
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="form-label">Nhập lại mật khẩu mới</label>
        <input type="password"
               name="password_confirmation"
               class="form-control"
               autocomplete="new-password"
               minlength="5"
               required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>

      <div class="text-center mt-3 auth-utility">
        Nhớ mật khẩu rồi?
        <a href="{{ route('login.form') }}">Đăng nhập</a> ·
        <a href="{{ url('/') }}">Trang chủ</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
  /* ======================= */
  /*  NỀN CINEMATIC FULL BODY (reuse từ login) */
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
      url('{{ asset('storage/pictures/background-auth3.png') }}');

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
  /*  RESET WRAPPER          */
  /* ======================= */
  .reset-wrapper {
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

  /* INPUTS */
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

  /* BUTTON */
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
