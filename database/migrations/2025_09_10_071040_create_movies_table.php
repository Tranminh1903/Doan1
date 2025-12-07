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
            $table->string('poster')->nullable(); 
            $table->string('background')->nullable(); 
            $table->unsignedSmallInteger('durationMin');     
            $table->string('rating')->nullable();   
            $table->string('genre')->nullable();
            $table->date('releaseDate')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_banner')->default(false)->index(); 
            $table->string('status')->default('active');
            $table->fullText('title');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('is_banner');
        });

        Schema::dropIfExists('movies');
    }
};
