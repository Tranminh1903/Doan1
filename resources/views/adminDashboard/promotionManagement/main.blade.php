@extends('layouts.app')
@section('title','Quản lý khuyến mãi')
@section('content')

@php
$types = ['percent' => 'Giảm %', 'fixed' => 'Giảm tiền cố định'];
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
use Illuminate\Support\Facades\Auth;

$now = now();
$hour = (int) $now->format('G');
$user = Auth::user();

$greeting = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
$weekdayMap=[ 'Mon'=> 'Thứ hai', 'Tue' => 'Thứ ba', 'Wed' => 'Thứ tư',
              'Thu' => 'Thứ năm', 'Fri' => 'Thứ sáu', 'Sat' => 'Thứ bảy', 'Sun' => 'Chủ nhật'];
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

  <div class="adm-promotion">
    @php
      $kpi = $kpi ?? [];
      $q = $q ?? request('q','');
    @endphp

    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-6">
        <div class="kpi-card kpi--blue p-3 rounded">
          <div class="text-muted">Mã khuyến mãi đang hoạt động</div>
          <div class="fs-4 fw-bold">{{ number_format((int)($kpi['promotion_active'] ?? 0)) }}</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-6">
        <div class="kpi-card kpi--green p-3 rounded">
          <div class="text-muted">Tổng mã khuyến mãi</div>
          <div class="fs-4 fw-bold">{{ number_format((int)($kpi['promotion_total'] ?? 0)) }}</div>
        </div>
      </div>
    </div>

    <div class="toolbar-wrap">
      <div class="toolbar">
        <form method="GET" class="search d-flex gap-2">
          <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo mã, mô tả, giá trị...">
          <button class="btn btn-soft">Tìm</button>
        </form>
        <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreate">
          + Thêm khuyến mãi
        </button>
      </div>
    </div>
    <div class="card-like mt-3">
      <div class="table-responsive">
        <table class="table table-promotion align-middle mb-0">
          <thead>
            <tr>
              <th>Mã</th>
              <th>Loại</th>
              <th>Giá trị</th>
              <th>Giới hạn lượt</th>
              <th>Giá trị tối thiểu</th>
              <th>Số vé tối thiểu</th>
              <th>Đã dùng</th>
              <th>Thời gian</th>
              <th>Trạng thái</th>
              <th class="text-end">Thao tác</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($promotions as $p)
            <tr>
              <td class="fw-semibold">{{ $p->code }}</td>
              <td>
                {{ $p->type === 'percent' ? 'Giảm %' : 'Giảm cố định' }}
              </td>

              <td>
                @if($p->type === 'percent')
                  {{ $p->value }}%
                @else
                  {{ number_format($p->value) }}đ
                @endif
              </td>

              <td>{{ $p->limit_count }}</td>
              <td>{{ $p->min_order_value ?? '-' }}</td>
              <td>{{ $p->min_ticket_quantity ?? '-' }}</td>

              <td>{{ $p->used_count }}</td>

              <td>
                {{ \Carbon\Carbon::parse($p->start_date)->format('d/m/Y H:i') }}  
                →  
                {{ \Carbon\Carbon::parse($p->end_date)->format('d/m/Y H:i') }}
              </td>

              <td>
                @if($p->status === 'active')
                  <span class="badge bg-success">Hoạt động</span>
                @else
                  <span class="badge bg-secondary">Ngưng</span>
                @endif
              </td>

              <td class="text-end">
                <div class="table-actions d-flex gap-2 justify-content-end">

                  <button class="btn btn-sm btn-soft"
                          onclick='openEditModal(@json($p))'>
                    Sửa
                  </button>

                  <form action="{{ route('admin.promotionManagement.delete', $p) }}"
                        method="POST"
                        onsubmit="return confirm('Xoá khuyến mãi này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                      Xoá
                    </button>
                  </form>

                </div>
              </td>
            </tr>
            @empty
              <tr>
                <td colspan="10"
                    class="text-center text-muted py-4">
                    Chưa có khuyến mãi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-3">
      {{ $linkPage->links('vendor.pagination.bootstrap-5') }}
    </div>
  </div>
</main>
</div>

