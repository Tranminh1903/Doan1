<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_theaters', function (Blueprint $table) {
            $table->id('theaterID'); 
            $table->string('roomName');
            $table->integer('capacity')->default(100);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_theaters');
    }
};
