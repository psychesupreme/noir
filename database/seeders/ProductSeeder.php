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

        // ── 1. Retail Curation Category (5 products) ───────────────────
        Product::create([
            'name' => 'The Noir Classic (Bespoke Rose Arrangement)',
            'sku' => 'NB-RET-NCL-01',
            'description' => 'A luxury signature dome of 24 deep velvet red roses arranged in an signature obsidian ceramic vase.',
            'price' => 8500,
            'stock' => 20,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Alabaster Dream (Astrantia & Clematis Mix)',
            'sku' => 'NB-RET-ADR-02',
            'description' => 'A delicate combination of white Astrantia Billion Star and Clematis Amazing from Nakuru.',
            'price' => 12500,
            'stock' => 12,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Safari Sunset (Stems & Grasses Blend)',
            'sku' => 'NB-RET-SSN-03',
            'description' => 'Earth-toned stems featuring Craspedia Paintball and eucalyptus greens representing the Rift Valley dusk.',
            'price' => 5800,
            'stock' => 15,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Rift Valley Majesty (Premium Red Rose Spray)',
            'sku' => 'NB-RET-RVM-04',
            'description' => 'Multi-headed spray roses sourced directly from Naivasha highland growers in glass containers.',
            'price' => 9800,
            'stock' => 18,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1533616688419-b7a585564566?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Naivasha Mist (White Gypsophila & Limonium Curation)',
            'sku' => 'NB-RET-NMS-05',
            'description' => 'An ethereal, voluminous cloud of Gypsophila Million Star and China White Limonium.',
            'price' => 6500,
            'stock' => 25,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1550950158-d0d960dff51b?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 2. Wholesale Graded Stems Category (5 products) ───────────
        Product::create([
            'name' => 'Clematis Amazing® Blue Pirouette (Wholesale Stems)',
            'sku' => 'NB-WHL-CBP-01',
            'description' => 'Premium Clematis cut flower stems from Marginpar Kenya. Sold in standard bundles of 20 stems.',
            'price' => 3800,
            'stock' => 150,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Eryngium Questar® Sirius (Wholesale Stems)',
            'sku' => 'NB-WHL-EQS-02',
            'description' => 'Highly decorative thistles with bright steel-blue flower heads. Sold in bundles of 10 stems.',
            'price' => 2400,
            'stock' => 100,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade B',
            'image_url' => 'https://images.unsplash.com/photo-1560717789-0ac7c58ac90a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limonium Tiffany Diamond (Wholesale Stems)',
            'sku' => 'NB-WHL-LTD-03',
            'description' => 'Nakuru-grown Limonium offering a dense spray of dark pink and yellow florets. Sold per 20 stems.',
            'price' => 1800,
            'stock' => 250,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Chrysanthemum Baltica (Wholesale Stems)',
            'sku' => 'NB-WHL-CBA-04',
            'description' => 'Bright white double-flowering chrysanthemum stems from Kinangop. Sold in bundles of 15 stems.',
            'price' => 1500,
            'stock' => 200,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1507504038482-7621c57fc65b?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Delphinium Dark Blue (Wholesale Stems)',
            'sku' => 'NB-WHL-DDB-05',
            'description' => 'Tall spikes of intense blue delphiniums from Cenacle Farm. Ideal for volumetric fillers.',
            'price' => 2800,
            'stock' => 120,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade B',
            'image_url' => 'https://images.unsplash.com/photo-1617957602123-263a21355c6e?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 3. Premium Gifting Category (5 products) ───────────────────
        Product::create([
            'name' => 'Atelier Velvet Suite (Roses, Chocolate & Candle Chest)',
            'sku' => 'NB-GFT-AVS-01',
            'description' => 'Luxury presentation trunk containing red spray roses, Belgian truffles, and a hand-poured soy candle.',
            'price' => 16500,
            'stock' => 15,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Champagne & Bloom Chest (Champagne & White Orchids)',
            'sku' => 'NB-GFT-CBC-02',
            'description' => 'Moët & Chandon champagne nestled inside an luxury chest surrounded by pristine white orchids.',
            'price' => 24000,
            'stock' => 8,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Imperial Romance Box (Infinity Roses Curation)',
            'sku' => 'NB-GFT-IRB-03',
            'description' => 'Preserved infinity roses that retain their deep crimson hue for a full year inside a velvet box.',
            'price' => 9500,
            'stock' => 22,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Grand Gala Decor Package (Premium Event Stage Decor)',
            'sku' => 'NB-GFT-GGD-04',
            'description' => 'Complete event styling suite featuring arches, centerpieces, and pedestals composed of premium Marginpar summer flowers.',
            'price' => 85000,
            'stock' => 5,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1469371670807-013ccf25f16a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'VIP Corporate Curation Suite (Bespoke Office Floral Setup)',
            'sku' => 'NB-GFT-VCC-05',
            'description' => 'A monthly rotation package of structural retail arrays and desk arrangements curated for corporate lobbies.',
            'price' => 45000,
            'stock' => 10,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 4. Uncategorized Products (2 products) ─────────────────────
        Product::create([
            'name' => 'Atelier Ceramic Vase (Luxury Accessory)',
            'sku' => 'NB-UNC-ACV-01',
            'description' => 'Handcrafted minimalist ceramic vase in structural matte gray. Excellent for long stem styling.',
            'price' => 4200,
            'stock' => 30,
            'category' => 'uncategorized',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Wax-Sealed Card & Envelopes Set (Curation Addition)',
            'sku' => 'NB-UNC-WSC-02',
            'description' => 'Premium handmade cotton paper stationery with custom wax-seal options for personalized notes.',
            'price' => 1500,
            'stock' => 100,
            'category' => 'uncategorized',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1572021335469-31706a17aaef?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}