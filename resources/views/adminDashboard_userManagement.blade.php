@extends('layouts.app')
@section('title','Quản lý người dùng')

@section('content')
<div class="ad-wrapper container-fluid px-0">
  <div class="row g-3">
    <!-- ===== Sidebar ===== -->
    <div class="col-lg-3">
      <aside class="ad-sidebar">
        <div class="ad-brand"><i class="bi bi-columns-gap"></i> Bảng điều khiển</div>

        <div class="ad-menu">
          <h6>Chức năng</h6>

          <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
             href="{{ route('admin.form') }}">
            <i class="bi bi-speedometer2"></i> Tổng quan
          </a>

          <a class="ad-link {{ request()->routeIs('admin_userManagement.form') ? 'active' : '' }}"
             href="{{ route('admin_userManagement.form') }}">
            <i class="bi bi-people"></i> Người dùng
          </a>

          <a class="ad-link {{ request()->routeIs('movies.*') ? 'active' : '' }}"
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
    <!-- ===== /Sidebar ===== -->
    <!-- ===== Main ===== -->
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

      <!-- User Management -->
      <div class="card ad-card">
        <div class="card-header bg-white">
          <h5 class="mb-0">Quản lý người dùng</h5>
        </div>
        <div class="card-body">
          <!-- Tabs -->
          <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active"
                      data-bs-toggle="pill"
                      data-bs-target="#u-view"
                      type="button"
                      role="tab"
                      aria-controls="u-view"
                      aria-selected="true">
                <i class="bi bi-person-lines-fill me-1"></i> Xem thông tin
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link"
                      data-bs-toggle="pill"
                      data-bs-target="#u-create"
                      type="button"
                      role="tab"
                      aria-controls="u-create"
                      aria-selected="false">
                <i class="bi bi-person-plus me-1"></i> Tạo tài khoản
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link"
                      data-bs-toggle="pill"
                      data-bs-target="#u-update"
                      type="button"
                      role="tab"
                      aria-controls="u-update"
                      aria-selected="false">
                <i class="bi bi-pencil-square me-1"></i> Cập nhật tài khoản
              </button>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            {{-- ===================== VIEW TAB ===================== --}}
            <div class="tab-pane fade show active" id="u-view" role="tabpanel" aria-labelledby="u-view-tab">
              <form action="#" method="GET" class="row gy-2 gx-2 align-items-end mb-3">
                <div class="col-md-4">
                  <label class="form-label">Từ khóa</label>
                  <input type="text" class="form-control" name="q" placeholder="Tên đăng nhập / Email">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Vai trò</label>
                  <select class="form-select" name="role">
                    <option value="">Tất cả</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Khách hàng</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Trạng thái</label>
                  <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="active">Hoạt động</option>
                    <option value="blocked">Bị chặn</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <button class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Tìm
                  </button>
                </div>
              </form>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Tên đăng nhập</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Ngày tạo</th>
                      <th class="text-end">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>duongminh</td>
                      <td>duong@example.com</td>
                      <td><span class="badge bg-danger">Admin</span></td>
                      <td>12/09/2025</td>
                      <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>trangngoc</td>
                      <td>trang@example.com</td>
                      <td><span class="badge bg-secondary">Khách hàng</span></td>
                      <td>01/09/2025</td>
                      <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-3 d-flex gap-2">
                <a href="#" class="btn btn-outline-secondary">
                  <i class="bi bi-list-ul me-1"></i> Danh sách người dùng
                </a>
              </div>
            </div>
            {{-- =================== /VIEW TAB =================== --}}

            {{-- ==================== CREATE TAB ==================== --}}
            <div class="tab-pane fade" id="u-create" role="tabpanel" aria-labelledby="u-create-tab">
              <form method="POST" action="{{ route('admin_userManagement.Create') }}">
                @csrf
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input id="username" type="text" name="username"class="form-control" required>
                    @error('username') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" required>
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-12">
                    <label for="birthday" class="form-label">Ngày sinh</label>
                    <div class="d-flex gap-2">
                      <select name="day" class="form-select" required>
                        <option value="">Ngày</option>
                        @for ($d = 1; $d <= 31; $d++)
                          <option value="{{ $d }}" @selected('day'==$d)>{{ $d }}</option>
                        @endfor
                      </select>
                      <select name="month" class="form-select" required>
                        <option value="">Tháng</option>
                        @for ($m = 1; $m <= 12; $m++)
                          <option value="{{ $m }}" @selected('month'==$m)>{{ $m }}</option>
                        @endfor
                      </select>
                      <select name="year" class="form-select" required>
                        <option value="">Năm</option>
                        @for ($y = now()->year - 12; $y >= 1900; $y--)
                          <option value="{{ $y }}" @selected('year'==$y)>{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="role" class="form-label">Vai trò</label>
                    <select id="role" class="form-select" name="role">
                      <option value="customers" @selected(old('role')==='customers')>Khách hàng</option>
                      <option value="admin"    @selected(old('role')==='admin')>Admin</option>
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
            {{-- ================== /CREATE TAB ================== --}}

            {{-- ==================== UPDATE TAB ==================== --}}
            <div class="tab-pane fade" id="u-update" role="tabpanel" aria-labelledby="u-update-tab">

              <!-- Chọn user để tải thông tin -->
              <form action="#" method="GET" class="row gy-2 gx-2 align-items-end mb-3">
                <div class="col-md-6">
                  <label class="form-label">Chọn người dùng</label>
                  <select class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="1">#1 – duongminh – duong@example.com</option>
                    <option value="2">#2 – trangngoc – trang@example.com</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <button class="btn btn-outline-primary w-100">Tải thông tin</button>
                </div>
              </form>

              <!-- Form cập nhật -->
              <form action="#" method="POST" class="row g-3">
                @csrf
                @method('PATCH')

                <div class="col-md-4">
                  <label class="form-label">Tên đăng nhập</label>
                  <input type="text" class="form-control" value="duongminh">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" value="duong@example.com">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Vai trò</label>
                  <select class="form-select">
                    <option value="admin" selected>Admin</option>
                    <option value="customer">Khách hàng</option>
                  </select>
                </div>

                <div class="col-12">
                  <button class="btn btn-warning">
                    <i class="bi bi-save me-1"></i> Lưu thay đổi
                  </button>
                  <a href="#" class="btn btn-outline-danger ms-2">
                    <i class="bi bi-x-lg me-1"></i> Khóa tài khoản
                  </a>
                </div>
              </form>
            </div>
            {{-- =================== /UPDATE TAB =================== --}}

          </div> <!-- /.tab-content -->
        </div>   <!-- /.card-body -->
      </div>     <!-- /.card -->
    </div>
    <!-- ===== /Main ===== -->
  </div>
</div>
@endsection
