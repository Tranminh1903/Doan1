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

          <a class="ad-link {{ request()->routeIs('moviesManagement_main.form') ? 'active' : '' }}"
             href="{{ route('moviesManagement_main.form') }}">
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

      {{-- Tabs hành động nhanh --}}
      <div class="card ad-card">
        <div class="card-body">
          <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            <a href="{{ route('userManagement_updateUser.form') }}"
              class="btn {{ request()->routeIs('userManagement_updateUser.form') ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
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
              <form action="{{ route('userManagement_updateUser.form') }}" method="GET" class="row gy-2 gx-3 align-items-end mb-3">
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
                  <a href="{{ route('userManagement_updateUser.form') }}" class="btn btn-outline-secondary">
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
                            <img src="{{ $user->avatar ? asset($user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
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
                            <a  href="#"
                                class="btn btn-sm btn-outline-primary rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal"
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ $user->role }}"
                                data-status="{{ $user->status }}">
                              <i class="bi bi-pencil me-1"></i> Sửa
                            </a>

                            <form action="" method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa user này không?')" class="d-inline">
                              @csrf
                              @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->username }}">
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
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserLabel">Chỉnh sửa người dùng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>

          <form id="editUserForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Tên đăng nhập</label>
                  <input type="text" name="username" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Email</label> 
                  <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Vai trò</label>
                  <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="customers">Khách hàng</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Trạng thái</label>
                  <select name="status" class="form-select" required>
                    <option value="active">Hoạt động</option>
                    <option value="blocked">Chặn</option>
                  </select>
                </div>

                <div class="col-md-12">
                  <label class="form-label">Avatar (tuỳ chọn)</label>
                  <input type="file" name="avatar" class="form-control">
                  <div class="form-text">jpg, jpeg, png, webp • tối đa 2MB</div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Lưu thay đổi
              </button>
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserLabel">Xóa người dùng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="deleteUserForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p class="mb-0">
            Bạn có chắc muốn xóa tài khoản <strong id="deleteUserName"></strong>?
            Hành động này không thể hoàn tác.
          </p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger">Xóa</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>

//Update user
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('editUserModal');
  const form    = document.getElementById('editUserForm');

  modalEl.addEventListener('show.bs.modal', function (event) {
    const btn       = event.relatedTarget; // nút Sửa vừa bấm
    const id        = btn.getAttribute('data-id');
    const username  = btn.getAttribute('data-username');
    const email     = btn.getAttribute('data-email');
    const role      = btn.getAttribute('data-role');
    const status    = btn.getAttribute('data-status');
    // Set action: /admin/users/{id}
    form.action = "{{ route('users.update', ':id') }}".replace(':id', id);
    // Đổ dữ liệu vào input
    form.querySelector('input[name="username"]').value = username || '';
    form.querySelector('input[name="email"]').value    = email    || '';
    form.querySelector('select[name="role"]').value    = role     || 'customers';
    form.querySelector('select[name="status"]').value  = status   || 'active';
  });
  // Clear file input khi đóng modal (tránh giữ file cũ)
  modalEl.addEventListener('hidden.bs.modal', function () {
    form.reset();
  });
});

//Delete User
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('deleteUserModal');
  const form    = document.getElementById('deleteUserForm');
  const nameEl  = document.getElementById('deleteUserName');

  modalEl.addEventListener('show.bs.modal', function (event) {
    const btn  = event.relatedTarget;
    const id   = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');

    form.action = "{{ route('users.delete', ':id') }}".replace(':id', id);
    nameEl.textContent = name || '';
  });
});
</script>
@endsection
