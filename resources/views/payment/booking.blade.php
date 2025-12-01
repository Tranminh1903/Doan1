@extends('layouts.app')

@section('title', 'Đặt Vé')

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
<div class="container py-4" id="payment-root">
  <h3 class="text-center mb-4">Đặt Vé</h3>

  <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-6 tw-items-start tw-justify-center">
  
  {{-- CỘT TRÁI: Gồm poster + thanh toán --}}
  <div class="tw-w-full lg:tw-w-1/3 tw-flex tw-flex-col tw-space-y-4">

    {{-- KHỐI THÔNG TIN PHIM --}}
    <div class="tw-bg-white tw-rounded-xl tw-shadow tw-p-4 tw-text-center">
      <img src="{{ asset($movie->poster) }}" 
           alt="{{ $movie->title }}" 
           class="img-fluid rounded">
           <p></p>
      <h4 class="tw-font-extrabold tw-text-3xl tw-text-gray-800 tw-uppercase">{{ $movie->title }}</h4>
      <p class="tw-text-sm tw-text-gray-600">Thời lượng: {{ $movie->duration ?? '120' }} phút</p>
    </div>

    {{-- KHỐI THANH TOÁN --}}
    <div class="tw-bg-gray-100 tw-rounded-xl tw-p-5 tw-shadow">
      <h4 class="tw-text-xl tw-font-bold tw-mb-4 tw-text-center">Thanh toán</h4>

      <div class="mb-3">
        <label for="promo_code" class="tw-font-semibold">Mã khuyến mãi:</label>
        <select id="promo_code" class="form-select tw-w-full">
          <option value="">-- Chọn mã khuyến mãi --</option>
        </select>
      </div>

      <div class="tw-font-semibold tw-space-y-2">
        <p>Tổng tiền: <span id="total_price" data-value="0">0</span> VND</p>
        <p>Giảm giá: <span id="discount_amount">0</span> VND</p>
        <h5>Thành tiền: <span id="final_price">0</span> VND</h5>
      </div>

      <div class="tw-text-center tw-mt-4">
        <button id="btn-pay" class="btn btn-danger tw-w-full" onclick="confirmSeats()">Thanh toán</button>
      </div>
    </div>

  </div>

    {{-- Cột phải: Sơ đồ ghế --}}
    <div class="tw-w-full lg:tw-w-2/3 tw-bg-white tw-rounded-xl tw-p-5 tw-shadow">
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

      <h4 class="text-center mb-4">Màn hình</h4>

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
      <div id="seat-map" class="mt-4">
        @foreach($seats as $row => $rowSeats)
          <div class="d-flex flex-wrap justify-content-center mb-5">
            @foreach($rowSeats as $seat)
              <div class="seat 
                {{ $seat->status === 'unavailable' ? 'booked' : '' }} 
                {{ $seat->status === 'held' ? 'held' : '' }}" 
                data-seat-id="{{ $seat->seatID }}"
                data-type="{{ $seat->seatType }}}" 
                data-price="{{ $seat->price }}">
                {{ $seat->verticalRow }}{{ $seat->horizontalRow }}
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
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
// 1) Đảm bảo nút onclick="closeQR()" luôn hoạt động
window.closeQR = function closeQR() {
  const overlay = document.getElementById('overlay');
  if (overlay) overlay.style.display = 'none';
};

