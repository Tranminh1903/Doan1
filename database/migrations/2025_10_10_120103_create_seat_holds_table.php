<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_holds', function (Blueprint $table) {
            $table->id('holdID');

            // 🔹 Khóa ngoại tới bảng showtime
            $table->unsignedBigInteger('showtimeID');
            $table->foreign('showtimeID')
                  ->references('showtimeID')
                  ->on('showtime')
                  ->cascadeOnDelete();

            // 🔹 Khóa ngoại tới bảng seats
            $table->unsignedBigInteger('seatID');
            $table->foreign('seatID')
                  ->references('seatID')
                  ->on('seats')
                  ->cascadeOnDelete();

            // 🔹 Khóa ngoại tới bảng users (người đặt)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            // 🔹 Khóa ngoại tới bảng orders (đơn hàng, nếu có)
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->nullOnDelete();

            // 🔹 Thời điểm hết hạn giữ ghế
            $table->timestamp('expires_at')->nullable();

            // 🔹 Trạng thái ghế trong quá trình giữ
            $table->enum('status', ['held', 'pending', 'paid', 'expired'])
                  ->default('held');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_holds');
    }
};
