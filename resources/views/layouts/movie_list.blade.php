@php 
  use Illuminate\Support\Str;
  $normalizeImg = fn ($path) =>
      $path && Str::startsWith($path, ['http', '/storage']) ? $path : ($path ? asset($path) : null);
@endphp

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
  @forelse ($movies as $movie)
    @php
      $ratingText = is_numeric($movie->rating) ? number_format((float) $movie->rating, 1) : ($movie->rating ?? '—');
      $stars      = is_numeric($movie->rating) ? max(0, min(5, (int) round((float) $movie->rating))) : 3;
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
          <div class="position-absolute top-0 end-0 m-2 small bg-white bg-opacity-75 px-2 py-1 rounded-1">
            @for ($i = 1; $i <= 5; $i++)
              <i class="bi {{ $i <= $stars ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
            @endfor
            <span class="ms-1">{{ $ratingText }}</span>
          </div>
        </div>

        <div class="card-quick-actions px-3 pt-3">
          <div class="d-flex gap-2">
            @if ($movie->showtimes->isNotEmpty())
              <a href="{{ route('select.showtime', ['movieID' => $movie->movieID]) }}" class="btn btn-primary btn-sm flex-fill">Mua vé</a>
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
            <span class="badge bg-secondary align-middle ms-1">{{ $ratingText }}</span>
          </h6>
        </div>
      </article>
    </div>
  @empty
    <div class="col">
      <div class="alert alert-light border text-center w-100" role="alert">
        Không tìm thấy phim nào.
      </div>
    </div>
  @endforelse
</div>
