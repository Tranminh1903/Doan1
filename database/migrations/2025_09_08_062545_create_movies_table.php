<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('movies', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED
            $table->string('title', 150);
            $table->unsignedSmallInteger('duration_min');
            $table->string('genre', 100);
            $table->string('rating', 10)->nullable();
            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('showtimes');
    }
};


