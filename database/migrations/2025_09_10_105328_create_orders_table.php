<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // PK tự tăng
            $table->unsignedBigInteger('customer_user_id'); // FK đến customers.user_id
            $table->string('code')->unique(); // mã đơn hàng
            $table->string('status', 10)->default('created');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->foreign('customer_user_id')->references('user_id')->on('customers')->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
