@php 
  use Illuminate\Support\Str;
  $normalizeImg = fn ($path) =>
      $path && Str::startsWith($path, ['http', '/storage']) ? $path : ($path ? asset($path) : null);
@endphp

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
  @forelse ($movies as $movie)
    @php
      $avg = $movie->ratings->avg('stars');        

      $avg = $avg !== null ? (float) $avg : null;

      $ratingValue = $avg !== null
          ? number_format($avg, 1)
          : null;

      $stars = $avg !== null
          ? max(0, min(5, (int) round($avg / 2)))
          : 0;

      $posterUrl  = $normalizeImg($movie->poster) ?? asset('images/placeholders/movie-banner.jpg');
    @endphp

    <div class="col">
      <article class="card movie-card h-100 border-0 shadow-sm">
        <div class="poster-wrap position-relative overflow-hidden">
          <img
            src="{{ $posterUrl }}"
            alt="{{ $movie->title }}"
            class="w-100 d-block poster-img"
            loading="lazy"
          >

          <div class="rating-pill-wrapper">
            @if ($ratingValue !== null)
              <div class="rating-pill rating-pill--has">
                <i class="bi bi-star-fill"></i>
                <span class="rating-pill-score">{{ $ratingValue }}</span>
                <span class="rating-pill-scale">/10</span>
              </div>
            @else
              <div class="rating-pill rating-pill--empty">
                <i class="bi bi-star"></i>
                <span>Chưa có đánh giá</span>
              </div>
            @endif
          </div>
        </div>

        <div class="card-quick-actions px-3 pt-3">
          <div class="d-flex gap-2">
            @if ($movie->showtimes->isNotEmpty())
              <a href="{{ route('select.showtime', ['movieID' => $movie->movieID]) }}" class="btn btn-danger btn-sm flex-fill">Mua vé</a>
            @else
              <button class="btn btn-secondary btn-sm flex-fill" type="button" disabled>Mua vé</button>
            @endif

            <a href="{{ route('movies.show', ['movieID' => $movie->movieID]) }}" class="btn btn-outline-secondary btn-sm flex-fill">Chi tiết</a>
          </div>
        </div>

        <div class="card-body">
          <p class="mb-3 text-muted small">{{ $movie->genre }} • {{ $movie->durationMin }} phút</p>
          <h6 class="card-title mb-1 text-truncate">
            {{ $movie->title }}
          </h6>
        </div>
      </article>
    </div>
  @empty
      <div class="empty-center-wrapper">
        <div class="empty-center-card">
          <div class="fs-1 mb-3">🎬</div>
            <h5 class="mb-2">Không tìm thấy phim nào</h5>
            <p class="mb-0 text-muted">Vui lòng thử tìm kiếm từ khóa khác.</p>
          </div>
        </div>
  @endforelse
</div>