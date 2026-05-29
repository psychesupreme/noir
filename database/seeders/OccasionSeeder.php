<?php

namespace Database\Seeders;

use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Clean Existing Occasions Safely ────────────────────────────
        Schema::disableForeignKeyConstraints();
        Occasion::truncate();
        DB::table('occasion_product')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create Luxury Occasions mapped to specific brand colors
        $corporate = Occasion::create([
            'name' => 'Corporate Atelier',
            'slug' => 'corporate-atelier',
            'accent_color' => '#D4AF37', // Muted Luxury Gold
            'is_major_holiday' => false,
        ]);

        $valentines = Occasion::create([
            'name' => "Rogue Valentine's",
            'slug' => 'rogue-valentines',
            'accent_color' => '#991B1B', // Deep Wine Red
            'is_major_holiday' => true,
        ]);

        $sympathy = Occasion::create([
            'name' => 'Minimalist Sympathy',
            'slug' => 'minimalist-sympathy',
            'accent_color' => '#64748B', // Serene Slate Gray
            'is_major_holiday' => false,
        ]);

        // 2. Attach existing products to these occasions safely
        $noirClassic = Product::where('sku', 'NB-RET-NCL-01')->first();
        if ($noirClassic) {
            $noirClassic->occasions()->sync([$valentines->id]);
        }

        $alabasterDream = Product::where('sku', 'NB-RET-ADR-02')->first();
        if ($alabasterDream) {
            $alabasterDream->occasions()->sync([$corporate->id]);
        }
        
        $safariSunset = Product::where('sku', 'NB-RET-SSN-03')->first();
        if ($safariSunset) {
            $safariSunset->occasions()->sync([$sympathy->id]);
        }

        $riftValley = Product::where('sku', 'NB-RET-RVM-04')->first();
        if ($riftValley) {
            $riftValley->occasions()->sync([$corporate->id]);
        }
    }
}