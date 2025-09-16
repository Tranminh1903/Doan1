@extends('layouts.app')
@section('title','Đặt lại mật khẩu')
@section('content')
<div class="container py-4" style="max-width:720px">
  <h2 class="text-center mb-4 fw-bold">Đặt lại mật khẩu</h2>
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
             minlength="8"
             required>
      @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="form-label">Nhập lại mật khẩu mới</label>
      <input type="password"
             name="password_confirmation"
             class="form-control"
             autocomplete="new-password"
             minlength="8"
             required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>

  </form>
</div>
@endsection
