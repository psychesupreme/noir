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
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable()->after('price');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->integer('cost_price_at_sale')->default(0)->after('price_at_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sizes');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('cost_price_at_sale');
        });
    }
};
