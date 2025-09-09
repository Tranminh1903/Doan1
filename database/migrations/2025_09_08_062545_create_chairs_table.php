<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chairs', function (Blueprint $table) {
            $table->id(); // chairID
            $table->foreignId('movie_theater_id')->constrained()->cascadeOnDelete();
            $table->string('vertical_row', 2);   // A, B, C...
            $table->unsignedSmallInteger('horizontal_row'); // 1,2,3...
            $table->enum('seat_type', ['normal','vip','couple'])->default('normal');
            $table->enum('status', ['active','maintenance'])->default('active');
            $table->timestamps();

            $table->unique(['movie_theater_id','vertical_row','horizontal_row']); // ghe duy nhat trong phong
        });
    }
    public function down(): void {
        Schema::dropIfExists('chairs');
    }
};
