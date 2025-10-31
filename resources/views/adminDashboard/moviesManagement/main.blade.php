@extends('layouts.app')
@section('title','Quản lý phim')

@section('content')
@php
  $genres  = ['Action','Adventure','Animation','Comedy','Crime','Documentary','Drama','Fantasy','Horror','Mystery','Romance','Sci-Fi','Thriller','War','Western'];
  $ratings = ['G','PG','PG-13','R','NC-17'];
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
      
      <h6>PHIM</h6>
      <a class="ad-link {{ request()->routeIs('admin.moviesManagement_main.form') ? 'active' : '' }}" 
        href="{{ route('admin.moviesManagement_main.form')}}">Quản lý phim</a>

      <h6>KHUYẾN MÃI</h6>
      <a class="ad-link {{ request()->routeIs('admin.promotionManagement.form') ? 'active' : '' }}"
        href="{{ route('admin.promotionManagement.form')}}">Quản lý khuyến mãi</a>

      <h6>PHÒNG CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.movietheaterManagement.form') ? 'active' : '' }}" 
        href="{{ route('admin.movietheaterManagement.form')}}">Quản lý phòng chiếu</a>

      <h6>SUẤT CHIẾU</h6>
      <a class="ad-link {{ request()->routeIs('admin.showtimeManagement.form') ? 'active' : '' }}"
         href="{{ route('admin.showtimeManagement.form')}}">Quản lý suất chiếu</a>
      
      <h6>BÁO CÁO</h6>
      <a class="ad-link {{request()->routeIs('admin.reports.revenue') ? 'active' : '' }}" 
        href="{{ route('admin.reports.revenue')}}">Doanh thu</a>
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

    <div class="adm-movies">
      @php $kpi = $kpi ?? []; @endphp

      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
          <div class="kpi-card kpi--blue p-3 rounded">
            <div class="text-muted">Phim đang hoạt động</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($kpi['movies_active'] ?? 0)) }}</div>
          </div>
        </div>

      <div class="col-12 col-sm-6 col-lg-6">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Tổng phim đang có</div>
              <div class="fs-4 fw-bold">{{ number_format((int)($kpi['movies_total'] ?? 0)) }}</div>
            </div>
          </div>

      <div class="toolbar-wrap">
        <div class="toolbar">
          <form method="GET" class="search d-flex gap-2">
            <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tên, thể loại, rating...">
            <button class="btn btn-soft">Tìm</button>
          </form>

          <a href="{{ route('moviesManage.template_csv') }}" class="btn btn-soft">CSV mẫu</a>
          <a href="{{ route('moviesManage.export_csv', ['q'=>$q]) }}" class="btn btn-success">Xuất CSV</a>

          <form action="{{ route('moviesManage.export_csv') }}" method="POST" enctype="multipart/form-data" class="csv-input">
            @csrf
            <button type="button" class="btn btn-soft fake-btn">Nhập CSV</button>
            <input type="file" name="file" accept=".csv" onchange="this.form.submit()">
          </form>
          <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Thêm phim</button>
          <a href="{{ route('admin.form') }}" class="btn btn-soft fake-btn">Trở về trang tổng quan</a>
        </div>
      </div>

      <div class="card-like mt-3">
        <div class="table-responsive">
          <table class="table table-movies align-middle mb-0">
            <thead>
              <tr>
                <th class="poster-col">Poster</th>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Thời lượng</th>
                <th>Thể loại</th>
                <th>Rating</th>
                <th>Ngày phát hành</th>
                <th>Trạng thái</th>
                <th class="text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($movies as $m)
              <tr>
                <td class="poster-col" data-label="Poster">
                  @if($m->poster)
                    <img src="{{ asset($m->poster) }}" class="poster-thumb" alt="poster">
                  @endif
                </td>
                <td class="text-muted" data-label="ID">{{ $m->movieID }}</td>
                <td class="fw-semibold" data-label="Tiêu đề">
                  {{ $m->title }}
                  @if(!empty($m->is_banner) && $m->is_banner)
                    <span class="badge bg-warning ms-2">Banner</span>
                  @endif
                </td>
                <td data-label="Thời lượng"><span class="badge badge-soft">{{ $m->durationMin }}p</span></td>
                <td data-label="Thể loại">{{ $m->genre }}</td>
                <td data-label="Rating">{{ $m->rating }}</td>
                <td data-label="Ngày phát hành">{{ $m->releaseDate }}</td>
                <td data-label="Trạng thái">
                  @if($m->status === 'unable')
                    <span class="badge bg-secondary">Ẩn</span>
                  @else
                    <span class="badge bg-success">Hiển thị</span>
                  @endif
                </td>
                <td class="text-end" data-label="Thao tác">
                  <div class="table-actions">
                    <button class="btn btn-sm btn-soft" data-bs-toggle="modal" data-bs-target="#edit{{ $m->movieID }}">Sửa</button>

                    @if(empty($m->is_banner) || !$m->is_banner)
                      <form action="{{ route('moviesManage.banner_set', $m) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-warning" title="Đặt làm banner">Đặt làm banner</button>
                      </form>
                    @else
                      <form action="{{ route('moviesManage.banner_unset', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Bỏ banner cho phim này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-warning" title="Bỏ banner">Bỏ banner</button>
                      </form>
                    @endif

                    <form action="{{ route('moviesManage.delete',$m) }}" method="POST" onsubmit="return confirm('Xoá phim này?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Xoá</button>
                    </form>
                  </div>
                </td>
              </tr>

              <div class="modal fade" id="edit{{ $m->movieID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form class="modal-content" method="POST" action="{{ route('moviesManage.update', $m) }}">
                      @csrf @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Sửa: {{ $m->title }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                      </div>

                      <div class="modal-body row g-3">
                        <div class="col-md-8">
                          <label class="form-label">Tiêu đề</label>
                          <input name="title" value="{{ $m->title }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Thời lượng (phút)</label>
                          <input type="number" name="durationMin" value="{{ $m->durationMin }}" min="0" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label">Thể loại</label>
                          <select name="genre" class="form-select">
                            <option value="">-- Chọn thể loại --</option>
                            @foreach($genres as $g)
                              <option value="{{ $g }}" @selected($m->genre === $g)>{{ $g }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label">Rating</label>
                          <select name="rating" class="form-select">
                            <option value="">-- Chọn rating --</option>
                            @foreach($ratings as $r)
                              <option value="{{ $r }}" @selected($m->rating === $r)>{{ $r }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label">Ngày phát hành</label>
                          <input type="date" name="releaseDate" value="{{ $m->releaseDate }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                          <label class="form-label">Poster</label>
                          <div class="input-group">
                            <input name="poster" id="poster{{ $m->movieID }}" value="{{ $m->poster }}" class="form-control"
                                   placeholder="https://...jpg hoặc storage/pictures/xxx.jpg">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="document.getElementById('posterFile{{ $m->movieID }}').click()">Chọn ảnh</button>
                            <input type="file" id="posterFile{{ $m->movieID }}" class="d-none" accept="image/*">
                          </div>
                          <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh”.</small>
                          <div class="mt-2">
                            <img id="posterPreview{{ $m->movieID }}" src="{{ $m->poster ? asset($m->poster) : '' }}"
                                 style="max-height:120px; {{ $m->poster ? '' : 'display:none' }}" class="rounded border">
                          </div>
                        </div>

                        <div class="col-md-12">
                          <label class="form-label">Trạng thái</label>
                          <select name="status" class="form-select" required>
                            <option value="active" @selected(($m->status ?? 'active') === 'active')>Hiển thị</option>
                            <option value="unable" @selected(($m->status ?? '') === 'unable')>Ẩn</option>
                          </select>
                        </div>

                        <div class="col-12">
                          <label class="form-label">Mô tả</label>
                          <textarea name="description" rows="4" class="form-control">{{ $m->description }}</textarea>
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
                <tr><td colspan="9" class="text-center text-muted py-4">Chưa có phim.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-3">
        {{ $movies->links() }}
      </div>

    </div>
  </main>
</div>

<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('moviesManage.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm phim</h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-8">
          <label class="form-label">Tiêu đề</label>
          <input name="title" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Thời lượng (phút)</label>
          <input type="number" name="durationMin" min="0" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Thể loại</label>
          <select name="genre" class="form-select">
            <option value="">-- Chọn thể loại --</option>
            @foreach($genres as $g)
              <option value="{{ $g }}">{{ $g }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Rating</label>
          <select name="rating" class="form-select">
            <option value="">-- Chọn rating --</option>
            @foreach($ratings as $r)
              <option value="{{ $r }}">{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Ngày phát hành</label>
          <input type="date" name="releaseDate" class="form-control">
        </div>

        <div class="col-md-12">
          <label class="form-label">Poster</label>
          <div class="input-group">
            <input name="poster" id="posterCreate" class="form-control" placeholder="https://...jpg hoặc storage/pictures/xxx.jpg">
            <button type="button" class="btn btn-outline-secondary"
                    onclick="document.getElementById('posterFileCreate').click()">Chọn ảnh</button>
            <input type="file" id="posterFileCreate" class="d-none" accept="image/*">
          </div>
          <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh”.</small>
          <div class="mt-2">
            <img id="posterPreviewCreate" style="max-height:120px; display:none" class="rounded border">
          </div>
        </div>

        <div class="col-md-12">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select" required>
            <option value="active" selected>Hiển thị</option>
            <option value="unable">Ẩn</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Mô tả</label>
          <textarea name="description" rows="4" class="form-control"></textarea>
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
  function attachPosterPicker(textId, fileId, previewId) {
    const txt = document.getElementById(textId);
    const file = document.getElementById(fileId);
    const prev = document.getElementById(previewId);

    file?.addEventListener('change', async () => {
      if (!file.files?.[0]) return;
      const form = new FormData();
      form.append('file', file.files[0]);
      try {
        const res = await fetch(`{{ route('moviesManage.upload_poster') }}`, {
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
        console.error('Upload poster lỗi:', e);
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

  attachPosterPicker('posterCreate', 'posterFileCreate', 'posterPreviewCreate');

  @foreach ($movies as $m)
    attachPosterPicker('poster{{ $m->movieID }}', 'posterFile{{ $m->movieID }}', 'posterPreview{{ $m->movieID }}');
  @endforeach
});
</script>
@endpush

@push('styles')
<style>
  .modal-backdrop.show {
    backdrop-filter: blur(4px);
    background-color: rgba(0,0,0,.4);
  }
  /* =========================================================
    ADMIN MOVIES (scoped by .adm-movies)
    ========================================================= */
  .adm-movies .card-like {
      background: #fff;
      border: 1px solid #eaecf0;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
      overflow: hidden;
  }
  .adm-movies .toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px;
      margin: 16px 0 12px;
  }
  .adm-movies .toolbar .search {
      flex: 1 1 320px;
      max-width: 560px;
  }
  .adm-movies .toolbar .search input[type="search"] {
      height: 44px;
      border-radius: 999px;
      border: 1px solid #eaecf0;
      padding: 0 16px;
      box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
  }
  .adm-movies .toolbar .search input[type="search"]:focus {
      outline: none;
      border-color: #b8bdfd;
      box-shadow: 0 0 0 4px rgba(69, 74, 242, 0.12);
  }
  .adm-movies .btn-soft {
      background: #f9fafb;
      border: 1px solid #eaecf0;
      color: #101828;
  }
  .adm-movies .btn-soft:hover {
      background: #fff;
  }
  .adm-movies .btn-brand {
      background: #454af2;
      border-color: #454af2;
      color: #fff;
  }
  .adm-movies .btn-brand:hover {
      filter: brightness(0.95);
  }
  .adm-movies .csv-input {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 8px;
  }
  .adm-movies .csv-input input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
  }
  .adm-movies .csv-input .fake-btn {
      pointer-events: none;
  }

  .adm-movies .table-movies {
      margin: 0;
  }
  .adm-movies .table-movies thead th {
      background: #f6f7fb;
      color: #667085;
      font-weight: 600;
      font-size: 0.85rem;
      border-bottom: 1px solid #eaecf0 !important;
      white-space: nowrap;
  }
  .adm-movies .table-movies tbody td {
      vertical-align: middle;
      border-color: #eaecf0;
      color: #101828;
  }
  .adm-movies .poster-col {
      width: 64px;
  }
  .adm-movies .poster-thumb {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      object-fit: cover;
      box-shadow: 0 2px 6px rgba(16, 24, 40, 0.06);
  }
  .adm-movies .badge-soft {
      background: #f9fafb;
      color: #667085;
      border: 1px solid #eaecf0;
      font-weight: 500;
  }
  .adm-movies .table-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
  }
  .adm-movies .toolbar-wrap {
      background: #fff;
      border: 1px solid #eaecf0;
      border-radius: 12px;
      padding: 10px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
  }

  /* Responsive bảng */
  @media (max-width: 992px) {
      .adm-movies .table-movies thead {
          display: none;
      }
      .adm-movies .table-movies tbody tr {
          display: block;
          border-bottom: 1px solid #eaecf0;
          padding: 12px 12px;
      }
      .adm-movies .table-movies tbody td {
          display: flex;
          justify-content: space-between;
          gap: 12px;
          padding: 8px 0;
          border: 0;
      }
      .adm-movies .table-movies tbody td::before {
          content: attr(data-label);
          color: #667085;
          font-weight: 600;
      }
      .adm-movies .table-actions {
          justify-content: flex-start;
      }
  }

  /* KPI CARD*/
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
  /* ===== Admin Dashboard – Greeting Banner ===== */

</style>
@endpush