document.addEventListener("DOMContentLoaded", () => {
  const seats = document.querySelectorAll('.seat');
  const showtimeID = {{ $showtime->showtimeID ?? 8 }};
  console.log("Initializing booking UI for showtime:", showtimeID);

  // Biến dùng chung
  let currentDiscount = 0;
  let selectedPromoCode = null;
  let countdownTimer = null;
  let checkInterval  = null; // nếu sau này bạn cần poll trạng thái thanh toán

  // --- Hàm tính tiền ---
  function updateFinal(total, discount) {
    document.getElementById('discount_amount').textContent = (discount || 0).toLocaleString('vi-VN');
    document.getElementById('final_price').textContent = (total - (discount || 0)).toLocaleString('vi-VN');
  }

  function updateTotal() {
    let total = 0;
    document.querySelectorAll('.seat.selected').forEach(s => {
      total += parseInt(s.dataset.price || 0, 10);
    });
    const totalEl = document.getElementById('total_price');
    totalEl.textContent = total.toLocaleString('vi-VN');
    totalEl.setAttribute('data-value', String(total));

    if (selectedPromoCode && total > 0) applyPromotion(selectedPromoCode, total);
    else updateFinal(total, 0);
  }

  // --- Tải danh sách mã khuyến mãi ---
  fetch('/promotion/active')
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('promo_code');
      data.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.code;
        opt.textContent = `${p.code} - ${p.description}`;
        select.appendChild(opt);
      });
    });

  // --- Khi chọn mã ---
  document.getElementById('promo_code').addEventListener('change', function() {
    selectedPromoCode = this.value || null;
    const total = parseInt(document.getElementById('total_price').getAttribute('data-value'), 10) || 0;
    if (!selectedPromoCode) {
      currentDiscount = 0;
      updateFinal(total, 0);
      return;
    }
    applyPromotion(selectedPromoCode, total);
  });

  // --- Gửi mã giảm giá đến server ---
  function applyPromotion(code, total) {
  const seatCount = document.querySelectorAll('.seat.selected').length;

  fetch('/promotion/apply', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ code, total, seat_count: seatCount })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        currentDiscount = data.discount;
        updateFinal(total, data.discount);

        //  Toast thông báo nhẹ
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: data.message,
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        });
      } else {
        //  Thông báo lỗi nhẹ, không chặn thao tác
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'warning',
          title: data.message,
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        });

        document.getElementById('promo_code').value = '';
        currentDiscount = 0;
        updateFinal(total, 0);
      }
    })
    .catch(err => {
      console.error('Lỗi áp mã:', err);
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Không thể áp dụng mã khuyến mãi!',
        showConfirmButton: false,
        timer: 2500
      });
    });
}

  // --- Chọn ghế ---
  seats.forEach(seat => {
    seat.addEventListener('click', () => {
      if (seat.classList.contains('booked') || seat.classList.contains('held')) return;
      seat.classList.toggle('selected');
      updateTotal();
    });
  });

  // --- Realtime sơ đồ ghế (có Echo thì mới lắng nghe) ---
  if (window.Echo) {
    window.Echo.channel(`showtime.${showtimeID}`).listen('.SeatStatusUpdated', e => {
      e.seats.forEach(seat => {
        const el = document.querySelector(`[data-seat-id="${seat.seatID}"]`);
        if (!el) return;
        el.classList.remove('selected', 'held', 'booked');
        if (seat.status === 'held')        el.classList.add('held');
        if (seat.status === 'unavailable') el.classList.add('booked');
      });
    });
  }

  // --- Đặt vé & hiển thị QR ---
  window.confirmSeats = async function confirmSeats() {
    const selectedSeats = [...document.querySelectorAll('.seat.selected')];
    if (!selectedSeats.length) return alert('Chưa chọn ghế!');

    const totalAmount = selectedSeats.reduce((sum, s) => sum + parseInt(s.dataset.price || 0, 10), 0) - (currentDiscount || 0);
    const seatIds = selectedSeats.map(s => s.dataset.seatId);

    try {
      const res  = await fetch("{{ route('orders.create') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ showtimeID, seats: seatIds, amount: totalAmount,promotion_code: selectedPromoCode })
      });

      const data = await res.json();
      if (!data.order_code) throw new Error('Không có order_code');

      // Đánh dấu UI: đang giữ chỗ
      selectedSeats.forEach(s => s.classList.replace('selected','held'));

      // Khoá nút thanh toán trong lúc chờ
      document.getElementById('btn-pay').disabled = true;

      // Mở QR và gắn listener thanh toán (chỉ sau khi có orderCode)
      show_qr(data.order_code, seatIds, totalAmount);

    } catch (e) {
      alert('Lỗi đặt vé: ' + e.message);
    }
  };

  window.show_qr = function show_qr(orderCode, seats, amount) {
    const bankCode = "MB";
    const accountNo = "0869083080";
    const accountName = "TRAN VAN HUNG MINH EM";

    const qrUrl = `https://img.vietqr.io/image/${bankCode}-${accountNo}-compact2.png?amount=${amount}&addInfo=${orderCode}&accountName=${encodeURIComponent(accountName)}`;

    document.getElementById('qr_image').src = qrUrl;
    document.getElementById('overlay').style.display = 'flex';
    document.getElementById('total-amount').textContent = amount.toLocaleString('vi-VN');

    let timeLeft = 300;
    const countdown = document.getElementById('countdown');
    const payBtn = document.getElementById('btn-pay');

    // Reset đếm ngược nếu đang chạy
    clearInterval(countdownTimer);
    countdownTimer = setInterval(() => {
      timeLeft--;
      countdown.innerText = `Còn ${timeLeft}s`;

      if (timeLeft <= 0) {
        clearInterval(countdownTimer);
        if (checkInterval) clearInterval(checkInterval);

        // Trả ghế về trạng thái available
        seats.forEach(id => {
          const el = document.querySelector(`[data-seat-id="${id}"]`);
          if (el) el.classList.remove('selected', 'held');
        });

        payBtn.disabled = false;

        fetch(`/orders/${orderCode}/expire`, {
          method: "POST",
          headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
        }).then(res => res.json()).then(data => console.log("Expired:", data));

        closeQR();
        alert("QR hết hạn, vui lòng thử lại!");
      }
    }, 1000);
    // Sau khi show QR
// ======= Cấu trúc polling tối ưu =======
let pollController = null;
if (window.checkInterval) clearInterval(window.checkInterval);

// Gắn toàn cục để quản lý (có thể dừng ở nơi khác)
window.checkInterval = setInterval(async () => {
  // Nếu Echo bắt được rồi thì dừng polling
  if (window.orderPaid) return;

  try {
    if (pollController) pollController.abort(); // hủy request cũ
    pollController = new AbortController();
    const signal = pollController.signal;

    const res = await fetch(`/orders/check-sync/${orderCode}`, { signal });
    const data = await res.json();

    if (data.status === 'paid') {
      //  Dừng tất cả timer
      clearInterval(countdownTimer);
      clearInterval(window.checkInterval);
      pollController.abort();

      // Cập nhật trạng thái UI (animation mượt)
      seats.forEach(id => {
        const el = document.querySelector(`[data-seat-id="${id}"]`);
        if (el) {
          requestAnimationFrame(() => {
            el.classList.remove('held', 'selected');
            el.classList.add('booked');
          });
        }
      });

      Swal.fire({
        icon: 'success',
        title: 'Thanh toán thành công!',
        timer: 1500,
        showConfirmButton: false
      });

      closeQR();
      document.getElementById('btn-pay').disabled = false;
      window.orderPaid = true; // đánh dấu đã thanh toán
    }
  } catch (e) {
    if (e.name !== 'AbortError') console.error('Polling error:', e);
  }
}, 12000); // mỗi 12 giây/lần



    // Lắng nghe đơn hàng đã thanh toán (nếu có Echo)
    if (window.Echo) {
  window.Echo.channel(`order.${orderCode}`).listen('OrderPaid', e => {
    window.orderPaid = true;
    if (window.checkInterval) clearInterval(window.checkInterval);
    if (pollController) pollController.abort();

    clearInterval(countdownTimer);

    e.seats.forEach(id => {
      const el = document.querySelector(`[data-seat-id="${id}"]`);
      if (!el) return;
      requestAnimationFrame(() => {
        el.classList.remove('selected', 'held');
        el.classList.add('booked');
      });
    });

    Swal.fire({
      icon: 'success',
      title: 'Thanh toán thành công!',
      timer: 1500,
      showConfirmButton: false
    });

    document.getElementById('btn-pay').disabled = false;
    closeQR();

    if (selectedPromoCode) {
      fetch(`/promotion/mark-used/${selectedPromoCode}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
      }).then(() => console.log("Đã ghi nhận lượt dùng mã:", selectedPromoCode));
    }
  });
}

  };

  // --- Poll ghế hết hạn (nằm trong DOMContentLoaded để dùng showtimeID) ---
  async function checkExpiredSeats() {
    try {
      const res = await fetch(`/check-expired-seats/${showtimeID}`);
      const data = await res.json();
      if (data.expiredSeats && data.expiredSeats.length > 0) {
        console.log("Ghế hết hạn:", data.expiredSeats);
        data.expiredSeats.forEach(id => {
          const seatEl = document.querySelector(`[data-seat-id="${id}"]`);
          if (seatEl) {
            seatEl.classList.remove('held', 'booked', 'selected');
            seatEl.style.backgroundColor = '';
          }
        });
      }
    } catch (err) {
      console.error("Lỗi khi check ghế hết hạn:", err);
    }
  }
  checkExpiredSeats();
  setInterval(checkExpiredSeats, 20000);
});
</script>

@endsection
