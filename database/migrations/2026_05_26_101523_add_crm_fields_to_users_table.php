<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_tier')->default('retail'); // retail, corporate, concierge
            $table->string('phone_number')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('default_delivery_address')->nullable();
            $table->string('default_region')->default('Nairobi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_tier', 'phone_number', 'kra_pin', 'default_delivery_address', 'default_region']);
        });
    }
};