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

        // ── 1. Stems (using images from public/media/flowers) ───────────────────
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

        // ── 2. Bouquets (using images from public/media/flowers or slide images) ───────────────────
        Product::create([
            'name' => 'Atelier Red Rose Harmony Bouquet',
            'sku' => 'NB-BQT-RRH-01',
            'description' => 'A luxury signature bouquet of red roses, premium foliage, and fine wraps.',
            'price' => 6500,
            'cost_price' => 2500,
            'stock' => 50,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/redrosestem.jpg',
        ]);

        Product::create([
            'name' => 'Classic White Romance Bouquet',
            'sku' => 'NB-BQT-CWR-02',
            'description' => 'A pristine arrangement of pure white roses and emerald green accents.',
            'price' => 6000,
            'cost_price' => 2200,
            'stock' => 40,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/whiterosestem.jpg',
        ]);

        Product::create([
            'name' => 'Blushing Grace Pink Bouquet',
            'sku' => 'NB-BQT-BGP-03',
            'description' => 'An elegant presentation of soft pink roses in premium designer wrap.',
            'price' => 6200,
            'cost_price' => 2400,
            'stock' => 45,
            'category' => 'bouquet',
            'unit_type' => 'arrangement',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/pinkrosestem.jpg',
        ]);

        // ── 3. Wrappings & Accents (using images from public/media/wraps) ───────────────────
        Product::create([
            'name' => 'Artisanal Brown Kraft Paper',
            'sku' => 'NB-DEC-KPW-01',
            'description' => 'Artisanal brown kraft wrapping paper with natural raffia tie.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/brownkraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Elegant Grey Kraft Paper',
            'sku' => 'NB-DEC-GKP-02',
            'description' => 'Modern grey kraft wrapping paper for a sophisticated layout.',
            'price' => 600,
            'cost_price' => 200,
            'stock' => 150,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/greykraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Classic White Kraft Paper',
            'sku' => 'NB-DEC-WKP-03',
            'description' => 'Clean, minimalist white kraft wrapping paper.',
            'price' => 550,
            'cost_price' => 180,
            'stock' => 180,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/whitekraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Signature Atelier Wrap',
            'sku' => 'NB-DEC-SAW-04',
            'description' => 'Premium signature wrap featuring custom textures and accents.',
            'price' => 800,
            'cost_price' => 300,
            'stock' => 250,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Premium Textured Wrappings',
            'sku' => 'NB-DEC-PTW-05',
            'description' => 'Exquisite textured paper wrapping for a high-end luxury feel.',
            'price' => 900,
            'cost_price' => 350,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrappings.jpg',
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
            'name' => 'Handwritten Luxury Calligraphy Note',
            'sku' => 'NB-DEC-HLN-13',
            'description' => 'Handwritten calligraphy card for custom messaging.',
            'price' => 500,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrappings.jpg',
        ]);

        Product::create([
            'name' => 'Champagne Satin Ribbon',
            'sku' => 'NB-DEC-CRB-11',
            'description' => 'Bespoke champagne-colored double-faced satin ribbon tied at the bottom.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Onyx Silk Ribbon',
            'sku' => 'NB-DEC-ORB-12',
            'description' => 'Deep black silk ribbon for a moody luxury finish.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'grade' => null,
            'image_url' => '/media/wraps/wrappings.jpg',
        ]);

        // ── 4. Fragrance Mists (using public/media/wines/wine.jpg as container image) ───────────────────
        Product::create([
            'name' => 'Atelier Rosewood & Amber Mist',
            'sku' => 'NB-DEC-RWM-11',
            'description' => 'Signature fragrance mist to spray over flower arrangements for a lasting woodsy aroma.',
            'price' => 1200,
            'cost_price' => 300,
            'stock' => 150,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/wine.jpg',
        ]);

        Product::create([
            'name' => 'Limuru Lavender Fields Mist',
            'sku' => 'NB-DEC-LLM-12',
            'description' => 'Soothing, fresh lavender fragrance mist derived from Limuru fields.',
            'price' => 1000,
            'cost_price' => 250,
            'stock' => 180,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/wine.jpg',
        ]);

        // ── 5. Giftings (using images from public/media/chocolates, public/media/jewelry, public/media/wines) ───────────────────
        // Chocolates (unit_type: box)
        Product::create([
            'name' => 'Luxury Ferrero Rocher Box',
            'sku' => 'NB-HMP-FRR-01',
            'description' => 'Classic Ferrero Rocher hazelnut chocolates in a premium golden presentation box.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 120,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/Ferrero Roacher.jpg',
        ]);

        Product::create([
            'name' => 'Cadbury Dairy Milk Collection',
            'sku' => 'NB-HMP-CAD-02',
            'description' => 'Curated collection of rich Cadbury chocolates.',
            'price' => 1500,
            'cost_price' => 600,
            'stock' => 200,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/Cadbury chocolate.jpg',
        ]);

        Product::create([
            'name' => 'Kitkat Crisp Chocolate Pack',
            'sku' => 'NB-HMP-KIT-03',
            'description' => 'Crispy wafer fingers covered in smooth milk chocolate.',
            'price' => 1200,
            'cost_price' => 500,
            'stock' => 150,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/Kitkat.jpg',
        ]);

        Product::create([
            'name' => 'Snickers Peanut Chocolate Bar',
            'sku' => 'NB-HMP-SNK-04',
            'description' => 'Milk chocolate bar filled with peanut, caramel, and nougat.',
            'price' => 1200,
            'cost_price' => 500,
            'stock' => 180,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/Snikers.jpg',
        ]);

        Product::create([
            'name' => 'Artisanal Premium Chocolate Box',
            'sku' => 'NB-HMP-APB-05',
            'description' => 'Handcrafted dark and milk chocolate assortments.',
            'price' => 3000,
            'cost_price' => 1200,
            'stock' => 100,
            'category' => 'giftings',
            'unit_type' => 'box',
            'grade' => null,
            'image_url' => '/media/chocolates/chocolate.jpg',
        ]);

        // Wine (unit_type: bottle)
        Product::create([
            'name' => 'Luxury Cabernet Sauvignon Wine',
            'sku' => 'NB-HMP-LSW-06',
            'description' => 'Rich, full-bodied red wine with dark fruit aromas and oak notes.',
            'price' => 4500,
            'cost_price' => 1800,
            'stock' => 50,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'grade' => null,
            'image_url' => '/media/wines/wine.jpg',
        ]);

        // Jewelry (unit_type: jewelry)
        Product::create([
            'name' => 'Bridal Golden Rings Set',
            'sku' => 'NB-HMP-BGR-07',
            'description' => 'Elegant matching set of bridal golden rings.',
            'price' => 15000,
            'cost_price' => 6000,
            'stock' => 15,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/bridal golden rings.jpg',
        ]);

        Product::create([
            'name' => 'Lucky Clover Bracelet',
            'sku' => 'NB-HMP-LCB-08',
            'description' => 'Four-leaf clover design bracelet in luxury casing.',
            'price' => 9500,
            'cost_price' => 4000,
            'stock' => 30,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/clovers bracelet.jpg',
        ]);

        Product::create([
            'name' => 'Crescent Moon Diamond Necklace',
            'sku' => 'NB-HMP-CMN-09',
            'description' => 'Stunning crescent-shaped diamond necklace.',
            'price' => 18500,
            'cost_price' => 8000,
            'stock' => 20,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/crescent necklace.jpg',
        ]);

        Product::create([
            'name' => 'Princess Cut Diamond Silver Ring',
            'sku' => 'NB-HMP-DSR-10',
            'description' => 'Brilliant princess cut diamond mounted on premium silver band.',
            'price' => 22000,
            'cost_price' => 9000,
            'stock' => 10,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/diamond silver ring.jpg',
        ]);

        Product::create([
            'name' => 'Drop Golden Earrings',
            'sku' => 'NB-HMP-DGE-11',
            'description' => 'Luxurious drop golden earrings with a modern sleek design.',
            'price' => 8000,
            'cost_price' => 3200,
            'stock' => 25,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/drop golden earrings.jpg',
        ]);

        Product::create([
            'name' => 'Golden Mesh Bracelet',
            'sku' => 'NB-HMP-GMB-12',
            'description' => 'Woven golden mesh bracelet with absolute comfort clasp.',
            'price' => 11000,
            'cost_price' => 4500,
            'stock' => 22,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/golden bracelet.jpg',
        ]);

        Product::create([
            'name' => 'Classic Solitaire Necklace',
            'sku' => 'NB-HMP-CSN-13',
            'description' => 'Premium diamond-look solitaire necklace with a gold chain.',
            'price' => 14500,
            'cost_price' => 5800,
            'stock' => 18,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/necklace.jpg',
        ]);

        Product::create([
            'name' => 'Luxury Silver Necklace',
            'sku' => 'NB-HMP-LSN-14',
            'description' => 'Sterling silver chain with a polished crystal pendant.',
            'price' => 12500,
            'cost_price' => 5000,
            'stock' => 25,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/silver necklace.jpg',
        ]);

        Product::create([
            'name' => 'Polished Silver Wedding Ring',
            'sku' => 'NB-HMP-SWR-15',
            'description' => 'Classic, highly-polished sterling silver wedding band.',
            'price' => 8500,
            'cost_price' => 3500,
            'stock' => 40,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/silver wedding ring.jpg',
        ]);

        Product::create([
            'name' => 'Square Golden Earrings',
            'sku' => 'NB-HMP-SGE-16',
            'description' => 'Minimalist geometric square earrings finished in 18k gold plating.',
            'price' => 7500,
            'cost_price' => 3000,
            'stock' => 35,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'grade' => null,
            'image_url' => '/media/jewelry/square golden earrings.jpg',
        ]);

        // ── 6. Bespoke Specializations Category (Services) ───────────────────
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