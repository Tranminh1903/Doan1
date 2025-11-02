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
            $table->unsignedSmallInteger('durationMin'); 
            $table->string('genre')->nullable();      
            $table->string('rating')->nullable();     
            $table->date('releaseDate')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_banner')->default(false)->index(); 
            $table->string('status')->default('active');
            $table->timestamps();
            // Full-text index on title for search functionality
            $table->fullText('title');
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
