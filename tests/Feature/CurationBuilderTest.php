<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CurationBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed some products
        Product::create([
            'name' => 'Naivasha Volcanic Red Roses (Grade A)',
            'sku' => 'NB-STM-NRO-01',
            'description' => 'Premium red roses. Sold per stem.',
            'price' => 300,
            'stock' => 500,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11',
        ]);

        Product::create([
            'name' => 'Matte Clay Rift Valley Vase',
            'sku' => 'NB-DEC-MRV-01',
            'description' => 'Artisan clay vase.',
            'price' => 4500,
            'stock' => 40,
            'category' => 'bundle',
            'unit_type' => 'bundle',
            'grade' => null,
            'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d',
        ]);
    }

    public function test_curation_builder_page_is_accessible(): void
    {
        $response = $this->get('/curate');
        $response->assertStatus(200);
        $response->assertSee('3D Custom Curation Desk');
    }

    public function test_curation_builder_initial_state(): void
    {
        $vase = Product::where('name', 'Matte Clay Rift Valley Vase')->first();

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->assertSet('selectedVaseId', $vase->id)
            ->assertSet('size', 'standard')
            ->assertSet('theme', 'onyx')
            ->assertSee('Matte Clay Rift Valley Vase')
            ->assertSee('Naivasha Volcanic Red Roses (Grade A)');
    }

    public function test_adding_stems_calculates_subtotal(): void
    {
        $stem = Product::where('category', 'stems')->first();
        $vase = Product::where('category', 'bundle')->first();

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->call('addStem', $stem->id)
            ->call('addStem', $stem->id)
            // Subtotal should be: vase (4500) + 2 * stem (300) = 5100
            ->assertSet('subtotal', 5100)
            ->call('removeStem', $stem->id)
            ->assertSet('subtotal', 4800);
    }

    public function test_changing_size_applies_price_multiplier(): void
    {
        $stem = Product::where('category', 'stems')->first();

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->call('addStem', $stem->id)
            ->call('setSize', 'deluxe') // Multiplier 1.5x on stems. subtotal = 4500 + round(300 * 1.5) = 4500 + 450 = 4950
            ->assertSet('subtotal', 4950)
            ->call('setSize', 'grand') // Multiplier 2.2x on stems. subtotal = 4500 + round(300 * 2.2) = 4500 + 660 = 5160
            ->assertSet('subtotal', 5160);
    }

    public function test_adding_curation_to_cart(): void
    {
        $stem = Product::where('category', 'stems')->first();
        $vase = Product::where('category', 'bundle')->first();

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->call('addStem', $stem->id)
            ->call('addStem', $stem->id)
            ->call('addToCart')
            ->assertRedirect(route('storefront'));

        $this->assertEquals(
            [
                $vase->id . '-standard' => 1,
                $stem->id . '-standard' => 2
            ],
            session()->get('noir_bloom_cart')
        );
    }
}
