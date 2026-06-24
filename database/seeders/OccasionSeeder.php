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

        $anniversary = Occasion::create([
            'name' => 'Anniversary Elegance',
            'slug' => 'anniversary-elegance',
            'accent_color' => '#B59A7A', // Warm Champagne Gold
            'is_major_holiday' => false,
        ]);

        $birthday = Occasion::create([
            'name' => 'Vibrant Birthday',
            'slug' => 'vibrant-birthday',
            'accent_color' => '#D97706', // Warm Amber
            'is_major_holiday' => false,
        ]);

        $congrats = Occasion::create([
            'name' => 'Bespoke Congrats',
            'slug' => 'bespoke-congrats',
            'accent_color' => '#065F46', // Elegant Emerald Green
            'is_major_holiday' => false,
        ]);

        $romance = Occasion::create([
            'name' => 'Silent Romance',
            'slug' => 'silent-romance',
            'accent_color' => '#BE185D', // Deep Blush Pink
            'is_major_holiday' => false,
        ]);

        // 2. Attach existing products to these occasions safely
        $products = Product::all();
        foreach ($products as $index => $product) {
            // Distribute products into occasions based on index
            if ($product->sku === 'NB-BQT-RRH-01') {
                $product->occasions()->sync([$valentines->id, $romance->id]);
            } elseif ($product->sku === 'NB-BQT-CWR-02') {
                $product->occasions()->sync([$corporate->id, $anniversary->id]);
            } elseif ($product->sku === 'NB-BQT-BGP-03') {
                $product->occasions()->sync([$sympathy->id]);
            } elseif ($product->sku === 'NB-STM-NRO-01') {
                $product->occasions()->sync([$corporate->id]);
            } else {
                // Distribute others deterministically
                $occ_id = match ($index % 6) {
                    0 => $birthday->id,
                    1 => $anniversary->id,
                    2 => $congrats->id,
                    3 => $romance->id,
                    4 => $corporate->id,
                    default => $sympathy->id,
                };
                $product->occasions()->sync([$occ_id]);
            }
        }
    }
}