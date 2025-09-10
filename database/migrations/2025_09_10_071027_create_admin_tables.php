<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();       // PK = FK
            $table->string('role', 100)->nullable();    // chức vụ admin
            $table->timestamps();
            $table->foreign('user_id') ->references('id')->on('users')->cascadeOnDelete(); // xóa user -> xóa admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
