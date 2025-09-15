<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders_histories', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('order_id'); // FK đến orders.id
            $table->string('status',10);//created','paid','cancelled','refunded'
            $table->text('note')->nullable(); 
            $table->timestamp('changed_at')->useCurrent(); 
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};
