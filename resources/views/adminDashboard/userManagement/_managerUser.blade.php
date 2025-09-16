@extends('layouts.app')
@section('title','Quản lý người dùng')

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

          <a class="ad-link {{ request()->routeIs('userManagement_*') ? 'active' : '' }}"
             href="{{ route('userManagement_main.form') }}">
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
            <div class="tab-pane fade show active" id="u-view" role="tabpanel" aria-labelledby="u-view-tab">
              <form action="{{ route('userManagement_main.form') }}" method="GET" class="row gy-2 gx-2 align-items-end mb-3">
                <div class="col-md-4">
                  <label class="form-label">Từ khóa</label>
                  <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên đăng nhập / Email">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Vai trò</label>
                  <select class="form-select" name="role">
                    <option value="">Tất cả</option>
                    <option value="admin"     @selected(request('role')==='admin')>Admin</option>
                    <option value="customers" @selected(request('role')==='customers')>Khách hàng</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Trạng thái</label>
                  <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="active"  @selected(request('status')==='active')>Hoạt động</option>
                    <option value="blocked" @selected(request('status')==='blocked')>Bị chặn</option>
                  </select>
                </div>

                <div class="col-auto d-flex align-items-end gap-2">
                  <button type="submit"
                          class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                    <i class="bi bi-search me-1"></i> Tìm
                  </button>

                  <a href="{{ route('userManagement_main.form') }}"
                    class="btn btn-outline-secondary px-3 py-2 rounded-3 shadow-sm text-nowrap">
                    <i class="bi bi-x-circle me-1"></i> Xoá
                  </a>
                </div>
              </form>

              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="small text-muted">
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Tên đăng nhập</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Trạng thái</th>
                      <th>Ngày tạo</th>
                      <th class="text-end">Hành động</th>
                    </tr>
                  </thead>
                </table>
              </div>

              <div class="mt-3">
              </div>
            </div>
    </div>
  </div>
</div>
@endsection