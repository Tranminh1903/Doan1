<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20); // created|paid|cancelled|refunded...
            $table->text('note')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
            $table->index(['order_id', 'changed_at']);
            $table->index(['order_id', 'status']);
            
          $table->foreignId('order_id')->constrained('orders', 'id')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `order_histories` DROP CHECK `chk_order_histories_status`");
        } catch (\Throwable $e) {
        }
        Schema::dropIfExists('order_histories');
    }
};
