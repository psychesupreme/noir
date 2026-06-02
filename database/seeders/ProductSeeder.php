<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Clean Existing Data safely without touching users ──────────
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        DB::table('occasion_product')->truncate();
        Schema::enableForeignKeyConstraints();

        // ── 1. Fresh Stems Category ───────────────────
        Product::create([
            'name' => 'Naomi Red Rose Stems (Grade A)',
            'sku' => 'NB-STM-NRO-01',
            'description' => 'Premium high-altitude red rose stems cut daily from Naivasha highland growers. Sold per stem.',
            'price' => 250,
            'stock' => 500,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'White Gypsophila Million Star Stems',
            'sku' => 'NB-STM-GWS-02',
            'description' => 'Volumetric sprays of Million Star baby breath, perfect for filler volume or cloud vases. Sold per stem.',
            'price' => 180,
            'stock' => 400,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1550950158-d0d960dff51b?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Marginpar Clematis Amazing® Blue Stems',
            'sku' => 'NB-STM-CBS-03',
            'description' => 'A beautiful bell-shaped Clematis in violet-blue tone, perfect for premium arrangements. Sold per stem.',
            'price' => 320,
            'stock' => 150,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 2. Bespoke Bouquets Category ───────────────────
        Product::create([
            'name' => 'The Obsidian Dome (Noir Signature)',
            'sku' => 'NB-BQT-NOD-01',
            'description' => 'A dense, low dome of 24 velvet red roses, styled in our custom matte black ceramic container.',
            'price' => 9500,
            'stock' => 30,
            'category' => 'bouquets',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Alabaster Whisper (White Astrantia & Orchid)',
            'sku' => 'NB-BQT-ALW-02',
            'description' => 'An ethereal, all-white arrangement of orchids, clematis, and delicate astrantia in an elegant vase.',
            'price' => 14000,
            'stock' => 15,
            'category' => 'bouquets',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Rift Valley Glow (Safari Sunset Spray)',
            'sku' => 'NB-BQT-RVG-03',
            'description' => 'Eucalyptus sprays, sunset proteas, and craspedia paintball globes embodying the Rift Valley landscape.',
            'price' => 7800,
            'stock' => 20,
            'category' => 'bouquets',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 3. Luxury Hampers Category ───────────────────
        Product::create([
            'name' => 'Gold & Velvet Truffle Box',
            'sku' => 'NB-HMP-GVT-01',
            'description' => 'Premium luxury storage box containing red spray roses, imported chocolate truffles, and a customized greeting card.',
            'price' => 18500,
            'stock' => 15,
            'category' => 'hampers',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Moët Champagne & White Orchid Trunk',
            'sku' => 'NB-HMP-MCT-02',
            'description' => 'Bespoke leather/linen trunk case housing a bottle of Moët & Chandon champagne surrounded by white phalaenopsis orchids.',
            'price' => 26000,
            'stock' => 8,
            'category' => 'hampers',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 4. Home & Vases Category ───────────────────
        Product::create([
            'name' => 'Matte Clay Ceramic Vase',
            'sku' => 'NB-DEC-MCV-01',
            'description' => 'Minimalist handcrafted matte finish clay vase. Fits tall stems and structural branches perfectly.',
            'price' => 4500,
            'stock' => 40,
            'category' => 'home_decor',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Hand-Poured Soy Wax Sandalwood Candle',
            'sku' => 'NB-DEC-SCS-02',
            'description' => 'Signature atelier candle featuring warm notes of sandalwood, amber, and cedarwood inside a dark glass jar.',
            'price' => 3200,
            'stock' => 60,
            'category' => 'home_decor',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1572021335469-31706a17aaef?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 5. Bespoke Specializations Category (Wide Cards Layout) ───────────────────
        Product::create([
            'name' => 'Custom Curation & Consultation Session',
            'sku' => 'NB-SPC-BCF-01',
            'description' => 'A dedicated 1-on-1 virtual or in-atelier consulting session with our design lead. We plan and spec your custom floral requirements, select varieties, and budget.',
            'price' => 5000,
            'stock' => 100, // Consultation slots
            'category' => 'specializations',
            'unit_type' => 'arrangement',
            'grade' => 'Consultation',
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Grand Gala Event Styling Package',
            'sku' => 'NB-SPC-GGE-02',
            'description' => 'Bespoke event planning, venue walkthroughs, and custom flower installations (arches, backdrops, centerpieces, ceiling suspensions) led by our expert team.',
            'price' => 95000,
            'stock' => 5,
            'category' => 'specializations',
            'unit_type' => 'arrangement',
            'grade' => 'Event Styling',
            'image_url' => 'https://images.unsplash.com/photo-1469371670807-013ccf25f16a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'VIP Corporate Weekly Curation Subscription',
            'sku' => 'NB-SPC-VCS-03',
            'description' => 'Tailored weekly rotation of corporate workspace flower arrangements, entry lobby structures, and meeting desk bowls, matching company brand guidelines.',
            'price' => 45000,
            'stock' => 10,
            'category' => 'specializations',
            'unit_type' => 'arrangement',
            'grade' => 'Subscription',
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}