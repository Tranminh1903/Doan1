@extends('layouts.app') {{-- Nếu bạn có layout chung --}}
@section('title', $movie->title)
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <img src="{{ asset($movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded">
        </div>
        <div class="movie-info col-md-8">
            @if($movie->description)
                <p>{{ $movie->description }}</p>
            @endif
            <p><strong>Thể loại:</strong> {{ $movie->genre }}</p>
            <p><strong>Thời lượng:</strong> {{ $movie->durationMin }} phút</p>
            @if($movie->releaseDate)
                <p><strong>Khởi chiếu:</strong> {{ \Carbon\Carbon::parse($movie->releaseDate)->format('d/m/Y') }}</p>
            @endif
            @if($movie->age_rating)
                <p><strong>Độ tuổi:</strong> {{ $movie->age_rating }}</p>
            @endif
                        @if ($movie->showtimes->isNotEmpty())
                <a href="{{ route('select.showtime', ['movieID' => $movie->movieID]) }}"
                class="btn btn-primary btn-sm flex-fill">Mua vé</a>

            @else
                <button class="btn btn-secondary btn-sm flex-fill" type="button" disabled>Mua vé</button>
            @endif

            @if (!empty($movie->trailerURL))
                <a href="{{ $movie->trailerURL }}" target="_blank"
                    class="btn btn-outline-secondary btn-sm flex-fill">Xem trailer</a>
            @else
                <button class="btn btn-outline-secondary btn-sm flex-fill" type="button" disabled>Trailer chưa có</button>
            @endif
        </div>

    </div>

</div>
@endsection