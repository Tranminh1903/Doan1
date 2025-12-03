@extends('layouts.app')
@section('title','Quản lý người dùng')
@section('content')

@php
  $q   = $q ?? request('q','');
  $kpi = $kpi ?? ['users_active'=>0,'users_total'=>0];
@endphp

<div class="ad-wrapper d-flex container-fluid">
   <aside class="ad-sidebar">
    <nav class="ad-menu">
      <h6>TỔNG QUAN</h6>
      <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}" 
        href="{{ route('admin.form')}}">Bảng điều khiển</a>

      <h6>NGƯỜI DÙNG</h6>
      <a class="ad-link {{request()->routeIs('admin.userManagement_main.form') ? 'active' : '' }}" 
        href="{{route('admin.userManagement_main.form')}}">Quản lý người dùng</a>
      
      <h6>KHUYẾN MÃI</h6>
      <a class="ad-link {{ request()->routeIs('admin.promotionManagement.form') ? 'active' : '' }}"
        href="{{ route('admin.promotionManagement.form')}}">Quản lý khuyến mãi</a>
        
      <h6>PHIM</h6>
      <a class="ad-link {{ request()->routeIs('admin.moviesManagement_main.form') ? 'active' : '' }}" 
        href="{{ route('admin.moviesManagement_main.form')}}">Quản lý phim</a>

      <h6>PHÒNG CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.movietheaterManagement.form') ? 'active' : '' }}" 
        href="{{ route('admin.movietheaterManagement.form')}}">Quản lý phòng chiếu</a>

      <h6>SUẤT CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.showtimeManagement.form') ? 'active' : '' }}"
         href="{{ route('admin.showtimeManagement.form')}}">Quản lý suất chiếu</a>
      
      <h6>BÁO CÁO</h6>
      <a class="ad-link {{request()->routeIs('admin.reports.revenue') ? 'active' : '' }}" 
        href="{{ route('admin.reports.revenue')}}">Doanh thu</a>

      <h6>TIN TỨC</h6>
      <a class="ad-link {{ request()->routeIs('admin.newsManagement.form') ? 'active' : '' }}"
        href="{{ route('admin.newsManagement.form') }}">Quản lý tin tức</a>
    </nav>
  </aside>
  @php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $now   = now();
    $hour  = (int) $now->format('G');
    $user  = Auth::user();

    $greeting = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
    $weekdayMap = [
        'Mon' => 'Thứ hai', 'Tue' => 'Thứ ba', 'Wed' => 'Thứ tư',
        'Thu' => 'Thứ năm', 'Fri' => 'Thứ sáu', 'Sat' => 'Thứ bảy', 'Sun' => 'Chủ nhật'
      ];
    $weekdayVN = $weekdayMap[$now->format('D')] ?? '';
    $dateVN = $now->format('d/m/Y');
  @endphp
  <main class="ad-main flex-grow-1">
    <div class="ad-greeting card shadow-sm border-0 mb-4 w-100">
      <div class="card-body d-flex align-items-center gap-3 flex-wrap">
        <img
          src="{{ $user?->avatar ? asset('storage/'.$user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
          class="rounded-circle me-3"
          style="width:72px;height:72px;object-fit:cover;flex:0 0 72px;"
          alt="avatar">
        <div class="me-auto min-w-0">
          <h5 class="mb-1 text-truncate">
            👋 {{ $greeting }}, <span class="text-primary">{{ $user?->username ?? 'Admin' }}</span>
          </h5>
          <div class="text-muted small">
            {{ $weekdayVN }}, {{ $dateVN }} • Chúc bạn làm việc hiệu quả!
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 ms-md-auto">
          <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-outline-primary">Xem báo cáo</a>
          <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Làm mới</a>
        </div>
      </div>
    </div>

    <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
      <h3 class="m-0">Tổng quan</h3>
    </div>

    <div class="adm-users">
      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6">
          <div class="kpi-card kpi--blue p-3 rounded">
            <div class="text-muted">Người dùng đang hoạt động</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($kpi['users_active'] ?? 0)) }}</div>
          </div>
        </div>
        <div class="col-12 col-sm-6">
          <div class="kpi-card kpi--green p-3 rounded">
            <div class="text-muted">Tổng người dùng</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($kpi['users_total'] ?? 0)) }}</div>
          </div>
        </div>
      </div>

      <div class="toolbar-wrap">
        <div class="toolbar">
          <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreateUser">+ Thêm người dùng</button>
          <a href="{{ route('admin.form') }}" class="btn btn-soft">Trở về trang tổng quan</a>

          <a href="{{ route('usersManage.template_csv') }}"class="btn btn-soft">CSV mẫu</a>
          <a href="{{ route('usersManage.export_csv', ['q' => $q]) }}" class="btn btn-success">Xuất CSV</a>
          <form action="{{ route('usersManage.import_csv') }}" method="POST" enctype="multipart/form-data" class="csv-input">
            @csrf
            <button type="button" class="btn btn-soft fake-btn">Nhập CSV</button>
            <input type="file" name="file" accept=".csv" onchange="this.form.submit()">
          </form>

        </div>
      </div>

      {{-- TABLE --}}
      <div class="card-like mt-3">
        <div class="table-responsive">
          <table class="table table-users align-middle mb-0">
            <thead>
              <tr>
                <th class="avatar-col">Ảnh</th>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th class="text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $u)
                <tr>
                  <td class="avatar-col" data-label="Ảnh">
                    @php
                      $src = null;
                      if (!empty($u->avatar)) {
                          $src = Str::startsWith($u->avatar, ['http://','https://']) ? $u->avatar : 'storage/'.$u->avatar;
                      } else {
                          $src = 'storage/pictures/dogavatar.jpg';
                      }
                    @endphp

                    <img
                      src="{{ asset($src) }}"
                      class="avatar-thumb"
                      alt="Avatar {{ $u->username ?? $u->name ?? 'user' }}">
                  </td>
                  <td class="text-muted" data-label="ID">{{ $u->id }}</td>
                  <td class="fw-semibold" data-label="Họ tên">{{ $u->username }}</td>
                  <td data-label="Email">{{ $u->email }}</td>
                  <td data-label="Vai trò">
                    <span class="badge role-badge">{{ $u->role ?? 'customers' }}</span>
                  </td>
                  <td data-label="Ngày tạo">{{ optional($u->created_at)->format('Y-m-d') }}</td>
                  <td data-label="Trạng thái">
                    @if(($u->status ?? 'active') === 'active')
                      <span class="badge bg-success">Hoạt động</span>
                    @else
                      <span class="badge bg-secondary">Khoá</span>
                    @endif
                  </td>
                  <td class="text-end" data-label="Thao tác">
                    <div class="table-actions">
                      @if(($u->status ?? 'active') === 'active')
                        <form action="{{ route('users.toggleStatus', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Khoá tài khoản này?')">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-outline-warning">Khoá</button>
                        </form>
                      @else
                        <form action="{{ route('users.toggleStatus', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Mở khoá tài khoản này?')">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-warning">Mở khoá</button>
                        </form>
                      @endif
                      <button class="btn btn-sm btn-soft" data-bs-toggle="modal" data-bs-target="#editUser{{ $u->id }}">Sửa</button>
                      <form action="{{ route('users.delete',$u) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá người dùng này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Xoá</button>
                      </form>
                    </div>
                  </td>
                </tr>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form class="modal-content" method="POST" action="{{ route('users.update', $u) }}">
                        @csrf @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title">Sửa: {{ $u->username }}</h5>
                          <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>

                        <div class="modal-body row g-3">
                          <div class="col-md-6">
                            <label class="form-label">Họ tên</label>
                            <input name="name" value="{{ $u->username }}" class="form-control" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ $u->email }}" class="form-control" required>
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">Vai trò</label>
                            <select name="role" class="form-select">
                              @php
                                $currentRole   = old('role',   $u->role   ?? 'customers');
                                $currentStatus = old('status', $u->status ?? 'active');
                              @endphp

                              @foreach (['admin','customers'] as $r)
                                <option value="{{ $r }}" @selected($currentRole === $r)>{{ $r }}</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                              <option value="active" @selected(($u->status ?? 'active')==='active')>Hoạt động</option>
                              <option value="locked" @selected(($u->status ?? '')==='locked')>Khoá</option>
                            </select>
                          </div>

                          <div class="col-md-4">
                            <label class="form-label">SĐT (tuỳ chọn)</label>
                            <input name="phone" value="{{ old('phone',$u->phone) }}" class="form-control">
                          </div>

                          <div class="col-md-12">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="input-group">
                              <input name="avatar" id="avatarText{{ $u->id }}" value="{{ $u->avatar }}" class="form-control" placeholder="https://...jpg hoặc storage/avatars/xxx.jpg">
                              <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('avatarFile{{ $u->id }}').click()">Chọn ảnh</button>
                              <input type="file" id="avatarFile{{ $u->id }}" class="d-none" accept="image/*">
                            </div>
                            <small class="text-muted">Dán URL trực tiếp hoặc chọn ảnh để upload.</small>
                            <div class="mt-2">
                              <img id="avatarPreview{{ $u->id }}" src="{{ $u->avatar ? asset($u->avatar) : '' }}" style="max-height:120px; {{ $u->avatar ? '' : 'display:none' }}" class="rounded border">
                            </div>
                          </div>

                          <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" rows="3" class="form-control">{{ $u->note }}</textarea>
                          </div>
                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
                          <button class="btn btn-brand">Lưu</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Chưa có người dùng.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-3">
        {{ $users->links() }}
      </div>
    </div>
  </main>
