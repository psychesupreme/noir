<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable(); // e.g., "Safaricom HQ" or "Bespoke Weddings Ltd"
            $table->string('contact_name'); // Full name of the procurement officer / individual
            $table->string('email')->unique();
            $table->string('phone'); // Primary contact number
            $table->string('region')->default('Nairobi'); // Nairobi or Kiambu routing context
            $table->text('delivery_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};