<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã khuyến mãi (vd: SALE10)
            $table->enum('type', ['percent', 'fixed']); // Loại giảm giá
            $table->decimal('value', 10, 2); // Giá trị giảm (VD: 10% hoặc 50000đ)
            $table->integer('limit_count')->default(0); // Giới hạn lượt dùng
            $table->integer('used_count')->default(0); // Số lượt đã dùng
            $table->decimal('min_order_value', 10, 2)->nullable(); // Giá trị đơn hàng tối thiểu để áp dụng
            $table->integer('min_ticket_quantity')->nullable(); // Số ghế tối thiểu để áp dụng
            $table->dateTime('start_date'); // Ngày bắt đầu hiệu lực
            $table->dateTime('end_date'); // Ngày kết thúc hiệu lực
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->text('description')->nullable(); // Mô tả ngắn cho mã
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion');
    }
};
