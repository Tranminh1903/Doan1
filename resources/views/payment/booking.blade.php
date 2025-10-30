@extends('layouts.app')

@section('title', 'Đặt Vé CGV')

@push('head')

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            prefix: 'tw-',                       
            corePlugins: { preflight: false },   
            important: '#payment-root'           
        }
    </script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="text-center mb-4">Đặt Vé CGV</h3>

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

  <div class="text-center mt-4">
    <div class="mb-3">
      <label for="promo_code" class="form-label fw-bold">Mã khuyến mãi:</label>
      <select id="promo_code" class="form-select d-inline-block w-auto">
        <option value="">-- Chọn mã khuyến mãi --</option>
      </select>
    </div>

    <div class="fw-bold">
      <p>Tổng tiền: <span id="total_price" data-value="0">0</span> VND</p>
      <p>Giảm giá: <span id="discount_amount">0</span> VND</p>
      <h5>Thành tiền: <span id="final_price">0</span> VND</h5>
    </div>
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

  // === Biến toàn cục ===
  let currentDiscount = 0;
  let selectedPromoCode = null;

  // === Tổng tiền ===
  function updateTotal() {
    let total = 0;
    document.querySelectorAll('.seat.selected').forEach(s => {
      total += parseInt(s.dataset.price || 0);
    });

    document.getElementById('total_price').textContent = total.toLocaleString('vi-VN');
    document.getElementById('total_price').setAttribute('data-value', total);

    if (selectedPromoCode) applyPromotion(selectedPromoCode, total);
    else updateFinal(total, 0);
  }

  // === Hàm cập nhật hiển thị tiền ===
  function updateFinal(total, discount) {
    document.getElementById('discount_amount').textContent = discount.toLocaleString('vi-VN');
    document.getElementById('final_price').textContent = (total - discount).toLocaleString('vi-VN');
  }

  // === Tải danh sách khuyến mãi hợp lệ ===
  fetch('/promotion/active')
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('promo_code');
      data.forEach(p => {
        const option = document.createElement('option');
        option.value = p.code;
        option.textContent = `${p.code} - ${p.description}`;
        select.appendChild(option);
      });
    });

  // === Khi chọn mã khuyến mãi ===
  document.getElementById('promo_code').addEventListener('change', function() {
    selectedPromoCode = this.value;
    const total = parseInt(document.getElementById('total_price').getAttribute('data-value')) || 0;

    if (!selectedPromoCode) {
      currentDiscount = 0;
      updateFinal(total, 0);
      return;
    }

    applyPromotion(selectedPromoCode, total);
  });

  // === Gọi API áp dụng khuyến mãi ===
  function applyPromotion(code, total) {
    fetch('/promotion/apply', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ code, total })
    })
    .then(res => res.json())
    .then(data => {
    if (data.success) {
      currentDiscount = data.discount;
      updateFinal(total, data.discount);
    } else {
      alert(data.message);
      document.getElementById('promo_code').value = '';
      currentDiscount = 0;
      updateFinal(total, 0);
    }
    })
    .catch(err => console.error('Lỗi áp mã:', err));
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

    const totalBeforeDiscount = selectedSeats.reduce((sum, s) => sum + parseInt(s.dataset.price || 0), 0);
    const discount = parseInt(document.getElementById('discount_amount').textContent.replace(/\D/g, '')) || 0;
    const totalAmount = totalBeforeDiscount - discount;
    const seatIds = selectedSeats.map(s => s.dataset.seatId);

    fetch("{{ route('orders.create') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
      showtimeID: {{ $showtime->showtimeID ?? 8 }},
      seats: selectedSeats.map(s => s.dataset.seatId),
      amount: totalAmount
      })
    })
    .then(res => res.json())
    .then(data => {
      if (!data.order_code) throw new Error('Không có order_code');

      console.log(" Order created:", data.order_code);
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

    let timeLeft = 300;
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
        .then(data => console.log(" Expired:", data));

        closeQR();
        // Nếu có chọn mã khuyến mãi thì ghi nhận lượt dùng
        if (selectedPromoCode) {
        fetch(`/promotion/mark-used/${selectedPromoCode}`, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
          }).then(() => console.log("Đã ghi nhận lượt dùng mã:", selectedPromoCode));
      }
        alert(" QR hết hạn, vui lòng thử lại!");
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
const showtimeID = "{{ $showtime->showtimeID ?? 8 }}";

    // Hàm gọi API kiểm tra ghế hết hạn
    async function checkExpiredSeats() {
        try {
            const res = await fetch(`/check-expired-seats/${showtimeID}`);
            const data = await res.json();

            if (data.expiredSeats && data.expiredSeats.length > 0) {
                console.log("Ghế hết hạn:", data.expiredSeats);

                // Đổi màu ghế hết hạn về trắng (available)
                data.expiredSeats.forEach(id => {
                const seatEl = document.querySelector(`[data-seat-id="${id}"]`);
  if (seatEl) {
    seatEl.classList.remove('held', 'booked'); 
    seatEl.classList.remove('selected');       
    seatEl.style.backgroundColor = '';         
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
