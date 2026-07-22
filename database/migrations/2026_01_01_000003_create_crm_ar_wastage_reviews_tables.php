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
        // 0. Campaigns (Marketing)
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('channel')->default('email');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sent_count')->default(0);
            $table->timestamps();
        });

        // 0b. Loyalty Transactions
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('type'); // earn, redeem
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 1. CRM Deals
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('deal_value')->default(0);
            $table->string('stage')->default('lead');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. CRM Timeline Logs
        Schema::create('crm_timeline_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type');
            $table->text('description');
            $table->timestamps();
        });

        // 3. Accounts Receivable Invoices
        Schema::create('accounts_receivable_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('amount_due');
            $table->integer('amount_paid')->default(0);
            $table->timestamp('due_at')->nullable();
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });

        // 4. Accounts Receivable Payments
        Schema::create('accounts_receivable_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ar_invoice_id')->constrained('accounts_receivable_invoices')->onDelete('cascade');
            $table->integer('amount');
            $table->string('payment_method');
            $table->string('reference_number')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 5. Spoilage & Wastage Logs
        Schema::create('wastage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity');
            $table->integer('cost_estimate')->default(0);
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Customer Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reviewer_name')->default('Anonymous');
            $table->integer('rating');
            $table->integer('quality_rating')->default(5);
            $table->integer('freshness_rating')->default(5);
            $table->integer('value_rating')->default(5);
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('wastage_logs');
        Schema::dropIfExists('accounts_receivable_payments');
        Schema::dropIfExists('accounts_receivable_invoices');
        Schema::dropIfExists('crm_timeline_logs');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('campaigns');
    }
};
