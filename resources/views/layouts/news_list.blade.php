@php
    use Illuminate\Support\Str;

    $normalizeImg = fn ($path) =>
        $path && Str::startsWith($path, ['http', '/storage'])
            ? $path
            : ($path ? asset($path) : null);
@endphp

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
    @forelse ($news as $item)
        @php
            $imageUrl = $normalizeImg($item->image) ?? asset('images/placeholders/news-banner.jpg');
            $date     = optional($item->created_at)->format('d/m/Y');

            // Tóm tắt mô tả
            $excerpt  = Str::limit(strip_tags($item->description), 90);
        @endphp

        <div class="col">
            <article class="card news-card h-100 border-0 shadow-sm position-relative">
                {{-- Ảnh thumbnail --}}
                <div class="news-thumb-wrap overflow-hidden">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $item->title }}"
                        class="w-100 d-block news-thumb-img"
                        loading="lazy"
                    >
                </div>

                {{-- Nội dung --}}
                <div class="card-body d-flex flex-column">
                    @if($date)
                        <div class="news-date small text-muted mb-2">
                            {{ $date }}
                        </div>
                    @endif

                    <h6 class="news-title text-uppercase fw-bold mb-2">
                        {{ $item->title }}
                    </h6>

                    @if($excerpt)
                        <p class="news-excerpt text-muted mb-0">
                            {{ $excerpt }}
                        </p>
                    @endif
                </div>

                <a href="{{ route('news.news_detail', ['id' => $item->id]) }}" class="stretched-link"></a>
            </article>
        </div>
    @empty
        <div class="empty-center-wrapper">
            <div class="empty-center-card">
                <div class="fs-1 mb-3">📰</div>
                <h5 class="mb-2">Hiện chưa có tin tức nào</h5>
                <p class="mb-0 text-muted">Quay lại sau nhé.</p>
            </div>
        </div>
    @endforelse
</div>
