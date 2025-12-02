@extends('layouts.app')
@section('title', $movie->title)

@section('content')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $posterUrl = Str::startsWith($movie->poster, ['http://', 'https://'])
        ? $movie->poster
        : asset($movie->poster);

    $backgroundUrl = null;
    if ($movie->background) {
        $backgroundUrl = Str::startsWith($movie->background, ['http://', 'https://'])
            ? $movie->background
            : asset($movie->background);
    }

    $genreMap = [
        'Action'      => 'Hành động',
        'Adventure'   => 'Phiêu lưu',
        'Animation'   => 'Hoạt hình',
        'Comedy'      => 'Hài',
        'Crime'       => 'Tội phạm',
        'Documentary' => 'Tài liệu',
        'Drama'       => 'Chính kịch',
        'Fantasy'     => 'Giả tưởng',
        'Horror'      => 'Kinh dị',
        'Mystery'     => 'Bí ẩn',
        'Romance'     => 'Lãng mạn',
        'Sci-Fi'      => 'Khoa học viễn tưởng',
        'Thriller'    => 'Giật gân',
        'War'         => 'Chiến tranh',
        'Western'     => 'Viễn tây',
    ];

    $genreLabel = null;
    if (!empty($movie->genre)) {
        $genresRaw = explode(',', $movie->genre);

        $genreLabel = collect($genresRaw)
            ->map(fn ($g) => trim($g))
            ->filter()
            ->map(fn ($g) => $genreMap[$g] ?? $g)
            ->implode(', ');
    }

    $ageMap = [
        'P'   => 'P: Phim dành cho mọi đối tượng.',
        'K'   => 'K: Phim dành cho khán giả dưới 13 tuổi, nên xem cùng người lớn.',
        'T13' => 'T13: Phim dành cho khán giả từ đủ 13 tuổi trở lên (13+).',
        'T16' => 'T16: Phim dành cho khán giả từ đủ 16 tuổi trở lên (16+).',
        'T18' => 'T18: Phim dành cho khán giả từ đủ 18 tuổi trở lên (18+).',
    ];
    $ageLabel = $ageMap[$movie->rating] ?? null;

    $avg = max(0, min(10, (float) $averageRating));
    $rounded = (int) round($avg);

    $releaseText = null;
    if (!empty($movie->releaseDate)) {
        $releaseText = Carbon::parse($movie->releaseDate)->format('d/m/Y');
    }
