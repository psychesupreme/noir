<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add CRM columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('loyalty_points')->default(0)->after('default_region');
            $table->string('referral_code', 50)->nullable()->unique()->after('loyalty_points');
        });

        // 2. Create campaigns table
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('channel'); // email, sms
            $table->string('subject')->nullable();
            $table->text('content');
            $table->timestamp('scheduled_at')->nullable();
            $table->string('status')->default('draft'); // draft, scheduled, sent
            $table->integer('sent_count')->default(0);
            $table->timestamps();
        });

        // 3. Create loyalty_transactions table
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->string('type'); // earn, redeem
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('campaigns');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'referral_code']);
        });
    }
};
