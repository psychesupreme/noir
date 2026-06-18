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
            'cost_price' => 120,
            'stock' => 500,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/redrosestem.jpg',
        ]);

        Product::create([
            'name' => 'Limuru Pure White Lilies (Grade A)',
            'sku' => 'NB-STM-LWL-02',
            'description' => 'Stunning, multi-bloom white Oriental Lilies sourced from the misty highlands of Limuru. Offers a rich, clean fragrance. Sold per stem.',
            'price' => 450,
            'cost_price' => 180,
            'stock' => 300,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/whiterosestem.jpg',
        ]);

        Product::create([
            'name' => 'Eldoret Golden Sunflowers',
            'sku' => 'NB-STM-EGS-03',
            'description' => 'Bright, radiant golden sunflowers harvested from the rich agricultural highlands of Eldoret. Adds warmth and sunlit charm to any room. Sold per stem.',
            'price' => 350,
            'cost_price' => 140,
            'stock' => 200,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/sunflowers.svg',
        ]);

        Product::create([
            'name' => 'Nakuru Pink Carnations',
            'sku' => 'NB-STM-NPC-04',
            'description' => 'Elegant ruffle-petalled pink carnations sourced from professional growers around Lake Nakuru. Known for their long vase life. Sold per stem.',
            'price' => 250,
            'cost_price' => 100,
            'stock' => 400,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/pinkrosestem.jpg',
        ]);

        Product::create([
            'name' => 'Nairobi Blue Hydrangeas',
            'sku' => 'NB-STM-NBH-05',
            'description' => 'Large, globe-like blue hydrangeas grown in shaded valley plots of Nairobi. Ideal for creating instant focus and structural volume. Sold per stem.',
            'price' => 500,
            'cost_price' => 200,
            'stock' => 150,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/blue_hydrangeas.svg',
        ]);

        Product::create([
            'name' => 'Tsavo Purple Orchids',
            'sku' => 'NB-STM-TPO-06',
            'description' => 'Exotic and rare purple orchids sourced from temperature-controlled conservatories near Tsavo. Represents absolute luxury. Sold per stem.',
            'price' => 600,
            'cost_price' => 240,
            'stock' => 0, // OUT OF STOCK
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/purple_orchids.svg',
        ]);

        Product::create([
            'name' => 'Nyeri Sunset Orange Gerberas',
            'sku' => 'NB-STM-NSG-07',
            'description' => 'Cheerful and vibrant orange gerbera daisies cultivated under the sunny skies of Nyeri. Striking daisy-like shape. Sold per stem.',
            'price' => 280,
            'cost_price' => 110,
            'stock' => 350,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/orange_gerberas.svg',
        ]);

        Product::create([
            'name' => 'Naivasha Pure White Roses (Grade A)',
            'sku' => 'NB-STM-NWR-08',
            'description' => 'Elegantly curated pure white Naivasha roses with long strong stems and a delicate scent. Sold per stem.',
            'price' => 300,
            'cost_price' => 120,
            'stock' => 450,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/whiterosestem.jpg',
        ]);

        Product::create([
            'name' => 'Naivasha Soft Pink Roses (Grade A)',
            'sku' => 'NB-STM-NPR-09',
            'description' => 'Exquisite soft pink roses sourced from premium lakebeds of Naivasha. Perfect romantic or celebratory accent. Sold per stem.',
            'price' => 300,
            'cost_price' => 120,
            'stock' => 400,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/pinkrosestem.jpg',
        ]);

        Product::create([
            'name' => 'Thika Lavender Mum Stems',
            'sku' => 'NB-STM-TLM-10',
            'description' => 'Beautiful multi-bloom purple/lavender chrysanthemums representing absolute longevity and joy. Sourced from Thika. Sold per stem.',
            'price' => 250,
            'cost_price' => 100,
            'stock' => 300,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/orange_gerberas.svg',
        ]);

        // ── 2. Bespoke Bouquets Category ───────────────────
        Product::create([
            'name' => 'The Nairobi Obsidian Dome (Noir Signature)',
            'sku' => 'NB-BQT-NOD-01',
            'description' => 'A dramatic arrangement of 24 velvet Naivasha red roses, tightly structured in our matte black ceramic container. The ultimate luxury statement.',
            'price' => 9500,
            'cost_price' => 4500,
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
            'cost_price' => 6000,
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
            'cost_price' => 3500,
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
            'cost_price' => 9000,
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
            'cost_price' => 12000,
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
            'cost_price' => 1800,
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
            'cost_price' => 2200,
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
            'cost_price' => 3200,
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
            'cost_price' => 2500,
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
            'cost_price' => 1200,
            'stock' => 60,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1572021335469-31706a17aaef?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 5. Bespoke Specializations Category ───────────────────
        Product::create([
            'name' => 'Nairobi Atelier Custom Curation Session',
            'sku' => 'NB-SPC-NCC-01',
            'description' => 'A 1-on-1 design consultation session at our Nairobi showroom or online. We plan, budget, and select varieties for your custom floral requirements.',
            'price' => 5000,
            'cost_price' => 1000,
            'stock' => 100,
            'category' => 'specialization',
            'unit_type' => 'arrangement',
            'grade' => 'Consultation',
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Grand Gala Event Styling Package',
            'sku' => 'NB-SPC-GGE-02',
            'description' => 'Bespoke event styling, venue walkthroughs, and custom flower installations (arches, centerpieces, suspensions) led by our expert design team.',
            'price' => 95000,
            'cost_price' => 40000,
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
            'cost_price' => 18000,
            'stock' => 10,
            'category' => 'specialization',
            'unit_type' => 'arrangement',
            'grade' => 'Subscription',
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Hand Curation Service',
            'sku' => 'NB-SRV-HCS-01',
            'description' => 'Professional hand-curation by our master florists, ensuring structural balance, aesthetic perfection, and premium packaging assembly.',
            'price' => 1500,
            'cost_price' => 200,
            'stock' => 9999,
            'category' => 'specialization',
            'unit_type' => 'arrangement',
            'grade' => 'Premium Service',
            'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 6. Curation Addons & Giftings ───────────────────
        Product::create([
            'name' => 'Kraft Paper Wrapping',
            'sku' => 'NB-DEC-KPW-01',
            'description' => 'Artisanal brown kraft wrapping paper with natural raffia tie.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/kraft_paper.svg',
        ]);

        Product::create([
            'name' => 'Satin Ribbon Accent',
            'sku' => 'NB-DEC-SRA-02',
            'description' => 'Luxury double-faced satin ribbon tied in a classic atelier bow.',
            'price' => 800,
            'cost_price' => 250,
            'stock' => 150,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'French Mesh Wrapping',
            'sku' => 'NB-DEC-FMW-03',
            'description' => 'Textured black mesh wrapping adding volume and theatrical depth.',
            'price' => 1000,
            'cost_price' => 300,
            'stock' => 100,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/french_mesh.svg',
        ]);

        Product::create([
            'name' => 'Organic Burlap Wrapping',
            'sku' => 'NB-DEC-OBW-04',
            'description' => 'Rustic natural jute burlap wrap for an organic, textured appearance.',
            'price' => 600,
            'cost_price' => 180,
            'stock' => 120,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/organic_burlap.svg',
        ]);

        Product::create([
            'name' => 'Linen Wrapping',
            'sku' => 'NB-DEC-LNW-05',
            'description' => 'Premium textured linen fabric wrap for a refined, natural presentation.',
            'price' => 1200,
            'cost_price' => 400,
            'stock' => 100,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/linen_wrap.svg',
        ]);

        Product::create([
            'name' => 'Premium Obsidian Gift Box',
            'sku' => 'NB-DEC-PGB-06',
            'description' => 'Signature atelier black-stone luxury gift box packaging.',
            'price' => 2500,
            'cost_price' => 800,
            'stock' => 80,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/boxed.svg',
        ]);

        Product::create([
            'name' => 'Glass Vase Presentation',
            'sku' => 'NB-DEC-GVP-07',
            'description' => 'Pre-arranged inside an elegant crystal clear glass display vase.',
            'price' => 3500,
            'cost_price' => 1200,
            'stock' => 50,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/glass_vase.svg',
        ]);

        Product::create([
            'name' => 'Bespoke Woven Wrapping',
            'sku' => 'NB-DEC-BWW-08',
            'description' => 'Bespoke woven premium mesh and natural fiber wrap.',
            'price' => 1500,
            'cost_price' => 500,
            'stock' => 100,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/linen_wrap.svg',
        ]);

        Product::create([
            'name' => 'Glitter Accent',
            'sku' => 'NB-DEC-GLT-09',
            'description' => 'Subtle reflective premium dusting overlay for petals.',
            'price' => 400,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Purple Satin Ribbon',
            'sku' => 'NB-DEC-PRB-10',
            'description' => 'Bespoke royal purple double-faced satin ribbon tied at the bottom.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Royal Gold Ribbon',
            'sku' => 'NB-DEC-GRB-11',
            'description' => 'Bespoke gold double-faced satin ribbon tied at the bottom.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Scarlet Red Ribbon',
            'sku' => 'NB-DEC-RRB-12',
            'description' => 'Bespoke scarlet red double-faced satin ribbon tied at the bottom.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Handwritten Luxury Calligraphy Note',
            'sku' => 'NB-DEC-HLN-13',
            'description' => 'Handwritten calligraphy card for custom messaging.',
            'price' => 500,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Premium South African Merlot',
            'sku' => 'NB-HMP-PSM-01',
            'description' => 'Rich, full-bodied red wine with dark fruit aromas and oak notes.',
            'price' => 4500,
            'cost_price' => 1800,
            'stock' => 50,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/merlot.svg',
        ]);

        Product::create([
            'name' => 'Moët & Chandon Imperial Brut',
            'sku' => 'NB-HMP-MCI-02',
            'description' => 'Distinguished French Champagne characterized by bright fruitiness and elegant maturity.',
            'price' => 12500,
            'cost_price' => 6500,
            'stock' => 30,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/moet.svg',
        ]);

        Product::create([
            'name' => 'Chamdor Non-Alcoholic Sparkling Wine',
            'sku' => 'NB-HMP-CNS-03',
            'description' => 'Sweet, carbonated non-alcoholic sparkling grape juice.',
            'price' => 3000,
            'cost_price' => 1200,
            'stock' => 100,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/chamdor.svg',
        ]);

        Product::create([
            'name' => 'Four Cousins Sweet Red Wine (750ml)',
            'sku' => 'NB-HMP-FCW-05',
            'description' => 'Sweet, exotic, easy-drinking red wine from South Africa. Very popular.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 120,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/merlot.svg',
        ]);

        Product::create([
            'name' => 'Artisanal Belgian Truffles Box',
            'sku' => 'NB-HMP-ABT-04',
            'description' => 'A curated selection of 16 hand-crafted luxury Belgian chocolate truffles.',
            'price' => 2800,
            'cost_price' => 1100,
            'stock' => 120,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/belgian_truffles.svg',
        ]);

        Product::create([
            'name' => 'Dark Hazelnut Pralines Box',
            'sku' => 'NB-CHOC-DHP-01',
            'description' => 'A box of 12 rich 70% dark Belgian chocolate pralines filled with roasted hazelnut paste.',
            'price' => 3200,
            'cost_price' => 1300,
            'stock' => 150,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/dark_hazelnut.svg',
        ]);

        Product::create([
            'name' => 'Champagne Strawberry Truffles',
            'sku' => 'NB-CHOC-CST-02',
            'description' => 'Delicate white chocolate truffles infused with Marc de Champagne and rolled in strawberry powder.',
            'price' => 3500,
            'cost_price' => 1400,
            'stock' => 110,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/strawberry_truffles.svg',
        ]);

        Product::create([
            'name' => 'Cadbury Dairy Milk Chocolate Bar (250g)',
            'sku' => 'NB-CHOC-CMB-03',
            'description' => 'Classic, rich dairy milk chocolate bar perfect for sweet cravings.',
            'price' => 600,
            'cost_price' => 250,
            'stock' => 200,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/dark_hazelnut.svg',
        ]);

        Product::create([
            'name' => 'Bespoke Gold Pearl Necklace',
            'sku' => 'NB-JWL-GPN-01',
            'description' => 'An elegant 18k gold chain adorned with a premium coastal freshwater pearl.',
            'price' => 15000,
            'cost_price' => 6000,
            'stock' => 20,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/pearl_necklace.svg',
        ]);

        Product::create([
            'name' => 'Rift Valley Obsidian Bracelet',
            'sku' => 'NB-JWL-ROB-02',
            'description' => 'Handcrafted bracelet featuring polished black obsidian beads from Rift Valley volcanic sites.',
            'price' => 9500,
            'cost_price' => 3800,
            'stock' => 35,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/obsidian_bracelet.svg',
        ]);

        Product::create([
            'name' => 'Champagne Diamond Studs',
            'sku' => 'NB-JWL-CDS-03',
            'description' => 'Exquisite pair of 0.25 carat brilliant-cut champagne diamonds set in solid rose gold.',
            'price' => 28000,
            'cost_price' => 12000,
            'stock' => 15,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/diamond_studs.svg',
        ]);

        Product::create([
            'name' => 'Round Golden Earrings (Gift Boxed)',
            'sku' => 'NB-JWL-RGE-04',
            'description' => 'Bespoke 18k gold-plated round earrings presented in a luxury red velvet box.',
            'price' => 4500,
            'cost_price' => 1800,
            'stock' => 50,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/pearl_necklace.svg',
        ]);

        Product::create([
            'name' => 'Yves Saint Laurent Black Opium Perfume (90ml)',
            'sku' => 'NB-GIF-YSL-05',
            'description' => 'Luxury couture fragrance with coffee, vanilla, and white flower notes.',
            'price' => 18500,
            'cost_price' => 9000,
            'stock' => 0, // OUT OF STOCK
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/diamond_studs.svg',
        ]);

        Product::create([
            'name' => 'Atelier Rosewood & Amber Mist',
            'sku' => 'NB-DEC-ARA-04',
            'description' => 'Signature fragrance mist to spray over flower arrangements for a lasting woodsy aroma.',
            'price' => 1500,
            'cost_price' => 450,
            'stock' => 90,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/flowers/rosewood_mist.svg',
        ]);

        Product::create([
            'name' => 'Limuru Lavender Fields Mist',
            'sku' => 'NB-DEC-LLF-05',
            'description' => 'Soothing, fresh lavender fragrance mist derived from Limuru fields.',
            'price' => 1200,
            'cost_price' => 350,
            'stock' => 110,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/flowers/lavender_mist.svg',
        ]);

        // ── 7. Car Customization Products (Specialization) ──
        Product::create([
            'name' => 'Model S Dual Motor',
            'sku' => 'CAR-MODEL-S',
            'description' => 'Model S Dual Motor All-Wheel Drive. Luxury performance sedan.',
            'price' => 74990,
            'cost_price' => 45000,
            'stock' => 99,
            'category' => 'specialization',
            'unit_type' => 'car',
            'image_url' => '/media/cars/car_profile.svg',
        ]);

        Product::create([
            'name' => 'Model S Plaid Tri-Motor',
            'sku' => 'CAR-MODEL-S-PLAID',
            'description' => 'Model S Plaid Tri-Motor All-Wheel Drive. High-performance electric sedan.',
            'price' => 89990,
            'cost_price' => 55000,
            'stock' => 99,
            'category' => 'specialization',
            'unit_type' => 'car',
            'image_url' => '/media/cars/car_profile.svg',
        ]);

        Product::create([
            'name' => 'Stealth Grey Paint',
            'sku' => 'CAR-PAINT-GREY',
            'description' => 'Sleek Stealth Grey metallic exterior paint.',
            'price' => 0,
            'cost_price' => 0,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'paint',
        ]);

        Product::create([
            'name' => 'Pearl White Multi-Coat Paint',
            'sku' => 'CAR-PAINT-WHITE',
            'description' => 'Premium Pearl White multi-coat paint.',
            'price' => 1500,
            'cost_price' => 300,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'paint',
        ]);

        Product::create([
            'name' => 'Deep Blue Metallic Paint',
            'sku' => 'CAR-PAINT-BLUE',
            'description' => 'Premium Deep Blue metallic paint.',
            'price' => 1500,
            'cost_price' => 300,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'paint',
        ]);

        Product::create([
            'name' => 'Solid Black Paint',
            'sku' => 'CAR-PAINT-BLACK',
            'description' => 'Classic Solid Black paint.',
            'price' => 1500,
            'cost_price' => 300,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'paint',
        ]);

        Product::create([
            'name' => 'Ultra Red Paint',
            'sku' => 'CAR-PAINT-RED',
            'description' => 'Signature Ultra Red high-gloss paint.',
            'price' => 2500,
            'cost_price' => 500,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'paint',
        ]);

        Product::create([
            'name' => '19" Tempest Wheels',
            'sku' => 'CAR-WHEELS-TEMPEST',
            'description' => 'Standard 19" Tempest aerodynamic wheels.',
            'price' => 0,
            'cost_price' => 0,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'wheels',
            'image_url' => '/media/cars/wheels_tempest.svg',
        ]);

        Product::create([
            'name' => '21" Arachnid Wheels',
            'sku' => 'CAR-WHEELS-ARACHNID',
            'description' => 'Performance 21" Arachnid multi-spoke wheels.',
            'price' => 4500,
            'cost_price' => 1000,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'wheels',
            'image_url' => '/media/cars/wheels_arachnid.svg',
        ]);

        Product::create([
            'name' => 'All Black Premium Interior',
            'sku' => 'CAR-INTERIOR-BLACK',
            'description' => 'All Black Premium interior with Ebony decor.',
            'price' => 0,
            'cost_price' => 0,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'interior',
            'image_url' => '/media/cars/interior_black.svg',
        ]);

        Product::create([
            'name' => 'Black and White Premium Interior',
            'sku' => 'CAR-INTERIOR-WHITE',
            'description' => 'Black and White Premium interior with Walnut decor.',
            'price' => 2000,
            'cost_price' => 400,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'interior',
            'image_url' => '/media/cars/interior_white.svg',
        ]);

        Product::create([
            'name' => 'Cream Premium Interior',
            'sku' => 'CAR-INTERIOR-CREAM',
            'description' => 'Cream Premium interior with Walnut decor.',
            'price' => 2000,
            'cost_price' => 400,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'interior',
            'image_url' => '/media/cars/interior_cream.svg',
        ]);

        Product::create([
            'name' => 'Full Self-Driving Capability',
            'sku' => 'CAR-ACC-FSD',
            'description' => 'Autopilot, Navigate on Autopilot, Auto Lane Change, Autopark, Summon, and Full Self-Driving computer.',
            'price' => 12000,
            'cost_price' => 2000,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'accessory',
        ]);

        Product::create([
            'name' => 'Wall Connector',
            'sku' => 'CAR-ACC-WALL',
            'description' => 'Home charging solution for convenient overnight charging.',
            'price' => 475,
            'cost_price' => 150,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'accessory',
        ]);

        Product::create([
            'name' => 'Mobile Connector',
            'sku' => 'CAR-ACC-MOBILE',
            'description' => 'On-the-go charging solution using standard outlets.',
            'price' => 230,
            'cost_price' => 80,
            'stock' => 999,
            'category' => 'specialization',
            'unit_type' => 'accessory',
        ]);
    }
}