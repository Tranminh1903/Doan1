@extends('layouts.app')
@section('title','Quản lý khuyến mãi')
@section('content')

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
    <div class="container-fluid">
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

      <div class="adm-movietheater">
        @php $kpi = $kpi ?? []; @endphp
        <div class="row g-3 mb-3">
          <div class="col-12 col-sm-6 col-lg-12">
            <div class="kpi-card kpi--blue p-3 rounded">
              <div class="text-muted">Phòng chiếu đang có</div>
              <div class="fs-4 fw-bold">{{ number_format((int)($kpi['movieTheaters_total'] ?? 0)) }}</div>
            </div>
          </div>
        </div>

        {{-- TOOLBAR --}}
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
            <a href="{{ route('admin.form') }}" class="btn btn-soft">Trở về trang tổng quan</a>
          </div>
        </div>

        <div class="ad-card p-3 mt-3" style="max-width: 1040px;">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="m-0">Phòng chiếu phim</h6>
            <a href="" class="small">Quản lý</a>
          </div>

          <div class="table-responsive">
            <table class="table table-clean mb-0">
              <thead>
                <tr>
                  <th>Phòng</th>
                  <th class="text-end">Số ghế</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($theaterMini ?? collect()) as $t)
                  <tr>
                    <td>{{ $t->roomName }}</td>
                    <td class="text-end">{{ (int) $t->capacity }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="2" class="text-muted text-center">Chưa có phòng chiếu</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div> 
  </main>
</div>
@endsection

@push('styles')
<style>
  /* ===== Toolbar (áp từ adm-movies sang adm-movietheater) ===== */
  .adm-movietheater .toolbar-wrap{
    background:#fff;
    border:1px solid #eaecf0;
    border-radius:12px;
    padding:10px;
    box-shadow:0 10px 30px rgba(16,24,40,.06);
  }
  .adm-movietheater .toolbar{
    display:flex;
    align-items:center;
    gap:12px;
    margin:16px 0 12px;
    flex-wrap:nowrap; /* Desktop: 1 hàng */
  }
  .adm-movietheater .toolbar .search{
    flex:1 1 320px;
    max-width:560px;
  }
  .adm-movietheater .toolbar .search .form-control{
    height:38px;
    border-radius:.375rem;
    padding:.375rem .75rem;
    border:1px solid #dee2e6;
    box-shadow:none;
  }
  .adm-movietheater .toolbar .search .form-control:focus{
    outline:0;
    border-color:#b8bdfd;
    box-shadow:0 0 0 .25rem rgba(69,74,242,.12);
  }

  .adm-movietheater .btn-soft{
    background:#f9fafb;
    border:1px solid #eaecf0;
    color:#101828;
  }
  .adm-movietheater .btn-soft:hover{ background:#fff; }

  .adm-movietheater .btn-brand{
    background:#454af2;
    border-color:#454af2;
    color:#fff;
  }
  .adm-movietheater .btn-brand:hover{ filter:brightness(.95); }

  .adm-movietheater .csv-input{
    position:relative;
    display:inline-flex;
    align-items:center;
    gap:8px;
  }
  .adm-movietheater .csv-input input[type="file"]{
    position:absolute;
    inset:0;
    opacity:0;
    cursor:pointer;
  }
  .adm-movietheater .csv-input .fake-btn{ pointer-events:none; }

  .adm-movietheater .toolbar .btn,
  .adm-movietheater .csv-input .btn{
    white-space:nowrap;
    flex-shrink:0;
    line-height:1.2;
    padding-left:12px;
    padding-right:12px;
    height:38px;
  }

  /* ===== Responsive: cho phép xuống dòng khi màn nhỏ ===== */
  @media (max-width: 992px){
    .adm-movietheater .toolbar{ flex-wrap:wrap; }
  }

  /* ===== KPI CARD (giữ nguyên) ===== */
  .kpi-card {
      background: #fff;
      border: 1px solid #eef2f6;
      border-radius: 14px;
      padding: 16px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
      transition: transform 0.12s ease, box-shadow 0.18s ease, border-color 0.18s ease;
  }
  .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 34px rgba(16, 24, 40, 0.08);
      border-color: #e3eaf3;
  }
  .kpi--blue { border-color: #e4ebff; }
  .kpi--green { border-color: #dcfce7; }
</style>
@endpush
