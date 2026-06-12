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
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Eldoret Golden Sunflowers',
            'sku' => 'NB-STM-EGS-03',
            'description' => 'Bright, radiant golden sunflowers harvested from the rich agricultural highlands of Eldoret. Adds warmth and sunlit charm to any room. Sold per stem.',
            'price' => 350,
            'stock' => 200,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1597848212624-a19eb35e2651?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Nakuru Pink Carnations',
            'sku' => 'NB-STM-NPC-04',
            'description' => 'Elegant ruffle-petalled pink carnations sourced from professional growers around Lake Nakuru. Known for their long vase life. Sold per stem.',
            'price' => 250,
            'stock' => 400,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1572915243851-bc29084df948?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Nairobi Blue Hydrangeas',
            'sku' => 'NB-STM-NBH-05',
            'description' => 'Large, globe-like blue hydrangeas grown in shaded valley plots of Nairobi. Ideal for creating instant focus and structural volume. Sold per stem.',
            'price' => 500,
            'stock' => 150,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1507504038482-7621ab2886f1?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Tsavo Purple Orchids',
            'sku' => 'NB-STM-TPO-06',
            'description' => 'Exotic and rare purple orchids sourced from temperature-controlled conservatories near Tsavo. Represents absolute luxury. Sold per stem.',
            'price' => 600,
            'stock' => 100,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Nyeri Sunset Orange Gerberas',
            'sku' => 'NB-STM-NSG-07',
            'description' => 'Cheerful and vibrant orange gerbera daisies cultivated under the sunny skies of Nyeri. Striking daisy-like shape. Sold per stem.',
            'price' => 280,
            'stock' => 350,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=600',
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
            'name' => 'Crystal Glass Watamu Vase',
            'sku' => 'NB-DEC-CGW-02',
            'description' => 'Earthy yet high-end hand-blown clear glass vase. Exposes elegant stem lines underwater.',
            'price' => 5500,
            'stock' => 25,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Obsidian Onyx Alabaster Vase',
            'sku' => 'NB-DEC-OOA-03',
            'description' => 'Ultra-premium polished black-stone vase with rich gold veins and polished metallic rim.',
            'price' => 7800,
            'stock' => 15,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Frosted Glacier Lily Vase',
            'sku' => 'NB-DEC-FGL-04',
            'description' => 'A fluted, semi-translucent frosted glass vase styled like a blooming white glacier lily.',
            'price' => 6200,
            'stock' => 20,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
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
            'category' => 'specialization',
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
            'category' => 'specialization',
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
            'category' => 'specialization',
            'unit_type' => 'arrangement',
            'grade' => 'Subscription',
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 6. Curation Addons & Giftings ───────────────────
        Product::create([
            'name' => 'Kraft Paper Wrapping',
            'sku' => 'NB-DEC-KPW-01',
            'description' => 'Artisanal brown kraft wrapping paper with natural raffia tie.',
            'price' => 500,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Satin Ribbon Accent',
            'sku' => 'NB-DEC-SRA-02',
            'description' => 'Luxury double-faced satin ribbon tied in a classic atelier bow.',
            'price' => 800,
            'stock' => 150,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'French Mesh Wrapping',
            'sku' => 'NB-DEC-FMW-03',
            'description' => 'Textured black mesh wrapping adding volume and theatrical depth.',
            'price' => 1000,
            'stock' => 100,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Organic Burlap Wrapping',
            'sku' => 'NB-DEC-OBW-04',
            'description' => 'Rustic natural jute burlap wrap for an organic, textured appearance.',
            'price' => 600,
            'stock' => 120,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Premium South African Merlot',
            'sku' => 'NB-HMP-PSM-01',
            'description' => 'Rich, full-bodied red wine with dark fruit aromas and oak notes.',
            'price' => 4500,
            'stock' => 50,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Moët & Chandon Imperial Brut',
            'sku' => 'NB-HMP-MCI-02',
            'description' => 'Distinguished French Champagne characterized by bright fruitiness and elegant maturity.',
            'price' => 12500,
            'stock' => 30,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Chamdor Non-Alcoholic Sparkling Wine',
            'sku' => 'NB-HMP-CNS-03',
            'description' => 'Sweet, carbonated non-alcoholic sparkling grape juice.',
            'price' => 3000,
            'stock' => 100,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Artisanal Belgian Truffles Box',
            'sku' => 'NB-HMP-ABT-04',
            'description' => 'A curated selection of 16 hand-crafted luxury Belgian chocolate truffles.',
            'price' => 2800,
            'stock' => 120,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Rosewood & Amber Mist',
            'sku' => 'NB-DEC-ARA-04',
            'description' => 'Signature fragrance mist to spray over flower arrangements for a lasting woodsy aroma.',
            'price' => 1500,
            'stock' => 90,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limuru Lavender Fields Mist',
            'sku' => 'NB-DEC-LLF-05',
            'description' => 'Soothing, fresh lavender fragrance mist derived from Limuru fields.',
            'price' => 1200,
            'stock' => 110,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}