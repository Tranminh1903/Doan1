@extends('layouts.app')
@section('title', 'Chọn Suất Chiếu - DuManMinh Cinema')
@section('content')
<div class="container py-5">
  <h3 class="text-center mb-5 fw-bold">🎬 Chọn Suất Chiếu</h3>

  <div class="row justify-content-center align-items-center min-vh-75">
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

    <div class="col-md-6">
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
