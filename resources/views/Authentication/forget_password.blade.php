@extends('layouts.app')
@section('title','Quên mật khẩu')
@section('content')
<div class="container py-4" style="max-width:720px">
  <h2 class="text-center mb-4 fw-bold">Quên mật khẩu</h2>
  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('forget_password.link') }}" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nhapemail@domain.com" required autofocus>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Gửi liên kết đặt lại mật khẩu</button>

        <div class="d-flex gap-3 mt-3">
        <span>Nhớ mật khẩu rồi?</span>
            <a href="{{ route('login') }}">Đăng nhập</a>
            <a href="{{ route('register') }}">Đăng ký</a>
            <a href="{{ route('home') }}">Trở về trang chủ</a>
        </div>
    </form>
</div>
@endsection
