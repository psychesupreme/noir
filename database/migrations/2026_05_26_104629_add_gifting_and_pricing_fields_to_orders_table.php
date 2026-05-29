<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_gift')->default(false)->after('client_id');
            $table->string('recipient_name')->nullable()->after('is_gift');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->integer('service_fee_amount')->default(0)->after('total_amount'); // Surprise upsell pricing ledger
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_gift', 'recipient_name', 'recipient_phone', 'service_fee_amount']);
        });
    }
};