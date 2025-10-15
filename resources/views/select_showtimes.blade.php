@extends('layouts.app')
@section('title', 'Chọn Suất Chiếu - DuManMinh Cinema')

@section('content')
<div class="container py-5">
  <h3 class="text-center mb-5 fw-bold">🎬 Chọn Suất Chiếu</h3>

  <div class="row justify-content-center align-items-center min-vh-75">
    {{-- Cột trái: Thông tin phim --}}
    <div class="col-md-5 mb-4 d-flex justify-content-center">
      <div class="card border-0 bg-transparent text-center w-100" style="max-width: 450px;">
        <img src="{{ asset($movie->poster) }}" alt="{{ $movie->title }}"
             class="rounded shadow-sm mb-3 w-100"
             style="max-height: 420px; object-fit: cover;">
        <h4 class="fw-bold mb-2">{{ $movie->title }}</h4>
        <p class="mb-1 text-muted">{{ $movie->genre }} • {{ $movie->durationMin }} phút</p>
        <p class="text-muted small mb-0">{{ $movie->description ?? 'Không có mô tả' }}</p>
      </div>
    </div>

    {{-- Cột phải: Ngày chiếu và suất chiếu --}}
    <div class="col-md-6">
      {{-- Bộ lọc ngày --}}
      <div class="mb-4 text-center text-md-start">
        <h5 class="fw-bold mb-3">📅 Chọn ngày chiếu</h5>
        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
          @foreach ($availableDates as $d)
            <button class="btn btn-outline-primary date-btn"
                    data-date="{{ $d->format('Y-m-d') }}">
              {{ $d->format('d/m') }}<br>
              <small>{{ $d->format('D') }}</small>
            </button>
          @endforeach
        </div>
      </div>

      {{-- Danh sách suất chiếu --}}
      <div id="showtime-list" class="fade-container">
        <h5 class="fw-bold mb-3">⏰ Chọn suất chiếu</h5>
        @forelse ($groupedShowtimes as $theaterName => $showtimes)
          <div class="card border-0 bg-transparent mb-3 showtime-card">
            <div class="card-body p-2">
              <h6 class="fw-bold mb-2">{{ $theaterName }}</h6>
              <div class="d-flex flex-wrap gap-2">
                @foreach ($showtimes as $showtime)
                  @if ($showtime->startTime)
                    <a href="{{ route('booking.time', ['showtimeID' => $showtime->showtimeID]) }}"
                       class="btn btn-outline-dark btn-sm time-btn"
                       data-date="{{ $showtime->startTime->format('Y-m-d') }}">
                      {{ $showtime->startTime->format('H:i') }}
                    </a>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted text-center">Hiện chưa có suất chiếu nào cho phim này.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ====================== CSS ====================== --}}
<style>
  body {
    background: none !important; /* Ẩn hoàn toàn nền git hoặc nền app */
  }

  .container {
    background-color: #fff; /* Trắng sạch, nhìn gọn */
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    padding: 40px;
  }

  .min-vh-75 { min-height: 75vh; }

  /* Nút chọn ngày */
  .date-btn {
    width: 85px;
    text-align: center;
    line-height: 1.3;
    border-radius: 12px;
    padding: 8px;
    font-weight: 600;
    border: none;
    background-color: #f8f9fa;
    transition: 0.25s;
    box-shadow: 0 0 4px rgba(0,0,0,0.1);
  }
  .date-btn.active {
    background-color: #0d6efd;
    color: #fff;
    box-shadow: 0 0 8px rgba(13,110,253,0.4);
  }

  /* Nút chọn suất chiếu */
  .time-btn {
    min-width: 70px;
    font-weight: 500;
    border: none;
    background-color: #f8f9fa;
    transition: 0.25s;
    border-radius: 10px;
    box-shadow: 0 0 3px rgba(0,0,0,0.05);
  }
  .time-btn:hover {
    background-color: #0d6efd;
    color: #fff;
  }

  /* Hiệu ứng hover nhẹ cho thẻ rạp */
  .showtime-card {
    transition: 0.3s;
  }
  .showtime-card:hover {
    transform: translateY(-3px);
  }

  /* Fade khi đổi ngày */
  .fade-container {
    opacity: 1;
    transition: opacity 0.4s ease;
  }
  .fade-container.fade-out {
    opacity: 0;
  }
</style>

{{-- ====================== JS ====================== --}}
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
