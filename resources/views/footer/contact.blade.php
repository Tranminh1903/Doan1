@extends('layouts.app') 
@section('title', 'Liên hệ DMM Cinema') 

@section('content')
    
    <div class="contact-wrapper"> 
    {{-- Sử dụng lớp container để căn giữa nội dung --}}
    <div class="container contact-container"> 
        
        <h2 class="text-center mb-5" style="font-weight: 700;">Liên Hệ</h2>
        
        {{-- ĐÃ THÊM CLASS 'gx-4' VÀO ROW ĐỂ TẠO KHOẢNG CÁCH NGANG GIỮA CÁC CỘT (Bootstrap Gap) --}}
        <div class="row gx-4"> 
            
            {{-- Cột 1: Thông tin liên hệ --}}
            <div class="col-md-5">
                <div class="contact-info">
                    
                    <h5>TRỤ SỞ</h5>
                    <p>209 Đ. 30 tháng 4, Xuân Khánh, Ninh Kiều, Cần Thơ</p>
                    
                    <h5>HỖ TRỢ KHÁCH HÀNG</h5>
                    <p>Hotline: 0869083090</p>
                    <p>Zalo: 0869083090</p>
                    <p>Giờ làm việc: 8:00 - 22:00</p>
                    <p>Tất cả các ngày bao gồm cả Lễ Tết</p>
                    <p>Email hỗ trợ: <a href="Emailto:tranminh19304@gmail.com">tranminh19304@gmail.com</a></p>
                    
                    <h5>LIÊN HỆ QUẢNG CÁO, TỔ CHỨC SỰ KIỆN, THUÊ RẠP</h5>
                    <p>Phòng dịch vụ</p>
                    <p>Hotline: 0869083789</p>
                    <p>Email: <a href="Emailto:huynhduyman2005tvc@gmail.com">huynhduyman2005tvc@gmail.com</a></p>

                    <h5>LIÊN HỆ MUA VÉ HỢP ĐỒNG</h5>
                    <p>Phòng Chiếu Phim và Trung tâm Dịch vụ Điện ảnh</p>
                    <p>Hotline: 0869083581</p>
                    <p>Email: <a href="Emailto:tuitendu@gmail.com">tuitendu@gmail.com</a></p>
                </div>
            </div>

            {{-- Cột 2: Bản đồ --}}
            <div class="col-md-7">
                <div class="map-container">
                    <iframe 
                        {{-- Đường dẫn nhúng bản đồ --}}
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7857.624639334254!2d105.77102909463821!3d10.032340975115284!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a08820f089974b%3A0x421d4098ab65e25d!2sAdidas%20Vincom%20Xu%C3%A2n%20Kh%C3%A1nh!5e0!3m2!1svi!2s!4v1751531421432!5m2!1svi!2s"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection

@push('styles')
<style>
    body, html {
        height: 100%;
        width: 100%;
        margin: 0;
        background: #020617 !important; 
    }

    .contact-wrapper {
        min-height: 100vh;
        width: 100%;
        padding: 60px 0;
        background:
            radial-gradient(circle at top, rgba(56,189,248,0.35), transparent 55%),
            radial-gradient(circle at bottom, rgba(248,250,252,0.08), transparent 60%),
            #020617; 
        color: #e5e7eb;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .contact-container {
        max-width: 1140px;
    }

    .contact-wrapper h2 {
        color: #f1f5f9;
        margin-bottom: 40px !important;
        font-size: 2.4rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        text-shadow:
            0 0 12px rgba(56,189,248,0.55),
            0 0 18px rgba(56,189,248,0.28);
    }

    .contact-wrapper h5 {
        color: #f1f5f9;
        margin-top: 20px;
        margin-bottom: 8px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 0.98rem;
        letter-spacing: 1px;
    }

    .contact-wrapper p {
        color: #dce3ea !important;
        margin-bottom: 4px;
        line-height: 1.7;
        font-size: 0.98rem;
    }

    .contact-wrapper p span {
        color: #dce3ea !important;
        font-weight: 600;
    }

    .contact-container .row {
        align-items: stretch;
    }

    .contact-info,
    .map-container {
        background: rgba(15, 23, 42, 0.92);
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.4);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(18px);
        padding: 24px 22px;
    }
    
    .contact-info a {
        color: #38bdf8;
        text-decoration: none;
        display: inline-block;
        transition: color 0.2s ease, transform 0.15s ease;
        margin-left: 4px;
        font-weight: 500;
    }

    .contact-info a:hover {
        color: #facc15;
        text-decoration: underline;
        transform: translateY(-1px);
    }

    /* MAP */
    .map-container {
        height: 100%;
        min-height: 400px;
        padding: 0; 
        overflow: hidden;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border-radius: 0.9rem; 
        border: 1px solid rgba(148, 163, 184, 0.4); 
    }

    /* RESPONSIVE MOBILE */
    @media (max-width: 767px) {
        .contact-wrapper {
            padding: 40px 0;
        }

        .contact-wrapper h2 {
            font-size: 1.9rem;
            margin-bottom: 24px !important;
        }

        .contact-info,
        .map-container {
            padding: 18px 16px;
            margin-bottom: 18px;
        }

        .map-container,
        .map-container iframe {
            min-height: 320px;
        }
    }
</style>
@endpush