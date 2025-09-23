@extends('layouts.app')
@section('title','DuManMinh Cinema – Đặt vé xem phim')
@section('content')
<div id="bannerCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel" data-bs-interval="3200">

  <div class="carousel-inner banner-wrapper rounded shadow-sm">
    @php
      $banners = $banners ?? [
        ['img'=>asset('storage/app/public/pictures/fastfurious.jpg'),
        'url'=>url('/promo/member-day'), 'title'=>'Member Day', 'desc'=>'X2 điểm thưởng'],
        ['img'=>asset('storage/app/public/pictures/giamcamquydu.jpg'),
        'url'=>url('/promo/combo'), 'title'=>'Combo Bắp Nước', 'desc'=>'Chỉ từ 49K'],
        ['img'=>asset('storage/app/public/pictures/hocduongnoiloan.jpg'),
        'url'=>url('/promo/early-bird'), 'title'=>'Early Bird', 'desc'=>'Đặt sớm -20%'],
      ];
    @endphp
    @foreach ($banners as $i => $b)
      <div class="carousel-item {{ $i===0 ? 'active' : '' }}">
        <a href="{{ $b['url'] }}" class="d-block position-relative">
          <img class="w-100 banner-img" src="{{ $b['img'] }}"
               alt="{{ $b['title'] ?? 'Banner '.($i+1) }}" loading="lazy">
          {{-- overlay mờ trên ảnh --}}
          <span class="banner-overlay"></span>
          {{-- caption trên ảnh --}}
          <div class="banner-caption">
            <h5 class="mb-1">{{ $b['title'] ?? '' }}</h5>
            @if(!empty($b['desc'])) <p class="mb-0">{{ $b['desc'] }}</p> @endif
          </div>
        </a>
      </div>
    @endforeach
  </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>

    <div class="carousel-indicators">
    @foreach ($banners as $i => $_)
      <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i===0?'active':'' }}"></button>
    @endforeach
    </div>
</div>

<h4 id="phimdangchieu" class="mb-3">Phim đang chiếu</h4>
{{-- Section: Lịch chiếu --}}
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12" >
        {{-- Ngày chiếu --}}
            <div class="d-flex justify-content-start mb-3">
                <button class="btn btn-primary me-2">7/9 CN</button>
                <button class="btn btn-outline-secondary me-2">8/9 Th 2</button>
                <button class="btn btn-outline-secondary me-2">9/9 Th 3</button>
                <button class="btn btn-outline-secondary me-2">10/9 Th 4</button>
                <button class="btn btn-outline-secondary me-2">11/9 Th 5</button>
                <button class="btn btn-outline-secondary">12/9 Th 6</button>
            </div>
            <div class="alert alert-warning mb-4" style ="background-color: orange; color: black;">Nhấn vào suất chiếu để tiến hành mua vé</div>
        @foreach ($movies as $movie)
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-2">
                        <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="img-fluid rounded-start h-100 w-100 object-fit-cover">
                    </div>
                    <div class="col-md-10">
                        <div class="card-body ms-2">
                        <h5 class="card-title mb-2">{{ $movie->title }} <span class="badge bg-secondary">{{ $movie->rating }}</span></h5>
                        <p class="card-text mb-2"><small class="text-muted">{{ $movie->genre }} - {{ $movie->durationMin }} phút</small></p>

                        {{-- Suất chiếu --}}
                        <div class="d-flex flex-wrap gap-2">
                        @foreach ($movie->showtimes as $showtime)
                            <a href="{{ route('booking.time', ['showtime' => $showtime->showtimeID]) }}" class="btn btn-outline-primary btn-sm mb-2">
                                {{ \Carbon\Carbon::parse($showtime->startTime)->format('H:i') }} - {{ $showtime->theater->roomName }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
{{-- ===== Section: Ưu đãi ===== --}}
<h4 class="mt-5 mb-3">Ưu đãi nổi bật</h4>
<div class="row g-3">
  <div class="col-md-4"><div class="p-3 border rounded-3">🎟️ Giảm 20% khi đặt trước 24h</div></div>
  <div class="col-md-4"><div class="p-3 border rounded-3">🍿 Combo bắp nước chỉ 49K</div></div>
  <div class="col-md-4"><div class="p-3 border rounded-3">🎁 Tích điểm đổi vé miễn phí</div></div>
</div>

        </div>
    </div>
</div>
@endsection