<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('admin.promotionManagement.store') }}">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Thêm khuyến mãi mới</h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
      </div>

      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Mã khuyến mãi</label>
          <input name="code" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Loại giảm giá</label>
          <select name="type" class="form-select">
            <option value="percent">Giảm %</option>
            <option value="fixed">Giảm cố định</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giá trị giảm</label>
          <input type="number" step="0.1" name="value" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giới hạn lượt dùng</label>
          <input type="number" name="limit_count" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giá trị đơn tối thiểu (VNĐ)</label>
          <input type="number" name="min_order_value" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Số vé tối thiểu</label>
          <input type="number" name="min_ticket_quantity" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="datetime-local" name="start_date" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="datetime-local" name="end_date" class="form-control" required>
        </div>

        <div class="col-12">
          <label class="form-label">Mô tả</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
        <button class="btn btn-brand">Lưu</button>
      </div>

    </form>
  </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" id="formEdit">
      @csrf @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Chỉnh sửa khuyến mãi</h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
      </div>

      <div class="modal-body row g-3">
        
        <div class="col-md-6">
          <label class="form-label">Mã khuyến mãi</label>
          <input name="code" id="edit_code" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Loại giảm</label>
          <select name="type" id="edit_type" class="form-select">
            <option value="percent">Giảm %</option>
            <option value="fixed">Giảm cố định</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giá trị giảm</label>
          <input type="number" step="0.1" id="edit_value" name="value" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Giới hạn lượt dùng</label>
          <input type="number" name="limit_count" id="edit_limit_count" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Giá trị đơn tối thiểu</label>
          <input type="number" name="min_order_value" id="edit_min_order_value" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Số vé tối thiểu</label>
          <input type="number" name="min_ticket_quantity" id="edit_min_ticket_quantity" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="datetime-local" name="start_date" id="edit_start_date" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control">
        </div>

        <div class="col-md-12">
          <label class="form-label">Trạng thái</label>
          <select name="status" id="edit_status" class="form-select">
            <option value="active">Hoạt động</option>
            <option value="inactive">Ngưng</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Mô tả</label>
          <textarea name="description" id="edit_description" class="form-control" rows="4"></textarea>
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
function openEditModal(data) {
  document.getElementById('formEdit').action =
      "{{ url('adminDashboard/promotion/update') }}/" + data.id;
  document.getElementById('edit_code').value = data.code;
  document.getElementById('edit_type').value = data.type;
  document.getElementById('edit_value').value = data.value;
  document.getElementById('edit_limit_count').value = data.limit_count ?? '';
  document.getElementById('edit_min_order_value').value = data.min_order_value ?? '';
  document.getElementById('edit_min_ticket_quantity').value = data.min_ticket_quantity ?? '';
  if (data.start_date) {
    document.getElementById('edit_start_date').value =
      data.start_date.replace(' ', 'T');
  }
  if (data.end_date) {
    document.getElementById('edit_end_date').value =
      data.end_date.replace(' ', 'T');
  }
  document.getElementById('edit_status').value = data.status ?? 'active';
  document.getElementById('edit_description').value = data.description ?? '';
  const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
  modal.show();
}
</script>
@endpush

@push('styles')
<style>
  .modal-backdrop.show {
    backdrop-filter: blur(4px);
    background-color: rgba(0,0,0,.4);
  }
  .adm-promotion .card-like {
      background: #fff;
      border: 1px solid #eaecf0;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
      overflow: hidden;
  }
  .adm-promotion .toolbar{
    display:flex;
    align-items:center;
    gap:12px;
    margin:16px 0 12px;
    flex-wrap: nowrap; 
  }
  .adm-promotion .toolbar .search {
      flex: 1 1 320px;
      max-width: 560px;
  }
  .adm-promotion .btn-soft {
      background: #f9fafb;
      border: 1px solid #eaecf0;
      color: #101828;
  }
  .adm-promotion .btn-soft:hover {
      background: #fff;
  }
  .adm-promotion .btn-brand {
      background: #454af2;
      border-color: #454af2;
      color: #fff;
  }
  .adm-promotion .btn-brand:hover {
      filter: brightness(0.95);
  }

  .adm-promotion .csv-input {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 8px;
  }
  .adm-promotion .csv-input input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
  }
  .adm-promotion .csv-input .fake-btn {
      pointer-events: none;
  }
  .adm-promotion .toolbar .btn,
  .adm-promotion .csv-input .btn{
      white-space:nowrap;  
      flex-shrink:0;     
      line-height:1.2;
      padding-left:12px;
      padding-right:12px;
  }
  .adm-promotion .table-promotion {
      margin: 0;
  }
  .adm-promotion .table-promotion thead th {
      background: #f6f7fb;
      color: #667085;
      font-weight: 600;
      font-size: 0.85rem;
      border-bottom: 1px solid #eaecf0 !important;
      white-space: nowrap;
  }
  .adm-promotion .table-promotion tbody td {
      vertical-align: middle;
      border-color: #eaecf0;
      color: #101828;
  }
  .adm-promotion .badge-soft {
      background: #f9fafb;
      color: #667085;
      border: 1px solid #eaecf0;
      font-weight: 500;
  }
  .adm-promotion .table-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
  }
  .adm-promotion .toolbar-wrap{
    background:#fff;
    border:1px solid #eaecf0;
    border-radius:12px;
    padding:10px;
    box-shadow:0 10px 30px rgba(16,24,40,.06);
  }
  .adm-promotion .toolbar .search .form-control{
    height:38px;                 
    border-radius:.375rem;       
    padding:.375rem .75rem;
    border:1px solid #dee2e6;
    box-shadow:none;
  }
  .adm-promotion .toolbar .search .form-control:focus{
    outline:0;
    border-color:#b8bdfd;
    box-shadow:0 0 0 .25rem rgba(69,74,242,.12);
  }
  @media (max-width: 992px) {
      .adm-promotion .table-promotion thead {
          display: none;
      }
      .adm-promotion .table-promotion tbody tr {
          display: block;
          border-bottom: 1px solid #eaecf0;
          padding: 12px 12px;
      }
      .adm-promotion .table-promotion tbody td {
          display: flex;
          justify-content: space-between;
          gap: 12px;
          padding: 8px 0;
          border: 0;
      }
      .adm-promotion .table-promotion tbody td::before {
          content: attr(data-label);
          color: #667085;
          font-weight: 600;
      }
      .adm-promotion .table-actions {
          justify-content: flex-start;
      }
      .adm-promotion .toolbar{ 
        flex-wrap:nowrap; 
      }
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
