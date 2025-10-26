@extends('layouts.app')

@section('title', 'Lịch sử vé - DuManMinh Cinema')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3 text-center">Lịch sử vé đã mua</h2>

    @if ($tickets->isEmpty())
        <p class="text-center text-muted">Bạn chưa có vé nào.</p>
    @else
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã vé</th>
                    <th>Phim</th>
                    <th>Suất chiếu</th>
                    <th>Ghế</th>
                    <th>Tổng tiền</th>
                    <th>Ngày mua</th>
                    <th>Mã đơn</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    @php
                        $order = optional($ticket->showtime)->orders->first(); // đã filter theo user
                    @endphp
                    <tr>
                        <td>{{ $ticket->ticketID }}</td>
                        <td>{{ optional(optional($ticket->showtime)->movie)->title }}</td>
                        <td>
                            @if(optional($ticket->showtime)->startTime)
                                {{ \Carbon\Carbon::parse($ticket->showtime->startTime)->format('d/m/Y H:i') }}
                            @endif
                        </td>
                        <td>{{ optional($ticket->seat)->seatID }}</td>
                        <td>{{ number_format($ticket->price, 0, ',', '.') }}₫</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ optional($order)->order_code }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection