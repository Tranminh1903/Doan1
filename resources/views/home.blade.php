@extends('layouts.app')
@section('title', 'Trang chủ - DuManMinh Cinema')

@section('content')
<div id="bannerCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel" data-bs-interval="3200">
  @php
    $banners = $banners ?? [
      [
        'img'   => asset('storage/app/public/pictures/mai.jpg'),
        'url'   => url('/promo/member-day'),
        'title' => 'Member Day',
        'desc'  => 'X2 điểm thưởng',
      ],
      [
        'img'   => asset('storage/app/public/pictures/muado.jpg'),
        'url'   => url('/promo/combo'),
        'title' => 'Combo Bắp Nước',
        'desc'  => 'Chỉ từ 49K',
      ],
      [
        'img'   => asset('storage/app/public/pictures/tuchientrenkhong.jpg'),
        'url'   => url('/promo/early-bird'),
        'title' => 'Early Bird',
        'desc'  => 'Đặt sớm -20%',
      ],
    ];
  @endphp

  <div class="carousel-inner banner-wrapper rounded shadow-sm">
    @foreach ($banners as $i => $b)
      <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
        <a href="{{ $b['url'] }}" class="d-block position-relative" aria-label="{{ $b['title'] ?? 'Banner ' . ($i + 1) }}">
          <img class="w-100 banner-img" src="{{ $b['img'] }}" alt="{{ $b['title'] ?? 'Banner ' . ($i + 1) }}" loading="lazy">
          {{-- overlay mờ trên ảnh --}}
          <span class="banner-overlay"></span>
          {{-- caption trên ảnh --}}
          <div class="banner-caption">
            @if (!empty($b['title']))
              <h5 class="mb-1">{{ $b['title'] }}</h5>
            @endif
            @if (!empty($b['desc']))
              <p class="mb-0">{{ $b['desc'] }}</p>
            @endif
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
      <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}" @if($i===0) aria-current="true" @endif aria-label="Chuyển đến banner {{ $i + 1 }}"></button>
    @endforeach
  </div>
</div>

{{-- Khu vực phim sắp chiếu --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mb-1">Phim sắp chiếu</h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>
  {{-- TODO: Render danh sách phim sắp chiếu nếu có biến $upcomingMovies --}}
</section>

{{-- Khu vực phim đang chiếu --}}
<section class="ns-section container mb-4">
  <div class="ns-head text-center mb-4">
    <h4 class="mb-1">Phim đang chiếu</h4>
    <p class="text-muted mb-0">Các suất chiếu mới nhất tại rạp</p>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
    @forelse ($movies as $movie)
      @php
        $firstShowtime = $movie->showtimes->first();
        $ratingRaw  = $movie->rating;
        $ratingText = $ratingRaw;
        $stars      = 3;
        if (is_numeric($ratingRaw)) {
          $val = (float) $ratingRaw;
          $ratingText = number_format($val, 1);
          $stars = max(0, min(5, (int) round($val)));
        }
      @endphp

      <div class="col">
        <article class="card movie-card h-100 border-0 shadow-sm">
          <div class="poster-wrap position-relative overflow-hidden">
            <img src="{{ asset($movie->poster) }}" alt="{{ $movie->title }}" class="w-100 d-block poster-img" style="aspect-ratio: 16/9; object-fit: cover;">

            <div class="position-absolute top-0 end-0 m-2 small bg-white bg-opacity-75 px-2 py-1 rounded-1">
              @for ($i = 1; $i <= 5; $i++)
                <i class="bi {{ $i <= $stars ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
              @endfor
              <span class="ms-1">{{ $ratingText }}</span>
            </div>
          </div>

          <div class="card-quick-actions px-3 pt-3">
            <div class="d-flex gap-2">
              @if ($firstShowtime)
                <a href="" class="btn btn-primary btn-sm flex-fill">Mua vé</a>
              @else
                <button class="btn btn-secondary btn-sm flex-fill" type="button" disabled aria-disabled="true">Mua vé</button>
              @endif

              <a href="" class="btn btn-outline-secondary btn-sm flex-fill">Chi tiết</a>
            </div>
          </div>

          <div class="card-body">
            <h6 class="card-title mb-1 text-truncate">
              {{ $movie->title }}
              <span class="badge bg-secondary align-middle ms-1">{{ $ratingText }}</span>
            </h6>
            <p class="mb-3 text-muted small">{{ $movie->genre }} • {{ $movie->durationMin }} phút</p>
          </div>

          <div class="card-footer bg-transparent border-0 pb-3 pt-0"></div>
        </article>
      </div>
    @empty
      <div class="col">
        <div class="alert alert-light border text-center w-100" role="alert">
          Hiện chưa có phim đang chiếu.
        </div>
      </div>
    @endforelse
  </div>
</section>

{{-- Ưu đãi nổi bật --}}
<section class="container mt-4">
  <h4 class="mt-2 mb-3">Ưu đãi nổi bật</h4>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="p-3 border rounded-3">🎟️ Giảm 20% khi đặt trước 24h</div>
    </div>
    <div class="col-md-4">
      <div class="p-3 border rounded-3">🍿 Combo bắp nước chỉ 49K</div>
    </div>
    <div class="col-md-4">
      <div class="p-3 border rounded-3">🎁 Tích điểm đổi vé miễn phí</div>
    </div>
  </div>
</section>
@endsection
