@extends('layouts.app')
@section('title','Đăng ký')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3 class="mb-3 text-center">Đăng ký</h3>
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Họ và tên đăng nhập</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
        @error('username') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="birthday" class="form-label">Ngày sinh</span></label>
        <div class="d-flex gap-2">
          <select name="day" class="form-select" required>
            <option value="">Ngày</option>
            @for ($d = 1; $d <= 31; $d++)
              <option value="{{ $d }}">{{ $d }}</option>
            @endfor
          </select>
          <select name="month" class="form-select" required>
            <option value="">Tháng</option>
            @for ($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}">{{ $m }}</option>
            @endfor
          </select>
          <select name="year" class="form-select" required>
            <option value="">Năm</option>
            @for ($y = now()->year-12; $y >= 1900; $y--)
              <option value="{{ $y }}">{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    </form>
    
    <div class="d-flex gap-3 mt-3">
        Đã có tài khoản? 
        <a href="{{ route('login.form') }}">Đăng nhập</a> 
        <a href="{{ url('/') }}">Trở về trang chủ</a>
    </div>
  </div>
</div>
@endsection