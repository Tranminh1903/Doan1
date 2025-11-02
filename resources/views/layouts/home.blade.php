@extends('layouts.app')
@section('title', 'Trang chủ - DuManMinh Cinema')

@section('content')
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mt-1">Phim sắp chiếu</h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>
</section>

@php
  use Illuminate\Support\Str;

  // Promo fallback (hiển thị khi không có banner phim)
  $promoBanners = [
    ['img' => asset('storage/pictures/mai.jpg'),             'url' => url('/promo/member-day'), 'title' => 'Member Day',  'desc' => 'X2 điểm thưởng'],
    ['img' => asset('storage/pictures/muado.jpg'),           'url' => url('/promo/combo'),      'title' => 'Combo Bắp Nước','desc' => 'Chỉ từ 49K'],
    ['img' => asset('storage/pictures/tuchientrenkhong.jpg'), 'url' => url('/promo/early-bird'), 'title' => 'Early Bird',  'desc' => 'Đặt sớm -20%'],
  ];

  // Chuẩn hoá URL ảnh: nếu đã là http|/storage thì giữ nguyên, ngược lại bọc asset()
  $normalizeImg = fn ($path) =>
      $path && Str::startsWith($path, ['http', '/storage']) ? $path : ($path ? asset($path) : null);

  // Gom tất cả phim đang là banner
  $movieBanners = collect($bannerMovies ?? [])
    ->filter(fn ($m) => !empty($m->poster))
    ->map(fn ($m) => [
      'img'   => $normalizeImg($m->poster),
      'url'   => route('movies.show', ['movieID' => $m->movieID]),
      'title' => $m->title,
      'desc'  => Str::limit((string) $m->description, 100),
    ]);

  // Fallback 1 phim nếu middleware chỉ share $bannerMovie
  if ($movieBanners->isEmpty() && !empty($bannerMovie) && !empty($bannerMovie->poster)) {
    $movieBanners = collect([[
      'img'   => $normalizeImg($bannerMovie->poster),
      'url'   => route('movies.show', ['movieID' => $bannerMovie->movieID]),
      'title' => $bannerMovie->title,
      'desc'  => Str::limit((string) $bannerMovie->description, 100),
    ]]);
  }

  // Chọn nguồn banner: phim > promo
  $banners = $movieBanners->isNotEmpty() ? $movieBanners->values()->all() : $promoBanners;
@endphp

@if (!empty($banners))
<div id="bannerCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel" data-bs-interval="3200">
  <div class="carousel-inner banner-wrapper rounded shadow-sm">
    @foreach ($banners as $i => $b)
      <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
        <a href="{{ $b['url'] }}" class="d-block position-relative" aria-label="{{ $b['title'] ?? 'Banner '.($i+1) }}">
          <img class="w-100 banner-img" src="{{ $b['img'] }}" alt="{{ $b['title'] ?? 'Banner '.($i+1) }}" loading="lazy">
          <span class="banner-overlay"></span>
            <div class="banner-caption">
              @isset($b['title']) <h5 class="mb-1">{{ $b['title'] }}</h5> @endisset
              @isset($b['desc'])  <p class="mb-0">{{ $b['desc'] }}</p>   @endisset
            </div>
        </a>
      </div>
    @endforeach
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev" aria-label="Slide trước">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next" aria-label="Slide sau">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>

  <div class="carousel-indicators">
    @foreach ($banners as $i => $_)
      <button
        type="button"
        data-bs-target="#bannerCarousel"
        data-bs-slide-to="{{ $i }}"
        class="{{ $i === 0 ? 'active' : '' }}"
        @if($i===0) aria-current="true" @endif
        aria-label="Chuyển đến banner {{ $i + 1 }}">
      </button>
    @endforeach
  </div>
</div>
@endif

<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mb-1">Phim đang chiếu</h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>

  <div id="movieListContainer">
    @include('layouts.movie_list', ['movies' => $movies])
  </div>
</section>


<section class="container mt-4">
  <h4 class="mt-2 mb-3">Ưu đãi nổi bật</h4>
  <div class="row g-3">
    <div class="col-md-4"><div class="p-3 border rounded-3">🎟️ Giảm 20% khi đặt trước 24h</div></div>
    <div class="col-md-4"><div class="p-3 border rounded-3">🍿 Combo bắp nước chỉ 49K</div></div>
    <div class="col-md-4"><div class="p-3 border rounded-3">🎁 Tích điểm đổi vé miễn phí</div></div>
  </div>
</section>
@endsection


@push('styles')
<style>
  /* =========================================================
   HOME PAGE / MOVIE LISTING
   ========================================================= */
  .ns-wrap {
      max-width: 1200px;
      margin: 48px auto;
  }
  .ns-row {
      row-gap: 24px;
  }

  .movie-card {
      border-radius: var(--card-radius);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
  }
  .movie-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.12);
  }
  .movie-card img {
      aspect-ratio: auto;
  }

  .poster-wrap {
      border-top-left-radius: var(--card-radius);
      border-top-right-radius: var(--card-radius);
      overflow: hidden;
  }
  .poster-img {
      transition: transform 0.35s ease;
  }
  .movie-card:hover .poster-img {
      transform: scale(1.04);
  }
  .actions-float {
      position: absolute;
      left: 12px;
      right: 12px;
      bottom: 12px;
      display: flex;
      gap: 10px;
      opacity: 0;
      transform: translateY(6px);
      transition: all 0.18s ease;
  }
  .movie-card:hover .actions-float {
      opacity: 1;
      transform: translateY(0);
  }
  .card-quick-actions {
      transition: opacity 0.25s ease;
  }
  .movie-card:hover .card-quick-actions {
      opacity: 1;
  }
</style>
@endpush