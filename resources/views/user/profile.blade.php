@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <aside class="col-md-3">
      <div class="cinema-title">TÀI KHOẢN CINEMA</div>
      <div class="list-group small shadow-sm" id="profile-tabs" role="tablist">
        <a class="list-group-item list-group-item-action active" id="link-general" data-bs-toggle="list"
          data-bs-target="#tab-general" role="tab" aria-controls="tab-general">
          <i class="bi bi-gear me-2"></i> THÔNG TIN CHUNG
        </a>

        <a class="list-group-item list-group-item-action" id="link-account" data-bs-toggle="list"
          data-bs-target="#tab-account" role="tab" aria-controls="tab-account">
          <i class="bi bi-gear me-2"></i> CHI TIẾT TÀI KHOẢN
        </a>

        <a class="list-group-item list-group-item-action" id="link-promos" data-bs-toggle="list"
          data-bs-target="#tab-promos" role="tab" aria-controls="tab-promos">
          <i class="bi bi-gift me-2"></i> DANH SÁCH KHUYẾN MÃI
        </a>

        <a class="list-group-item list-group-item-action" id="link-history" data-bs-toggle="list"
          data-bs-target="#tab-history" role="tab" aria-controls="tab-history">
          <i class="bi bi-clock-history me-2"></i> LỊCH SỬ GIAO DỊCH
        </a>
      </div>
    </aside>

    @php
    $user = Auth::user();
    @endphp

    <section class="col-md-9">
      <div class="card shadow-sm rounded-3">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">Hồ sơ khách hàng</h5>
        </div>
        <div class="card-body">
          <div class="tab-content" id="profile-tabContent">
            <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="link-general">
              <div class="row mb-3">
                <div class="col-md-3 text-center">
                  <form action="{{ route('avatar.update') }}" method="POST" enctype="multipart/form-data"
                    class="d-inline">
                    @csrf
                    <div class="d-flex flex-column align-items-center">
                      <img
                        src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
                        class="rounded-circle shadow-sm mb-2" style="width:100px;height:100px;object-fit:cover"
                        alt="avatar">
                      <label class="btn btn-outline-secondary btn-sm mb-2">
                        <input type="file" name="avatar" accept="image/*" hidden onchange="this.form.submit()">
                        Chọn ảnh mới
                      </label>
                      @if (session('success'))
                      <div class="text-success small mt-1">{{ session('success') }}</div>
                      @endif
                      @error('avatar')
                      <div class="text-danger small mt-1">{{ $message }}</div>
                      @enderror
                    </div>
                  </form>
                </div>

                <div class="col-md-9">
                  <p class="mb-1"><i class="bi bi-envelope me-2"></i>Xin chào khách hàng,
                    {{ $customer->customer_name }}</p>
                  <p class="mb-1"><i class="bi bi-wallet2 me-2"></i>Tổng tiêu dùng:
                    {{ number_format($totalAmount, 0, ',', '.') }} VND</p>
                  <p class="mb-1"><i class="bi bi-receipt me-2"></i>Tổng đơn hàng:
                    {{ $total_order_amount }}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>THÔNG TIN LIÊN HỆ:</strong></p>
                  <p class="mb-1"><i class="bi bi-person me-2"></i>Tên:
                    {{ $customer->customer_name }}</p>
                  <p class="mb-1"><i class="bi bi-envelope me-2"></i>Email:
                    {{ optional($customer->user)->email ?? 'chưa cập nhật' }}</p>
                  <p class="mb-1"><i class="bi bi-telephone me-2"></i>Số điện thoại:
                    {{ optional($customer->user)->phone ?? 'chưa cập nhật' }}</p>
                  <p class="mb-1"><i class="bi bi-gender-ambiguous me-2"></i>Giới tính:
                    {{ optional($customer->user)->sex ?? 'chưa cập nhật' }}</p>
                  <p class="mb-1"><i class="bi bi-calendar-date me-2"></i>Ngày sinh:
                    {{ optional($customer->user)->birthday ?? 'chưa cập nhật' }}</p>
                  <p><strong>Ngày tạo tài khoản:</strong>
                    {{ optional($customer->user->created_at)->format('d/m/Y') }}</p>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="tab-account" role="tabpanel" aria-labelledby="link-account">
              <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                  <label class="form-label">Tên</label>
                  <input type="text" name="customer_name"
                    class="form-control @error('customer_name') is-invalid @enderror"
                    value="{{ old('customer_name', $customer->customer_name) }}">
                  @error('customer_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Điện thoại</label>
                  <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $customer->user->phone ?? '') }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label d-block">Giới tính</label>

                  @php
                      $sex = old('sex', $customer->user->sex ?? 'none');
                  @endphp

                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sex" id="sex_m" value="male"   {{ $sex === 'male'   ? 'checked' : '' }}>
                    <label class="form-check-label" for="sex_m">Nam</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sex" id="sex_f" value="female" {{ $sex === 'female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sex_f">Nữ</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sex" id="sex_o" value="other"  {{ $sex === 'other'  ? 'checked' : '' }}>
                    <label class="form-check-label" for="sex_o">Khác</label>
                  </div>

                  @error('sex')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12">
                  <label class="form-label">
                    <span class="fw-semibold">Địa chỉ email:</span><br>
                    {{ $customer->user->email }}
                  </label>
                </div>

                <div class="col-12 d-flex justify-content-end">
                  <button class="btn btn-danger px-4 fw-bold">LƯU LẠI</button>
                </div>
              </form>
            </div>

            <div class="tab-pane fade" id="tab-promos" role="tabpanel" aria-labelledby="link-promos">
              <h5 class="mb-3">Danh sách khuyến mãi khả dụng</h5>

              <div class="row">
                @forelse($promotions as $promo)
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card border-0 shadow-sm h-100 promo-card">
                    <div class="card-body d-flex flex-column justify-content-between">
                      <h5 class="card-title text-uppercase text-center text-primary fw-bold mb-3">
                        {{ $promo->code }}
                      </h5>

                      @if ($promo->description)
                      <p class="card-text text-muted small text-center mb-3">
                        {{ $promo->description }}
                      </p>
                      @endif

                      <p class="text-center mb-3 fw-semibold">
                        @if ($promo->type === 'percent')
                        Giảm <span class="text-success">{{ $promo->value }}%</span>
                        @else
                        Giảm <span class="text-success">{{ number_format($promo->value, 0, ',', '.') }}₫</span>
                        @endif
                      </p>

                      <a href="{{ route('home') }}" class="btn btn-outline-danger w-100 fw-bold mt-auto">
                        Áp dụng ngay
                      </a>
                    </div>
                  </div>
                </div>
                @empty
                <div class="col-12 text-muted">
                  Hiện chưa có khuyến mãi khả dụng.
                </div>
                @endforelse
              </div>
            </div>
            <div class="tab-pane fade" id="tab-history" role="tabpanel" aria-labelledby="link-history">
              <h5 class="mb-3">Lịch sử vé đã mua</h5>

              @if ($tickets->isEmpty())
              <p class="text-muted">Bạn chưa có vé nào.</p>
              @else
              <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>Mã vé</th>
                    <th>Phim</th>
                    <th>Suất chiếu</th>
                    <th>Ghế</th>
                    <th>Tổng tiền</th>
                    <th>Ngày mua</th>
                    <th>Mã đơn</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($tickets as $ticket)
                  @php
                  $order = optional($ticket->showtime)->orders->first();
                  @endphp
                  <tr>
                    <td>{{ $ticket->ticketID }}</td>
                    <td>{{ optional(optional($ticket->showtime)->movie)->title }}</td>
                    <td>
                      @if (optional($ticket->showtime)->startTime)
                      {{ \Carbon\Carbon::parse($ticket->showtime->startTime)->format('d/m/Y H:i') }}
                      @endif
                    </td>
                    <td>{{ optional($ticket->seat)->seatID }}</td>
                    <td>{{ number_format($ticket->price, 0, ',', '.') }}₫</td>
                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td>{{ optional($order)->order_code }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @endif
            </div>
          </div>
        </div>
      </div>
  </div>
  </section>

</div>
</div>
@endsection

@push('styles')
<style>
  .cinema-title {
    color: #e71a0f;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 1.3rem;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }

  .card-header {
    display: flex;
    align-items: center;
    gap: 20px;
  }
</style>
@endpush