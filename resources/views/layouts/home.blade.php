@extends('layouts.app')
@section('title', 'Trang chủ - DuManMinh Cinema')

@section('content')
@php
  use Illuminate\Support\Str;

  // Promo fallback (hiển thị khi không có banner phim)
  $promoBanners = [
    ['img' => asset('storage/pictures/mai.jpg'),              'url' => url('/promo/member-day'), 'title' => 'Member Day',   'desc' => 'X2 điểm thưởng'],
    ['img' => asset('storage/pictures/muado.jpg'),            'url' => url('/promo/combo'),      'title' => 'Combo Bắp Nước','desc' => 'Chỉ từ 49K'],
    ['img' => asset('storage/pictures/tuchientrenkhong.jpg'), 'url' => url('/promo/early-bird'), 'title' => 'Early Bird',   'desc' => 'Đặt sớm -20%'],
  ];

  // Chuẩn hoá URL ảnh: nếu đã là http|/storage thì giữ nguyên, ngược lại bọc asset()
  $normalizeImg = fn ($path) =>
      $path && Str::startsWith($path, ['http', '/storage']) ? $path : ($path ? asset($path) : null);

  // Gom tất cả phim đang là banner
  $movieBanners = collect($bannerMovies ?? [])
    ->filter(fn ($m) => !empty($m->background))
    ->map(fn ($m) => [
      'img'   => $normalizeImg($m->background),
      'url'   => route('movies.show', ['movieID' => $m->movieID]),
      'title' => $m->title,
      'desc'  => Str::limit((string) $m->description, 100),
    ]);

  // Fallback 1 phim nếu middleware chỉ share $bannerMovie
  if ($movieBanners->isEmpty() && !empty($bannerMovie) && !empty($bannerMovie->background)) {
    $movieBanners = collect([[
      'img'   => $normalizeImg($bannerMovie->background),
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

{{-- PHIM BÁN CHẠY – luôn hiển thị, không dính search --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mb-1">
      <span class="status-dot"></span>
      Phim bán chạy
    </h4>
    <p class="text-muted mb-0">Các bộ phim hot trong rạp</p>
  </div>

  <div id="topSellingContainer">
    @include('layouts.movie_list', [
        'movies'        => $topSellingMovies,
        'emptyTitle'    => 'Chưa có dữ liệu doanh thu.',
        'emptySubtitle' => 'Hệ thống sẽ tự động cập nhật khi có đơn hàng được thanh toán.'
    ])
  </div>
</section>

{{-- PHIM ĐANG CHIẾU --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mb-1">
      <span class="status-dot"></span>
      Phim đang chiếu
    </h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>

  <div id="nowShowingContainer" class="mb-5">
    @include('layouts.movie_list', [
        'movies'        => $nowShowingMovies,
        'emptyTitle'    => 'Hiện chưa có phim đang chiếu nào.',
        'emptySubtitle' => 'Vui lòng quay lại sau, hệ thống sẽ cập nhật khi có lịch chiếu mới.'
    ])
  </div>
</section>

{{-- PHIM SẮP CHIẾU --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mt-1">
      <span class="status-dot"></span>
      Phim sắp chiếu
    </h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>

  <div id="comingSoonContainer" class="mb-5">
    @include('layouts.movie_list', [
        'movies'        => $comingSoonMovies,
        'emptyTitle'    => 'Hiện chưa có phim sắp chiếu nào.',
        'emptySubtitle' => 'Vui lòng quay lại sau, khi có lịch chiếu mới sẽ hiện ở đây.'
    ])
  </div>
</section>

{{-- TIN TỨC --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mt-1">
      <span class="status-dot"></span>
      Tin tức
    </h4>
    <p class="text-muted mb-0">Cập nhập nhanh tin tức điện ảnh</p>
  </div>

  <div id="newsListContainer">
    @include('layouts.news_list', ['newsList' => $news])
  </div>
</section>
@endsection

@push('styles')
<style>
/* =========================================================
   DARK THEME – Trang chủ DMM Cinema (force override)
   ========================================================= */

body {
    background: #0b1220 !important;
    color: #e5e7eb !important;
}
/* =========================================================
   BANNER / CAROUSEL – FULL WIDTH + CAPTION + ARROW
   ========================================================= */
.banner-wrapper {
    max-height: 720px;
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow: hidden;
    position: relative;
}

.banner-img {
    width: 100%;
    aspect-ratio: 21 / 9;                    
    object-fit: cover;                        
    transform: scale(1);
    transition: transform 4s ease, box-shadow 0.3s ease-in-out;
    border-radius: 0 !important;
    box-shadow: none !important;
}

.banner-img:hover {
    transform: scale(1.03);
}
.carousel-item.active .banner-img {
    transform: scale(1.02);
}

.banner-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(
        180deg,
        rgba(15, 23, 42, 0.05) 0%,
        rgba(15, 23, 42, 0.8) 100%
    );
}

.banner-caption {
    position: absolute;
    left: 4%;                                 
    bottom: 12%;                               
    z-index: 2;                             
    color: #fff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.85);
    opacity: 0;
    transform: translateY(8px);
    transition: all 0.5s ease;
    max-width: 520px;                      
    pointer-events: none;                    
}

.carousel-item.active .banner-caption {
    opacity: 1;
    transform: translateY(0);
}

.banner-caption h5 {
    margin: 0;
    font-weight: 700;
    background: rgba(15,23,42,0.85);
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 0.75rem;
    letter-spacing: 0.03em;
}

.banner-caption p {
    margin-top: 0.4rem;
    background: rgba(15,23,42,0.7);
    display: inline-block;
    padding: 0.35rem 0.8rem;
    border-radius: 0.75rem;
    font-size: 0.95rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .banner-caption {
        left: 6%;
        right: 6%;
        bottom: 10%;
        max-width: none;
        font-size: 0.9rem;
    }
}
/* =========================================================
   NÚT PREV / NEXT – sát mép ảnh, luôn đúng vị trí
   ========================================================= */
#bannerCarousel {
    position: relative;
}
#bannerCarousel .carousel-control-prev,
#bannerCarousel .carousel-control-next {
    width: auto !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    opacity: 1 !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    z-index: 3;
}
#bannerCarousel .carousel-control-prev {
    left: 5px !important;
}
#bannerCarousel .carousel-control-next {
    right: 5px !important;
}
#bannerCarousel .carousel-control-prev-icon,
#bannerCarousel .carousel-control-next-icon {
    width: 2.4rem !important;
    height: 2.4rem !important;
    border-radius: 999px;
    background-color: rgba(0,0,0,0.55) !important;
    background-size: 60% 60% !important;
}

#bannerCarousel .carousel-control-prev:hover .carousel-control-prev-icon,
#bannerCarousel .carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(0,0,0,0.85) !important;
}

.status-dot {
  width: 12px;
  height: 12px;
  background: #ff3b30;
  border-radius: 50%;
  display: inline-block;
}
/* =========================================================
   SECTION TITLES
   ========================================================= */
.ns-head h4,
h4 {
    color: #f9fafb !important;
}
.text-muted {
    color: #9ca3af !important;
}

/* =========================================================
   MOVIE CARDS - Movie List Layout
   ========================================================= */
.movie-card {
    border-radius: var(--card-radius);
    background: #020617 !important;
    border: 1px solid rgba(148, 163, 184, 0.4) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    box-shadow: 0 16px 45px rgba(15, 23, 42, 0.9) !important;
    position: relative;
    overflow: hidden;
}

.movie-card .poster-img {
    aspect-ratio: 2 / 3;        
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.movie-card .card-body {
    padding: 0.9rem 1rem 1rem;
}

.movie-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 22px 60px rgba(15, 23, 42, 1) !important;
    border-color: #38bdf8 !important;
}
.movie-card:hover .poster-img {
    transform: scale(1.04);
}

.movie-card:hover .actions-float {
    opacity: 1;
    transform: translateY(0);
}

.movie-card .card-title {
    color: #ffffff !important;
    font-size: 1.05rem !important;     
    font-weight: 700 !important;
    letter-spacing: 0.3px;
    margin-bottom: 0.25rem;
    white-space: normal !important;

    display: -webkit-box;
    -webkit-line-clamp: 2;              
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.movie-card .text-muted {
    color: #9ca3af !important;
    font-size: 0.86rem;
}
.empty-center-wrapper {
    width: 100%;
    padding: 24px 0 40px; 
    display: flex;
    justify-content: center;   
    align-items: center;       
}

.empty-center-card {
    text-align: center;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 28px 24px;
    color: #e5e7eb;
    max-width: 360px;
    width: 100%;
    backdrop-filter: blur(6px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.45);
}

.empty-center-card h5 {
    color: #ffffff;
    font-weight: 700;
}

.empty-center-card p {
    color: #9ca3af !important;
    font-size: 0.95rem;
}

.empty-center-card .fs-1 {
    opacity: 0.85;
}
.poster-wrap {
    border-top-left-radius: var(--card-radius);
    border-top-right-radius: var(--card-radius);
    overflow: hidden;
}
.poster-img {
    transition: transform 0.35s ease;
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
.rating-pill-wrapper {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  z-index: 2;
  pointer-events: none; 
}

.rating-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.18rem 0.6rem;
  border-radius: 999px;
  font-size: 0.74rem;
  white-space: nowrap;
  box-shadow: 0 10px 18px rgba(0,0,0,0.55);
  backdrop-filter: blur(6px);
}

.rating-pill--has {
  background: linear-gradient(135deg, rgba(15,23,42,0.92), rgba(30,64,175,0.95));
  color: #fefce8;
}

.rating-pill--has i {
  font-size: 0.85rem;
  color: #facc15;
}

.rating-pill-score {
  font-weight: 700;
}

.rating-pill-scale {
  opacity: 0.8;
  font-size: 0.7rem;
}

.rating-pill--empty {
  background: rgba(15,23,42,0.85);
  border: 1px dashed rgba(148,163,184,0.7);
  color: #e5e7eb;
}

.rating-pill--empty i {
  font-size: 0.8rem;
  opacity: 0.9;
}
/* =========================================================
   News - Movie List Layout
   ========================================================= */
.news-card {
    background: #020617;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.9);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.news-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 22px 60px rgba(15, 23, 42, 1);
}

.news-thumb-wrap {
    aspect-ratio: 16 / 9;
    background: #020617;
}

.news-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-date {
    font-size: 0.8rem;
}

.news-title {
    color: #ffffff !important;
    font-weight: 700;
    letter-spacing: .3px;
}

.news-excerpt {
    font-size: 0.85rem;
    line-height: 1.5;
}

</style>
@endpush
