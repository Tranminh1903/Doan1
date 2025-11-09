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
  {{-- MAIN CONTENT --}}
  @php
    use Illuminate\Support\Facades\Auth;

    $now = now();
    $hour = (int) $now->format('G');
    $user = Auth::user();

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
    
      @php
        $kpi = $kpi ?? []; 
        $q   = $q ?? request('q','');
      @endphp

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
      </div>

    <!-- Page Title -->
    <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
      <h3 class="m-0">Quản lý khuyến mãi</h3>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
        + Thêm khuyến mãi
      </button>
    </div>

    <!-- Promotion Table -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Mã</th>
              <th>Loại</th>
              <th>Giá trị</th>
              <th>Giới hạn</th>
              <th>Giá trị tối thiểu</th>
              <th>Số ghế tối thiểu</th>
              <th>Đã dùng</th>
              <th>Hiệu lực</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
              
            </tr>
          </thead>
          <tbody>
            @foreach ($promotions as $promotion)
              <tr>
                <td>{{ $promotion->code }}</td>
                <td>{{ $promotion->type == 'percent' ? 'Giảm %' : 'Giảm cố định' }}</td>
                <td>{{ $promotion->type == 'percent' ? $promotion->value.'%' : number_format($promotion->value).'đ' }}</td>
                <td>{{ $promotion->limit_count }}</td>
                <td>{{ $promotion->min_order_value ?? '-' }}</td>
                <td>{{ $promotion->min_ticket_quantity ?? '-' }}</td>
                <td>{{ $promotion->used_count }}</td>
                <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}</td>
                <td>
                  <span class="badge bg-{{ $promotion->status == 'active' ? 'success' : 'secondary' }}">
                    {{ $promotion->status == 'active' ? 'Hoạt động' : 'Ngưng' }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm"
                    onclick='openEditModal(@json($promotion))'>
                    Sửa
                  </button>
                  <form action="{{ route('admin.promotion.delete', $promotion->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa khuyến mãi này?')">Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Thêm khuyến mãi -->
    <div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.promotion.store') }}" method="POST" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addPromotionLabel">Thêm khuyến mãi mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Mã khuyến mãi</label>
                <input type="text" name="code" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Loại giảm giá</label>
                <select name="type" class="form-select" required>
                  <option value="percent">Giảm theo %</option>
                  <option value="fixed">Giảm cố định (VNĐ)</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Giá trị giảm</label>
                <input type="number" step="0.01" name="value" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Giới hạn lượt dùng</label>
                <input type="number" name="limit_count" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Giá trị đơn hàng tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_value" class="form-control" placeholder="VD: 50000">
              </div>
              <div class="col-md-6">
                <label class="form-label">Số ghế tối thiểu</label>
                <input type="number" name="min_ticket_quantity" class="form-control" placeholder="VD: 2">
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
                <textarea name="description" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Thêm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          </div>
        </form>
      </div>
    </div>

<div class="modal fade" id="editPromotionModal" tabindex="-1" aria-labelledby="editPromotionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editPromotionForm" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editPromotionLabel">Chỉnh sửa khuyến mãi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Mã khuyến mãi</label>
            <input type="text" name="code" id="edit_code" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Loại giảm giá</label>
            <select name="type" id="edit_type" class="form-select" required>
              <option value="percent">Giảm theo %</option>
              <option value="fixed">Giảm cố định (VNĐ)</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Giá trị giảm</label>
            <input type="number" step="0.01" name="value" id="edit_value" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Giới hạn lượt dùng</label>
            <input type="number" name="limit_count" id="edit_limit_count" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Giá trị đơn hàng tối thiểu (VNĐ)</label>
            <input type="number" name="min_order_value" id="edit_min_order_value" class="form-control" placeholder="VD: 50000">
          </div>

          <div class="col-md-6">
            <label class="form-label">Số ghế tối thiểu</label>
            <input type="number" name="min_ticket_quantity" id="edit_min_ticket_quantity" class="form-control" placeholder="VD: 2">
          </div>

          <div class="col-md-6">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="datetime-local" name="start_date" id="edit_start_date" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Ngày kết thúc</label>
            <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_status" class="form-label">Trạng thái</label>
            <select name="status" id="edit_status" class="form-select">
            <option value="active">Hoạt động</option>
            <option value="inactive">Ngừng</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" id="edit_description" class="form-control"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
      </div>
    </form>
  </div>
</div>

  </main>
</div>
@endsection
@push('scripts')
    <script>
function openEditModal(promotion) {
  console.log(promotion);
    // Gán giá trị vào input
    document.querySelector('#editPromotionForm').action = '/adminDashboard/promotion/update/' + promotion.id;
    document.querySelector('#edit_code').value = promotion.code;
    document.querySelector('#edit_type').value = promotion.type;
    document.querySelector('#edit_value').value = promotion.value;
    document.querySelector('#edit_limit_count').value = promotion.limit_count;
    document.querySelector('#edit_min_order_value').value = promotion.min_order_value ?? '';
    document.querySelector('#edit_min_ticket_quantity').value = promotion.min_ticket_quantity ?? '';
    document.querySelector('#edit_start_date').value = promotion.start_date;
    document.querySelector('#edit_end_date').value = promotion.end_date;
    document.querySelector('#edit_status').value = promotion.status;
    document.querySelector('#edit_description').value = promotion.description ?? '';
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('editPromotionModal'));
    modal.show();
}
</script>
@endpush
@push('styles')
<style>
  .table th, .table td { vertical-align: middle; }
  .ad-page-title h3 { font-weight: 600; }

   /* KPI CARD ====== */
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
</style>
@endpush

