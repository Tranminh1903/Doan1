<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers_promotions', function (Blueprint $table) {
            $table->id();
            // Khách hàng sở hữu voucher → FK TỚI customers(id)
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            // Tham chiếu chương trình khuyến mãi
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            // Lưu đơn đã dùng voucher (nếu có)
            $table->foreignId('order_id_used')->nullable()->constrained('orders')->nullOnDelete();
            // (tuỳ chọn) Mã cá nhân hoá nếu phát nhiều mã cùng 1 promotion
            $table->string('code', 60)->nullable();
            $table->enum('status', ['unused','used','expired','cancelled'])->default('unused');
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('used_at')->nullable();
            // Index/Unique gợi ý
            $table->unique(['customer_id','promotion_id','code']);
            $table->index(['customer_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('customers_promotions');
    }
};

