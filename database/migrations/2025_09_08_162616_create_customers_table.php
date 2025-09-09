<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); // 1-1 User
            $table->unsignedInteger('customer_point')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('customers');
    }
};

