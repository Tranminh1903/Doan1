@extends('layouts.app')
@section('title','Quản lý người dùng')

@section('content')
<style>
  .card-profile { background:#f8f9fa; }
  .avatar-100 { width:100px;height:100px;object-fit:cover; }
  .avatar-28  { width:28px;height:28px;object-fit:cover; }
</style>

<div class="ad-wrapper container-fluid px-0">
  <div class="row g-3">

    {{-- Sidebar trái --}}
    <div class="col-lg-3">
      <aside class="ad-sidebar">
        <div class="ad-brand"><i class="bi bi-columns-gap"></i> Bảng điều khiển</div>

        <nav class="ad-menu">
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
        </nav>
      </aside>
    </div>

    {{-- Khu vực chính --}}
    <div class="col-lg-9 ad-main">

      {{-- Profile card --}}
      <div class="card card-profile mb-3 border-0 shadow-sm rounded-4">
        <div class="card-body d-flex align-items-center gap-3 p-4">
          <img src="{{ asset('storage/pictures/dogavatar.jpg') }}"
               class="rounded-circle border border-3 border-white shadow-sm avatar-100"
               alt="avatar">
          <div>
            <h5 class="mb-1 fw-bold text-dark">ADMIN {{ auth()->user()->username }}</h5>
            <span class="text-muted small">Hệ thống bán vé xem phim</span>
          </div>
        </div>
      </div>

      {{-- Tabs hành động nhanh --}}
      <div class="card ad-card">
        <div class="card-body">
          <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            <a href="{{ route('userManagement_checkUser.form') }}"
               class="btn {{ request()->routeIs('userManagement_checkUser.form') ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
              <i class="bi bi-list-ul me-1"></i> Danh sách
            </a>
            <a href="{{ route('userManagement_createUser.form') }}"
               class="btn {{ request()->routeIs('userManagement_createUser.form') ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
              <i class="bi bi-person-plus me-1"></i> Tạo tài khoản
            </a>
          </div>
          <hr class="my-3">

          {{-- Nội dung --}}
          <div class="tab-content">
            <div class="tab-pane fade show active" id="u-view" role="tabpanel" aria-labelledby="u-view-tab">

              {{-- Form lọc --}}
              <form action="{{ route('userManagement_checkUser.form') }}" method="GET" class="row gy-2 gx-3 align-items-end mb-3">
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

                <div class="col-auto d-flex gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Tìm
                  </button>
                  <a href="{{ route('userManagement_checkUser.form') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Xoá
                  </a>
                </div>
              </form>

              {{-- Bảng danh sách --}}
              <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                  <thead class="table-light">
                    <tr class="text-muted small">
                      <th>#</th>
                      <th>Tên đăng nhập</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Trạng thái</th>
                      <th>Ngày tạo</th>
                      <th class="text-end">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($users as $i => $user)
                      <tr>
                        <td>{{ $users->firstItem() + $i }}</td>

                        <td>
                          <div class="d-flex align-items-center">
                            <img src="{{ $user->avatar ?: asset('storage/pictures/dogavatar.jpg') }}"
                                 class="rounded-circle me-2 avatar-28" alt="avatar">
                            <span class="fw-semibold">{{ $user->username }}</span>
                          </div>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td><span class="badge bg-info-subtle text-dark">{{ $user->role ?? '—' }}</span></td>

                        <td>
                          <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->status === 'active' ? 'Hoạt động' : 'Chặn' }}
                          </span>
                        </td>

                        <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>

                        <td class="text-end">
                          <div class="d-inline-flex gap-2">
                            <a href="" {{--{{ route('users.edit', $user) }} --}}
                               class="btn btn-sm btn-outline-primary rounded-pill">
                              <i class="bi bi-pencil me-1"></i> Sửa
                            </a>

                            <form action="" method="POST" {{--{{ route('users.destroy', $user) }} --}}
                                  onsubmit="return confirm('Bạn có chắc muốn xóa user này không?')" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                <i class="bi bi-trash me-1"></i> Xóa
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-4">Không có người dùng nào</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              {{-- Phân trang --}}
              @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div class="small text-muted">
                    Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }}
                  </div>
                  <div>{{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                </div>
              @endif

            </div>
          </div>
        </div>
      </div>

    </div>
    {{-- /ad-main --}}
  </div>
</div>
@endsection
