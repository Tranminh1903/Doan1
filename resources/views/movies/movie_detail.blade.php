@extends('layouts.app')
@section('title', $movie->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Ảnh phim -->
        <div class="col-md-4">
            <img src="{{ asset($movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded">
        </div>

        <!-- Thông tin phim -->
        <div class="movie-info col-md-8">
            <h3>{{ $movie->title }}</h3>
            <p><strong>Thể loại:</strong> {{ $movie->genre }}</p>
            <p><strong>Thời lượng:</strong> {{ $movie->durationMin }} phút</p>

            <hr>

            <!-- Hiển thị đánh giá trung bình -->
            <p>
                <strong>Đánh giá phim này:</strong>
                <span style="color: gold; font-size: 22px;">
                    @for ($i = 1; $i <= 5; $i++)
                        {!! $i <= round($averageRating) ? '&#9733;' : '&#9734;' !!}
                    @endfor
                </span>

                @if($averageRating > 0)
                    <strong> ({{ number_format($averageRating, 1) }}/5)</strong>
                @else
                    <strong> (Chưa có đánh giá)</strong>
                @endif
            </p>

            <hr>

            <!-- Form đánh giá -->
            @auth
                <h5>Gửi đánh giá của bạn:</h5>

                <form action="{{ route('movies.rate', ['movieID' => $movie->movieID]) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="rating-stars" style="font-size: 26px; color: gold; cursor: pointer;">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}">&#9734;</span>
                        @endfor
                    </div>

                    <input type="hidden" name="stars" id="stars-input" value="0">

                    <button type="submit" class="btn btn-primary btn-sm mt-2">Gửi đánh giá</button>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('stars') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
            @else
                <p><a href="{{ route('login') }}">Đăng nhập</a> để gửi đánh giá cho phim này.</p>
            @endauth

            <hr>

            <!-- Mô tả phim -->
            <p>{{ $movie->description }}</p>
        </div>
    </div>
</div>

<!-- Script chọn sao -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const stars = document.querySelectorAll(".star");
    const input = document.getElementById("stars-input");

    stars.forEach(star => {
        star.addEventListener("click", function() {
            const rating = this.getAttribute("data-value");
            input.value = rating;

            stars.forEach(s => {
                s.innerHTML = s.getAttribute("data-value") <= rating ? "&#9733;" : "&#9734;";
            });
        });
    });
});
</script>
@endsection

@push('styles')
<style>
/* Movie detail */
.movie-info p {
    margin-bottom: 5px;
}
</style>
@endpush