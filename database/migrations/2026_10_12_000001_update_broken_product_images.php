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
        DB::table('products')
            ->where('sku', 'NB-STM-NWR-08')
            ->orWhere('image_url', 'like', '%photo-1533604131587-35661a153243%')
            ->update([
                'image_url' => 'https://images.unsplash.com/photo-1527061011665-3652c757a4d4?auto=format&fit=crop&w=600&q=80',
            ]);

        DB::table('products')
            ->where('sku', 'NB-BQT-BGP-03')
            ->orWhere('image_url', 'like', '%photo-1587334206502-747683070c77%')
            ->update([
                'image_url' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=600&q=80',
            ]);

        DB::table('products')
            ->where('sku', 'NB-HMP-GPC-02')
            ->orWhere('image_url', 'like', '%photo-1607344645866-009c320c5ab8%')
            ->update([
                'image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=600&q=80',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non-destructive fallback
    }
};
