@extends('layouts.app')
@section('title', 'Bảng điều khiển - DMM Cinema')

@section('content')
<div class="ad-wrapper d-flex">
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

  {{-- Main --}}
  <main class="ad-main flex-grow-1">
    <div class="container-fluid">
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

      <div class="ad-greeting card shadow-sm border-0 w-100">
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

      {{-- Tiêu đề trang --}}
      <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
        <h3 class="m-0">Tổng quan</h3>
      </div>

      {{-- KPI --}}
      @php $kpi = $kpi ?? []; @endphp
      <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="kpi-card kpi--blue p-3 rounded">
            <div class="text-muted">Doanh thu hôm nay</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($kpi['revenue_today'] ?? 0)) }} đ</div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="kpi-card kpi--green p-3 rounded">
            <div class="text-muted">Vé đã bán hôm nay</div>
            <div class="fs-4 fw-bold">{{ (int)($kpi['tickets_today'] ?? 0) }}</div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="kpi-card kpi--orange p-3 rounded">
            <div class="text-muted">Tổng người dùng</div>
            <div class="fs-4 fw-bold">{{ (int)($kpi['users_total'] ?? 0) }}</div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="kpi-card kpi--purple p-3 rounded">
            <div class="text-muted">Phim đang hoạt động</div>
            <div class="fs-4 fw-bold">{{ (int)($kpi['movies_active'] ?? 0) }}</div>
          </div>
        </div>
      </div>

      {{-- 2 cột: Lịch sử & Top phim --}}
      <div class="row g-3">
        <div class="col-lg-7">
          <div class="ad-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="m-0">Lịch sử vé gần đây</h6>
              <a href="{{ url('/adminDashboard') }}">Xem tất cả</a>
            </div>
            <div class="table-responsive">
              <table class="table table-clean mb-0">
                <thead>
                  <tr>
                    <th>Mã vé</th>
                    <th>Phim</th>
                    <th>Ghế</th>
                    <th>Giá</th>
                    <th>Thời gian</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(($recentTickets ?? collect()) as $t)
                    <tr>
                      <td>{{ $t->code }}</td>
                      <td>{{ $t->movie }}</td>
                      <td>{{ $t->seat }}</td>
                      <td>{{ number_format((int)$t->price) }} đ</td>
                      <td>{{ $t->time }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-muted text-center">Chưa có vé</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="ad-card p-2">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <h6 class="m-0">Top phim</h6>
              <a href="{{ route('admin.reports.revenue') }}">Báo cáo</a>
            </div>
            <div class="table-responsive">
              <table class="table table-clean mb-0">
                <tbody>
                  @forelse(($topMovies ?? collect()) as $m)
                    <tr>
                      <td>{{ $m->title }}</td>
                      <td class="text-end">{{ number_format((int)$m->revenue) }} đ</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="2" class="text-muted text-center">Chưa có dữ liệu</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Suất chiếu sắp tới --}}
      <div class="ad-card p-2 mt-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="m-0">Suất chiếu sắp tới</h6>
          <a href="{{ url('/adminDashboard/moviesManagement/main') }}">Lịch chiếu</a>
        </div>
        <div class="table-responsive">
          <table class="table table-clean mb-0">
            <thead>
              <tr>
                <th>Giờ</th>
                <th>Phim</th>
                <th>Phòng</th>
                <th class="text-end">Ghế</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($upcomingShowtimes ?? collect()) as $s)
                <tr>
                  <td>{{ $s->time }}</td>
                  <td>{{ $s->movie }}</td>
                  <td>{{ $s->theater }}</td>
                  <td class="text-end">{{ $s->seats }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-muted text-center">Chưa có suất nào</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="ad-card p-2 mt-3" style="max-width: 1040px;">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="m-0">Phòng chiếu phim</h6>
          {{-- Nếu có trang quản lý rạp, giữ link; không có thì bỏ dòng dưới --}}
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
  </main>
</div>
@endsection


@push('styles')
<style>
  /* --- KPI Grid --- */
  .kpi-grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
      margin-bottom: 16px;
  }
  .kpi-grid > .kpi-col {
      grid-column: span 3;
  } /* 4 items */
  @media (max-width: 1200px) {
      .kpi-grid > .kpi-col {
          grid-column: span 4;
      }
  } /* 3 */
  @media (max-width: 992px) {
      .kpi-grid > .kpi-col {
          grid-column: span 6;
      }
  } /* 2 */
  @media (max-width: 576px) {
      .kpi-grid > .kpi-col {
          grid-column: span 12;
      }
  } /* 1 */

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
  .kpi-card .kpi-label {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #667085;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 0.2px;
      margin-bottom: 6px;
  }
  .kpi-card .kpi-value {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.1;
      color: #111827;
  }
  .kpi-card .kpi-sub {
      margin-top: 6px;
      color: #667085;
      font-size: 0.85rem;
  }
  .kpi--blue {
      border-color: #e4ebff;
  }
  .kpi--green {
      border-color: #dcfce7;
  }
  .kpi--orange {
      border-color: #ffedd5;
  }
  .kpi--purple {
      border-color: #ede9fe;
  }
</style>
@endpush