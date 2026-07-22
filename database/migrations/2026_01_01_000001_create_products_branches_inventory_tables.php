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
        // 1. Branches
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location_city');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->integer('price')->index();
            $table->integer('cost_price')->default(0);
            $table->integer('stock')->default(0);
            $table->string('category')->index();
            $table->string('subcategory')->nullable();
            $table->string('unit_type')->default('piece');
            $table->string('size_unit')->nullable();
            $table->string('grade')->nullable();
            $table->text('image_url')->nullable();
            $table->json('sizes')->nullable();
            $table->string('photographer_name')->nullable();
            $table->string('photographer_username')->nullable();
            $table->timestamps();
        });

        // 3. Occasions
        Schema::create('occasions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('accent_color')->default('#C5A880');
            $table->boolean('is_major_holiday')->default(false);
            $table->timestamps();
        });

        // 4. Occasion Product Pivot
        Schema::create('occasion_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occasion_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Branch Product Stock Allocation
        Schema::create('branch_product_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->integer('min_stock_level')->default(5);
            $table->timestamps();
            $table->unique(['branch_id', 'product_id']);
        });

        // 6. Inventory Adjustment Logs
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->integer('adjustment');
            $table->string('reason');
            $table->timestamps();
        });

        // 7. Vendors
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('category')->nullable();
            $table->string('payment_terms')->nullable();
            $table->integer('reliability_rating')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('draft');
            $table->decimal('total_cost', 12, 2)->default(0.00);
            $table->date('expected_delivery_date')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->integer('unit_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('branch_product_stock');
        Schema::dropIfExists('occasion_product');
        Schema::dropIfExists('occasions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('branches');
    }
};
