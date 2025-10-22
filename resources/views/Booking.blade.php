@extends('layouts.app')

@section('title', 'Đặt Vé CGV')

@push('head')
    <!-- ✅ Load Tailwind trước -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Config Tailwind sau khi load -->
    <script>
        tailwind.config = {
            prefix: 'tw-',                       // Thêm tiền tố để không đụng bootstrap
            corePlugins: { preflight: false },   // Tắt reset mặc định
            important: '#payment-root'           // Giới hạn phạm vi
        }
    </script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="text-center mb-4">🎬 Đặt Vé CGV</h3>

  {{-- Legend --}}
  <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center">
      <div style="width:20px;height:20px;background-color:#ccc;border-radius:4px;margin-right:8px;"></div>
      <span>Ghế trống</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width:20px;height:20px;background-color:limegreen;border-radius:4px;margin-right:8px;"></div>
      <span>Ghế đang chọn</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width:20px;height:20px;background-color:gold;border-radius:4px;margin-right:8px;"></div>
      <span>Được giữ chỗ</span>
    </div>
    <div class="d-flex align-items-center">
      <div style="width:20px;height:20px;background-color:red;border-radius:4px;margin-right:8px;"></div>
      <span>Đã đặt trước</span>
    </div>
  </div>

  <h4 class="text-center mb-4">Giờ chiếu:</h4>

  <div style="
    border:4px solid #333;
    border-radius:12px;
    padding:16px 32px;
    background-color:#f8f9fa;
    font-weight:bold;
    font-size:28px;
    text-align:center;
    width:100%;
    max-width:600px;
    margin:0 auto;">
    MÀN HÌNH
  </div>

  {{-- Seat map --}}
  <div id="seat-map" class="mb-4">
    @foreach($seats as $row => $rowSeats)
      <div class="row-label">Hàng {{ $row }}</div>
      <div class="d-flex flex-wrap justify-content-center mb-2">
        @foreach($rowSeats as $seat)
          <div class="seat 
            {{ $seat->status === 'unavailable' ? 'booked' : '' }} 
            {{ $seat->status === 'held' ? 'held' : '' }}" 
            data-seat-id="{{ $seat->seatID }}"
            data-type="{{ $seat->type }}" 
            data-price="{{ $seat->type === 'vip' ? 3000 : ($seat->type === 'couple' ? 3000 : 2000) }}">
            {{ $seat->verticalRow }}{{ $seat->horizontalRow }}
          </div>
        @endforeach
      </div>
    @endforeach
  </div>

  <div class="text-center">
    <button class="btn btn-danger" onclick="confirmSeats()">Thanh toán</button>
  </div>
</div>

{{-- Overlay QR --}}
<div id="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:9998;">
  <div class="bg-white rounded p-4 text-center" style="max-width:300px;">
    <strong class="mb-2 d-block">Quét mã để thanh toán</strong>
    <img id="qr_image" src="" alt="qr_code" style="max-width:200px;">
    <div id="countdown" class="mt-2 text-danger fw-bold"></div>
    <div class="text-center my-3">
      <h5>Tổng tiền: <span id="total-amount">0</span> VND</h5>
    </div> 
    <button class="btn btn-secondary mt-3" onclick="closeQR()">Hủy</button>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  .seat {
    width:40px;height:40px;margin:4px;
    background-color:#ccc;border-radius:5px;
    text-align:center;line-height:40px;
    font-weight:bold;cursor:pointer;
    transition:background-color .3s;
  }
  .seat.selected {background-color:limegreen;}
  .seat.held {background-color:gold;}
  .seat.booked {background-color:red;cursor:not-allowed;}
  .row-label {width:100%;text-align:left;font-weight:bold;margin-top:10px;}
  @media(max-width:576px){
    .seat{width:32px;height:32px;line-height:32px;font-size:12px;}
  }
