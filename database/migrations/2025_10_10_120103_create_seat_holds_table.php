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
            $table->unsignedBigInteger('showtimeID');
            $table->foreign('showtimeID')
                  ->references('showtimeID')
                  ->on('showtime')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('seatID');
            $table->foreign('seatID')
                  ->references('seatID')
                  ->on('seats')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->nullOnDelete();
            $table->timestamp('expires_at')->nullable(); 
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
