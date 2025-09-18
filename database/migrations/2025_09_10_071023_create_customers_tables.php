<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();     // PK = FK
            $table->string('customer_name')->nullable();
            $table->integer('customer_point')->default(0);
            $table->string('tier', 10)->default('bronze');
            $table->integer('total_order_amount')->default(0);
            $table->integer('total_promotions_unused')->default(0);   
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete(); // xóa user -> xóa customer
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
