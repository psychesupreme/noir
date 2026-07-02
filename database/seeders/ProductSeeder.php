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
        // ── Clean Existing Data safely without touching user credentials ──────────
        Schema::disableForeignKeyConstraints();
        
        // Products and related tables
        Product::truncate();
        DB::table('occasion_product')->truncate();
        DB::table('branch_product_stock')->truncate();
        DB::table('inventory_logs')->truncate();
        DB::table('wastage_logs')->truncate();
        DB::table('reviews')->truncate();
        
        // Procurement
        DB::table('purchase_order_items')->truncate();
        DB::table('purchase_orders')->truncate();
        
        // Orders, Invoices & Payments
        DB::table('order_product')->truncate();
        DB::table('etims_invoices')->truncate();
        DB::table('accounts_receivable_payments')->truncate();
        DB::table('accounts_receivable_invoices')->truncate();
        DB::table('payments')->truncate();
        DB::table('orders')->truncate();
        
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
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/redrosestem.jpg',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 300, 'cost_price' => 120, 'stock' => 500],
                ['name' => 'Bunch (5 Stems)', 'price' => 1200, 'cost_price' => 480, 'stock' => 100],
                ['name' => 'Luxe (10 Stems)', 'price' => 2400, 'cost_price' => 960, 'stock' => 50],
            ]
        ]);

        Product::create([
            'name' => 'Naivasha Pure White Roses (Grade A)',
            'sku' => 'NB-STM-NWR-08',
            'description' => 'Elegantly curated pure white Naivasha roses with long strong stems and a delicate scent. Sold per stem.',
            'price' => 300,
            'cost_price' => 120,
            'stock' => 450,
            'category' => 'stems',
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/whiterosestem.jpg',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 300, 'cost_price' => 120, 'stock' => 450],
                ['name' => 'Bunch (5 Stems)', 'price' => 1200, 'cost_price' => 480, 'stock' => 90],
                ['name' => 'Luxe (10 Stems)', 'price' => 2400, 'cost_price' => 960, 'stock' => 45],
            ]
        ]);

        Product::create([
            'name' => 'Naivasha Soft Pink Roses (Grade A)',
            'sku' => 'NB-STM-NPR-09',
            'description' => 'Exquisite soft pink roses sourced from premium lakebeds of Naivasha. Perfect romantic or celebratory accent. Sold per stem.',
            'price' => 300,
            'cost_price' => 120,
            'stock' => 400,
            'category' => 'stems',
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/pinkrosestem.jpg',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 300, 'cost_price' => 120, 'stock' => 400],
                ['name' => 'Bunch (5 Stems)', 'price' => 1200, 'cost_price' => 480, 'stock' => 80],
                ['name' => 'Luxe (10 Stems)', 'price' => 2400, 'cost_price' => 960, 'stock' => 40],
            ]
        ]);

        // ── 2. Bouquets (using images from public/media/flowers) ───────────────────
        Product::create([
            'name' => 'Atelier Red Rose Harmony Bouquet',
            'sku' => 'NB-BQT-RRH-01',
            'description' => 'A luxury signature bouquet of red roses, premium foliage, and fine wraps.',
            'price' => 6500,
            'cost_price' => 2500,
            'stock' => 50,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/bouquet1.jpg',
            'sizes' => [
                ['name' => 'Classic', 'price' => 6500, 'cost_price' => 2500, 'stock' => 50],
                ['name' => 'Deluxe', 'price' => 9750, 'cost_price' => 3750, 'stock' => 35],
                ['name' => 'Grand', 'price' => 13000, 'cost_price' => 5000, 'stock' => 20],
            ]
        ]);

        Product::create([
            'name' => 'Classic White Romance Bouquet',
            'sku' => 'NB-BQT-CWR-02',
            'description' => 'A pristine arrangement of pure white roses and emerald green accents.',
            'price' => 6000,
            'cost_price' => 2200,
            'stock' => 40,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/whiterosesbouquet.jpg',
            'sizes' => [
                ['name' => 'Classic', 'price' => 6000, 'cost_price' => 2200, 'stock' => 40],
                ['name' => 'Deluxe', 'price' => 9000, 'cost_price' => 3300, 'stock' => 28],
                ['name' => 'Grand', 'price' => 12000, 'cost_price' => 4400, 'stock' => 16],
            ]
        ]);

        Product::create([
            'name' => 'Blushing Grace Pink Bouquet',
            'sku' => 'NB-BQT-BGP-03',
            'description' => 'An elegant presentation of soft pink roses in premium designer wrap.',
            'price' => 6200,
            'cost_price' => 2400,
            'stock' => 45,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/flowers/pinkbouquet.jpg',
            'sizes' => [
                ['name' => 'Classic', 'price' => 6200, 'cost_price' => 2400, 'stock' => 45],
                ['name' => 'Deluxe', 'price' => 9300, 'cost_price' => 3600, 'stock' => 31],
                ['name' => 'Grand', 'price' => 12400, 'cost_price' => 4800, 'stock' => 18],
            ]
        ]);

        // ── 3. Wrappings & Accents (using images from public/media/wraps) ───────────────────
        Product::create([
            'name' => 'Artisanal Brown Kraft Paper',
            'sku' => 'NB-ACC-KPW-01',
            'description' => 'Artisanal brown kraft wrapping paper with natural raffia tie.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wraps/brownkraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Elegant Grey Kraft Paper',
            'sku' => 'NB-ACC-GKP-02',
            'description' => 'Modern grey kraft wrapping paper for a sophisticated layout.',
            'price' => 600,
            'cost_price' => 200,
            'stock' => 150,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wraps/greykraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Classic White Kraft Paper',
            'sku' => 'NB-ACC-WKP-03',
            'description' => 'Clean, minimalist white kraft wrapping paper.',
            'price' => 550,
            'cost_price' => 180,
            'stock' => 180,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wraps/whitekraft paper.jpg',
        ]);

        Product::create([
            'name' => 'Signature Atelier Wrap',
            'sku' => 'NB-ACC-SAW-04',
            'description' => 'Premium signature wrap featuring custom textures and accents.',
            'price' => 800,
            'cost_price' => 300,
            'stock' => 250,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Premium Textured Wrappings',
            'sku' => 'NB-ACC-PTW-05',
            'description' => 'Exquisite textured paper wrapping for a high-end luxury feel.',
            'price' => 900,
            'cost_price' => 350,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/wraps/wrappings.jpg',
        ]);

        Product::create([
            'name' => 'Glitter Accent Spray',
            'sku' => 'NB-ACC-GLT-09',
            'description' => 'Subtle reflective premium dusting overlay for petals.',
            'price' => 400,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'accessories',
            'subcategory' => 'Glitter & Spritz',
            'unit_type' => 'spray',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Handwritten Luxury Calligraphy Note',
            'sku' => 'NB-ACC-HLN-13',
            'description' => 'Handwritten calligraphy card for custom messaging.',
            'price' => 500,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'accessories',
            'subcategory' => 'Greeting Cards',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Artisanal',
            'image_url' => '/media/wraps/wrappings.jpg',
        ]);

        Product::create([
            'name' => 'Champagne Satin Ribbon',
            'sku' => 'NB-ACC-CRB-11',
            'description' => 'Bespoke champagne-colored double-faced satin ribbon tied at the bottom.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        Product::create([
            'name' => 'Onyx Silk Ribbon',
            'sku' => 'NB-ACC-ORB-12',
            'description' => 'Deep black silk ribbon for a moody luxury finish.',
            'price' => 500,
            'cost_price' => 150,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'roll',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
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
            'category' => 'home_decor',
            'subcategory' => 'Aroma Diffusers',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wines/wine.jpg',
        ]);

        Product::create([
            'name' => 'Limuru Lavender Fields Mist',
            'sku' => 'NB-DEC-LLM-12',
            'description' => 'Soothing, fresh lavender fragrance mist derived from Limuru fields.',
            'price' => 1000,
            'cost_price' => 250,
            'stock' => 180,
            'category' => 'home_decor',
            'subcategory' => 'Aroma Diffusers',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/wines/wine.jpg',
        ]);

        // ── 5. Giftings (using images from public/media/chocolates, public/media/jewelry, public/media/wines) ───────────────────
        // Chocolates (unit_type: box)
        Product::create([
            'name' => 'Luxury Ferrero Rocher Box',
            'sku' => 'NB-GFT-FRR-01',
            'description' => 'Classic Ferrero Rocher hazelnut chocolates in a premium golden presentation box.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 120,
            'category' => 'giftings',
            'subcategory' => 'Chocolates & Truffles',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Premium',
            'image_url' => '/media/chocolates/Ferrero Roacher.jpg',
            'sizes' => [
                ['name' => '30g Petite', 'price' => 750, 'cost_price' => 300, 'stock' => 120],
                ['name' => '100g Classic', 'price' => 2500, 'cost_price' => 1000, 'stock' => 84],
                ['name' => '400g Grand', 'price' => 7500, 'cost_price' => 3000, 'stock' => 48],
            ]
        ]);

        Product::create([
            'name' => 'Cadbury Dairy Milk Collection',
            'sku' => 'NB-GFT-CAD-02',
            'description' => 'Curated collection of rich Cadbury chocolates.',
            'price' => 1500,
            'cost_price' => 600,
            'stock' => 200,
            'category' => 'giftings',
            'subcategory' => 'Chocolates & Truffles',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Standard',
            'image_url' => '/media/chocolates/Cadbury chocolate.jpg',
            'sizes' => [
                ['name' => '30g Petite', 'price' => 450, 'cost_price' => 180, 'stock' => 200],
                ['name' => '100g Classic', 'price' => 1500, 'cost_price' => 600, 'stock' => 140],
                ['name' => '400g Grand', 'price' => 4500, 'cost_price' => 1800, 'stock' => 80],
            ]
        ]);

        Product::create([
            'name' => 'Kitkat Crisp Chocolate Pack',
            'sku' => 'NB-GFT-KIT-03',
            'description' => 'Crispy wafer fingers covered in smooth milk chocolate.',
            'price' => 1200,
            'cost_price' => 500,
            'stock' => 150,
            'category' => 'giftings',
            'subcategory' => 'Chocolates & Truffles',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Standard',
            'image_url' => '/media/chocolates/Kitkat.jpg',
            'sizes' => [
                ['name' => '30g Petite', 'price' => 360, 'cost_price' => 150, 'stock' => 150],
                ['name' => '100g Classic', 'price' => 1200, 'cost_price' => 500, 'stock' => 105],
                ['name' => '400g Grand', 'price' => 3600, 'cost_price' => 1500, 'stock' => 60],
            ]
        ]);

        Product::create([
            'name' => 'Snickers Peanut Chocolate Bar',
            'sku' => 'NB-GFT-SNK-04',
            'description' => 'Milk chocolate bar filled with peanut, caramel, and nougat.',
            'price' => 1200,
            'cost_price' => 500,
            'stock' => 180,
            'category' => 'giftings',
            'subcategory' => 'Chocolates & Truffles',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Standard',
            'image_url' => '/media/chocolates/Snikers.jpg',
            'sizes' => [
                ['name' => '30g Petite', 'price' => 360, 'cost_price' => 150, 'stock' => 180],
                ['name' => '100g Classic', 'price' => 1200, 'cost_price' => 500, 'stock' => 126],
                ['name' => '400g Grand', 'price' => 3600, 'cost_price' => 1500, 'stock' => 72],
            ]
        ]);

        Product::create([
            'name' => 'Artisanal Premium Chocolate Box',
            'sku' => 'NB-GFT-APB-05',
            'description' => 'Handcrafted dark and milk chocolate assortments.',
            'price' => 3000,
            'cost_price' => 1200,
            'stock' => 100,
            'category' => 'giftings',
            'subcategory' => 'Chocolates & Truffles',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Premium',
            'image_url' => '/media/chocolates/chocolate.jpg',
            'sizes' => [
                ['name' => '30g Petite', 'price' => 900, 'cost_price' => 360, 'stock' => 100],
                ['name' => '100g Classic', 'price' => 3000, 'cost_price' => 1200, 'stock' => 70],
                ['name' => '400g Grand', 'price' => 9000, 'cost_price' => 3600, 'stock' => 40],
            ]
        ]);

        // Wine (unit_type: bottle)
        Product::create([
            'name' => 'Luxury Cabernet Sauvignon Wine',
            'sku' => 'NB-GFT-LSW-06',
            'description' => 'Rich, full-bodied red wine with dark fruit aromas and oak notes.',
            'price' => 4500,
            'cost_price' => 1800,
            'stock' => 50,
            'category' => 'giftings',
            'subcategory' => 'Wines & Champagnes',
            'unit_type' => 'bottle',
            'size_unit' => 'litres',
            'grade' => 'Premium',
            'image_url' => '/media/wines/wine.jpg',
            'sizes' => [
                ['name' => '375ml Half', 'price' => 2700, 'cost_price' => 1080, 'stock' => 50],
                ['name' => '750ml Standard', 'price' => 4500, 'cost_price' => 1800, 'stock' => 35],
                ['name' => '1.5L Magnum', 'price' => 9000, 'cost_price' => 3600, 'stock' => 15],
            ]
        ]);

        // Jewelry (unit_type: set/piece)
        Product::create([
            'name' => 'Bridal Golden Rings Set',
            'sku' => 'NB-GFT-BGR-07',
            'description' => 'Elegant matching set of bridal golden rings.',
            'price' => 15000,
            'cost_price' => 6000,
            'stock' => 15,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'set',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/jewelry/bridal golden rings.jpg',
        ]);

        Product::create([
            'name' => 'Lucky Clover Bracelet',
            'sku' => 'NB-GFT-LCB-08',
            'description' => 'Four-leaf clover design bracelet in luxury casing.',
            'price' => 9500,
            'cost_price' => 4000,
            'stock' => 30,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/jewelry/clovers bracelet.jpg',
        ]);

        Product::create([
            'name' => 'Crescent Moon Diamond Necklace',
            'sku' => 'NB-GFT-CMN-09',
            'description' => 'Stunning crescent-shaped diamond necklace.',
            'price' => 18500,
            'cost_price' => 8000,
            'stock' => 20,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/jewelry/crescent necklace.jpg',
        ]);

        Product::create([
            'name' => 'Princess Cut Diamond Silver Ring',
            'sku' => 'NB-GFT-DSR-10',
            'description' => 'Brilliant princess cut diamond mounted on premium silver band.',
            'price' => 22000,
            'cost_price' => 9000,
            'stock' => 10,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/jewelry/diamond silver ring.jpg',
        ]);

        Product::create([
            'name' => 'Drop Golden Earrings',
            'sku' => 'NB-GFT-DGE-11',
            'description' => 'Luxurious drop golden earrings with a modern sleek design.',
            'price' => 8000,
            'cost_price' => 3200,
            'stock' => 25,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'set',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/jewelry/drop golden earrings.jpg',
        ]);

        Product::create([
            'name' => 'Golden Mesh Bracelet',
            'sku' => 'NB-GFT-GMB-12',
            'description' => 'Woven golden mesh bracelet with absolute comfort clasp.',
            'price' => 11000,
            'cost_price' => 4500,
            'stock' => 22,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/jewelry/golden bracelet.jpg',
        ]);

        Product::create([
            'name' => 'Classic Solitaire Necklace',
            'sku' => 'NB-GFT-CSN-13',
            'description' => 'Premium diamond-look solitaire necklace with a gold chain.',
            'price' => 14500,
            'cost_price' => 5800,
            'stock' => 18,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/jewelry/necklace.jpg',
        ]);

        Product::create([
            'name' => 'Luxury Silver Necklace',
            'sku' => 'NB-GFT-LSN-14',
            'description' => 'Sterling silver chain with a polished crystal pendant.',
            'price' => 12500,
            'cost_price' => 5000,
            'stock' => 25,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => '/media/jewelry/silver necklace.jpg',
        ]);

        Product::create([
            'name' => 'Polished Silver Wedding Ring',
            'sku' => 'NB-GFT-SWR-15',
            'description' => 'Classic, highly-polished sterling silver wedding band.',
            'price' => 8500,
            'cost_price' => 3500,
            'stock' => 40,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => '/media/jewelry/silver wedding ring.jpg',
        ]);

        Product::create([
            'name' => 'Square Golden Earrings',
            'sku' => 'NB-GFT-SGE-16',
            'description' => 'Minimalist geometric square earrings finished in 18k gold plating.',
            'price' => 7500,
            'cost_price' => 3000,
            'stock' => 35,
            'category' => 'giftings',
            'subcategory' => 'Home Scents & Mists',
            'unit_type' => 'set',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
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
            'subcategory' => 'Hand Curation Desk',
            'unit_type' => 'consultation',
            'size_unit' => 'service',
            'grade' => 'Consultation',
            'image_url' => '/media/flowers/vaseflowers.jpg',
        ]);

        Product::create([
            'name' => 'Atelier Grand Gala Event Styling Package',
            'sku' => 'NB-SPC-GGE-02',
            'description' => 'Bespoke event styling, venue walkthroughs, and custom flower installations (arches, centerpieces, suspensions) led by our expert design team.',
            'price' => 95000,
            'cost_price' => 40000,
            'stock' => 5,
            'category' => 'specialization',
            'subcategory' => 'Event Installations',
            'unit_type' => 'event',
            'size_unit' => 'service',
            'grade' => 'Event Styling',
            'image_url' => '/media/flowers/whiterosesbouquet.jpg',
        ]);

        Product::create([
            'name' => 'VIP Corporate Weekly Curation Subscription',
            'sku' => 'NB-SPC-VCS-03',
            'description' => 'Tailored weekly rotation of corporate workspace flower arrangements, entry lobby structures, and meeting desk bowls matching brand guidelines.',
            'price' => 45000,
            'cost_price' => 18000,
            'stock' => 10,
            'category' => 'specialization',
            'subcategory' => 'Corporate Subscriptions',
            'unit_type' => 'rotation',
            'size_unit' => 'service',
            'grade' => 'Subscription',
            'image_url' => '/media/flowers/bouquet5.jpg',
        ]);

        Product::create([
            'name' => 'Atelier Hand Curation Service',
            'sku' => 'NB-SRV-HCS-01',
            'description' => 'Professional hand-curation by our master florists, ensuring structural balance, aesthetic perfection, and premium packaging assembly.',
            'price' => 1500,
            'cost_price' => 200,
            'stock' => 9999,
            'category' => 'specialization',
            'subcategory' => 'Hand Curation Desk',
            'unit_type' => 'consultation',
            'size_unit' => 'service',
            'grade' => 'Premium Service',
            'image_url' => '/media/flowers/whiterosestem.jpg',
        ]);
    }
}