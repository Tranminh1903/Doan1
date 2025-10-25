<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('showtimeID')->nullable();
            $table->string('username')->nullable();
            $table->string('order_code')->unique();
            $table->string('seats')->nullable();
            $table->string('status', 10)->default('created');
            $table->decimal('amount', 12, 2)->nullable();
            $table->timestamps();
            $table->foreign('showtimeID')->references('showtimeID')->on('showtime')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
    Schema::table('orders', function (Blueprint $table) {
        try { $table->dropForeign(['showtimeID']); } catch (\Throwable $e) {}
    });
    
    Schema::dropIfExists('orders');
    }
};

