@extends('layouts.app')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="ad-wrapper container-fluid px-0">
  <div class="row g-3">
    <div class="col-lg-3">
      <aside class="ad-sidebar">
        <div class="ad-brand"><i class="bi bi-columns-gap"></i> Bảng điều khiển</div>

        <div class="ad-menu">
          <h6>Chức năng</h6>

          <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
             href="{{ route('admin.form') }}">
            <i class="bi bi-speedometer2"></i> Tổng quan
          </a>

          <a class="ad-link {{ request()->routeIs('userManagement_*') ? 'active' : '' }}"
             href="{{ route('userManagement_main.form') }}">
            <i class="bi bi-people"></i> Người dùng
          </a>

          <a class="ad-link {{ request()->routeIs('movies.*') ? 'active' : '' }}" href="#">
            <i class="bi bi-film"></i> Phim
          </a>

          <a class="ad-link {{ request()->routeIs('showtimes.*') ? 'active' : '' }}" href="#">
            <i class="bi bi-clock-history"></i> Suất chiếu
          </a>

          <a class="ad-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}" href="#">
            <i class="bi bi-ticket-perforated"></i> Khuyến mãi
          </a>
        </div>
      </aside>
    </div>

    <div class="col-lg-9 ad-main">
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
            <a href="{{ route('userManagement_managerUser.form') }}"
              class="btn {{ request()->routeIs('userManagement_managerUser.form') ? 'btn-primary' : 'btn-light' }}"
              aria-current="{{ request()->routeIs('userManagement_managerUser.form') ? 'page' : 'false' }}">
              <i class="bi bi-person-lines-fill me-1"></i> Xem thông tin
            </a>

            <a href="{{ route('userManagement_createUser.form') }}"
              class="btn {{ request()->routeIs('userManagement_createUser.form') ? 'btn-primary' : 'btn-light' }}"
              aria-current="{{ request()->routeIs('userManagement_createUser.form') ? 'page' : 'false' }}">
              <i class="bi bi-person-plus me-1"></i> Tạo tài khoản
            </a>

            <a href="{{ route('userManagement_updateUser.form') }}"
              class="btn {{ request()->routeIs('userManagement_updateUser.form') ? 'btn-primary' : 'btn-light' }}"
              aria-current="{{ request()->routeIs('userManagement_updateUser.form') ? 'page' : 'false' }}">
              <i class="bi bi-pencil-square me-1"></i> Cập nhật tài khoản
            </a>
          </div>
          <hr class="my-3">

          <div class="tab-content">
            <div class="tab-pane fade" id="u-view" role="tabpanel" aria-labelledby="u-view-tab">
              <div class="text-muted">Chức năng xem danh sách sẽ đặt ở đây.</div>
            </div>
            <div class="tab-pane fade show active" id="u-create" role="tabpanel" aria-labelledby="u-create-tab">
              <form method="POST" action="{{ route('admin_userManagement.Create') }}" autocomplete="off">
                @csrf
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input id="username" type="text" name="username" class="form-control" required>
                    @error('username')
                      <div class="text-danger small">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" required>
                    @error('email')
                      <div class="text-danger small">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label for="birthday" class="form-label">Ngày sinh</label>
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
                        @for ($y = now()->year - 12; $y >= 1900; $y--)
                          <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                    @error('password')
                      <div class="text-danger small">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="role" class="form-label">Vai trò</label>
                    <select id="role" class="form-select" name="role" required>
                      <option value="customers">Khách hàng</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>

                  <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                      <i class="bi bi-check2 me-1"></i> Tạo tài khoản
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">Làm lại</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade" id="u-update" role="tabpanel" aria-labelledby="u-update-tab">
              <div class="text-muted">Chức năng cập nhật tài khoản sẽ đặt ở đây.</div>
            </div>
          </div> 
        </div>   
      </div>    
    </div>
  </div> 
</div>   
@endsection
