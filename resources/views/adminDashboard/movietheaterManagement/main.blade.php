@extends('layouts.app')
@section('title','Quản lý phòng chiếu')
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

      <h6>TIN TỨC</h6>
      <a class="ad-link {{ request()->routeIs('admin.newsManagement.form') ? 'active' : '' }}"
        href="{{ route('admin.newsManagement.form') }}">Quản lý tin tức</a>        
    </nav>
  </aside>
  @php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;

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
        @php 
        $kpi = $kpi ?? []; 
        $q   = $q ?? request('q','');
        @endphp
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
              <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tên phòng chiếu">
              <button class="btn btn-soft">Tìm</button>
            </form>

            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Thêm phòng chiếu</button>
            <a href="{{ route('admin.form') }}" class="btn btn-soft">Trở về trang tổng quan</a>
          </div>
        </div>

        <div class="card-like mt-3">
          <div class="table-responsive">
            <table class="table table-movietheater align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tên phòng</th>
                        <th class="text-end">Số ghế</th>
                        <th>Trạng thái</th>
                        <th>Mô tả</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($theaterMini as $t)
                    <tr>
                        <td data-label="Tên phòng" class="fw-semibold">
                            {{ $t->roomName }}
                        </td>

                        <td data-label="Số ghế" class="text-end">
                            {{ $t->capacity }}
                        </td>

                        <td data-label="Trạng thái">
                            @if($t->status === 'active')
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Không hoạt động</span>
                            @endif
                        </td>

                        <td data-label="Mô tả">
                            {{ Str::limit($t->description, 35) }}
                        </td>

                        <td class="text-end" data-label="Thao tác">
                            <div class="table-actions">
                                <button class="btn btn-sm btn-soft"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $t->theaterID }}">
                                    Sửa
                                </button>

                                <form action="{{ route('admin.movietheaterManagement.delete', $t) }}"
                                      method="POST"
                                      onsubmit="return confirm('Xoá phòng chiếu này?')"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Xoá
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($theaters->hasPages())
              <div class="mt-3 d-flex justify-content-center">
                {{ $theaters->links('vendor.pagination.bootstrap-5') }}
              </div>
            @endif
          </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold">Tạo phòng chiếu mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>

              <form method="POST" action="{{ route('admin.movietheaterManagement.store') }}">
                @csrf

                <div class="modal-body">
                  <div class="row g-4">

                    <!-- ================= Left form ================= -->
                    <div class="col-md-4">
                      
                      <div class="mb-3">
                        <label class="form-label">Tên phòng chiếu</label>
                        <input type="text" name="roomName" class="form-control" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                          <option value="active">Đang hoạt động</option>
                          <option value="inactive">Không hoạt động</option>
                        </select>
                      </div>

                      <div class="row">
                        <div class="col-6 mb-3">
                          <label class="form-label">Số hàng ghế</label>
                          <input type="number" name="rows" id="rowCount" class="form-control"
                                min="1" max="26" value="4" required>
                        </div>
                        <div class="col-6 mb-3">
                          <label class="form-label">Ghế mỗi hàng</label>
                          <input type="number" name="cols" id="seatPerRow" class="form-control"
                                min="1" max="50" value="10" required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Giá ghế thường</label>
                        <input type="number" name="normal_price" class="form-control" required value="50000">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Giá ghế VIP (hàng A)</label>
                        <input type="number" name="vip_price" class="form-control" required value="70000">
                      </div>
                    </div>

                    <!-- ================= Right preview ================= -->
                    <div class="col-md-8">
                      <div class="text-center mb-3">
                        <div class="screen">MÀN HÌNH</div>
                      </div>

                      <div id="seatPreview" class="text-center"></div>
                    </div>

                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Huỷ</button>
                  <button type="submit" class="btn btn-primary">Tạo phòng chiếu</button>
                </div>

              </form>

            </div>
          </div>
        </div>

        @foreach ($theaters as $t)
        <div class="modal fade" id="edit{{ $t->theaterID }}" tabindex="-1" aria-labelledby="editLabel{{ $t->theaterID }}" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold">Chỉnh sửa phòng chiếu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>

              <form method="POST" action="{{ route('admin.movietheaterManagement.update', $t->theaterID) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">

                  <div class="row g-4">
                    <!-- ================= Left form ================= -->
                    <div class="col-md-4">

                      <div class="mb-3">
                        <label class="form-label">Tên phòng chiếu</label>
                        <input type="text" name="roomName" class="form-control"
                              required value="{{ $t->roomName }}">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select" required>
                          <option value="active"  {{ $t->status === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                          <option value="inactive" {{ $t->status === 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                      </div>

                      @php
                        // Lấy số hàng = số ký tự alphabet khác nhau
                        $rowsOld = $t->seats->groupBy('verticalRow')->count();
                        // Lấy số ghế mỗi hàng (giả sử hàng A)
                        $colsOld = $t->seats->where('verticalRow', 'A')->count();
                        // Lấy giá ghế
                        $normalPrice = $t->seats->where('seatType','normal')->first()->price ?? 50000;
                        $vipPrice    = $t->seats->where('seatType','vip')->first()->price ?? 70000;
                      @endphp

                      <div class="row">
                        <div class="col-6 mb-3">
                          <label class="form-label">Số hàng ghế</label>
                          <input type="number" name="rows"
                                class="form-control rowInput"
                                min="1" max="26"
                                value="{{ $rowsOld }}"
                                data-theater="{{ $t->theaterID }}" required>
                        </div>

                        <div class="col-6 mb-3">
                          <label class="form-label">Ghế mỗi hàng</label>
                          <input type="number" name="cols"
                                class="form-control colInput"
                                min="1" max="50"
                                value="{{ $colsOld }}"
                                data-theater="{{ $t->theaterID }}" required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Giá ghế thường</label>
                        <input type="number" name="normal_price" class="form-control"
                              required value="{{ $normalPrice }}">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Giá ghế VIP (hàng A)</label>
                        <input type="number" name="vip_price" class="form-control"
                              required value="{{ $vipPrice }}">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="2">{{ $t->note }}</textarea>
                      </div>

                    </div>

                    <!-- ================= Right preview ================= -->
                    <div class="col-md-8">
                      <div class="text-center mb-3">
                        <div class="screen">MÀN HÌNH</div>
                      </div>

                      <div id="seatPreviewEdit{{ $t->theaterID }}" class="text-center"></div>
                    </div>

                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Huỷ</button>
                  <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
              </form>

            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div> 
  </main>
</div>
@endsection

@push('scripts')
<script>
function admRenderSeats() {
    const container = document.getElementById('seatPreview');
    const rowInput   = document.getElementById('rowCount');
    const colInput   = document.getElementById('seatPerRow');
    if (!container || !rowInput || !colInput) return;

    const rows = parseInt(rowInput.value || 0, 10);
    const cols = parseInt(colInput.value || 0, 10);
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

    container.innerHTML = '';
    for (let r = 0; r < rows; r++) {
        const rowDiv = document.createElement('div');
        rowDiv.classList.add('seat-row');
        for (let c = 1; c <= cols; c++) {
            const seat = document.createElement('button');
            seat.type = 'button';
            seat.classList.add('seat');
            if (r === 0) seat.classList.add('vip');   // hàng A = VIP
            seat.innerText = letters[r] + c;
            rowDiv.appendChild(seat);
        }
        container.appendChild(rowDiv);
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const createModal = document.getElementById('modalCreate');
    if (!createModal) return;
    createModal.addEventListener('shown.bs.modal', function () {
        admRenderSeats();
    });
    const rowInput = document.getElementById('rowCount');
    const colInput = document.getElementById('seatPerRow');
    if (rowInput) rowInput.addEventListener('input', admRenderSeats);
    if (colInput) colInput.addEventListener('input', admRenderSeats);
});


function admRenderSeatsEdit(theaterID) {
    const container = document.getElementById(`seatPreviewEdit${theaterID}`);
    const rowInput = document.querySelector(`#edit${theaterID} .rowInput`);
    const colInput = document.querySelector(`#edit${theaterID} .colInput`);

    if (!container || !rowInput || !colInput) return;

    const rows = parseInt(rowInput.value || 0, 10);
    const cols = parseInt(colInput.value || 0, 10);
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

    container.innerHTML = '';
    for (let r = 0; r < rows; r++) {
        const rowDiv = document.createElement('div');
        rowDiv.classList.add('seat-row');

        for (let c = 1; c <= cols; c++) {
            const seat = document.createElement('button');
            seat.type = 'button';
            seat.classList.add('seat');

            if (r === 0) seat.classList.add('vip'); // A = VIP

            seat.innerText = letters[r] + c;
            rowDiv.appendChild(seat);
        }

        container.appendChild(rowDiv);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    @foreach($theaters as $t)
        const modalEdit{{ $t->theaterID }} = document.getElementById("edit{{ $t->theaterID }}");
        modalEdit{{ $t->theaterID }}.addEventListener('shown.bs.modal', function () {
            admRenderSeatsEdit({{ $t->theaterID }});
        });
        modalEdit{{ $t->theaterID }}.querySelector('.rowInput')
            .addEventListener('input', function () {
                admRenderSeatsEdit({{ $t->theaterID }});
            });
        modalEdit{{ $t->theaterID }}.querySelector('.colInput')
            .addEventListener('input', function () {
                admRenderSeatsEdit({{ $t->theaterID }});
            });
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
.adm-movietheater .card-like,
.adm-movietheater .ad-card {
  background: #fff;
  border: 1px solid #eaecf0;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(16,24,40,.06);
  overflow: hidden;
  padding: 0;
}
.adm-movietheater .toolbar-wrap {
  background:#fff;
  border:1px solid #eaecf0;
  border-radius:12px;
  padding:10px;
  box-shadow:0 10px 30px rgba(16,24,40,.06);
  margin-bottom: 16px;
}
.adm-movietheater .toolbar {
  display:flex;
  align-items:center;
  gap:12px;
  margin:16px 0 12px;
  flex-wrap: nowrap;
}
.adm-movietheater .toolbar .search {
  flex:1 1 320px;
  max-width:560px;
}
.adm-movietheater .toolbar .search .form-control {
  height:38px;
  border-radius:.375rem;
  padding:.375rem .75rem;
  border:1px solid #dee2e6;
  box-shadow:none;
}
.adm-movietheater .toolbar .search .form-control:focus {
  outline:0;
  border-color:#b8bdfd;
  box-shadow:0 0 0 .25rem rgba(69,74,242,.12);
}
.btn-soft {
  background:#f9fafb;
  border:1px solid #eaecf0;
  color:#101828;
}
.btn-soft:hover { background:#fff; }
.btn-brand {
  background:#454af2;
  border-color:#454af2;
  color:#fff;
}
.btn-brand:hover {
  filter:brightness(.95);
}
.adm-movietheater .table-movietheater thead th {
  background: #f6f7fb;
  color: #667085;
  font-weight: 600;
  font-size: 0.85rem;
  border-bottom: 1px solid #eaecf0 !important;
  white-space: nowrap;
  padding: 14px 12px;
}
.adm-movietheater .table-movietheater tbody td {
  vertical-align: middle;
  border-color: #eaecf0;
  color: #101828;
  font-size: .9rem;
  padding: 14px 12px;
}
.adm-movietheater .table-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
}
.adm-movietheater .badge {
  padding: 6px 10px;
  border-radius: 6px;
  font-size: .75rem;
  font-weight: 600;
}
.adm-movietheater .badge.bg-success {
  background: #16a34a !important;
}
.adm-movietheater .badge.bg-secondary {
  background: #9ca3af !important;
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
@media (max-width: 992px){
    .adm-movietheater .table-movietheater thead {
      display: none;
    }
    .adm-movietheater .table-movietheater tbody tr {
      display: block;
      border-bottom: 1px solid #eaecf0;
      padding: 12px 10px;
    }
    .adm-movietheater .table-movietheater tbody td {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 8px 0;
      border: 0;
    }
    .adm-movietheater .table-movietheater tbody td::before {
      content: attr(data-label);
      color: #667085;
      font-weight: 600;
    }
    .adm-movietheater .toolbar {
      flex-wrap: wrap;
    }
}
.adm-movietheater .screen {
  border: 2px solid #444;
  display: inline-block;
  border-radius: 10px;
  padding: 12px 50px;
  margin-bottom: 24px;
  font-weight: 600;
  background: #f7f7f7;
  letter-spacing: 1px;
  box-shadow: 0 3px 8px rgba(0,0,0,.05);
}
.adm-movietheater .seat-row {
  margin-bottom: 6px;
}
.adm-movietheater .seat {
  width: 40px;
  weight: 40px;
  margin: 4px;
  border-radius: 8px;
  border: 1px solid #c8c8c8;
  background: #e5e7eb;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: default;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #111;
  box-shadow: 0 2px 4px rgba(0,0,0,.05);
}
.adm-movietheater .seat.vip {
  background: #ffd64d;
  border-color: #e4b100;
}

.adm-movietheater .seat.normal {
  background: #d9d9d9;
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
