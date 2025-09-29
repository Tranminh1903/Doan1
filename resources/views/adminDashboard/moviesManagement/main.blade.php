@extends('layouts.app')
@section('title','Quản lý phim')

@section('content')
<div class="ad-wrapper container-fluid px-0">
  <div class="row g-3">
    <!-- sidebar bên trái -->
    <div class="col-lg-3">
      <aside class="ad-sidebar">
        <div class="ad-brand"><i class="bi bi-columns-gap"></i> Bảng điều khiển</div>

        <div class="ad-menu">
          <h6>Chức năng</h6>

          <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
             href="{{ route('admin.form') }}">
            <i class="bi bi-speedometer2"></i> Tổng quan
          </a>

          <a class="ad-link {{ request()->routeIs('userManagement_main.form') ? 'active' : '' }}"
             href="{{ route('userManagement_main.form') }}">
            <i class="bi bi-people"></i> Người dùng
          </a>

          <a class="ad-link {{ request()->routeIs('moviesManagement_main.form') ? 'active' : '' }}"
             href="#">
            <i class="bi bi-film"></i> Phim
          </a>

          <a class="ad-link {{ request()->routeIs('showtimes.*') ? 'active' : '' }}"
             href="#">
            <i class="bi bi-clock-history"></i> Suất chiếu
          </a>

          <a class="ad-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}"
             href="#">
            <i class="bi bi-ticket-perforated"></i> Khuyến mãi
          </a>
        </div>
      </aside>
    </div>

    <div class="col-lg-9 ad-main">
      <!-- Header card -->
      <div class="card ad-card mb-3">
        <div class="card-body d-flex align-items-center gap-3">
          <img
            src="{{ asset('storage/pictures/dogavatar.jpg') }}"
            class="rounded-circle shadow-sm"
            style="width:100px;height:100px;object-fit:cover"
            alt="avatar"
          >
          <div>
            <h5 class="mb-0 fw-bold">ADMIN {{ auth()->user()->username }}</h5>
            <span class="text-muted small">Hệ thống bán vé xem phim</span>
          </div>
        </div>
      </div>

      <div class="card ad-card">
        <div class="card-body">
          <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            <a href="{{ route('userManagement_updateUser.form') }}"
              class="btn {{ request()->routeIs('moviesManagement_updateMovies.form') ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
              <i class="bi bi-list-ul me-1"></i> Danh sách
            </a>
            <a href="{{ route('userManagement_createUser.form') }}"
              class="btn {{ request()->routeIs('moviesManagement_createMovies.form') ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
              <i class="bi bi-person-plus me-1"></i> Tạo phim
            </a>
          </div>
          <hr class="my-3">

          {{-- Nội dung theo route hiện tại --}}
          @if (request()->routeIs('userManagement_updateUser.form'))
            @include('adminDashboard.userManagement._updateUser')
          @elseif (request()->routeIs('userManagement_createUser.form'))
            @include('adminDashboard.userManagement._createUser')
          @endif
    </div>
  </div>
</div>
@endsection
