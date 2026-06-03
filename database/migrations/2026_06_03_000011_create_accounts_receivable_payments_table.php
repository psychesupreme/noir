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
        Schema::create('accounts_receivable_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ar_invoice_id')->constrained('accounts_receivable_invoices')->onDelete('cascade');
            $table->integer('amount');
            $table->string('payment_method'); // bank_transfer, cheque, cash, mpesa
            $table->string('reference_number')->nullable();
            $table->timestamp('recorded_at');
            $table->foreignId('recorded_by_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receivable_payments');
    }
};
