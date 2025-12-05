@extends('layouts.app') 
@section('title', 'Giới Thiệu Về DMM Cinema') 

@section('content')
    
    <div class="about-wrapper"> 
        {{-- Sử dụng lớp container để giới hạn nội dung --}}
        <div class="container"> 
            
            <h2 class="text-center mb-5">Về DMM Cinema</h2>
            
            {{-- HÀNG 1: GIỚI THIỆU CHUNG (Chiếm hết chiều ngang) --}}
            <div class="row">
                <div class="col-12">
                    <div class="glass-card">
                        <h3>Trải Nghiệm Điện Ảnh Tối Thượng</h3>
                        <p>
                            **DMM Cinema** không chỉ là một rạp chiếu phim; chúng tôi là một điểm đến giải trí nơi công nghệ hiện đại gặp gỡ đam mê điện ảnh. Với hệ thống phòng chiếu tiên tiến, âm thanh sống động chuẩn **Dolby Atmos**, và màn hình lớn rực rỡ, chúng tôi cam kết mang lại trải nghiệm xem phim chân thực và đáng nhớ nhất cho mọi khán giả.
                        </p>
                        <p>
                            Kể từ khi thành lập, sứ mệnh của chúng tôi là trở thành cầu nối mang những tác phẩm điện ảnh xuất sắc từ khắp nơi trên thế giới đến với công chúng Việt Nam, đồng thời xây dựng một cộng đồng yêu phim văn minh và hiện đại.
                        </p>
                        <p>
                            Hãy đến và khám phá sự khác biệt tại DMM Cinema – nơi mỗi lần ghé thăm là một chuyến phiêu lưu mới.
                        </p>
                    </div>
                </div>
            </div>

            {{-- HÀNG 2: TẦM NHÌN & GIÁ TRỊ CỐT LÕI (Chia 3 cột) --}}
            <div class="row g-4">
                {{-- Đảm bảo các card có chiều cao bằng nhau --}}
                <div class="col-md-4 d-flex">
                    <div class="glass-card flex-fill">
                        <h4>Tầm Nhìn</h4>
                        <p>Trở thành chuỗi rạp chiếu phim được yêu thích và tin cậy nhất Việt Nam, dẫn đầu về công nghệ trình chiếu và dịch vụ khách hàng xuất sắc.</p>
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="glass-card flex-fill">
                        <h4>Giá Trị Cốt Lõi</h4>
                        <p>Chúng tôi hoạt động dựa trên ba giá trị: **Đổi Mới Công Nghệ**, **Tôn Trọng Cộng Đồng**, và **Chất Lượng Dịch Vụ**. Mỗi quyết định đều hướng đến việc nâng cao trải nghiệm khách hàng.</p>
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="glass-card flex-fill">
                        <h4>Cam Kết Khách Hàng</h4>
                        <p>Cung cấp môi trường xem phim an toàn, sạch sẽ, thoải mái. Luôn lắng nghe phản hồi và không ngừng cải tiến để vượt qua mong đợi của khán giả.</p>
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

    .about-wrapper {
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

    .about-wrapper h2 {
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
    
    .glass-card {
        background: rgba(15, 23, 42, 0.92);
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.4);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(18px);
        
        padding: 30px; 
        margin-bottom: 30px;
        height: 100%; 
    }

    .glass-card h3 {
        color: #38bdf8; 
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 1.6rem;
    }
    .glass-card h4 {
        color: #facc15; 
        font-weight: 600;
        margin-top: 15px;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }

    .glass-card p {
        color: #dce3ea;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 1rem;
    }
    
    .glass-card strong {
        color: #f1f5f9;
        font-weight: 700;
    }

    @media (max-width: 767px) {
        .about-wrapper {
            padding: 40px 0;
        }

        .about-wrapper h2 {
            font-size: 1.9rem;
            margin-bottom: 24px !important;
        }

        .glass-card {
            padding: 20px;
        }
    }
</style>
@endpush