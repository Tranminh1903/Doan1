@extends('layouts.app')
@section('title','Quản lý suất chiếu')
@section('content')

<div class="ad-wrapper d-flex container-fluid">
  <!-- SIDEBAR -->
  <aside class="ad-sidebar">
    <nav class="ad-menu">
      <h6>TỔNG QUAN</h6>
      <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
        href="{{ route('admin.form')}}">Bảng điều khiển</a>

      <h6>NGƯỜI DÙNG</h6>
      <a class="ad-link {{request()->routeIs('admin.userManagement_main.form') ? 'active' : '' }}"
        href="{{ route('admin.userManagement_main.form')}}">Quản lý người dùng</a>

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
  use Illuminate\Support\Facades\Auth;
  $now = now();
  $hour = (int)$now->format('G');
  $user = Auth::user();

  $greeting =
  $hour < 12 ? 'Chào buổi sáng' :
    ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối' );

    $weekdayMap=[ 'Mon'=>'Thứ hai', 'Tue'=>'Thứ ba', 'Wed'=>'Thứ tư',
    'Thu'=>'Thứ năm', 'Fri'=>'Thứ sáu', 'Sat'=>'Thứ bảy', 'Sun'=>'Chủ nhật'
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
            style="width:72px;height:72px;object-fit:cover">
          <div class="me-auto">
            <h5 class="mb-1">👋 {{ $greeting }}, <span class="text-primary">{{ $user->username }}</span></h5>
            <div class="text-muted small">{{ $weekdayVN }}, {{ $dateVN }}</div>
          </div>

          <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Làm mới</a>
        </div>
      </div>

      <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
        <h3 class="m-0">Tổng quan</h3>
      </div>

      <div class="adm-showtime">
        <div class="row g-3 mb-3">
          @php $kpi = $kpi ?? []; @endphp

          <div class="col-12 col-sm-6 col-lg-6">
            <div class="kpi-card kpi--blue p-3 rounded">
              <div class="text-muted">Suất chiếu đang có</div>
              <div class="fs-4 fw-bold">{{ $kpi['showtime_total'] ?? 0 }}</div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-6">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Suất chiếu hôm nay</div>
              <div class="fs-4 fw-bold">{{ $kpi['today'] ?? 0 }}</div>
            </div>
          </div>
        </div>

        <div class="toolbar-wrap">
          <div class="toolbar">
            <form method="GET" class="search d-flex gap-2">
              <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo phim, phòng chiếu...">
              <button class="btn btn-soft">Tìm</button>
            </form>

            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Thêm suất chiếu</button>
          </div>
        </div>

        <div class="card-like p-3 mt-3">
          <div class="table-responsive">
            <table class="table table-showtime align-middle mb-0">
              <thead>
                <tr>
                  <th>Phim</th>
                  <th>Phòng chiếu</th>
                  <th>Bắt đầu</th>
                  <th>Kết thúc</th>
                  <th class="text-end">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($showtimes as $s)
                <tr>
                  <td>{{ $s->movie->title }}</td>
                  <td>{{ $s->theater->roomName }}</td>
                  <td>{{ $s->startTime }}</td>
                  <td>{{ $s->endTime }}</td>

                  <td class="text-end">
                    <button class="btn btn-sm btn-soft"
                      data-bs-toggle="modal"
                      data-bs-target="#edit{{ $s->showtimeID }}">Sửa</button>
                    <form method="POST" action="{{ route('admin.showtime.delete', $s) }}"
                          onsubmit="return confirm('Xoá suất chiếu này?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Xoá</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Chưa có suất chiếu</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @foreach ($showtimes as $s)
          <div class="modal fade" id="edit{{ $s->showtimeID }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <form class="modal-content" method="POST"
                action="{{ route('admin.showtime.update', $s) }}">
                @csrf @method('PUT')

                <div class="modal-header">
                  <h5 class="modal-title">Sửa suất chiếu</h5>
                  <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Phim</label>
                    <select name="movieID" class="form-select">
                      @foreach($movies as $m)
                      <option value="{{ $m->movieID }}"
                        @selected($m->movieID == $s->movieID)>
                        {{ $m->title }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Phòng chiếu</label>
                    <select name="theaterID" class="form-select">
                      @foreach($theaters as $t)
                      <option value="{{ $t->theaterID }}"
                        @selected($t->theaterID == $s->theaterID)>
                        {{ $t->roomName }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Bắt đầu</label>
                    <input type="datetime-local" name="startTime"
                      value="{{ \Carbon\Carbon::parse($s->startTime)->format('Y-m-d\TH:i') }}"
                      class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Kết thúc</label>
                    <input type="datetime-local" name="endTime"
                      value="{{ \Carbon\Carbon::parse($s->endTime)->format('Y-m-d\TH:i') }}"
                      class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-soft" data-bs-dismiss="modal">Đóng</button>
                  <button class="btn btn-brand">Lưu thay đổi</button>
                </div>
              </form>
            </div>
          </div>
           @endforeach
          <div class="mt-3">{{ $showtimes->links('vendor.pagination.bootstrap-5') }}</div>
        </div>
        <div class="modal fade" id="modalCreate" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST"
              action="{{ route('admin.showtime.store') }}">
              @csrf

              <div class="modal-header">
                <h5 class="modal-title">Tạo suất chiếu mới</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body row g-3">

                <div class="col-md-6">
                  <label class="form-label">Phim</label>
                  <select name="movieID" class="form-select" required>
                    @foreach($movies as $m)
                    <option value="{{ $m->movieID }}">{{ $m->title }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phòng chiếu</label>
                  <select name="theaterID" class="form-select" required>
                    @foreach($theaters as $t)
                    <option value="{{ $t->theaterID }}">{{ $t->roomName }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Thời gian bắt đầu</label>
                  <input type="datetime-local" name="startTime" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Thời gian kết thúc</label>
                  <input type="datetime-local" name="endTime" class="form-control" required>
                </div>
              </div>

              <div class="modal-footer">
                <button class="btn btn-soft" data-bs-dismiss="modal">Đóng</button>
                <button class="btn btn-brand">Lưu</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const movieDurations = @json($movies->pluck('durationMin', 'movieID'));

    function autoEndTime(modalId) {
      const root = document.getElementById(modalId);
      if (!root) return;

      const movieSelect = root.querySelector('select[name="movieID"]');
      const startInput  = root.querySelector('input[name="startTime"]');
      const endInput    = root.querySelector('input[name="endTime"]');

      if (!movieSelect || !startInput || !endInput) return;

      function pad(num) {
        return num.toString().padStart(2, '0');
      }

      function updateEnd() {
        const movieID = movieSelect.value;
        const start   = startInput.value;

        if (!movieID || !start) return;

        const duration = Number(movieDurations[movieID]);
        if (!duration) return;

        const [datePart, timePart] = start.split('T');
        const [year, month, day]   = datePart.split('-').map(Number);
        const [hour, minute]       = timePart.split(':').map(Number);

        const startTime = new Date(year, month - 1, day, hour, minute);
        startTime.setMinutes(startTime.getMinutes() + duration);

        const y  = startTime.getFullYear();
        const m  = pad(startTime.getMonth() + 1);
        const d  = pad(startTime.getDate());
        const hh = pad(startTime.getHours());
        const mm = pad(startTime.getMinutes());


        endInput.value = `${y}-${m}-${d}T${hh}:${mm}`;
      }

      movieSelect.addEventListener('change', updateEnd);
      startInput.addEventListener('input', updateEnd);
    }

    autoEndTime('modalCreate');

    @foreach($showtimes as $s)
      autoEndTime('edit{{ $s->showtimeID }}');
    @endforeach
  });
</script>
@endpush

@push('styles')
<style>
  .adm-showtime .card-like {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 12px;
    padding: 0 !important;
    box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
    overflow: hidden;
  }

  .adm-showtime .table-showtime {
    margin: 0;
  }

  .adm-showtime .table-showtime thead th {
    background: #f6f7fb !important;
    color: #667085 !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    padding: 14px 16px !important;
    white-space: nowrap;
    border-bottom: 1px solid #eaecf0 !important;
  }

  .adm-showtime .table-showtime tbody td {
    padding: 14px 16px !important;
    font-size: 0.9rem !important;
    color: #101828 !important;
    border-color: #eaecf0 !important;
    vertical-align: middle !important;
  }

  .adm-showtime .table-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
  }

  .adm-showtime .toolbar-wrap {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 10px 30px rgba(16, 24, 40, .06);
  }

  .adm-showtime .toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 16px 0 12px;
  }

  .adm-showtime .toolbar .search {
    flex: 1 1 320px;
    max-width: 560px;
  }

  .adm-showtime .toolbar .search .form-control {
    height: 38px;
    border-radius: .375rem;
    padding: .375rem .75rem;
    border: 1px solid #dee2e6;
  }

  .adm-showtime .toolbar .search .form-control:focus {
    border-color: #b8bdfd;
    box-shadow: 0 0 0 .25rem rgba(69, 74, 242, .12);
  }

  .adm-showtime .btn-soft {
    background: #f9fafb;
    border: 1px solid #eaecf0;
    color: #101828;
  }

  .adm-showtime .btn-soft:hover {
    background: #fff;
  }

  .adm-showtime .btn-brand {
    background: #454af2;
    border-color: #454af2;
    color: #fff;
  }

  .adm-showtime .btn-brand:hover {
    filter: brightness(.95);
  }

  .adm-showtime .badge {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  @media(max-width:992px) {
    .adm-showtime .table-showtime thead {
      display: none !important;
    }

    .adm-showtime .table-showtime tbody tr {
      display: block !important;
      border-bottom: 1px solid #eaecf0 !important;
      padding: 12px !important;
    }

    .adm-showtime .table-showtime tbody td {
      display: flex !important;
      justify-content: space-between !important;
      gap: 12px !important;
      padding: 8px 0 !important;
      border: 0 !important;
    }

    .adm-showtime .table-showtime tbody td::before {
      content: attr(data-label);
      color: #667085;
      font-weight: 600;
    }

    .adm-showtime .table-actions {
      justify-content: flex-start !important;
    }
  }

  .pagination {
    margin-top: 16px !important;
  }

  .pagination .page-item.active .page-link {
    background: #454af2 !important;
    border-color: #454af2 !important;
    color: #fff !important;
  }

  .pagination .page-link {
    color: #454af2 !important;
    border-radius: 6px !important;
  }

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
</style>
@endpush