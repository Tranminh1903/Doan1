<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // ticketID
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('showtime_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chair_id')->constrained()->cascadeOnDelete();
            $table->string('seat_no');                // ghép từ row/col để hiển thị
            $table->string('qr_code')->unique();
            $table->decimal('price', 12, 2);
            $table->enum('status', ['issued','used','refunded'])->default('issued');
            $table->dateTime('issued_at')->useCurrent();
            $table->timestamps();
            $table->unique(['showtime_id','chair_id']); // một ghế chỉ có 1 vé cho 1 suất
            $table->index(['order_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};
