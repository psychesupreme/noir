<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etims_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('internal_invoice_number')->unique(); // e.g., INV-2026-0001
            $table->string('cu_invoice_number')->unique()->nullable(); // Official KRA Control/Fiscal Number
            
            // Financial breakdowns stored as precise integers in Ksh
            $table->integer('gross_amount'); 
            $table->integer('taxable_amount'); // Net before tax
            $table->integer('vat_amount'); // 16% VAT element
            
            $table->string('status')->default('pending'); // pending, transmitted, failed
            $table->string('kra_qr_url')->nullable(); // Mandatory URL matching printout verification requirements
            $table->text('error_log_payload')->nullable(); // Diagnostic catch-all if transmission stumbles
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etims_invoices');
    }
};