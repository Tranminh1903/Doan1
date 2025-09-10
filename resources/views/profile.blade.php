@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')
@section('content')
<div class="container py-4">
  <div class="row g-4">

    <!-- Sidebar -->
    <aside class="col-md-3">
      <div class="list-group small shadow-sm">
        <a href="#" class="list-group-item list-group-item-action active">
          <i class="bi bi-person-circle me-2"></i> Thông tin tài khoản
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-gear me-2"></i> Cài đặt
        </a>
      </div>
    </aside>

    <!-- Main profile -->
    <section class="col-md-9">
      <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Hồ sơ khách hàng</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3 text-center">
              <!-- Ảnh đại diện -->
              <img src="https://ui-avatars.com/api/?name={{ $customer->user->name }}" 
                   class="rounded-circle img-fluid shadow-sm" 
                   alt="avatar">
            </div>
            <div class="col-md-9">
              <h4 class="fw-bold">{{ $customer->user->name }}</h4>
              <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $customer->user->email }}</p>
              <p class="mb-1"><i class="bi bi-phone me-2"></i>{{ $customer->phone ?? 'Chưa cập nhật' }}</p>
              <p class="mb-1"><i class="bi bi-star me-2"></i>Hạng thành viên: 
                <span class="badge bg-success">{{ $customer->tier ?? 'Mới' }}</span>
              </p>
            </div>
          </div>

          <hr>

          <h6 class="fw-bold">Thông tin chi tiết</h6>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Ngày tạo tài khoản:</strong> {{ $customer->user->created_at->format('d/m/Y') }}</p>
              <p><strong>Địa chỉ:</strong> {{ $customer->address ?? 'Chưa cập nhật' }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Trạng thái:</strong> 
                @if($customer->user->email_verified_at)
                  <span class="text-success">Đã xác minh</span>
                @else
                  <span class="text-danger">Chưa xác minh</span>
                @endif
              </p>
              <p><strong>Tổng đơn hàng:</strong> {{ $customer->orders->count() ?? 0 }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>
@endsection
