@extends('layouts.app')
@section('title', 'Quản lý tin tức')

@section('content')

  <div class="ad-wrapper d-flex container-fluid">
    {{-- SIDEBAR --}}
    <aside class="ad-sidebar">
      <nav class="ad-menu">
        <h6>TỔNG QUAN</h6>
        <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
           href="{{ route('admin.form')}}">Bảng điều khiển</a>

        <h6>NGƯỜI DÙNG</h6>
        <a class="ad-link {{ request()->routeIs('admin.userManagement_main.form') ? 'active' : '' }}"
           href="{{ route('admin.userManagement_main.form') }}">Quản lý người dùng</a>

        <h6>KHUYẾN MÃI</h6>
        <a class="ad-link {{ request()->routeIs('admin.promotionManagement.form') ? 'active' : '' }}"
           href="{{ route('admin.promotionManagement.form') }}">Quản lý khuyến mãi</a>

        <h6>PHIM</h6>
        <a class="ad-link {{ request()->routeIs('admin.moviesManagement_main.form') ? 'active' : '' }}"
           href="{{ route('admin.moviesManagement_main.form') }}">Quản lý phim</a>

        <h6>PHÒNG CHIẾU</h6>
        <a class="ad-link {{ request()->routeIs('admin.movietheaterManagement.form') ? 'active' : '' }}"
           href="{{ route('admin.movietheaterManagement.form') }}">Quản lý phòng chiếu</a>

        <h6>SUẤT CHIẾU</h6>
        <a class="ad-link {{ request()->routeIs('admin.showtimeManagement.form') ? 'active' : '' }}"
           href="{{ route('admin.showtimeManagement.form') }}">Quản lý suất chiếu</a>

        <h6>BÁO CÁO</h6>
        <a class="ad-link {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}"
           href="{{ route('admin.reports.revenue') }}">Doanh thu</a>

        <h6>TIN TỨC</h6>
        <a class="ad-link {{ request()->routeIs('admin.newsManagement.form') ? 'active' : '' }}"
           href="{{ route('admin.newsManagement.form') }}">Quản lý tin tức</a>
      </nav>
    </aside>

    @php
      use Illuminate\Support\Facades\Auth;
      use Illuminate\Support\Str;

      $now = now();
      $hour = (int) $now->format('G');
      $user = Auth::user();

      $greeting = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
      $weekdayMap = [
          'Mon' => 'Thứ hai',
          'Tue' => 'Thứ ba',
          'Wed' => 'Thứ tư',
          'Thu' => 'Thứ năm',
          'Fri' => 'Thứ sáu',
          'Sat' => 'Thứ bảy',
          'Sun' => 'Chủ nhật',
      ];
      $weekdayVN = $weekdayMap[$now->format('D')] ?? '';
      $dateVN = $now->format('d/m/Y');

      $kpi = $kpi ?? [];
      $q   = $q ?? request('q', '');
    @endphp

    <main class="ad-main flex-grow-1">
      {{-- GREETING --}}
      <div class="ad-greeting card shadow-sm border-0 mb-4 w-100">
        <div class="card-body d-flex align-items-center gap-3 flex-wrap">
          <img src="{{ $user?->avatar ? asset('storage/' . $user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
               class="rounded-circle me-3"
               style="width:72px;height:72px;object-fit:cover;flex:0 0 72px;"
               alt="avatar">

          <div class="me-auto min-w-0">
            <h5 class="mb-1 text-truncate">
              {{ $greeting }}, <span class="text-primary">{{ $user?->username ?? 'Admin' }}</span>
            </h5>

            <div class="text-muted small">
              {{ $weekdayVN }}, {{ $dateVN }} • Chúc bạn làm việc hiệu quả!
            </div>
          </div>

          <div class="d-flex align-items-center gap-2 ms-md-auto">
            <a href="{{ route('admin.newsManagement.form') }}" class="btn btn-sm btn-light">Làm mới</a>
          </div>
        </div>
      </div>

      <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
        <h3 class="m-0">Quản lý tin tức</h3>
      </div>

      <div class="adm-news">
        {{-- KPI --}}
        <div class="row g-3 mb-4">
          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--blue p-3 rounded">
              <div class="text-muted">Tổng số bài viết</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['news_total'] ?? 0)) }}</div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Bài viết trong 7 ngày qua</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['news_last_7_days'] ?? 0)) }}</div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Bài viết hôm nay</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['news_today'] ?? 0)) }}</div>
            </div>
          </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="toolbar-wrap">
          <div class="toolbar">
            <form method="GET" class="search d-flex gap-2">
              <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tiêu đề, nội dung...">
              <button class="btn btn-soft" type="submit">Tìm</button>
            </form>

            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreateNews">
              + Thêm tin tức
            </button>
          </div>
        </div>

        {{-- TABLE --}}
        <div class="card-like mt-3">
          <div class="table-responsive">
            <table class="table table-news align-middle mb-0">
              <thead>
                <tr>
                  <th class="poster-col">Ảnh</th>
                  <th>ID</th>
                  <th>Tiêu đề</th>
                  <th>Mô tả ngắn</th>
                  <th>Ngày tạo</th>
                  <th class="text-end">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($news as $n)
                  @php
                    $thumb = $n->image
                        ? (Str::startsWith($n->image, ['http', 'https'])
                            ? $n->image
                            : asset($n->image))
                        : null;

                    $shortDesc = Str::limit(strip_tags($n->description), 80);
                  @endphp

                  <tr>
                    <td class="poster-col" data-label="Ảnh">
                      @if ($thumb)
                        <img src="{{ $thumb }}" class="poster-thumb" alt="thumb">
                      @endif
                    </td>
                    <td class="text-muted" data-label="ID">{{ $n->id }}</td>
                    <td class="fw-semibold" data-label="Tiêu đề">
                      {{ $n->title }}
                    </td>
                    <td data-label="Mô tả ngắn">
                      <span class="text-muted small">{{ $shortDesc }}</span>
                    </td>
                    <td data-label="Ngày tạo">
                      {{ optional($n->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="text-end" data-label="Thao tác">
                      <div class="table-actions">
                        <button class="btn btn-sm btn-soft"
                                data-bs-toggle="modal"
                                data-bs-target="#editNews{{ $n->id }}">
                          Sửa
                        </button>

                        <form action="{{ route('admin.newsManage.delete', $n) }}"
                              method="POST"
                              onsubmit="return confirm('Xoá bài viết này?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger">Xoá</button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  {{-- MODAL EDIT --}}
                  <div class="modal fade" id="editNews{{ $n->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <form class="modal-content" method="POST"
                              action="{{ route('admin.newsManage.update', $n) }}">
                          @csrf
                          @method('PUT')

                          <div class="modal-header">
                            <h5 class="modal-title">Sửa tin: {{ $n->title }}</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                          </div>

                          <div class="modal-body row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Tiêu đề</label>
                              <input name="title" value="{{ $n->title }}" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                              <label class="form-label">Ảnh đại diện</label>
                              <div class="input-group">
                                <input name="image" id="newsImage{{ $n->id }}"
                                       value="{{ $n->image }}"
                                       class="form-control"
                                       placeholder="https://...jpg hoặc storage/news/xxx.jpg">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="document.getElementById('newsImageFile{{ $n->id }}').click()">
                                  Chọn ảnh
                                </button>
                                <input type="file" id="newsImageFile{{ $n->id }}" class="d-none" accept="image/*">
                              </div>
                              <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh” để upload.</small>
                              <div class="mt-2">
                                <img id="newsImagePreview{{ $n->id }}"
                                     src="{{ $thumb }}"
                                     style="max-height:120px; {{ $thumb ? '' : 'display:none' }}"
                                     class="rounded border">
                              </div>
                            </div>

                            <div class="col-12">
                              <label class="form-label">Nội dung / mô tả</label>
                              <textarea name="description" rows="6" class="form-control">{{ $n->description }}</textarea>
                            </div>
                          </div>

                          <div class="modal-footer">
                            <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
                            <button class="btn btn-brand" type="submit">Lưu</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Chưa có tin tức.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="mt-3">
          {{ $news->links('vendor.pagination.bootstrap-5') }}
        </div>
      </div>
    </main>
  </div>

  {{-- MODAL CREATE --}}
  <div class="modal fade" id="modalCreateNews" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form class="modal-content" method="POST" action="{{ route('admin.newsManage.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Thêm tin tức</h5>
          <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
        </div>

        <div class="modal-body row g-3">
          <div class="col-md-12">
            <label class="form-label">Tiêu đề</label>
            <input name="title" class="form-control" required>
          </div>

          <div class="col-md-12">
            <label class="form-label">Ảnh đại diện</label>
            <div class="input-group">
              <input name="image" id="newsImageCreate" class="form-control"
                     placeholder="https://...jpg hoặc storage/news/xxx.jpg">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="document.getElementById('newsImageFileCreate').click()">
                Chọn ảnh
              </button>
              <input type="file" id="newsImageFileCreate" class="d-none" accept="image/*">
            </div>
            <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh” để upload.</small>
            <div class="mt-2">
              <img id="newsImagePreviewCreate" style="max-height:120px; display:none" class="rounded border">
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Nội dung / mô tả</label>
            <textarea name="description" rows="6" class="form-control"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
          <button class="btn btn-brand" type="submit">Lưu</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    function attachImagePicker(textId, fileId, previewId, uploadRoute) {
        const textInput  = document.getElementById(textId);
        const fileInput  = document.getElementById(fileId);
        const previewImg = document.getElementById(previewId);

        if (!textInput || !fileInput || !previewImg) return;

        // Upload file
        fileInput.addEventListener('change', async () => {
            if (!fileInput.files || !fileInput.files[0]) return;

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            try {
                const res = await fetch(uploadRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (!res.ok) throw new Error('Upload thất bại');

                const data = await res.json();

                // data.path = "storage/news/xxx.jpg"
                textInput.value = data.path;

                previewImg.src = data.path.startsWith('http')
                    ? data.path
                    : '/' + data.path;
                previewImg.style.display = 'inline-block';
            } catch (err) {
                console.error(err);
                alert('Tải ảnh thất bại!');
            }
        });

        // Nhập URL thủ công
        textInput.addEventListener('input', () => {
            const val = textInput.value.trim();

            if (!val) {
                previewImg.style.display = 'none';
                return;
            }

            previewImg.src = val.startsWith('http') ? val : ('/' + val);
            previewImg.style.display = 'inline-block';
        });
    }

    const uploadRoute = '{{ route('admin.newsManage.upload_image') }}';

    // Create
    attachImagePicker(
        'newsImageCreate',
        'newsImageFileCreate',
        'newsImagePreviewCreate',
        uploadRoute
    );

    // Edit
    @foreach ($news as $n)
      attachImagePicker(
          'newsImage{{ $n->id }}',
          'newsImageFile{{ $n->id }}',
          'newsImagePreview{{ $n->id }}',
          uploadRoute
      );
    @endforeach
});
</script>
@endpush

@push('styles')
<style>
  .modal-backdrop.show {
    backdrop-filter: blur(4px);
    background-color: rgba(0, 0, 0, .4);
  }

  .adm-news .card-like {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
    overflow: hidden;
  }

  .adm-news .toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 16px 0 12px;
    flex-wrap: nowrap;
  }

  .adm-news .toolbar .search {
    flex: 1 1 320px;
    max-width: 560px;
  }

  .adm-news .btn-soft {
    background: #f9fafb;
    border: 1px solid #eaecf0;
    color: #101828;
  }
  .adm-news .btn-soft:hover {
    background: #fff;
  }

  .adm-news .btn-brand {
    background: #454af2;
    border-color: #454af2;
    color: #fff;
  }
  .adm-news .btn-brand:hover {
    filter: brightness(0.95);
  }

  .adm-news .toolbar-wrap {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 10px 30px rgba(16, 24, 40, .06);
  }

  .adm-news .toolbar .search .form-control {
    height: 38px;
    border-radius: .375rem;
    padding: .375rem .75rem;
    border: 1px solid #dee2e6;
    box-shadow: none;
  }
  .adm-news .toolbar .search .form-control:focus {
    outline: 0;
    border-color: #b8bdfd;
    box-shadow: 0 0 0 .25rem rgba(69, 74, 242, .12);
  }

  .adm-news .table-news {
    margin: 0;
  }

  .adm-news .table-news thead th {
    background: #f6f7fb;
    color: #667085;
    font-weight: 600;
    font-size: 0.85rem;
    border-bottom: 1px solid #eaecf0 !important;
    white-space: nowrap;
  }

  .adm-news .table-news tbody td {
    vertical-align: middle;
    border-color: #eaecf0;
    color: #101828;
  }

  .adm-news .poster-col {
    width: 72px;
  }

  .adm-news .poster-thumb {
    width: 64px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(16, 24, 40, 0.06);
  }

  .adm-news .table-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
  }

  @media (max-width: 992px) {
    .adm-news .table-news thead {
      display: none;
    }
    .adm-news .table-news tbody tr {
      display: block;
      border-bottom: 1px solid #eaecf0;
      padding: 12px 12px;
    }
    .adm-news .table-news tbody td {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 8px 0;
      border: 0;
    }
    .adm-news .table-news tbody td::before {
      content: attr(data-label);
      color: #667085;
      font-weight: 600;
    }
    .adm-news .table-actions {
      justify-content: flex-start;
    }
  }

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
  .kpi--blue  { border-color: #e4ebff; }
  .kpi--green { border-color: #dcfce7; }
  .pagination svg {
  width: 16px;
  height: 16px;
}
</style>
@endpush
