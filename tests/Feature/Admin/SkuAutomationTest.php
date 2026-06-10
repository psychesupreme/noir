<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SkuAutomationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Branch $branchNairobi;
    private Branch $branchKiambu;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        // Create/Retrieve Default Branches
        $this->branchNairobi = Branch::where('code', 'NB-NBO')->first() ?: Branch::create([
            'name' => 'Nairobi Central Atelier',
            'code' => 'NB-NBO',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        $this->branchKiambu = Branch::where('code', 'NB-KBU')->first() ?: Branch::create([
            'name' => 'Kiambu Ridge Hub',
            'code' => 'NB-KBU',
            'location_city' => 'Kiambu',
            'is_active' => true,
        ]);
    }

    /**
     * Test that SKU generates NRB for Nairobi store and KMB for Kiambu hub,
     * using correct category letters and sequence indexing.
     */
    public function test_automatic_sku_generation_on_nairobi_and_kiambu(): void
    {
        $this->actingAs($this->admin);

        // 1. Create a product on Nairobi branch with stems category
        Livewire::test(\App\Livewire\Admin\ProductIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Naivasha Volcanic Red Roses')
            ->set('sku', '') // Leave SKU empty to test auto-generation
            ->set('category', 'stems')
            ->set('unit_type', 'stem')
            ->set('price', 300)
            ->set('cost_price', 120)
            ->set('stock', 100)
            ->set('selectedBranchId', $this->branchNairobi->id)
            ->call('save')
            ->assertHasNoErrors();

        // Retrieve and assert product
        $product1 = Product::where('name', 'Naivasha Volcanic Red Roses')->first();
        $this->assertNotNull($product1);
        $this->assertEquals('NRB-S-0001', $product1->sku);

        // 2. Create another product on Nairobi branch with stems category to check incrementing
        Livewire::test(\App\Livewire\Admin\ProductIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Limuru Pure White Lilies')
            ->set('sku', '')
            ->set('category', 'stems')
            ->set('unit_type', 'stem')
            ->set('price', 450)
            ->set('cost_price', 180)
            ->set('stock', 50)
            ->set('selectedBranchId', $this->branchNairobi->id)
            ->call('save')
            ->assertHasNoErrors();

        $product2 = Product::where('name', 'Limuru Pure White Lilies')->first();
        $this->assertNotNull($product2);
        $this->assertEquals('NRB-S-0002', $product2->sku);

        // 3. Create a product on Kiambu branch with bouquet category
        Livewire::test(\App\Livewire\Admin\ProductIndex::class)
            ->call('openCreateModal')
            ->set('name', 'The Obsidian Dome Bouquet')
            ->set('sku', '')
            ->set('category', 'bouquet')
            ->set('unit_type', 'arrangement')
            ->set('price', 9500)
            ->set('cost_price', 4000)
            ->set('stock', 10)
            ->set('selectedBranchId', $this->branchKiambu->id)
            ->call('save')
            ->assertHasNoErrors();

        $product3 = Product::where('name', 'The Obsidian Dome Bouquet')->first();
        $this->assertNotNull($product3);
        $this->assertEquals('KMB-B-0001', $product3->sku);
    }

    /**
     * Test that SKU prefix generates dynamic 3-letter codes for new branch locations
     * using our consonant extraction algorithm.
     */
    public function test_dynamic_consonant_abbreviations_for_new_branches(): void
    {
        $this->actingAs($this->admin);

        // Create a new branch in Mombasa city
        $branchMombasa = Branch::create([
            'name' => 'Mombasa Coast Atelier',
            'code' => 'NB-MBA',
            'location_city' => 'Mombasa',
            'is_active' => true,
        ]);

        // Create a new branch in Nakuru city
        $branchNakuru = Branch::create([
            'name' => 'Nakuru Highlands Curation Desk',
            'code' => 'NB-NKR',
            'location_city' => 'Nakuru',
            'is_active' => true,
        ]);

        // Create a product in Mombasa with giftings category (G)
        Livewire::test(\App\Livewire\Admin\ProductIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Coast Coconut Gourmet Hamper')
            ->set('sku', '')
            ->set('category', 'giftings')
            ->set('unit_type', 'hamper')
            ->set('price', 15000)
            ->set('cost_price', 6500)
            ->set('stock', 5)
            ->set('selectedBranchId', $branchMombasa->id)
            ->call('save')
            ->assertHasNoErrors();

        $productMombasa = Product::where('name', 'Coast Coconut Gourmet Hamper')->first();
        $this->assertNotNull($productMombasa);
        // Mombasa consonants are: M, B, S (unique) -> MBS. Category is Giftings -> G.
        $this->assertEquals('MBS-G-0001', $productMombasa->sku);

        // Create a product in Nakuru with bundle category (U)
        Livewire::test(\App\Livewire\Admin\ProductIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Highlands Volcanic Vase')
            ->set('sku', '')
            ->set('category', 'bundle')
            ->set('unit_type', 'bundle')
            ->set('price', 4800)
            ->set('cost_price', 1900)
            ->set('stock', 25)
            ->set('selectedBranchId', $branchNakuru->id)
            ->call('save')
            ->assertHasNoErrors();

        $productNakuru = Product::where('name', 'Highlands Volcanic Vase')->first();
        $this->assertNotNull($productNakuru);
        // Nakuru consonants are: N, K, R -> NKR. Category is Bundle -> U.
        $this->assertEquals('NKR-U-0001', $productNakuru->sku);
    }
}
