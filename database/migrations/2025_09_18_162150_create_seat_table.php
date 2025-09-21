<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seatID'); 
            $table->unsignedBigInteger('theaterID'); 
            $table->string('verticalRow'); 
            $table->integer('horizontalRow'); 
            $table->string('seatType', 10)->default('normal'); // ['normal','vip','couple']
            
            // status có 3 trạng thái: available, held, unavailable
            $table->enum('status', ['available', 'held', 'unavailable'])->default('available');

            $table->timestamps();

            $table->foreign('theaterID')
                  ->references('theaterID')
                  ->on('movie_theaters')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
