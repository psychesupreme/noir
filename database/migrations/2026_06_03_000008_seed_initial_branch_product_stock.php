<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Branch;
use App\Models\Product;
use App\Models\BranchProductStock;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Avoid running if not in CLI or if table is empty
        if (Schema::hasTable('branches') && Schema::hasTable('products') && Schema::hasTable('branch_product_stock')) {
            $branch = Branch::where('is_active', true)->first() ?: Branch::first();
            
            if (!$branch) {
                $branch = Branch::create([
                    'name' => 'Nairobi Central Atelier',
                    'code' => 'NB-NBO',
                    'location_city' => 'Nairobi',
                    'is_active' => true,
                ]);
            }

            $products = Product::all();
            foreach ($products as $product) {
                // Ensure we don't insert duplicate key
                BranchProductStock::firstOrCreate(
                    ['branch_id' => $branch->id, 'product_id' => $product->id],
                    ['stock' => $product->stock, 'min_stock_level' => 5]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('branch_product_stock')) {
            BranchProductStock::truncate();
        }
    }
};
