<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Vé xem phim của bạn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f8fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .mail-container {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #e50914;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .qr {
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #e50914;
            color: #fff !important;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 13px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="mail-container">
        <h2>Cảm ơn bạn đã thanh toán thành công!</h2>

        <div class="movie-info">
            <h3 style="color:#e50914; text-align:center;"> {{ $movieName }}</h3>
<p style="text-align:center;">
    <strong>Rạp:</strong> {{ $cinema->roomName ?? 'Rạp ẩn danh' }}<br>
    <strong>Thời gian chiếu:</strong> {{ $startTime }}<br>
    <strong>Giá vé:</strong> {{ number_format($showtime->price ?? 0, 0, ',', '.') }} VND
</p>

        </div>

        <p><strong>Mã đơn:</strong> {{ $order->order_code }}</p>
        <p><strong>Ghế đã đặt:</strong> {{ isset($seatsFormatted) ? implode(', ', $seatsFormatted) : 'Chưa có dữ liệu' }}</p>

        <div class="qr">
            <h3>Mã QR vé của bạn</h3>
            <img src="{{ $qrUrl }}" alt="QR Code" width="200">
        </div>

        <p style="text-align:center;">
            Cảm ơn bạn đã đặt vé tại hệ thống của chúng tôi!<br>
            Chúc bạn xem phim vui vẻ 
        </p>

        <div style="text-align:center;">
            <a href="{{ config('app.url') }}" class="button">Xem vé tại trang web</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Movie Booking System
        </div>
    </div>
</body>
</html>