</div>

{{-- CREATE USER MODAL --}}
<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm người dùng</h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Họ tên</label>
          <input name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Mật khẩu</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Vai trò</label>
          <select name="role" class="form-select">
            <option>admin</option><option>customers</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="active" selected>Hoạt động</option>
            <option value="locked">Khoá</option>
          </select>
        </div>

        <div class="col-md-12">
          <label class="form-label">Ảnh đại diện</label>
          <div class="input-group">
            <input name="avatar" id="avatarCreateText" class="form-control" placeholder="https://...jpg hoặc storage/avatars/xxx.jpg">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('avatarCreateFile').click()">Chọn ảnh</button>
            <input type="file" id="avatarCreateFile" class="d-none" accept="image/*">
          </div>
          <small class="text-muted">Dán URL hoặc chọn ảnh để upload.</small>
          <div class="mt-2">
            <img id="avatarCreatePreview" style="max-height:120px; display:none" class="rounded border">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
        <button class="btn btn-brand">Lưu</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  function attachImagePicker(textId, fileId, previewId, uploadRoute) {
    const txt = document.getElementById(textId);
    const file = document.getElementById(fileId);
    const prev = document.getElementById(previewId);

    file?.addEventListener('change', async () => {
      if (!file.files?.[0]) return;
      const form = new FormData();
      form.append('file', file.files[0]);
      try {
        const res = await fetch(uploadRoute, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: form
        });
        if (!res.ok) throw new Error('Upload thất bại');
        const data = await res.json();
        txt.value = data.path;
        if (prev) {
          prev.src = '/' + data.path;
          prev.style.display = 'inline-block';
        }
      } catch (e) {
        console.error('Upload avatar lỗi:', e);
        alert('Tải ảnh thất bại.');
      }
    });

    txt?.addEventListener('input', () => {
      if (!prev) return;
      const v = txt.value?.trim();
      if (!v) { prev.style.display = 'none'; return; }
      prev.src = v.startsWith('http') ? v : ('/' + v);
      prev.style.display = 'inline-block';
    });
  }

  // route upload avatar (tạo trong web.php của bạn)
  const uploadAvatarUrl = `{{ route('users.upload_avatar', [], false) ?? '#' }}`;

  attachImagePicker('avatarCreateText','avatarCreateFile','avatarCreatePreview', uploadAvatarUrl);

  @foreach ($users as $u)
    attachImagePicker('avatarText{{ $u->id }}','avatarFile{{ $u->id }}','avatarPreview{{ $u->id }}', uploadAvatarUrl);
  @endforeach
});
</script>
@endpush

@push('styles')
<style>
  .modal-backdrop.show {
      backdrop-filter: blur(4px);
      background-color: rgba(0, 0, 0, 0.4);
  }

  /* ===== KPI cards (đồng bộ với phim/dashboard) ===== */
  .kpi-card {
      background: #fff;
      border: 1px solid #eef2f6;
      border-radius: 14px;
      padding: 16px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
      transition: transform 0.12s ease, box-shadow 0.18s ease,
      border-color 0.18s ease;
  }
  .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 34px rgba(16, 24, 40, 0.08);
      border-color: #e3eaf3;
  }
  .kpi--blue {
      border-color: #e4ebff;
  }
  .kpi--green {
      border-color: #dcfce7;
  }

/* ===== Toolbar trắng có bóng (đồng bộ với trang Phim) ===== */
  .adm-users .toolbar-wrap{
    background:#fff;
    border:1px solid #eaecf0;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(16,24,40,.06);
    padding:10px;
    margin-top:4px;
  }
  .adm-users .toolbar{
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:12px;
    margin:16px 0 12px;
  }
  .adm-users .toolbar .search{
    flex:1 1 420px;              
    max-width:560px;             
  }
  .adm-users .toolbar .search .form-control{
    height:38px;                 
    border-radius:.375rem;      
    padding: .375rem .75rem;     
    border:1px solid #dee2e6;    
    box-shadow:none;
  }
  .adm-users .toolbar .search .form-control:focus{
    outline:0;
    border-color:#b8bdfd;
    box-shadow:0 0 0 .25rem rgba(69,74,242,.12);
  }
  .adm-users .btn-soft{background:#f9fafb;border:1px solid #eaecf0;color:#101828}
  .adm-users .btn-soft:hover{background:#fff}
  .adm-users .btn-brand{background:#454af2;border-color:#454af2;color:#fff}
  .adm-users .btn-brand:hover{filter:brightness(.95)}
  .adm-users .csv-input{position:relative;display:inline-flex;align-items:center;gap:8px}
  .adm-users .csv-input input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer}
  .adm-users .csv-input .fake-btn{pointer-events:none}
  .adm-users .csv-input {
      position: relative;
      display: inline-flex;
      align-items: center;
  }
  .adm-users .csv-input input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
  }
  .adm-users .csv-input .fake-btn {
      pointer-events: none;
  }
  .adm-users .toolbar .btn,
  .adm-users .csv-input .btn {
      white-space: nowrap;      
      line-height: 1.2;         
      padding-left: 12px;       
      padding-right: 12px;
      flex-shrink: 0;           
}

  /* ===== Card & Bảng USERS (đồng bộ tone) ===== */
  .adm-users .card-like {
      background: #fff;
      border: 1px solid #e9edf3;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(2, 8, 23, 0.06);
      overflow: hidden;
      margin-top: 12px;
  }
  .table-users {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 0;
  }
  .table-users thead th {
      background: #f8fafc;
      color: #334155;
      font-weight: 600;
      font-size: 0.85rem;
      border-bottom: 1px solid #e2e8f0 !important;
      white-space: nowrap;
      padding: 12px 14px;
  }
  .table-users td {
      padding: 12px 14px;
      vertical-align: middle;
      border-color: #e9edf3;
      color: #101828;
      border-bottom: 1px solid #f4f6fb;
  }
  .text-end {
      text-align: right;
  }

  /* avatar & badge */
  .avatar-col {
      width: 72px;
  }
  .avatar-thumb {
      width: 42px;
      height: 42px;
      border-radius: 999px;
      object-fit: cover;
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
  }
  .role-badge {
      background: #eef2ff;
      border: 1px solid #e2e8f0;
      color: #334155;
      font-weight: 600;
      border-radius: 999px;
      padding: 0.35rem 0.5rem;
  }
  .table-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
  }

  /* ===== Modal z-index (đồng bộ) ===== */
  .modal {
      z-index: 2000 !important;
  }
  .modal-backdrop {
      z-index: 1999 !important;
  }

  /* ===== Responsive (đồng bộ với phim) ===== */
  @media (max-width: 768px) {
      .adm-users .toolbar {
          flex-direction: column;
          align-items: stretch;
      }
      .table-users thead {
          display: none;
      }
      .table-users tbody tr {
          display: block;
          border-bottom: 1px solid #e5e7eb;
          padding: 12px;
      }
      .table-users tbody td {
          display: flex;
          justify-content: space-between;
          gap: 12px;
          padding: 8px 0;
          border: 0;
      }
      .table-users tbody td::before {
          content: attr(data-label);
          color: #64748b;
          font-weight: 600;
      }
      .table-actions {
          justify-content: flex-start;
      }
  }
  @media (min-width: 992px){
      .adm-users .toolbar{ 
        flex-wrap:nowrap; 
      }
}
</style>
@endpush