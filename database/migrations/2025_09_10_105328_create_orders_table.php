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
            $table->unsignedBigInteger('customer_user_id')->nullable(); 
            $table->string('order_code')->unique(); 
            $table->string('seats')->nullable();
            $table->string('status', 10)->default('created');
            $table->decimal('amount', 12, 2)->nullable();
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
