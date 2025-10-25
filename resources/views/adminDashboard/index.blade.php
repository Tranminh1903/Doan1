@extends('layouts.app')
@section('title','Bảng điều khiển Admin')
@section('content')
<style>
</style>

<div class="ad-wrapper container-fluid px-0">
  <div class="row g-3">
    <!-- Sidebar (tiếng Việt) -->
    <div class="col-lg-3">
      <aside class="ad-sidebar">
        <div class="ad-brand"><i class="bi bi-columns-gap"></i> Bảng điều khiển</div>
        <div class="ad-menu">
            <h6>Chức năng</h6>
              <a class="ad-link ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
                href="{{ route('admin.form') }}">
                <i class="bi bi-speedometer2"></i>Tổng quan
              </a>
              <a class="ad-link {{ request()->routeIs('userManagement_main.form') ? 'active' : '' }}" 
                href="{{ route('userManagement_main.form') }}">
                <i class="bi bi-people"></i> Người dùng
              </a>
              <a class="ad-link {{ request()->routeIs('moviesManagement_main.form') ? 'active' : '' }}" 
                href="{{ route('moviesManagement_main.form') }}">
                <i class="bi bi-camera-reels"></i> Chuyển sang quản lý phim
              </a>
                <a class="ad-link" href="#"><i class="bi bi-film"></i> Suất chiếu</a>
                <a class="ad-link" href="#"><i class="bi bi-ticket-perforated"></i> Khuyến mãi</a>
        </div>
      </aside>
    </div>

    <div class="col-lg-9 ad-main">
        <div class="card ad-card mb-3">
            <div class="card-body d-flex align-items-center gap-3">
                <img src="{{ asset('storage/pictures/dogavatar.jpg') }}"
                       class="rounded-circle shadow-sm"
                       style="width:100px;height:100px;object-fit:cover" alt="avatar">
                <div>
                <h5 class="mb-0 fw-bold">ADMIN {{ auth()->user()->username }}</h5>
                <span class="text-muted small">Hệ thống bán vé xem phim</span>
                </div>
            </div>
        </div>

        <div class="row g-3">
        <div class="col-md-6">
          <div class="card ad-card h-100">
            <div class="card-header bg-white d-flex justify-content-between">
              <strong>Quản lý người dùng</strong>
              <i class="bi bi-people text-warning"></i>
            </div>
            <div class="card-body">
              <p class="text-muted small">Xem thông tin người dùng,tạo tài khoản và cập nhật.</p>
              <a href="{{ route('userManagement_main.form')}}" class="btn btn-info btn-sm"><i class="bi bi-plus-lg me-1"></i>Chuyển sang quản lý người dùng</a>
            </div>
          </div>
        </div>

       <div class="col-md-6">
          <div class="card ad-card h-100">
            <div class="card-header bg-white d-flex justify-content-between">
              <strong>Cập nhật thông tin phim</strong>
              <i class="bi bi-camera-reels text-danger"></i>
            </div>
            <div class="card-body">
              <p class="text-muted small">Thêm/sửa phim, trailer, poster, thời lượng…</p>
              <a href="{{ route('moviesManagement_main.form')}}" class="btn btn-danger btn-sm"><i class="bi bi-plus-lg me-1"></i>Chuyển sang quản lý phim</a>
            </div>
          </div>
        </div>


        <div class="col-md-6">
          <div class="card ad-card h-100">
            <div class="card-header bg-white d-flex justify-content-between">
              <strong>Tạo suất chiếu</strong>
              <i class="bi bi-film text-primary"></i>
            </div>
            <div class="card-body">
              <p class="text-muted small">Chọn phim, phòng chiếu và khung giờ.</p>
              <a href="#" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Chuyển sang quản lý suất chiếu</a>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card ad-card h-100">
            <div class="card-header bg-white d-flex justify-content-between">
              <strong>Thêm khuyến mãi</strong>
              <i class="bi bi-ticket-perforated text-success"></i>
            </div>
            <div class="card-body">
              <p class="text-muted small">Tạo mã, đặt điều kiện áp dụng, bật/tắt.</p>
              <a href="#" class="btn btn-success btn-sm"><i class="bi bi-plus-lg me-1"></i>Chuyển sang quản lý khuyến mãi</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
