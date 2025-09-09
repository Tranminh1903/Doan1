@extends('layouts.app')
@section('title','DuManMinh Cinema – Đặt vé xem phim')
@section('content')

<div class="p-4 p-md-5 hero mb-4">
  <div class="hero-overlay p-4 p-md-5 rounded">
    <h1 class="display-5 fw-bold">Đặt vé xem phim nhanh chóng</h1>
    <p class="lead mb-4">Lịch chiếu mới nhất, ưu đãi hấp dẫn. Chọn phim, chọn ghế, thanh toán trong 1 phút.</p>
    <a href="#now" class="btn btn-primary btn-lg">Đặt vé ngay</a>
  </div>
</div>

<h4 id="now" class="mb-3">Phim đang chiếu</h4>
<div class="row g-3">
  @php
    // dữ liệu demo – sau này thay bằng $movies từ controller
    $movies = [
      ['title'=>'Dune: Part Two','genre'=>'Sci-Fi','duration'=>166,'poster'=>'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?q=80&w=600'],
      ['title'=>'Inside Out 2','genre'=>'Animation','duration'=>96,'poster'=>'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?q=80&w=600'],
      ['title'=>'A Quiet Place: Day One','genre'=>'Thriller','duration'=>99,'poster'=>'https://images.unsplash.com/photo-1497032205916-ac775f0649ae?q=80&w=600'],
      ['title'=>'Deadpool & Wolverine','genre'=>'Action','duration'=>127,'poster'=>'https://images.unsplash.com/photo-1517602302552-471fe67acf66?q=80&w=600'],
    ];
  @endphp

  @foreach ($movies as $m)
    <div class="col-6 col-md-3">
      <div class="card movie-card h-100">
        <img src="{{ $m['poster'] }}" class="card-img-top" alt="{{ $m['title'] }}">
        <div class="card-body">
          <h6 class="card-title mb-1">{{ $m['title'] }}</h6>
          <div class="text-muted small">{{ $m['genre'] }} • {{ $m['duration'] }} phút</div>
          <a href="#" class="btn btn-sm btn-primary mt-2">Đặt vé</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

<h4 class="mt-5 mb-3">Ưu đãi nổi bật</h4>
<div class="row g-3">
  <div class="col-md-4"><div class="p-3 border rounded-3">🎟️ Giảm 20% khi đặt trước 24h</div></div>
  <div class="col-md-4"><div class="p-3 border rounded-3">🍿 Combo bắp nước chỉ 49K</div></div>
  <div class="col-md-4"><div class="p-3 border rounded-3">🎁 Tích điểm đổi vé miễn phí</div></div>
</div>
@endsection
