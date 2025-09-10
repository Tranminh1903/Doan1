<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('showtime_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['created','paid','cancelled','refunded'])->default('created');
            $table->timestamps(); 
            $table->index(['user_id','showtime_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};

