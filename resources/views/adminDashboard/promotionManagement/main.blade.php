@extends('layouts.app')
@section('title','Quản lý khuyến mãi')
@section('content')

<div class="ad-wrapper d-flex container-fluid">
  <aside class="ad-sidebar">
    <nav class="ad-menu">
      <h6>TỔNG QUAN</h6>
      <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}" 
        href="{{ route('admin.form')}}">Bảng điều khiển</a>

      <h6>NGƯỜI DÙNG</h6>
      <a class="ad-link {{request()->routeIs('admin.userManagement_main.form') ? 'active' : '' }}" 
        href="{{route('admin.userManagement_main.form')}}">Quản lý người dùng</a>
      
      <h6>PHIM</h6>
      <a class="ad-link {{ request()->routeIs('admin.moviesManagement_main.form') ? 'active' : '' }}" 
        href="{{ route('admin.moviesManagement_main.form')}}">Quản lý phim</a>

      <h6>KHUYẾN MÃI</h6>
      <a class="ad-link {{ request()->routeIs('admin.promotionManagement.form') ? 'active' : '' }}"
        href="{{ route('admin.promotionManagement.form')}}">Quản lý khuyến mãi</a>

      <h6>PHÒNG CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.movietheaterManagement.form') ? 'active' : '' }}" 
        href="{{ route('admin.movietheaterManagement.form')}}">Quản lý phòng chiếu</a>

      <h6>SUẤT CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.showtimeManagement.form') ? 'active' : '' }}"
         href="{{ route('admin.showtimeManagement.form')}}">Quản lý suất chiếu</a>
      
      <h6>BÁO CÁO</h6>
      <a class="ad-link {{request()->routeIs('admin.reports.revenue') ? 'active' : '' }}" 
        href="{{ route('admin.reports.revenue')}}">Doanh thu</a>
    </nav>
  </aside>
  @php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $now   = now();
    $hour  = (int) $now->format('G');
    $user  = Auth::user();

    $greeting = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
    $weekdayMap = [
        'Mon' => 'Thứ hai', 'Tue' => 'Thứ ba', 'Wed' => 'Thứ tư',
        'Thu' => 'Thứ năm', 'Fri' => 'Thứ sáu', 'Sat' => 'Thứ bảy', 'Sun' => 'Chủ nhật'
      ];
    $weekdayVN = $weekdayMap[$now->format('D')] ?? '';
    $dateVN = $now->format('d/m/Y');
  @endphp
  <main class="ad-main flex-grow-1">
    <div class="ad-greeting card shadow-sm border-0 mb-4 w-100">
      <div class="card-body d-flex align-items-center gap-3 flex-wrap">
        <img
          src="{{ $user?->avatar ? asset('storage/'.$user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
          class="rounded-circle me-3"
          style="width:72px;height:72px;object-fit:cover;flex:0 0 72px;"
          alt="avatar">
        <div class="me-auto min-w-0">
          <h5 class="mb-1 text-truncate">
            👋 {{ $greeting }}, <span class="text-primary">{{ $user?->username ?? 'Admin' }}</span>
          </h5>
          <div class="text-muted small">
            {{ $weekdayVN }}, {{ $dateVN }} • Chúc bạn làm việc hiệu quả!
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 ms-md-auto">
          <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-outline-primary">Xem báo cáo</a>
          <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Làm mới</a>
        </div>
      </div>
    </div>

    <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
      <h3 class="m-0">Tổng quan</h3>
    </div> 
  </main>
</div>
@endsection

@push('styles')
<style>
</style>
@endpush