<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_ratings', function (Blueprint $table) {
            $table->id('ratingID'); // Khóa chính tự tăng

            
            $table->unsignedBigInteger('movieID');
            $table->unsignedBigInteger('userID');

            
            $table->tinyInteger('stars')->default(0);

            $table->timestamps();


            $table->foreign('movieID')
                  ->references('movieID')
                  ->on('movies')
                  ->onDelete('cascade');

            $table->foreign('userID')
                  ->references('user_id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_ratings');
    }
};
