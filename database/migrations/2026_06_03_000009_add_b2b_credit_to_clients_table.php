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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('payment_terms')->default('prepaid'); // prepaid, net_30, cod
            $table->integer('credit_limit')->default(0); // Maximum credit limit in KSH
            $table->integer('outstanding_balance')->default(0); // Current credit balance in KSH
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['payment_terms', 'credit_limit', 'outstanding_balance']);
        });
    }
};