@endphp
<section class="movie-detail-hero py-5">
    <div class="container-xxl">
        <div class="row g-4 align-items-start">

            {{-- Poster --}}
            <div class="col-md-4 col-lg-3">
                <div class="movie-poster-card position-relative">
                    @if($movie->rating)
                        <div class="movie-age-chip">{{ $movie->rating }}</div>
                    @endif

                    <img src="{{ $posterUrl }}" alt="{{ $movie->title }}"
                         class="img-fluid w-100 movie-poster-img">
                </div>
            </div>

            {{-- Nội dung --}}
            <div class="col-md-8 col-lg-9">
                <div class="movie-detail-content">

                    {{-- Tên phim --}}
                    <h1 class="movie-title mb-3">
                        {{ $movie->title }}
                        @if($movie->rating)
                            <span class="movie-title-rating">({{ $movie->rating }})</span>
                        @endif
                    </h1>

                    {{-- Meta --}}
                    <div class="movie-meta mb-3">
                        @if($genreLabel)
                            <div class="movie-meta-item">
                                <i class="bi bi-film"></i> Thể loại: {{ $genreLabel }}
                            </div>
                        @endif

                        <div class="movie-meta-item">
                            <i class="bi bi-clock"></i> Thời lượng: {{ $movie->durationMin }}'
                        </div>
                    </div>
                    
                    @if($ageLabel)
                        <div class="movie-age-highlight mb-4">{{ $ageLabel }}</div>
                    @endif

                    <div class="movie-section mb-3">
                        <h5 class="movie-section-title">NỘI DUNG PHIM</h5>

                        <p class="movie-description mb-2">
                            {!! nl2br(e($movie->description)) !!}
                        </p>
                    </div>

                    <div class="movie-section mb-3">
                        <h5 class="movie-section-title">MÔ TẢ PHIM</h5>
                        <span class="movie-release-inline">
                            Phim được công chiếu vào ngày
                            <strong>{{ Carbon::parse($movie->releaseDate)->format('d/m/Y') }}</strong>.
                        </span>
                    </div>

                    <div class="movie-section mt-4">
                        <h5 class="movie-section-title">ĐÁNH GIÁ PHIM NÀY</h5>

                        <div class="movie-rating-summary d-flex align-items-center gap-2 flex-wrap">
                            <span class="rating-stars-static">
                                @for ($i = 1; $i <= 10; $i++)
                                    {!! $i <= $rounded ? '&#9733;' : '&#9734;' !!}
                                @endfor
                            </span>

                            <span class="movie-score-text">
                                {{ number_format($avg, 1) }}/10
                            </span>
                        </div>
                    </div>


                    <div class="movie-divider mt-4 mb-3"></div>


                    <div class="movie-section mt-2">
                        <h5 class="movie-section-title">GỬI ĐÁNH GIÁ CỦA BẠN</h5>

                        @auth
                            <form action="{{ route('movies.rate', ['movieID' => $movie->movieID]) }}"
                                  method="POST" class="mb-3">
                                @csrf

                                <div class="rating-stars editable" id="rating-stars-control">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <span class="star" data-value="{{ $i }}">&#9734;</span>
                                    @endfor
                                </div>

                                <input type="hidden" name="stars" id="stars-input" value="0">

                                <button type="submit" class="btn btn-primary btn-sm mt-3">
                                    Gửi đánh giá
                                </button>
                            </form>
                        @else
                            <p class="mt-2">
                                <a href="{{ route('login') }}">Đăng nhập</a> để gửi đánh giá cho phim này.
                            </p>
                        @endauth
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- Script chọn sao --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll("#rating-stars-control .star");
    const input = document.getElementById("stars-input");

    stars.forEach(star => {
        star.addEventListener("click", () => {
            const rating = parseInt(star.dataset.value);
            input.value = rating;

            stars.forEach(s => {
                s.innerHTML = parseInt(s.dataset.value) <= rating ? "★" : "☆";
            });
        });
    });
});
</script>
@endsection

@push('styles')
<style>
body {
    position: relative;
    min-height: 100vh;
    color: #e5e7eb;
    background-color: #020617;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    @if($backgroundUrl)
        background-image: url('{{ $backgroundUrl }}');
    @else
        background: radial-gradient(circle at bottom left, #1e1b4b 0, #020617 60%, #020617 100%);
    @endif
    background-size: cover;
    background-position: center;
    filter: blur(22px);
    transform: scale(1.08);
    z-index: -2;
}

body::after {
    content: '';
    position: fixed;
    inset: 0;
    background: radial-gradient(circle at top left,
        rgba(15, 23, 42, 0.15),
        rgba(15, 23, 42, 0.9));
    z-index: -1;
}

.movie-detail-hero {
    position: relative;
}

.movie-poster-card {
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.85);
    position: relative;
}
.movie-poster-img {
    width: 100%;
    border-radius: 16px;
}

.movie-age-chip {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: #f97316;
    padding: 4px 10px;
    color: #111;
    font-weight: 700;
    border-radius: 10px;
    font-size: .9rem;
}

.movie-title {
    font-size: 2rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #fff;
}
.movie-title-rating {
    font-size: .95rem;
    color: #e5e7eb;
}

.movie-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.movie-meta-item {
    font-size: 1rem;
    display: flex;
    align-items: center;
    color: #e5e7eb;
}
.movie-meta-item i {
    margin-right: 6px;
    color: #facc15;
}

.movie-age-highlight {
    display: inline-block;
    background: #fde047;
    color: #111;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
}

.movie-section-title {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: .5rem;
}
.movie-description {
    font-size: 1rem;
    line-height: 1.6;
}
.movie-release-inline {
    color: #cbd5f5;
    font-size: .95rem;
}

.rating-stars-static { font-size: 22px; color: gold; }
.rating-stars.editable { font-size: 26px; cursor: pointer; color: gold; }
.star { padding: 0 2px; }
.movie-score-text { font-weight: 600; }

.movie-divider {
    width: 100%;
    max-width: 500px;
    height: 1px;
    background: rgba(255,255,255,0.25);
}

@media (max-width: 767.98px) {
    .movie-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush
