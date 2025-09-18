<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id('ticketID');
            $table->decimal('price', 10, 2);
            $table->string('status', 20)->default('issued');
            $table->uuid('qr_token')->unique();   
            $table->string('qr_code')->nullable();
            $table->dateTime('issueAt')->nullable();
            $table->string('refund_reason')->nullable();
            $table->timestamps();
            $table->unique(['showtimeID', 'seatID']);
            $table->index(['status', 'showtimeID']);

            $table->foreignId('showtimeID')->constrained('showtime', 'showtimeID')->cascadeOnDelete();
            $table->foreignId('seatID')->constrained('seats', 'seatID')->cascadeOnDelete();            
        });
    }

    public function down(): void
    {
        try { 
            DB::statement("ALTER TABLE ticket DROP CONSTRAINT chk_ticket_status"); 
        } catch (\Throwable $e) {

        }
        Schema::dropIfExists('ticket');
    }
};
