@extends('layouts.app')
@section('title', 'Đặt Vé CGV')

@section('content')
<div class="container py-4">
  <h3 class="text-center mb-4">🎬 Đặt Vé Xem Phim</h3>

  <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center">
      <div style="width: 20px; height: 20px; background-color: #ccc; border-radius: 4px; margin-right: 8px;"></div>
      <span>Ghế trống</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width: 20px; height: 20px; background-color: limegreen; border-radius: 4px; margin-right: 8px;"></div>
      <span>Ghế đang chọn</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width: 20px; height: 20px; background-color: gold; border-radius: 4px; margin-right: 8px;"></div>
      <span>Được giữ chỗ</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width: 20px; height: 20px; background-color: red; border-radius: 4px; margin-right: 8px;"></div>
      <span>Đã đặt trước</span>
    </div>
  </div>

  <h4 class="text-center mb-4">Giờ chiếu: {{ $time }}</h4>


  <div style="
    border: 4px solid #333;
    border-radius: 12px;
    padding: 16px 32px;
    background-color: #f8f9fa;
    font-weight: bold;
    font-size: 28px;
    text-align: center;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;">
    MÀN HÌNH
  </div>

  <div id="seat-map" class="mb-4">
    <!-- Ghế sẽ được tạo bằng JS -->
  </div>

  <div class="text-center">
    <button class="btn btn-danger" onclick="confirmSeats()">Thanh toán</button>
  </div>
</div>

<style>
  .seat {
    width: 40px;
    height: 40px;
    margin: 4px;
    background-color: #ccc;
    border-radius: 5px;
    text-align: center;
    line-height: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .seat.selected {
    background-color: limegreen;
  }

  .seat.held {
    background-color: gold;
  }

  .seat.booked {
    background-color: red;
    cursor: not-allowed;
  }

  .row-label {
    width: 100%;
    text-align: left;
    font-weight: bold;
    margin-top: 10px;
  }

  @media (max-width: 576px) {
    .seat {
      width: 32px;
      height: 32px;
      line-height: 32px;
      font-size: 12px;
    }
  }
</style>

<script>
  const seatMap = document.getElementById('seat-map');
  const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
  const cols = 8;

  rows.forEach(row => {
    const rowLabel = document.createElement('div');
    rowLabel.classList.add('row-label');
    rowLabel.innerText = `Hàng ${row}`;
    seatMap.appendChild(rowLabel);

    const rowDiv = document.createElement('div');
    rowDiv.classList.add('d-flex', 'flex-wrap', 'justify-content-center');

    for (let c = 1; c <= cols; c++) {
      const seat = document.createElement('div');
      seat.classList.add('seat');
      seat.innerText = `${row}${c}`;
      seat.dataset.seatId = `${row}${c}`;
      seat.addEventListener('click', () => {
        if (seat.classList.contains('booked') || seat.classList.contains('held')) return;
        seat.classList.toggle('selected');
      });
      rowDiv.appendChild(seat);
    }

    seatMap.appendChild(rowDiv);
  });

  function confirmSeats() {
    document.querySelectorAll('.seat.selected').forEach(seat => {
      seat.classList.remove('selected');
      seat.classList.add('booked');
    });
  }
</script>
@endsection
