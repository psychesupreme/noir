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

        // ── 1. Single Flower Stems (KES 250 – KES 450 per stem) ───────────────────
        Product::create([
            'name' => 'Naivasha Volcanic Red Roses (Grade A)',
            'sku' => 'NB-STM-NRO-01',
            'description' => 'Premium, long-stemmed Naomi red roses grown in volcanic soils of Lake Naivasha. Cut daily and shipped via cold chain. Sold per stem.',
            'price' => 350,
            'cost_price' => 140,
            'stock' => 500,
            'category' => 'stems',
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 350, 'cost_price' => 140, 'stock' => 500],
                ['name' => 'Bunch (5 Stems)', 'price' => 1750, 'cost_price' => 700, 'stock' => 100],
                ['name' => 'Luxe (10 Stems)', 'price' => 3500, 'cost_price' => 1400, 'stock' => 50],
            ]
        ]);

        Product::create([
            'name' => 'Naivasha Pure White Avalanche Roses (Grade A)',
            'sku' => 'NB-STM-NWR-08',
            'description' => 'Elegantly curated pure white Avalanche roses with sturdy stems and delicate floral scent. Sold per stem.',
            'price' => 300,
            'cost_price' => 120,
            'stock' => 450,
            'category' => 'stems',
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1533604131587-35661a153243?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 300, 'cost_price' => 120, 'stock' => 450],
                ['name' => 'Bunch (5 Stems)', 'price' => 1500, 'cost_price' => 600, 'stock' => 90],
                ['name' => 'Luxe (10 Stems)', 'price' => 3000, 'cost_price' => 1200, 'stock' => 45],
            ]
        ]);

        Product::create([
            'name' => 'Naivasha Soft Pink Spray Roses (Grade A)',
            'sku' => 'NB-STM-NPR-09',
            'description' => 'Exquisite soft pink spray roses sourced from rift valley floriculturists. Perfect romantic accent. Sold per stem.',
            'price' => 250,
            'cost_price' => 100,
            'stock' => 400,
            'category' => 'stems',
            'subcategory' => 'Roses',
            'unit_type' => 'stem',
            'size_unit' => 'pieces',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Single Stem', 'price' => 250, 'cost_price' => 100, 'stock' => 400],
                ['name' => 'Bunch (5 Stems)', 'price' => 1250, 'cost_price' => 500, 'stock' => 80],
                ['name' => 'Luxe (10 Stems)', 'price' => 2500, 'cost_price' => 1000, 'stock' => 40],
            ]
        ]);

        // ── 2. Bespoke Bouquets (Standard KES 2,500 | Deluxe KES 5,500 | Grand KES 12,000) ──
        Product::create([
            'name' => 'Royal Rogue Heart Arrangement',
            'sku' => 'NB-BQT-RRH-01',
            'description' => 'A luxury signature bouquet of red roses, premium eucalyptus foliage, and fine designer wraps.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 50,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Standard', 'price' => 2500, 'cost_price' => 1000, 'stock' => 50],
                ['name' => 'Deluxe', 'price' => 5500, 'cost_price' => 2200, 'stock' => 35],
                ['name' => 'Grand', 'price' => 12000, 'cost_price' => 4800, 'stock' => 20],
            ]
        ]);

        Product::create([
            'name' => 'White Avalanche Cascade Bouquet',
            'sku' => 'NB-BQT-CWR-02',
            'description' => 'A pristine arrangement of pure white roses and emerald green hypericum accents.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 40,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Standard', 'price' => 2500, 'cost_price' => 1000, 'stock' => 40],
                ['name' => 'Deluxe', 'price' => 5500, 'cost_price' => 2200, 'stock' => 28],
                ['name' => 'Grand', 'price' => 12000, 'cost_price' => 4800, 'stock' => 16],
            ]
        ]);

        Product::create([
            'name' => 'Blush Grace Petal Array',
            'sku' => 'NB-BQT-BGP-03',
            'description' => 'An elegant presentation of soft pink roses in champagne designer wrap.',
            'price' => 2500,
            'cost_price' => 1000,
            'stock' => 45,
            'category' => 'bouquet',
            'subcategory' => 'Hand-tied Bouquets',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1587334206502-747683070c77?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Standard', 'price' => 2500, 'cost_price' => 1000, 'stock' => 45],
                ['name' => 'Deluxe', 'price' => 5500, 'cost_price' => 2200, 'stock' => 31],
                ['name' => 'Grand', 'price' => 12000, 'cost_price' => 4800, 'stock' => 18],
            ]
        ]);

        // ── 3. Wrappings & Accents (Calligraphy KES 200, Ribbon KES 150, Vase KES 1,200) ───────────
        Product::create([
            'name' => 'Artisanal Brown Kraft Wrap',
            'sku' => 'NB-ACC-KPW-01',
            'description' => 'Artisanal brown kraft wrapping paper with natural raffia tie.',
            'price' => 300,
            'cost_price' => 90,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'wrap',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Signature Atelier Matte Wrap',
            'sku' => 'NB-ACC-SAW-04',
            'description' => 'Premium signature wrap featuring custom textures and gold accents.',
            'price' => 450,
            'cost_price' => 150,
            'stock' => 250,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'wrap',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Glitter Accent Petal Dusting',
            'sku' => 'NB-DEC-GLT-09',
            'description' => 'Subtle reflective premium dusting overlay for arrangement petals.',
            'price' => 300,
            'cost_price' => 50,
            'stock' => 500,
            'category' => 'accessories',
            'subcategory' => 'Glitter & Spritz',
            'unit_type' => 'spray',
            'size_unit' => 'pieces',
            'grade' => 'Standard',
            'image_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Handwritten Calligraphy Card',
            'sku' => 'NB-DEC-HLN-13',
            'description' => 'Custom handwritten calligraphy card on gold-embossed cotton stock.',
            'price' => 200,
            'cost_price' => 40,
            'stock' => 500,
            'category' => 'accessories',
            'subcategory' => 'Greeting Cards',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Artisanal',
            'image_url' => 'https://images.unsplash.com/photo-1572021335469-31706a17aaef?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Champagne Satin Ribbon',
            'sku' => 'NB-ACC-CRB-11',
            'description' => 'Bespoke champagne-colored double-faced satin ribbon (KES 150 rate).',
            'price' => 150,
            'cost_price' => 40,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'wrap',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Onyx Silk Ribbon',
            'sku' => 'NB-ACC-ORB-12',
            'description' => 'Deep black silk ribbon for a moody luxury finish.',
            'price' => 150,
            'cost_price' => 40,
            'stock' => 200,
            'category' => 'accessories',
            'subcategory' => 'Satin Ribbons',
            'unit_type' => 'wrap',
            'size_unit' => 'pieces',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Handcrafted Crystal Glass Vase',
            'sku' => 'NB-DEC-CVS-08',
            'description' => 'Heavyweight fluted crystal glass vase for long-stem arrangements.',
            'price' => 1200,
            'cost_price' => 450,
            'stock' => 100,
            'category' => 'accessories',
            'subcategory' => 'Glass Vases',
            'unit_type' => 'piece',
            'size_unit' => 'pieces',
            'grade' => 'Luxury',
            'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=600&q=80',
        ]);

        // ── 4. Fragrance Mists ───────────────────
        Product::create([
            'name' => 'Atelier Rosewood & Amber Mist',
            'sku' => 'NB-DEC-RWM-11',
            'description' => 'Botanical floral scent spray infused with rosewood and amber notes.',
            'price' => 1200,
            'cost_price' => 400,
            'stock' => 120,
            'category' => 'bundle',
            'subcategory' => 'Glitter & Spritz',
            'unit_type' => 'bottle',
            'size_unit' => 'ml',
            'grade' => 'Artisanal',
            'image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=600&q=80',
        ]);

        // ── 5. Luxury Giftings & Hampers (KES 6,500 – KES 35,000) ───────────────────
        Product::create([
            'name' => 'Ferrero Rocher Confectionery Box',
            'sku' => 'NB-GFT-FRC-01',
            'description' => 'Box of 16 hazelnut dark chocolates.',
            'price' => 2200,
            'cost_price' => 1200,
            'stock' => 100,
            'category' => 'giftings',
            'subcategory' => 'Chocolates',
            'unit_type' => 'box',
            'size_unit' => 'grams',
            'grade' => 'Premium',
            'image_url' => 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Moët & Chandon Imperial Brut Champagne',
            'sku' => 'NB-GFT-MCC-05',
            'description' => '750ml imported French Brut Champagne.',
            'price' => 12500,
            'cost_price' => 8000,
            'stock' => 40,
            'category' => 'giftings',
            'subcategory' => 'Wines & Spirits',
            'unit_type' => 'bottle',
            'size_unit' => 'ml',
            'grade' => 'Grand Reserve',
            'image_url' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'name' => 'Atelier Luxury Wine & Treats Box',
            'sku' => 'NB-HMP-LWT-01',
            'description' => 'Premium luxury gift hamper featuring vintage wine, artisanal chocolates, and velvet roses.',
            'price' => 8500,
            'cost_price' => 4000,
            'stock' => 30,
            'category' => 'hampers',
            'subcategory' => 'Luxury Hampers',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Luxury',
            'image_url' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Classic', 'price' => 8500, 'cost_price' => 4000, 'stock' => 30],
                ['name' => 'Deluxe', 'price' => 15000, 'cost_price' => 7000, 'stock' => 20],
                ['name' => 'Grand', 'price' => 35000, 'cost_price' => 16000, 'stock' => 10],
            ]
        ]);

        Product::create([
            'name' => 'Grand Presidential Celebration Hamper',
            'sku' => 'NB-HMP-GPC-02',
            'description' => 'Elite executive celebration hamper containing French Champagne, Belgian truffles, and arrangement.',
            'price' => 35000,
            'cost_price' => 16000,
            'stock' => 15,
            'category' => 'hampers',
            'subcategory' => 'Luxury Hampers',
            'unit_type' => 'arrangement',
            'size_unit' => 'pieces',
            'grade' => 'Executive',
            'image_url' => 'https://images.unsplash.com/photo-1607344645866-009c320c5ab8?auto=format&fit=crop&w=600&q=80',
            'sizes' => [
                ['name' => 'Standard', 'price' => 35000, 'cost_price' => 16000, 'stock' => 15],
            ]
        ]);
    }
}