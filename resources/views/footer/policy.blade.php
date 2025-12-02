@extends('layouts.app') 

{{-- Đặt tiêu đề cho trang --}}
@section('title', 'Chính Sách và Quy Định') 

@push('styles')
<style>
    /* ************************************** */
    /* KHẮC PHỤC NỀN FULLSCREEN VÀ PHONG CÁCH CHUNG */
    /* ************************************** */
    body, html {
        height: 100%;
        width: 100%;
        margin: 0;
        background: #020617 !important; 
    }

    .policy-wrapper {
        min-height: 100vh;
        width: 100%;
        padding: 60px 0;
        /* Áp dụng gradient nền đồng bộ */
        background:
            radial-gradient(circle at top, rgba(56,189,248,0.35), transparent 55%),
            radial-gradient(circle at bottom, rgba(248,250,252,0.08), transparent 60%),
            #020617; 
        color: #e5e7eb;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    /* Tiêu đề chính kiểu neon nhẹ */
    .policy-wrapper h2 {
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

    /* ************************************** */
    /* GLASS CARD VÀ NỘI DUNG CHÍNH SÁCH */
    /* ************************************** */
    .policy-card {
        /* Glassmorphism Effect */
        background: rgba(15, 23, 42, 0.92);
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.4);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(18px);
        
        padding: 40px; /* Padding lớn cho nội dung */
        margin-bottom: 40px;
    }

    /* Tiêu đề mục (h3, h4) */
    .policy-card h3 {
        color: #38bdf8; /* Màu xanh dương cho tiêu đề mục chính */
        font-weight: 700;
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 1.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        padding-bottom: 8px;
    }
    .policy-card h4 {
        color: #facc15; /* Màu vàng cam cho tiêu đề mục phụ */
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }

    /* Định dạng văn bản chính sách */
    .policy-card p {
        color: #dce3ea;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .policy-card ul {
        color: #dce3ea;
        margin-left: 20px;
        margin-bottom: 20px;
    }

    .policy-card li {
        margin-bottom: 8px;
        line-height: 1.7;
    }
    
    .policy-card strong {
        color: #f1f5f9; /* Làm nổi bật các từ khóa quan trọng */
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .policy-wrapper {
            padding: 40px 0;
        }

        .policy-wrapper h2 {
            font-size: 1.9rem;
            margin-bottom: 24px !important;
        }

        .policy-card {
            padding: 20px;
        }
        .policy-card h3 {
             font-size: 1.3rem;
        }
    }
</style>
@endpush

@section('content')
    
    <div class="policy-wrapper"> 
        {{-- Sử dụng lớp container để giới hạn nội dung --}}
        <div class="container"> 
            
            <h2 class="text-center mb-5">Chính Sách & Quy Định</h2>
            
            <div class="policy-card">
                
                <h3>1. Chính Sách Thanh Toán</h3>
                <p>Khách hàng có thể thanh toán vé xem phim và các dịch vụ đi kèm bằng nhiều phương thức bao gồm thẻ tín dụng/ghi nợ (Visa, Mastercard), ví điện tử (Momo, ZaloPay), và chuyển khoản ngân hàng qua mã QR.</p>
                <p>Tất cả các giao dịch thanh toán phải được hoàn tất <strong>trước 15 phút</strong> so với giờ chiếu đã đặt.</p>

                <h4>1.1. Giá vé và Phí dịch vụ</h4>
                <p>Giá vé được niêm yết công khai trên website và có thể thay đổi tùy thuộc vào suất chiếu (giờ vàng, ngày cuối tuần), loại rạp (IMAX, 4DX) và độ tuổi người xem. Mọi phí dịch vụ (nếu có) sẽ được thông báo rõ ràng trước khi thanh toán.</p>

                <h3>2. Chính Sách Hoàn/Hủy Vé và Đổi Suất Chiếu</h3>
                <p>DMM Cinema có các quy định rõ ràng về việc hoàn, hủy hoặc đổi vé đã mua:</p>
                <ul>
                    <li><strong>Hoàn/Hủy vé:</strong> Vé đã mua <strong>không được hoàn lại</strong> dưới bất kỳ hình thức nào. Khách hàng cần kiểm tra kỹ thông tin trước khi xác nhận thanh toán.</li>
                    <li><strong>Đổi suất chiếu:</strong> Chỉ chấp nhận đổi suất chiếu sang ngày khác hoặc giờ khác nếu yêu cầu được gửi <strong>ít nhất 60 phút</strong> trước giờ chiếu ban đầu và có sẵn vé cho suất chiếu mới. Khách hàng phải trả phí dịch vụ đổi vé là <strong>10%</strong> giá trị vé.</li>
                    <li><strong>Lỗi kỹ thuật:</strong> Trường hợp suất chiếu bị hủy do lỗi kỹ thuật hoặc sự cố bất khả kháng từ phía rạp, khách hàng sẽ được hoàn lại 100% giá vé hoặc đổi sang suất chiếu khác.</li>
                </ul>
                
                <h3>3. Quy Định Về Độ Tuổi và Phân Loại Phim</h3>
                <p>Khách hàng phải tuân thủ nghiêm ngặt các quy định phân loại phim theo chuẩn Cục Điện ảnh Việt Nam:</p>
                <ul>
                    <li>Phim **P** (Phổ biến): Dành cho mọi lứa tuổi.</li>
                    <li>Phim **K** (Khán giả dưới 13 tuổi phải có người giám hộ): Trẻ em dưới 13 tuổi cần đi cùng bố mẹ hoặc người lớn giám hộ.</li>
                    <li>Phim **T16** (Khán giả từ đủ 16 tuổi trở lên): Khách hàng phải xuất trình giấy tờ tùy thân nếu được yêu cầu.</li>
                    <li>Phim **T18** (Khán giả từ đủ 18 tuổi trở lên): Nghiêm cấm trẻ vị thành niên dưới 18 tuổi vào xem.</li>
                </ul>

                <h3>4. Chính Sách Bảo Mật Thông Tin Khách Hàng</h3>
                <p>Chúng tôi cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng. Thông tin được thu thập chỉ phục vụ cho việc đặt vé, thanh toán và cung cấp dịch vụ chăm sóc khách hàng. Dữ liệu sẽ không được chia sẻ với bên thứ ba trừ khi có yêu cầu từ cơ quan pháp luật.</p>
                <p>Tất cả thông tin thanh toán (số thẻ, mã bảo mật) đều được mã hóa theo tiêu chuẩn quốc tế và không được lưu trữ trên hệ thống của DMM Cinema.</p>

            </div>
        </div>
    </div>
    
@endsection