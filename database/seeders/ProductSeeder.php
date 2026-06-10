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
        DB::table('branch_product_stock')->truncate();
        DB::table('inventory_logs')->truncate();
        Schema::enableForeignKeyConstraints();
        // ── 1. Fresh Stems Category ───────────────────
        Product::create([
            'name' => 'Naivasha Volcanic Red Roses (Grade A)',
            'sku' => 'NB-STM-NRO-01',
            'description' => 'Premium, long-stemmed Naomi red roses grown in the nutrient-dense volcanic soils of Lake Naivasha. Cut daily and shipped via cold chain. Sold per stem.',
            'price' => 300,
            'stock' => 500,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limuru Pure White Lilies (Grade A)',
            'sku' => 'NB-STM-LWL-02',
            'description' => 'Stunning, multi-bloom white Oriental Lilies sourced from the misty highlands of Limuru. Offers a rich, clean fragrance. Sold per stem.',
            'price' => 450,
            'stock' => 300,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1550950158-d0d960dff51b?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Watamu Coral Hibiscus Stems',
            'sku' => 'NB-STM-WCH-03',
            'description' => 'Vibrant coral hibiscus stems harvested from the warm coastal gardens of Watamu. Brings a tropical breeze to any vase. Sold per stem.',
            'price' => 350,
            'stock' => 200,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 2. Bespoke Bouquets Category ───────────────────
        Product::create([
            'name' => 'The Nairobi Obsidian Dome (Noir Signature)',
            'sku' => 'NB-BQT-NOD-01',
            'description' => 'A dramatic arrangement of 24 velvet Naivasha red roses, tightly structured in our matte black ceramic container. The ultimate luxury statement.',
            'price' => 9500,
            'stock' => 30,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limuru Alabaster Orchid Spray',
            'sku' => 'NB-BQT-LAO-02',
            'description' => 'An ethereal, all-white arrangement of highland orchids, clematis, and delicate astrantia in an elegant ceramic vase.',
            'price' => 14000,
            'stock' => 15,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Rift Valley Sunset Proteas',
            'sku' => 'NB-BQT-RVS-03',
            'description' => 'Eucalyptus sprays, sunset proteas, and craspedia paintballs embodying the golden hour landscape of the Great Rift Valley.',
            'price' => 7800,
            'stock' => 20,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 3. Luxury Hampers Category ───────────────────
        Product::create([
            'name' => 'Kericho Gold Premium Gift Box',
            'sku' => 'NB-HMP-KGG-01',
            'description' => 'Premium luxury storage box containing red spray roses, Kericho gold special reserve tea, imported truffles, and a custom greeting card.',
            'price' => 18500,
            'stock' => 15,
            'category' => 'giftings',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limuru Berry & Bloom Luxury Trunk',
            'sku' => 'NB-HMP-LBB-02',
            'description' => 'Bespoke leather/linen trunk case housing premium highland tea infusions, local gourmet berry jams, and white phalaenopsis orchids.',
            'price' => 26000,
            'stock' => 8,
            'category' => 'giftings',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 4. Home & Vases Category ───────────────────
        Product::create([
            'name' => 'Matte Clay Rift Valley Vase',
            'sku' => 'NB-DEC-MRV-01',
            'description' => 'Minimalist, artisan-crafted clay vase made from Rift Valley volcanic soils. Fits tall structural stems and branches perfectly.',
            'price' => 4500,
            'stock' => 40,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Tsavo Amber Hand-Poured Candle',
            'sku' => 'NB-DEC-TAC-02',
            'description' => 'Signature atelier candle hand-poured inside a dark glass jar, featuring warm, earthy notes of sandalwood, cedarwood, and Tsavo amber.',
            'price' => 3200,
            'stock' => 60,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1572021335469-31706a17aaef?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 5. Bespoke Specializations Category (Wide Cards Layout) ───────────────────
        Product::create([
            'name' => 'Nairobi Atelier Custom Curation Session',
            'sku' => 'NB-SPC-NCC-01',
            'description' => 'A 1-on-1 design consultation session at our Nairobi showroom or online. We plan, budget, and select varieties for your custom floral requirements.',
            'price' => 5000,
            'stock' => 100, // Consultation slots
            'category' => 'specializtion',
            'unit_type' => 'arrangement',
            'grade' => 'Consultation',
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Grand Gala Event Styling Package',
            'sku' => 'NB-SPC-GGE-02',
            'description' => 'Bespoke event planning, venue walkthroughs, and custom flower installations (arches, centerpieces, suspensions) led by our expert team.',
            'price' => 95000,
            'stock' => 5,
            'category' => 'specializtion',
            'unit_type' => 'arrangement',
            'grade' => 'Event Styling',
            'image_url' => 'https://images.unsplash.com/photo-1469371670807-013ccf25f16a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'VIP Corporate Weekly Curation Subscription',
            'sku' => 'NB-SPC-VCS-03',
            'description' => 'Tailored weekly rotation of corporate workspace flower arrangements, entry lobby structures, and meeting desk bowls matching brand guidelines.',
            'price' => 45000,
            'stock' => 10,
            'category' => 'specializtion',
            'unit_type' => 'arrangement',
            'grade' => 'Subscription',
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}