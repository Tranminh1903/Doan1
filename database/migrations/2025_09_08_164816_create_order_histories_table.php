<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('action', ['created','paid','cancelled','refund']);
            $table->text('note')->nullable();
            $table->dateTime('created_at')->useCurrent(); // theo UML có createdAt riêng
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_histories');
    }
};

