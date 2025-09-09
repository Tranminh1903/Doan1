<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id(); // promotionID (internal)
            $table->string('code')->unique(); // nếu muốn giữ mã như UML: promotionID:string
            $table->string('description')->nullable();
            $table->string('condition')->nullable(); // mô tả điều kiện
            $table->dateTime('start_time');
            $table->dateTime('expired_time');
            $table->decimal('amount_decimal', 12, 2)->nullable(); // mức giảm tính sẵn hoặc % quy đổi
            $table->timestamps();

            $table->index(['code','start_time','expired_time']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('promotions');
    }
};

