<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for M-Pesa Daraja ledger tracking.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('mpesa_receipt_number')->unique()->nullable(); // e.g., QRL71234X (filled on callback success)
            $table->string('phone_number'); // The 2547XXXXXXXX format utilized for the prompt
            $table->integer('amount'); // Amount paid in Ksh
            $table->string('status')->default('requested'); // requested, completed, failed, cancelled
            
            // Unique tracking keys handed back instantly by Daraja API for webhook matching
            $table->string('merchant_request_id')->unique()->nullable();
            $table->string('checkout_request_id')->unique()->nullable();
            
            $table->text('result_description')->nullable(); // Stores error messages or success logs from Safaricom
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};