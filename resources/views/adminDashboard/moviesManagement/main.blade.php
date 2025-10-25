@extends('layouts.app')
@section('title','Quản lý phim')

@section('content')
@php
  // options cho combobox
  $genres  = ['Action','Adventure','Animation','Comedy','Crime','Documentary','Drama','Fantasy','Horror','Mystery','Romance','Sci-Fi','Thriller','War','Western'];
  $ratings = ['G','PG','PG-13','R','NC-17'];
@endphp

<div class="adm-movies container-fluid">

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

            <td data-label="Thời lượng">
              <span class="badge badge-soft">{{ $m->durationMin }}p</span>
            </td>
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
                    @csrf
                    @method('DELETE')
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

          {{-- Modal sửa --}}
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
                      <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh” để tải lên.</small>
                      <div class="mt-2">
                        <img id="posterPreview{{ $m->movieID }}" src="{{ $m->poster ? asset($m->poster) : '' }}"
                             style="max-height:120px; {{ $m->poster ? '' : 'display:none' }}" class="rounded border">
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label class="form-label">Trạng thái</label>
                      <select name="status" class="form-select" required>
                        <option value="active" @selected(($m->status ?? 'active') === 'active')>Hiển thị (active)</option>
                        <option value="unable" @selected(($m->status ?? '') === 'unable')>Ẩn khỏi trang chủ (unable)</option>
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

{{-- Modal thêm --}}
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
            <input name="poster" id="posterCreate" class="form-control"
                   placeholder="https://...jpg hoặc storage/pictures/xxx.jpg">
            <button type="button" class="btn btn-outline-secondary"
                    onclick="document.getElementById('posterFileCreate').click()">Chọn ảnh</button>
            <input type="file" id="posterFileCreate" class="d-none" accept="image/*">
          </div>
          <small class="text-muted">Có thể dán URL trực tiếp, hoặc bấm “Chọn ảnh” để tải lên.</small>
          <div class="mt-2">
            <img id="posterPreviewCreate" style="max-height:120px; display:none" class="rounded border">
          </div>
        </div>

        <div class="col-md-12">
          <label class="form-label">Trạng thái</label>
          <select name="status" class="form-select" required>
            <option value="active" selected>Hiển thị (active)</option>
            <option value="unable">Ẩn khỏi trang chủ (unable)</option>
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

@push('styles')
<style>
/* Làm mờ nền xung quanh khi mở modal */
.modal-backdrop.show {
  backdrop-filter: blur(4px);
  background-color: rgba(0,0,0,.4);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  function attachPosterPicker(textId, fileId, previewId) {
    const txt = document.getElementById(textId);
    const file = document.getElementById(fileId);
    const prev = document.getElementById(previewId);

    // chọn file -> upload -> nhận path -> điền input + preview
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
        txt.value = data.path;                    // storage/pictures/xxx.jpg
        if (prev) {
          prev.src = '/' + data.path;             // hiển thị public
          prev.style.display = 'inline-block';
        }
      } catch (e) {
        console.error('Upload poster lỗi:', e);
        alert('Tải ảnh thất bại, thử lại nhé.');
      }
    });

    // dán URL thủ công -> cập nhật preview
    txt?.addEventListener('input', () => {
      if (!prev) return;
      const v = txt.value?.trim();
      if (!v) { prev.style.display = 'none'; return; }
      prev.src = v.startsWith('http') ? v : ('/' + v);
      prev.style.display = 'inline-block';
    });
  }

  // Create
  attachPosterPicker('posterCreate', 'posterFileCreate', 'posterPreviewCreate');

  // Edit: tạo picker cho từng modal theo movieID
  @foreach ($movies as $m)
    attachPosterPicker('poster{{ $m->movieID }}', 'posterFile{{ $m->movieID }}', 'posterPreview{{ $m->movieID }}');
  @endforeach
});
</script>
@endpush
