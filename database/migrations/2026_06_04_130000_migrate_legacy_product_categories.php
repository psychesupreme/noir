<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Loop through products and map legacy category values to correct luxury categories
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $oldCat = $product->category;
            if (in_array($oldCat, ['retail', 'wholesale', 'gifting', 'uncategorized', ''])) {
                $name = strtolower($product->name);
                $newCat = 'stems'; // default fallback
                
                if (str_contains($name, 'bouquet') || str_contains($name, 'arrangement') || str_contains($name, 'dome') || str_contains($name, 'bloom')) {
                    $newCat = 'bouquets';
                } elseif (str_contains($name, 'hamper') || str_contains($name, 'box') || str_contains($name, 'trunk') || str_contains($name, 'gift')) {
                    $newCat = 'hampers';
                } elseif (str_contains($name, 'vase') || str_contains($name, 'candle') || str_contains($name, 'decor')) {
                    $newCat = 'home_decor';
                } elseif (str_contains($name, 'curation') || str_contains($name, 'session') || str_contains($name, 'styling') || str_contains($name, 'subscription') || str_contains($name, 'specialization')) {
                    $newCat = 'specializations';
                } elseif (str_contains($name, 'stem') || str_contains($name, 'rose') || str_contains($name, 'lily') || str_contains($name, 'hibiscus')) {
                    $newCat = 'stems';
                }
                
                DB::table('products')->where('id', $product->id)->update(['category' => $newCat]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback required as this is a data correction migration
    }
};
