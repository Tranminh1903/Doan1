<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('showtime', function (Blueprint $table) {
            $table->id('showtimeID');                        
            $table->unsignedBigInteger('movieID');             
            $table->unsignedBigInteger('theaterID');           
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->decimal('price', 10, 2)->nullable();      
            $table->timestamps();
            $table->index(['movieID', 'theaterID', 'startTime']);

            $table->foreign('movieID')->references('movieID')->on('movies')->cascadeOnDelete();
            $table->foreign('theaterID')->references('theaterID')->on('movie_theaters')->cascadeOnDelete();
        });
    }


    public function down(): void
    {
        Schema::table('showtime', function (Blueprint $table) {
            try { $table->dropForeign(['movieID']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['theaterID']); } catch (\Throwable $e) {}
        });

        Schema::dropIfExists('showtime');
    }
};
