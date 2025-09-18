@extends('layouts.app')
@section('title','Đăng nhập')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3 class="mb-3 text-center">Đăng nhập</h3>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
      </div>
      <button class="btn btn-primary w-100">Đăng nhập</button>
    </form>
    <div class="d-flex gap-3 mt-3">Chưa có tài khoản? 
        <a href="{{ route('register.form') }}">Đăng ký</a> 
        <a href="{{ route('forget_password.form') }}">Quên mật khẩu</a>
        <a href="{{ url('/') }}">Trở về trang chủ</a>
    </div>
  </div>
</div>
@endsection
