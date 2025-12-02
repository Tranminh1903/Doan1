@extends('layouts.app')
@section('title', 'Chọn Suất Chiếu - DuManMinh Cinema')

@section('content')
<div class="main-section container">
  @php
    $bg = $movie->background
        ? asset($movie->background)
        : ($movie->poster ? asset($movie->poster) : asset('images/default_poster.jpg'));
    $weekdayVN = [
        'Mon' => 'Thứ hai',
        'Tue' => 'Thứ ba',
        'Wed' => 'Thứ tư',
        'Thu' => 'Thứ năm',
        'Fri' => 'Thứ sáu',
        'Sat' => 'Thứ bảy',
        'Sun' => 'Chủ nhật',
    ];
  @endphp

  <div class="left-content">
    <div class="hero-section">
      <div class="hero-content">
        <h1>{{ $movie->title }}</h1>
        <p class="mb-1">{{ $movie->genre }} • {{ $movie->durationMin }} phút</p>
        <p class="mb-3">{{ $movie->description ?? 'Không có mô tả' }}</p>
        <a href="#showtime-section" class="btn btn-custom"> Đặt vé ngay</a>
      </div>
    </div>
    
    <div class="showtimes-container" id="showtime-section">
      <hr>
      <h3 class="fw-bold mb-3 text-start"> Chọn ngày chiếu</h3>

      <div class="d-flex justify-content-start flex-wrap gap-2 mb-5">
        @foreach ($availableDates as $d)
          @if ($d->isToday() || $d->isFuture())
            @php
                $weekdayKey = $d->format('D'); // Mon, Tue, ...
                $weekday    = $weekdayVN[$weekdayKey] ?? $weekdayKey;
            @endphp

            <button class="btn date-btn" data-date="{{ $d->format('Y-m-d') }}">
              {{ $d->format('d/m') }} - {{ $weekday }}
            </button>
          @endif
        @endforeach
      </div>

      <div id="showtime-list" class="fade-container">
        <h4 class="fw-bold mb-3"> Chọn suất chiếu</h4>

        {{-- RENDER THEO RẠP --}}
        @forelse ($groupedShowtimes as $theaterName => $showtimes)
          <div class="showtime-card">
            <h6 class="fw-bold mb-2">{{ $theaterName }}</h6>
            <div class="d-flex flex-wrap gap-2">

              {{--  CHỈ HIỂN THỊ GIỜ CHIẾU TRONG TƯƠNG LAI --}}
              @foreach ($showtimes as $showtime)
                @if ($showtime->startTime && $showtime->startTime->isFuture())
                  <a href="{{ route('booking.time', ['showtimeID' => $showtime->showtimeID]) }}"
                    class="btn time-btn"
                    data-date="{{ $showtime->startTime->format('Y-m-d') }}">
                    {{ $showtime->startTime->format('H:i') }}
                  </a>
                @endif
              @endforeach

            </div>
          </div>
        @empty
          <p class="text-muted text-center">Hiện chưa có suất chiếu nào cho phim này.</p>
        @endforelse

      </div>
    </div>
  </div>
</div>

{{-- JS FILTER --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
  const dateButtons = document.querySelectorAll('.date-btn');
  const timeButtons = document.querySelectorAll('.time-btn');
  const fadeContainer = document.querySelector('.fade-container');

  function filterShowtimes(selectedDate) {
    timeButtons.forEach(st => {
      st.style.display = (st.dataset.date === selectedDate) ? 'inline-block' : 'none';
    });
  }

  dateButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      dateButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const selectedDate = btn.dataset.date;

      fadeContainer.classList.add('fade-out');
      setTimeout(() => {
        filterShowtimes(selectedDate);
        fadeContainer.classList.remove('fade-out');
      }, 200);
    });
  });

  if (dateButtons.length > 0) dateButtons[0].click();
});
</script>

@endsection

@push('styles')
<style>
body {
  background: #05070b url('{{ $bg }}') center/cover fixed no-repeat !important;
  color: #fff;
  font-family: 'Poppins', sans-serif;
}

.main-section.container {
  max-width: 1200px;
  width: 100%;
  margin-top: 40px;
}

.hero-section {
  position: relative;
  min-height: 380px;
  border-radius: 24px;
  overflow: hidden;
  display: flex;
  align-items: center;
  background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,1));
}

.hero-content {
  position: relative;
  z-index: 1;
  padding: 3rem;
  width: 100%;
  max-width: none;   
}
.hero-content h1 {
  font-size: 2.8rem;
  font-weight: 700;
}
.hero-content p {
  color: #ccc;
  text-align: justify;
  text-justify: inter-word;
  line-height: 1.55; 
}

.showtimes-container {
  background: rgba(0, 0, 0, 0.8);
  padding: 2rem;
  margin-top: 2rem;
  border-radius: 24px;
}

.date-btn {
  background: transparent;
  border: 1px solid #555;
  color: white;
  border-radius: 8px;
  padding: 10px 16px;
  transition: all 0.3s;
}
.date-btn.active,
.date-btn:hover {
  background-color: #e50914;
  border-color: #e50914;
}

.showtime-card {
  background: #1f1f1f;
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 0 10px rgba(0,0,0,0.4);
}
.time-btn {
  background: transparent;
  border: 1px solid #888;
  color: #fff;
  border-radius: 6px;
  padding: 6px 10px;
  transition: 0.3s;
}
.time-btn:hover {
  background-color: #e50914;
  border-color: #e50914;
}

.fade-container {
  transition: opacity 0.3s ease-in-out;
}
.fade-out {
  opacity: 0;
}

@media (max-width: 768px) {
  .hero-content {
    padding: 2rem 1.5rem;
  }
  .main-section.container {
    max-width: 100%;
    padding: 0 1rem;
  }
}
.btn-custom {
  background: #e50914;
  color: #fff;
  border: none;
  padding: 0.6rem 1.4rem;
  font-weight: 600;
  border-radius: 6px;
  transition: 0.3s;
}

.btn-custom:hover {
  background: #f40612;
  transform: scale(1.05);
  color: #fff;
}
</style>
@endpush
