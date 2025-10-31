<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED PK
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('sex', 4)->nullable(); 
            $table->string('google_id')->nullable();
            $table->date('birthday')->nullable(); 
            $table->string('role', 20)->default('customers');
            $table->string('avatar',255)->nullable();
            $table->string('status',20)->default('active'); //'active' & 'banned'
            $table->timestamps(); 
        });

    }
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
