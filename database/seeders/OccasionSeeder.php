<?php

namespace Database\Seeders;

use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
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
        $crimson = Product::where('sku', 'NB-RET-CRM-01')->first();
        if ($crimson) {
            $crimson->occasions()->sync([$valentines->id]);
        }

        $orchid = Product::where('sku', 'NB-RET-ORC-02')->first();
        if ($orchid) {
            $orchid->occasions()->sync([$corporate->id]);
        }
        
        $calla = Product::where('sku', 'NB-RET-CAL-03')->first();
        if ($calla) {
            $calla->occasions()->sync([$sympathy->id]);
        }

        $emerald = Product::where('sku', 'NB-RET-EMR-04')->first();
        if ($emerald) {
            $emerald->occasions()->sync([$corporate->id]);
        }
    }
}