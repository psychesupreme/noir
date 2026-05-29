<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Curated Retail Category ───────────────────────────────────────
        Product::create([
            'name' => 'The Crimson Noir Arrangement',
            'sku' => 'NB-RET-CRM-01',
            'description' => 'Deep velvet premium structural arrangement in geometric charcoal structural vases.',
            'price' => 8500,
            'stock' => 15,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Alabaster Orchids',
            'sku' => 'NB-RET-ORC-02',
            'description' => 'Pristine white double-stem Phalaenopsis orchids housed inside structural stone casing.',
            'price' => 12500,
            'stock' => 8,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Midnight Calla Curation',
            'sku' => 'NB-RET-CAL-03',
            'description' => 'Jet-black and dark purple calla lilies arranged minimally in textured basalt rock cylinders.',
            'price' => 7200,
            'stock' => 12,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1587334206574-35113abf512a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Emerald Garden Silhouette',
            'sku' => 'NB-RET-EMR-04',
            'description' => 'Architectural monstera, leather leaf, and jade green ranunculus curated for corporate desks.',
            'price' => 9800,
            'stock' => 20,
            'category' => 'retail',
            'unit_type' => 'arrangement',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 2. Wholesale Graded Stems Category ───────────────────────────────
        Product::create([
            'name' => 'Naivasha Red Roses (Grade A)',
            'sku' => 'NB-WHL-ROS-0A',
            'description' => 'Premium export-grade long stems (60cm+), large heads. Sold per bundle of 20 stems.',
            'price' => 1800,
            'stock' => 200,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Limuru White Lilies (Grade B)',
            'sku' => 'NB-WHL-LIL-0B',
            'description' => 'Standard local market grade commercial processing stems. Sold per bundle of 10 stems.',
            'price' => 2400,
            'stock' => 120,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade B',
            'image_url' => 'https://images.unsplash.com/photo-1560717789-0ac7c58ac90a?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Atelier Hydrangeas (Grade A)',
            'sku' => 'NB-WHL-HYD-0A',
            'description' => 'Plush blue hydrangeas with thick heads. Sold per bundle of 5 stems.',
            'price' => 3800,
            'stock' => 80,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Naivasha Spray Roses (Grade A)',
            'sku' => 'NB-WHL-SPR-0A',
            'description' => 'Multiblossom delicate pink spray roses. Sold per bundle of 10 stems.',
            'price' => 1500,
            'stock' => 150,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1562240020-ce31ccb0fa7d?auto=format&fit=crop&q=80&w=600',
        ]);

        // ── 3. Premium Gifting Category ──────────────────────────────────────
        Product::create([
            'name' => 'The Atelier Gourmet Hamper',
            'sku' => 'NB-GFT-HAM-04',
            'description' => 'Curated luxury gifting pairing: dark chocolates, Naivasha spray roses, and premium wine.',
            'price' => 16500,
            'stock' => 10,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'Midnight Truffle & Flora Case',
            'sku' => 'NB-GFT-TRF-05',
            'description' => 'Artisanal chocolate truffles paired with mini black-box velvet roses and coordinate card.',
            'price' => 9500,
            'stock' => 18,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&q=80&w=600',
        ]);

        Product::create([
            'name' => 'The Champagne & Bloom Chest',
            'sku' => 'NB-GFT-CHM-06',
            'description' => 'Vintage French Champagne paired with premium white orchids in a velvet-lined wooden chest.',
            'price' => 24000,
            'stock' => 5,
            'category' => 'gifting',
            'unit_type' => 'hamper',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}