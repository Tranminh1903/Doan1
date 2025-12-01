@extends('layouts.app')
@section('title', 'Tin Tức')

@section('content')
<div class="news-page-wrapper">
    <div class="container py-5">
        <h2 class="text-center text-white fw-bold mb-5 text-uppercase">Tin tức</h2>

        <div class="row g-4">
            @foreach($news as $item)
            <div class="col-12 col-md-6 col-lg-3">
                {{-- Bọc toàn bộ card bằng thẻ a để click --}}
                <a href="{{ route('news.news_detail', $item->id) }}" class="text-decoration-none">
                    <div class="news-card h-100 d-flex flex-column">
                        {{-- Ảnh thumbnail (Cao hơn) --}}
                        <div class="news-img-wrap mb-3">
                            <img src="{{ asset($item->image) }}" 
                                 alt="{{ $item->title }}" 
                                 class="img-fluid w-100 rounded"
                                 style="aspect-ratio: 4/3; object-fit: cover;"> {{-- Đổi tỷ lệ ảnh --}}
                        </div>

                        {{-- Nội dung --}}
                        <div class="news-body flex-grow-1">
                            <div class="news-date mb-2">
                                {{ $item->created_at->format('d/m/Y') }}
                            </div>
                            <h5 class="news-title mb-0">
                                {{ Str::limit($item->title, 70) }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #0b0b0f;
        color: #fff;
    }
    .news-page-wrapper {
        min-height: 80vh;
    }
    .news-card {
        background: transparent;
        transition: transform 0.3s ease;
        /* Tăng chiều cao tối thiểu cho card */
        min-height: 380px; 
    }
    .news-card:hover {
        transform: translateY(-8px);
    }
    .news-img-wrap img {
        transition: opacity 0.3s ease;
        /* Bo góc mềm mại hơn */
        border-radius: 8px !important; 
    }
    .news-card:hover .news-img-wrap img {
        opacity: 0.9;
    }
    .news-date {
        color: #a0a0a0;
        font-size: 0.9rem;
    }
    .news-title {
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 1.15rem;
        line-height: 1.5;
        transition: color 0.2s;
    }
    /* Hiệu ứng hover cho toàn bộ card */
    .news-card:hover .news-title {
        color: #e50914;
    }
</style>
@endpush