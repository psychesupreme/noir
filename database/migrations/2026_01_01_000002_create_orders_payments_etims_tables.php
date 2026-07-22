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
        // 1. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('total_amount')->index();
            $table->integer('service_fee_amount')->default(0);
            $table->integer('delivery_fee')->default(0);
            $table->string('delivery_type')->default('standard');
            $table->string('status')->default('pending')->index();
            $table->boolean('is_gift')->default(false);
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('required_delivery_at')->nullable();
            $table->string('pod_photo_path')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('courier_notes')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('overall_rating')->nullable();
            $table->integer('quality_rating')->nullable();
            $table->integer('freshness_rating')->nullable();
            $table->integer('value_rating')->nullable();
            $table->integer('product_rating')->nullable();
            $table->integer('packaging_rating')->nullable();
            $table->integer('delivery_rating')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->timestamps();
        });

        // 2. Order Product Pivot
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price_at_sale');
            $table->integer('cost_price_at_sale')->default(0);
            $table->string('size')->default('standard');
            $table->timestamps();
        });

        // 3. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('merchant_request_id')->nullable()->index();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('phone_number');
            $table->integer('amount');
            $table->string('mpesa_receipt_number')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->text('result_description')->nullable();
            $table->timestamps();
        });

        // 4. KRA eTIMS Invoices
        Schema::create('etims_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('internal_invoice_number')->unique();
            $table->string('cu_serial_number')->nullable();
            $table->string('cu_invoice_number')->nullable();
            $table->string('uti')->nullable();
            $table->string('kra_qr_url')->nullable();
            $table->integer('gross_amount');
            $table->integer('taxable_amount');
            $table->integer('vat_amount');
            $table->string('status')->default('pending');
            $table->json('raw_request_payload')->nullable();
            $table->json('raw_response_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etims_invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
    }
};
