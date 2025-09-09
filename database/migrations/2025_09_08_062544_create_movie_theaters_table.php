<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('movie_theaters', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED
            $table->string('room_name', 100);
            $table->unsignedInteger('capacity');
            $table->enum('type', ['2D','3D','4DX','IMAX']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('movie_theaters');
    }
};

