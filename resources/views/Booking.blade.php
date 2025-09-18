  @push('head')
  <script>
    tailwind.config = {
      prefix: 'tw-',                      
      corePlugins: { preflight: false },  
      important: '#payment-root'          
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
@endpush

  @extends('layouts.app')
  @section('title', 'Đặt Vé CGV')
  @section('content')
  <div class="container py-4">
    <h3 class="text-center mb-4">🎬 Đặt Vé CGV</h3>

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

    <h4 class="text-center mb-4">Giờ chiếu: </h4>

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

    <div id="seat-map" class="mb-4"></div>

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
      <button class="btn btn-secondary mt-3" onclick="closeQR()">Hủy</button>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    .seat {width:40px;height:40px;margin:4px;background-color:#ccc;border-radius:5px;text-align:center;line-height:40px;font-weight:bold;cursor:pointer;transition:background-color .3s;}
    .seat.selected {background-color:limegreen;}
    .seat.held {background-color:gold;}
    .seat.booked {background-color:red;cursor:not-allowed;}
    .row-label {width:100%;text-align:left;font-weight:bold;margin-top:10px;}
    @media(max-width:576px){.seat{width:32px;height:32px;line-height:32px;font-size:12px;}}
  </style>

  <script>
  const seatMap = document.getElementById('seat-map');
  const rows = ['A','B','C','D','E','F','G','H'];
  const cols = 8;

  rows.forEach(row=>{
    const rowLabel=document.createElement('div');
    rowLabel.classList.add('row-label');
    rowLabel.innerText=`Hàng ${row}`;
    seatMap.appendChild(rowLabel);

    const rowDiv=document.createElement('div');
    rowDiv.classList.add('d-flex','flex-wrap','justify-content-center');

    for(let c=1;c<=cols;c++){
      const seat=document.createElement('div');
      seat.classList.add('seat');
      seat.innerText=`${row}${c}`;
      seat.dataset.seatId=`${row}${c}`;
      seat.addEventListener('click',()=>{
        if(seat.classList.contains('booked')||seat.classList.contains('held')) return;
        seat.classList.toggle('selected');
      });
      rowDiv.appendChild(seat);
    }
    seatMap.appendChild(rowDiv);
  });

  let checkInterval, countdownTimer;

function confirmSeats(){
  const selectedSeats = [...document.querySelectorAll('.seat.selected')].map(s=>s.dataset.seatId);
  if (!selectedSeats.length) { alert('Chưa chọn ghế!'); return; }

  fetch("{{ route('orders.create') }}", {     // dùng route helper, tránh sai path
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ seats: selectedSeats, amount: 1000 })
  })
  .then(async res => {
    const ct = res.headers.get('content-type') || '';
    const body = ct.includes('application/json') ? await res.json() : { raw: await res.text() };
    if (!res.ok) throw new Error(`HTTP ${res.status} ${JSON.stringify(body)}`);
    return body;
  })
  .then(data => {
  if (data.order_code) {
    // đổi ghế đang chọn thành "held" (vàng)
    selectedSeats.forEach(id => {
      const el = document.querySelector(`[data-seat-id="${id}"]`);
      if (el) {
        el.classList.remove('selected');
        el.classList.add('held');
      }
    });

    // disable toàn bộ ghế
    document.querySelectorAll('.seat').forEach(seat => {
      seat.style.pointerEvents = 'none';
    });

    // disable nút thanh toán
    document.querySelector('button[onclick="confirmSeats()"]').disabled = true;

    // show QR và bắt đầu countdown
    show_qr(data.order_code, selectedSeats);
    startPolling(data.order_code, selectedSeats);
  } else {
    alert('Server không trả order_code. Trả về: ' + JSON.stringify(data));
  }
})

  .catch(e => alert('Tạo order lỗi: ' + e.message));
}

  function show_qr(orderCode,seats){
    const bankCode="MB";
    const accountNo="0869083080";
    const accountName="NGUYEN VAN A";
    const info=orderCode;
    const total_amount=2000;

    const qrUrl=`https://img.vietqr.io/image/${bankCode}-${accountNo}-compact2.png?amount=${total_amount}&addInfo=${encodeURIComponent(info)}&accountName=${encodeURIComponent(accountName)}`;
    document.getElementById('qr_image').src=qrUrl;
    document.getElementById('overlay').style.display='flex';

    let timeLeft=50;
    const countdown=document.getElementById('countdown');
    countdownTimer=setInterval(()=>{
      timeLeft--;
      countdown.innerText=`Còn ${timeLeft}s`;

      if(timeLeft <= 0){
  clearInterval(countdownTimer);
  clearInterval(checkInterval);

  // reset ghế
  seats.forEach(id=>{
    const el=document.querySelector(`[data-seat-id="${id}"]`);
    if(el){el.classList.remove('selected','held');}
  });

  // mở lại tất cả ghế
  document.querySelectorAll('.seat').forEach(seat => {
    seat.style.pointerEvents = 'auto';
  });

  // bật lại nút thanh toán
  document.querySelector('button[onclick="confirmSeats()"]').disabled = false;

  fetch(`/orders/${orderCode}/expire`, {
    method:"POST",
    headers:{
      "X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type":"application/json"
    }
  });
  closeQR();
  alert("❌ QR hết hạn, vui lòng thử lại!");
}

    },1000);
  }

  function startPolling(orderCode,seats){
    checkInterval=setInterval(()=>{
      fetch("/sync-payments")
        .then(()=>fetch(`/check-payment/${orderCode}`))
        .then(res=>res.json())
        .then(data=>{
          if(data.status==='paid'){
  clearInterval(checkInterval);
  clearInterval(countdownTimer);

  seats.forEach(id=>{
    const el=document.querySelector(`[data-seat-id="${id}"]`);
    if(el){
      el.classList.remove('selected','held');
      el.classList.add('booked'); // đỏ
    }
  });

  // mở lại các ghế khác (chỉ còn đỏ là khóa)
  document.querySelectorAll('.seat').forEach(seat => {
    if(!seat.classList.contains('booked')){
      seat.style.pointerEvents = 'auto';
    }
  });

  // bật lại nút thanh toán
  document.querySelector('button[onclick="confirmSeats()"]').disabled = false;

  closeQR();
}

        })
        .catch(err=>console.error(err));
    },3000);
  }

  function closeQR(){
    document.getElementById('overlay').style.display='none';
  }
  </script>
  @endsection