</style>
@vite(['resources/js/app.js'])
<script>
document.addEventListener("DOMContentLoaded", () => {
  const seats = document.querySelectorAll('.seat');
  const totalDisplay = document.getElementById('total-amount');
  const showtimeID = {{ $showtime->showtimeID ?? 8 }};
  
  console.log(" Initializing booking UI for showtime:", showtimeID);

  // === Tổng tiền ===
  function updateTotal() {
    let total = 0;
    seats.forEach(s => {
      if (s.classList.contains('selected')) {
        total += parseInt(s.dataset.price || 0);
      }
    });
    totalDisplay.textContent = total.toLocaleString('vi-VN');
  }

  // === Click chọn ghế ===
  seats.forEach(seat => {
    seat.addEventListener('click', () => {
      if (seat.classList.contains('booked') || seat.classList.contains('held')) return;
      seat.classList.toggle('selected');
      updateTotal();
    });
  });

  // === Khởi tạo realtime ===
  if (window.initSeatRealtime) {
    window.initSeatRealtime(showtimeID);
  }

  // === Các hàm đặt vé, QR, check thanh toán ===
  window.confirmSeats = function() {
    const selectedSeats = [...document.querySelectorAll('.seat.selected')];
    if (!selectedSeats.length) return alert('Chưa chọn ghế!');

    const totalAmount = selectedSeats.reduce((sum, s) => sum + parseInt(s.dataset.price || 0), 0);
    const seatIds = selectedSeats.map(s => s.dataset.seatId);

    fetch("{{ route('orders.create') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        showtimeID,
        seats: seatIds,
        amount: totalAmount
      })
    })
    .then(res => res.json())
    .then(data => {
      if (!data.order_code) throw new Error('Không có order_code');

      console.log("🧾 Order created:", data.order_code);
      selectedSeats.forEach(s => {
        s.classList.remove('selected');
        s.classList.add('held');
      });
      document.querySelector('button[onclick="confirmSeats()"]').disabled = true;
      show_qr(data.order_code, seatIds, totalAmount);
      startPolling(data.order_code, seatIds);
    })
    .catch(e => alert('Lỗi đặt vé: ' + e.message));
  };

  window.show_qr = function(orderCode, seats, amount) {
    const bankCode = "MB";
    const accountNo = "0869083080";
    const accountName = "TRAN VAN HUNG MINH EM";

    const qrUrl = `https://img.vietqr.io/image/${bankCode}-${accountNo}-compact2.png?amount=${amount}&addInfo=${orderCode}&accountName=${encodeURIComponent(accountName)}`;

    document.getElementById('qr_image').src = qrUrl;
    document.getElementById('overlay').style.display = 'flex';
    document.getElementById('total-amount').textContent = amount.toLocaleString('vi-VN');

    let timeLeft = 30;
    const countdown = document.getElementById('countdown');
    const button = document.querySelector('button[onclick="confirmSeats()"]');

    countdownTimer = setInterval(() => {
      timeLeft--;
      countdown.innerText = `Còn ${timeLeft}s`;

      if (timeLeft <= 0) {
        clearInterval(countdownTimer);
        clearInterval(checkInterval);

        seats.forEach(id => {
          const el = document.querySelector(`[data-seat-id="${id}"]`);
          if (el) el.classList.remove('selected', 'held');
        });
        button.disabled = false;

        fetch(`/orders/${orderCode}/expire`, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          }
        })
        .then(res => res.json())
        .then(data => console.log("⏰ Expired:", data));

        closeQR();
        alert("❌ QR hết hạn, vui lòng thử lại!");
      }
    }, 1000);
  };

  window.startPolling = function(orderCode, seats) {
    checkInterval = setInterval(() => {
      fetch("/sync-payments")
        .then(() => fetch(`/check-payment/${orderCode}`))
        .then(res => res.json())
        .then(data => {
          if (data.status === 'paid') {
            clearInterval(checkInterval);
            clearInterval(countdownTimer);
            seats.forEach(id => {
              const el = document.querySelector(`[data-seat-id="${id}"]`);
              if (el) {
                el.classList.remove('selected', 'held');
                el.classList.add('booked');
              }
            });
            document.querySelector('button[onclick="confirmSeats()"]').disabled = false;
            closeQR();
          }
        });
    }, 3000);
  };

  window.closeQR = function() {
    document.getElementById('overlay').style.display = 'none';
  };
});
const showtimeID = "{{ $showtimeID }}";

    // Hàm gọi API kiểm tra ghế hết hạn
    async function checkExpiredSeats() {
        try {
            const res = await fetch(`/check-expired-seats/${showtimeID}`);
            const data = await res.json();

            if (data.expiredSeats && data.expiredSeats.length > 0) {
                console.log("Ghế hết hạn:", data.expiredSeats);

                // Đổi màu ghế hết hạn về trắng (available)
                data.expiredSeats.forEach(id => {
                    const seatEl = document.querySelector(`#seat-${id}`);
                    if (seatEl) {
                        seatEl.classList.remove('bg-yellow-400', 'cursor-not-allowed');
                        seatEl.classList.add('bg-white', 'hover:bg-green-200', 'cursor-pointer');
                    }
                });
            }
        } catch (err) {
            console.error("Lỗi khi check ghế hết hạn:", err);
        }
    }

    // Gọi lần đầu
    checkExpiredSeats();

    // Gọi lại mỗi 5 giây
    setInterval(checkExpiredSeats, 5000);

</script>
@endsection
