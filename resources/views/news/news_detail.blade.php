@extends('layouts.app')

{{-- Set Title cho tab trình duyệt --}}
@section('title', $news->title)

@section('content')
<div class="news-detail-wrapper">
    <div class="container py-5" style="max-width: 960px;"> 
        {{-- Giới hạn chiều rộng 960px để dễ đọc như trang báo --}}

        <div class="article-container">
            
            {{-- 1. Tiêu đề bài viết (In đậm, lớn) --}}
            <h1 class="article-title mb-3">
                {{ $news->title }}
            </h1>

            {{-- 2. Ngày đăng (Nhỏ, màu xám) --}}
            <div class="article-meta mb-4">
                <i class="bi bi-clock"></i> {{ $news->created_at->format('H:i - d/m/Y') }}
            </div>

            {{-- 3. Nội dung văn bản (Hiển thị trước) --}}
            <div class="article-content mb-4">
                {{-- nl2br: chuyển xuống dòng thành thẻ <br>, e(): chống lỗi XSS --}}
                {!! nl2br(e($news->description)) !!}
            </div>

            {{-- 4. Hình ảnh minh họa (Lớn, căn giữa, nằm dưới văn bản) --}}
            @if($news->image)
            <div class="article-image text-center mb-5">
                <img src="{{ asset($news->image) }}" 
                     alt="{{ $news->title }}" 
                     class="img-fluid rounded shadow-sm">
            </div>
            @endif

            {{-- Nút quay lại (Thêm cho tiện điều hướng) --}}
            <div class="text-center mt-5 pt-4 border-top border-secondary">
                <a href="{{ route('news.news') }}" class="btn btn-outline-light rounded-pill px-4">
                    <i class="bi bi-arrow-left"></i> Xem tin khác
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #0b0b0f;
        color: #e0e0e0; 
    }

    .news-detail-wrapper {
        min-height: 80vh;
        padding-top: 20px;
    }

    .article-title {
        font-weight: 800;
        color: #ffffff;
        font-size: 1.8rem;
        line-height: 1.4;
        text-transform: uppercase; 
    }

    .article-meta {
        color: #a0a0a0;
        font-size: 0.9rem;
        font-style: italic;
    }

    .article-content {
        font-size: 1.1rem;
        line-height: 1.8; 
        text-align: justify; 
        color: #f1f1f1;
    }

    .article-image img {
        max-width: 100%;
        height: auto;
        border: 1px solid #333;
    }
</style>
@endpush