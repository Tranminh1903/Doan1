<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('movieID');                    
            $table->string('title');
            $table->string('poster')->nullable(); // đường dẫn ảnh poster
            $table->unsignedSmallInteger('durationMin'); 
            $table->string('genre')->nullable();      
            $table->string('rating')->nullable();     
            $table->date('releaseDate')->nullable();
            $table->text('description')->nullable(); // thêm mô tả phim
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
