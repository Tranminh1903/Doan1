@extends('layouts.app')
@section('title', 'Chọn Suất Chiếu - DuManMinh Cinema')

@section('content')

<style>
body {
  background-color: #0b0b0f;
  color: #fff;
  font-family: 'Poppins', sans-serif;
}

/* Hero */
.hero-section {
  position: relative;
  background: url('{{ asset($movie->poster ?? "images/default_poster.jpg") }}') center/cover no-repeat;
  min-height: 90vh;
  border-radius: 20px;
  overflow: hidden;
  display: flex;
  align-items: flex-end;
}

.hero-section::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
}

/* Hero content */
.hero-content {
  position: relative;
  z-index: 2;
  padding: 3rem;
  max-width: 600px;
}

.hero-content h1 {
  font-size: 2.8rem;
  font-weight: 700;
}

.hero-content p {
  color: #ccc;
}

.btn-custom {
  background: #e50914;
  color: white;
  border: none;
  padding: 0.6rem 1.4rem;
  font-weight: 600;
  border-radius: 6px;
  transition: 0.3s;
}

.btn-custom:hover {
  background: #f40612;
  transform: scale(1.05);
}

/* Main layout */
.main-section {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  margin-top: 2rem;
}

.left-content {
  flex: 2;
  min-width: 60%;
}

.showtimes-container {
  background-color: #141414;
  padding: 2rem;
  margin-top: -40px;
  border-radius: 20px;
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
  box-shadow: 0 0 10px rgba(255,255,255,0.05);
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
</style>

<div class="main-section container">

  {{-- LEFT: Hero + showtimes --}}
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

      {{--  CHỈ HIỂN THỊ NGÀY >= HIỆN TẠI --}}
      <div class="d-flex justify-content-start flex-wrap gap-2 mb-5">
        @foreach ($availableDates as $d)
          @if ($d->isToday() || $d->isFuture())
            <button class="btn date-btn" data-date="{{ $d->format('Y-m-d') }}">
              {{ $d->format('d/m') }}<br>
              <small>{{ $d->translatedFormat('l') }}</small>
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
</style>
@endpush
