@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container py-4">
  <div class="row g-4">

    {{-- Sidebar --}}
    <aside class="col-md-3">
      <div class="cinema-title">TÀI KHOẢN CINEMA</div>
      <div class="list-group small shadow-sm" id="profile-tabs" role="tablist">
        <a class="list-group-item list-group-item-action active"
           id="link-general" data-bs-toggle="list" data-bs-target="#tab-general"
           role="tab" aria-controls="tab-general">
          <i class="bi bi-gear me-2"></i> THÔNG TIN CHUNG
        </a>

        <a class="list-group-item list-group-item-action"
           id="link-account" data-bs-toggle="list" data-bs-target="#tab-account"
           role="tab" aria-controls="tab-account">
          <i class="bi bi-gear me-2"></i> CHI TIẾT TÀI KHOẢN
        </a>
        
        <a class="list-group-item list-group-item-action"
           id="link-promos" data-bs-toggle="list" data-bs-target="#tab-promos"
           role="tab" aria-controls="tab-promos">
          <i class="bi bi-gift me-2"></i> DANH SÁCH KHUYẾN MÃI
        </a>

        <a class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
          <i class="bi bi-gear me-2"></i> THẺ THÀNH VIÊN
        </a>

        <a class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">
          <i class="bi bi-gear me-2"></i> LỊCH SỬ GIAO DỊCH
        </a>
      </div>
    </aside>

    {{-- Main --}}
    <section class="col-md-9">
      <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Hồ sơ khách hàng</h5>
        </div>
        <div class="card-body">
          {{-- NỘI DUNG TAB --}}
          <div class="tab-content" id="profile-tabContent">
            {{-- TAB: THÔNG TIN CHUNG --}}
            <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="link-general">
              <div class="row mb-3">
                <div class="col-md-3 text-center">
                  <img src="{{ asset('storage/pictures/dogavatar.jpg') }}"
                       class="rounded-circle shadow-sm"
                       style="width:100px;height:100px;object-fit:cover" alt="avatar">
                </div>
                <div class="col-md-9">
                  <p class="mb-1"><i class="bi bi-envelope me-2"></i>Xin chào khách hàng {{ $customer->customer_name }}</p>
                  <p class="mb-1"><i class="bi bi-star me-2"></i>Hạng thành viên:
                    <span class="badge bg-success">{{ $customer->tier }}</span>
                  </p>
                  <p class="mb-1"><i class="bi bi-phone me-2"></i>Điểm tích lũy: {{ $customer->customer_point }}</p>
                  <p class="mb-1"><i class="bi bi-phone me-2"></i>Tổng tiêu dùng: {{ number_format($totalAmount, 0, ',', '.') }} VND</p>
                  <p class="mb-1"><i class="bi bi-phone me-2"></i>Tổng đơn hàng: {{ $customer->total_order_amount }}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Ngày tạo tài khoản:</strong> {{ $customer->user->created_at->format('d/m/Y') }}</p>
                  <p><strong>THÔNG TIN LIÊN HỆ:</strong></p>
                  <p class="mb-1"><i class="bi bi-person me-2"></i>Tên: {{ $customer->customer_name }}</p>
                  <p class="mb-1"><i class="bi bi-envelope me-2"></i>Email: {{ $customer->user->email ?? 'chưa cập nhật' }}</p>
                  <p class="mb-1"><i class="bi bi-telephone me-2"></i>Số điện thoại: {{ $customer->user->phone ?? 'chưa cập nhật'}}</p>
                  <p class="mb-1"><i class="bi bi-telephone me-2"></i>Giới tính: {{ $customer->user->sex ?? 'chưa cập nhật'}}</p>
                  <p class="mb-1"><i class="bi bi-telephone me-2"></i>Ngày sinh: {{ $customer->user->birthday }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Trạng thái:</strong>
                    @if($customer->user->email_verified_at)
                      <span class="text-success">Đã xác minh</span>
                    @else
                      <span class="text-danger">Chưa xác minh</span>
                    @endif
                  </p>
                </div>
              </div>
            </div>

            {{-- TAB: CHI TIẾT TÀI KHOẢN --}}
            <div class="tab-pane fade" id="tab-account" role="tabpanel" aria-labelledby="link-account">
                <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                  @csrf
                  {{-- CHỈNH SỬA USERNAME & NUMBERPHONE --}}
                  <div class="col-md-6">
                    <label class="form-label">Tên</span></label>
                    <input type="text" name="customer_name"
                          class="form-control @error('customer_name') is-invalid @enderror"
                          value="{{ old('customer_name', $customer->customer_name) }}">
                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Điện thoại</span></label>
                    <input type="text" name="phone" class="form-control"
                          value="{{ old('phone', $customer->user->phone) }}">
                  </div>
                  {{-- LỰA CHỌN GIỚI TÍNH --}}
                  <div class="col-md-6">
                    <label class="form-label d-block">Giới tính</span></label>
                    @php $sex = old('sex', $customer->user->sex ?? 'none'); @endphp
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="sex" id="sex_m" value="Nam" {{ $sex==='Nam' ? 'checked' : '' }}>
                      <label class="form-check-label" for="sex_m">Nam</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="sex" id="sex_f" value="Nữ" {{ $sex==='Nữ' ? 'checked' : '' }}>
                      <label class="form-check-label" for="sex_f">Nữ</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="sex" id="sex_n" value="Khác"{{ $sex==='Khác' ? 'checked' : '' }}>
                      <label class="form-check-label" for="sex_n">Khác</label>
                    </div>
                    @error('sex') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-12">
                    <label class="form-label">
                      <span class="fw-semibold">Đia chỉ email</span> <br>{{ $customer->user->email}}
                    </label>
                  </div>
                  {{-- NÚT LƯU THÔNG TIN --}}
                  <form action="{{ route('profile.update') }}" method="POST" class="d-inline">
                    @csrf
                  <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-danger px-4 fw-bold">LƯU LẠI</button>
                  </div>
                  </form>
                </form>
            </div>

            {{-- TAB: DANH SÁCH KHUYẾN MÃI --}}
            <div class="tab-pane fade" id="tab-promos" role="tabpanel" aria-labelledby="link-promos">
              <p class="text-muted">Bạn chưa có khuyến mãi khả dụng.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>
</div>
@endsection
