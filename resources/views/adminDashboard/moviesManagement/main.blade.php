@extends('layouts.app')
@section('title', 'Quản lý phim')

@section('content')

  @php
    $genres = [
      'Action'      => 'Hành động',
      'Adventure'   => 'Phiêu lưu',
      'Animation'   => 'Hoạt hình',
      'Comedy'      => 'Hài',
      'Crime'       => 'Tội phạm',
      'Documentary' => 'Tài liệu',
      'Drama'       => 'Chính kịch',
      'Fantasy'     => 'Giả tưởng',
      'Horror'      => 'Kinh dị',
      'Mystery'     => 'Bí ẩn',
      'Romance'     => 'Lãng mạn',
      'Sci-Fi'      => 'Khoa học viễn tưởng',
      'Thriller'    => 'Giật gân',
      'War'         => 'Chiến tranh',
      'Western'     => 'Viễn tây',
    ];

    $ratings = ['P', 'K', 'T13', 'T16', 'T18'];
  @endphp

  <div class="ad-wrapper d-flex container-fluid">
    <aside class="ad-sidebar">
      <nav class="ad-menu">
        <h6>TỔNG QUAN</h6>
        <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}" href="{{ route('admin.form')}}">Bảng điều khiển</a>

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
    @endphp

    <main class="ad-main flex-grow-1">
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
            <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-outline-primary">Xem báo cáo</a>
            <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Làm mới</a>
          </div>
        </div>
      </div>

      <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
        <h3 class="m-0">Tổng quan</h3>
      </div>

      <div class="adm-movies">
        @php
          $kpi = $kpi ?? [];
          $q = $q ?? request('q', '');
          $type = $type ?? request('type', 'all'); 
          $today = now()->toDateString();
        @endphp

        <div class="row g-3 mb-4">
          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--blue p-3 rounded">
              <div class="text-muted">Phim đang hoạt động</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['movies_active'] ?? 0)) }}</div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Tổng phim đang có</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['movies_total'] ?? 0)) }}</div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <div class="kpi-card kpi--green p-3 rounded">
              <div class="text-muted">Tổng phim sắp chiếu</div>
              <div class="fs-4 fw-bold">{{ number_format((int) ($kpi['movies_coming_soon'] ?? 0)) }}</div>
            </div>
          </div>
        </div>

        <div class="toolbar-wrap">
          <div class="toolbar">
            <form method="GET" class="search d-flex gap-2">
              <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tên, thể loại, rating...">
              <button class="btn btn-soft">Tìm</button>
            </form>
            <a href="{{ route('moviesManage.template_csv') }}" class="btn btn-soft">CSV mẫu</a>
            <a href="{{ route('moviesManage.export_csv', ['q' => $q]) }}" class="btn btn-success">Xuất CSV</a>

            <form action="{{ route('moviesManage.export_csv') }}" method="POST" enctype="multipart/form-data"
              class="csv-input">
              @csrf
              <button type="button" class="btn btn-soft fake-btn">Nhập CSV</button>
              <input type="file" name="file" accept=".csv" onchange="this.form.submit()">
            </form>

            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Thêm phim</button>
            <div class="dropdown">
              <button class="btn btn-soft dropdown-toggle filter-dropdown-btn"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-expanded="false">
                  {{-- Hiển thị trạng thái đang chọn --}}
                  @switch($type)
                      @case('now_showing')
                          Đang chiếu
                          @break
                      @case('coming_soon')
                          Sắp chiếu
                          @break
                      @case('hidden')
                          Đã ẩn
                          @break
                      @default
                          Tất cả
                  @endswitch
              </button>

              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item {{ $type === 'all' ? 'active' : '' }}"
                    href="{{ route('admin.moviesManagement_main.form', ['type' => 'all', 'q' => $q]) }}">
                    Tất cả
                  </a>
                </li>

                <li>
                  <a class="dropdown-item {{ $type === 'now_showing' ? 'active' : '' }}"
                    href="{{ route('admin.moviesManagement_main.form', ['type' => 'now_showing', 'q' => $q]) }}">
                    Đang chiếu
                  </a>
                </li>

                <li>
                  <a class="dropdown-item {{ $type === 'coming_soon' ? 'active' : '' }}"
                    href="{{ route('admin.moviesManagement_main.form', ['type' => 'coming_soon', 'q' => $q]) }}">
                    Sắp chiếu
                  </a>
                </li>

                <li>
                  <a class="dropdown-item {{ $type === 'hidden' ? 'active' : '' }}"
                    href="{{ route('admin.moviesManagement_main.form', ['type' => 'hidden', 'q' => $q]) }}">
                    Đã ẩn
                  </a>
                </li>
              </ul>
            </div>
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
                      @if ($m->poster)
                        <img src="{{ asset($m->poster) }}" class="poster-thumb" alt="poster">
                      @endif
                    </td>
                    <td class="text-muted" data-label="ID">{{ $m->movieID }}</td>
                    <td class="fw-semibold" data-label="Tiêu đề">
                      {{ $m->title }}

                      @if (!empty($m->is_banner) && $m->is_banner)
                        <span class="badge bg-warning ms-2">Banner</span>
                      @endif

                      @if ($m->releaseDate && $m->releaseDate > $today && $m->status === 'active')
                        <span class="badge bg-info ms-2">Sắp chiếu</span>
                      @endif
                    </td>
                    <td data-label="Thời lượng">
                      <span class="badge badge-soft">{{ $m->durationMin }}p</span>
                    </td>
                    <td data-label="Thể loại">{{ $m->genre }}</td>
                    <td data-label="Rating">{{ $m->rating }}</td>
                    <td data-label="Ngày phát hành">{{ $m->releaseDate }}</td>
                    <td data-label="Trạng thái">
                      @if ($m->status === 'unable')
                        <span class="badge bg-secondary">Ẩn</span>
                      @else
                        @if ($m->releaseDate && $m->releaseDate > $today)
                          <span class="badge bg-info">Sắp chiếu</span>
                        @else
                          <span class="badge bg-success">Đang chiếu</span>
                        @endif
                      @endif
                    </td>
                    <td class="text-end" data-label="Thao tác">
                      <div class="table-actions">
                        <button class="btn btn-sm btn-soft" data-bs-toggle="modal"
                          data-bs-target="#edit{{ $m->movieID }}">Sửa</button>

                        @if (empty($m->is_banner) || !$m->is_banner)
                          <form action="{{ route('moviesManage.banner_set', $m) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-warning" title="Đặt làm banner">Đặt làm banner</button>
                          </form>
                        @else
                          <form action="{{ route('moviesManage.banner_unset', $m) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bỏ banner cho phim này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-warning" title="Bỏ banner">Bỏ banner</button>
                          </form>
                        @endif

                        <form action="{{ route('moviesManage.delete', $m) }}" method="POST"
                          onsubmit="return confirm('Xoá phim này?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger">Xoá</button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  {{-- Modal edit cho từng phim --}}
                  <div class="modal fade" id="edit{{ $m->movieID }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <form class="modal-content" method="POST" action="{{ route('moviesManage.update', $m) }}">
                          @csrf
                          @method('PUT')
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
                              <input type="number" name="durationMin" value="{{ $m->durationMin }}" min="0"
                                class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Thể loại</label>

                                <div class="tag-box" id="genreTags{{ $m->movieID }}"></div>

                                <button type="button" class="btn btn-soft mt-2" id="genreAddBtn{{ $m->movieID }}">+ Thêm</button>

                                <div class="dropdown-genre d-none" id="genreDropdown{{ $m->movieID }}">
                                    @foreach ($genres as $value => $label)
                                        <div class="genre-item" data-value="{{ $value }}">{{ $label }}</div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="genre" id="genre{{ $m->movieID }}" value="{{ $m->genre }}">
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Rating</label>
                              <select name="rating" class="form-select">
                                <option value="">-- Chọn rating --</option>
                                @foreach ($ratings as $r)
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
                                <input name="poster" id="poster{{ $m->movieID }}" value="{{ $m->poster }}"
                                  class="form-control"
                                  placeholder="https://...jpg hoặc storage/pictures/xxx.jpg">
                                <button type="button" class="btn btn-outline-secondary"
                                  onclick="document.getElementById('posterFile{{ $m->movieID }}').click()">
                                  Chọn ảnh
                                </button>
                                <input type="file" id="posterFile{{ $m->movieID }}" class="d-none" accept="image/*">
                              </div>
                              <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh”.</small>
                              <div class="mt-2">
                                <img id="posterPreview{{ $m->movieID }}"
                                  src="{{ $m->poster ? asset($m->poster) : '' }}"
                                  style="max-height:120px; {{ $m->poster ? '' : 'display:none' }}"
                                  class="rounded border">
                              </div>
                            </div>

                            <div class="col-md-12">
                              <label class="form-label">Background (1920x1080)</label>
                              <div class="input-group">
                                <input name="background" id="background{{ $m->movieID }}"
                                  value="{{ $m->background }}"
                                  class="form-control"
                                  placeholder="https://...jpg hoặc storage/pictures/xxx-bg.jpg">
                                <button type="button" class="btn btn-outline-secondary"
                                  onclick="document.getElementById('backgroundFile{{ $m->movieID }}').click()">
                                  Chọn ảnh
                                </button>
                                <input type="file" id="backgroundFile{{ $m->movieID }}" class="d-none" accept="image/*">
                              </div>
                              <small class="text-muted">Ảnh ngang, nên dùng 1920x1080 để làm background / banner.</small>
                              <div class="mt-2">
                                <img id="backgroundPreview{{ $m->movieID }}"
                                  src="{{ $m->background ? asset($m->background) : '' }}"
                                  style="max-height:120px; {{ $m->background ? '' : 'display:none' }}"
                                  class="rounded border">
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
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">Chưa có phim.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="mt-3">
          {{ $movies->links('vendor.pagination.bootstrap-5') }}
        </div>
      </div>
    </main>
  </div>

  {{-- Modal tạo phim --}}
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

          <div class="col-md-6">
              <label class="form-label">Thể loại</label>

              <div class="tag-box" id="genreTagsCreate"></div>

              <button type="button" class="btn btn-soft mt-2" id="genreAddBtnCreate">+ Thêm</button>

              <div class="dropdown-genre d-none" id="genreDropdownCreate">
                  @foreach ($genres as $value => $label)
                      <div class="genre-item" data-value="{{ $value }}">{{ $label }}</div>
                  @endforeach
              </div>

              <input type="hidden" name="genre" id="genreCreate">
          </div>

          <div class="col-md-4">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-select">
              <option value="">-- Chọn rating --</option>
              @foreach ($ratings as $r)
                <option value="{{ $r }}">{{ $r }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Ngày phát hành</label>
            <input type="date" name="releaseDate" class="form-control">
          </div>

          <label class="form-label">Poster (1080x1920)</label>
          <div class="input-group">
            <input name="poster" id="posterCreate" class="form-control"
              placeholder="https://...jpg hoặc storage/pictures/xxx.jpg">
            <button type="button" class="btn btn-outline-secondary"
              onclick="document.getElementById('posterFileCreate').click()">Chọn ảnh</button>
            <input type="file" id="posterFileCreate" class="d-none" accept="image/*">
          </div>
          <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh”.</small>
          <div class="mt-2">
            <img id="posterPreviewCreate" style="max-height:120px; display:none" class="rounded border">
          </div>

          <label class="form-label mt-3">Background (1920x1080)</label>
          <div class="input-group">
            <input name="background" id="backgroundCreate" class="form-control"
              placeholder="Background 1920x1080, https://...jpg hoặc storage/pictures/xxx-bg.jpg">
            <button type="button" class="btn btn-outline-secondary"
              onclick="document.getElementById('backgroundFileCreate').click()">Chọn ảnh</button>
            <input type="file" id="backgroundFileCreate" class="d-none" accept="image/*">
          </div>
          <small class="text-muted">Ảnh ngang, nên dùng 1920x1080 để làm background / banner.</small>
          <div class="mt-2">
            <img id="backgroundPreviewCreate" style="max-height:120px; display:none" class="rounded border">
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

          <div class="modal-footer">
            <button class="btn btn-soft" data-bs-dismiss="modal" type="button">Đóng</button>
            <button class="btn btn-brand">Lưu</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const GENRE_LABELS = @json($genres);

    function initGenreTagSystem(suffix) {
        const tagBox      = document.getElementById('genreTags' + suffix);
        const addBtn      = document.getElementById('genreAddBtn' + suffix);
        const dropdown    = document.getElementById('genreDropdown' + suffix);
        const hiddenInput = document.getElementById('genre' + suffix);

        if (!tagBox || !addBtn || !dropdown || !hiddenInput) return;

        function syncHidden() {
            const values = Array.from(tagBox.querySelectorAll('.tag'))
                .map(t => t.dataset.value);
            hiddenInput.value = values.join(', ');
        }

        function addTag(value) {
            if (!value) return;
            if (tagBox.querySelector('[data-value="' + value + '"]')) return;

            const span = document.createElement('span');
            span.className = 'tag';
            span.dataset.value = value;

            const label = GENRE_LABELS[value] || value;
            span.innerHTML = `
                ${label}
                <span class="remove-tag">&times;</span>
            `;

            tagBox.appendChild(span);
        }

        // click vào dấu × để xoá tag
        tagBox.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-tag')) {
                const tagEl = e.target.closest('.tag');
                if (tagEl) {
                    tagEl.remove();
                    syncHidden();
                }
            }
        });

        // mở / đóng dropdown
        addBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('d-none');
        });

        // chọn 1 thể loại
        dropdown.addEventListener('click', (e) => {
            const item = e.target.closest('.genre-item');
            if (!item) return;

            const value = item.dataset.value;
            addTag(value);
            syncHidden();
            dropdown.classList.add('d-none');
        });

        // click ra ngoài thì đóng dropdown
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && e.target !== addBtn) {
                dropdown.classList.add('d-none');
            }
        });

        // ===== Khởi tạo từ giá trị có sẵn trong hiddenInput (khi sửa) =====
        const raw = (hiddenInput.value || '').trim();
        if (raw !== '') {
            const rawTags = raw.split(',')
                .map(s => s.trim())
                .filter(Boolean);

            rawTags.forEach((item) => {
                let value = item;

                // nếu value đã là key chuẩn (Action, Adventure,...) thì chơi luôn
                if (!GENRE_LABELS[value]) {
                    // còn nếu DB đang lưu nhãn tiếng Việt (Hành động, Phiêu lưu,...) thì map ngược lại
                    let mapped = null;
                    for (const [k, label] of Object.entries(GENRE_LABELS)) {
                        if (label === item) {
                            mapped = k;
                            break;
                        }
                    }
                    value = mapped || item;
                }

                addTag(value);
            });

            syncHidden(); // chuẩn hoá lại hidden để từ giờ toàn key chuẩn
        }
    }

    // Form TẠO mới
    initGenreTagSystem('Create');

    // Các modal SỬA – mỗi phim 1 suffix movieID
    @foreach ($movies as $m)
        initGenreTagSystem('{{ $m->movieID }}');
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
    .adm-movies .card-like {
      background: #fff;
      border: 1px solid #eaecf0;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
      overflow: hidden;
    }
    .adm-movies .toolbar {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 16px 0 12px;
      flex-wrap: nowrap;
    }
    .adm-movies .toolbar .search {
      flex: 1 1 320px;
      max-width: 560px;
    }
    .dropdown-menu {
        border-radius: 10px;
        padding: 6px 0;
    }
    .dropdown-item.active {
        background: #e5edff !important;
        color: #2d4eff !important;
        font-weight: 600;
    }
    .filter-dropdown-btn {
      min-width: 130px;   
      text-align: left;   
      display: inline-flex;
      justify-content: space-between;
      align-items: center;
    }
    .filter-dropdown-btn::after {
      margin-left: 8px;
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
    .adm-movies .toolbar .btn,
    .adm-movies .csv-input .btn {
      white-space: nowrap;
      flex-shrink: 0;
      line-height: 1.2;
      padding-left: 12px;
      padding-right: 12px;
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
      box-shadow: 0 10px 30px rgba(16, 24, 40, .06);
    }
    .adm-movies .toolbar .search .form-control {
      height: 38px;
      border-radius: .375rem;
      padding: .375rem .75rem;
      border: 1px solid #dee2e6;
      box-shadow: none;
    }
    .adm-movies .toolbar .search .form-control:focus {
      outline: 0;
      border-color: #b8bdfd;
      box-shadow: 0 0 0 .25rem rgba(69, 74, 242, .12);
    }
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
      .adm-movies .toolbar {
        flex-wrap: nowrap;
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
    .kpi--blue {
      border-color: #e4ebff;
    }
    .kpi--green {
      border-color: #dcfce7;
    }
    .tag-box {
    border: 1px solid #ddd;
    min-height: 40px;
    padding: 6px;
    border-radius: 8px;
    background: #fff;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    }
    .tag {
        background: #e7efff;
        color: #3554f4;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .tag .remove-tag {
        cursor: pointer;
        font-weight: bold;
    }
    .dropdown-genre {
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 6px;
        background: #fff;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        width: 240px;
        z-index: 99;
    }
    .genre-item {
        padding: 8px 10px;
        cursor: pointer;
    }
    .genre-item:hover {
        background: #f4f6ff;
    }
  </style>
@endpush
