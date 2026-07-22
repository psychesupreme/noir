<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CurationBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected Product $redRoses;
    protected Product $whiteLilies;
    protected Product $kraftWrap;
    protected Product $glassVaseWrap;
    protected Product $amberMist;
    protected Product $merlotWine;
    protected Product $trufflesChoc;
    protected Product $necklaceJewel;
    protected Product $handCurationProduct;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed necessary flower curation products
        $this->redRoses = Product::create([
            'name' => 'Naivasha Volcanic Red Roses (Grade A)',
            'sku' => 'NB-STM-NRO-01',
            'description' => 'Premium red roses.',
            'price' => 300,
            'stock' => 500,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/redrosestem.jpg',
        ]);

        $this->whiteLilies = Product::create([
            'name' => 'Limuru Pure White Lilies (Grade A)',
            'sku' => 'NB-STM-LWL-02',
            'description' => 'White lilies.',
            'price' => 450,
            'stock' => 300,
            'category' => 'stems',
            'unit_type' => 'stem',
            'grade' => 'Grade A',
            'image_url' => '/media/flowers/whiterosestem.jpg',
        ]);

        $this->kraftWrap = Product::create([
            'name' => 'Kraft Paper Wrapping',
            'sku' => 'NB-DEC-KPW-01',
            'description' => 'Kraft wrap.',
            'price' => 500,
            'stock' => 200,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        $this->glassVaseWrap = Product::create([
            'name' => 'Glass Vase Presentation',
            'sku' => 'NB-DEC-GVP-07',
            'description' => 'Glass vase.',
            'price' => 3500,
            'stock' => 50,
            'category' => 'bundle',
            'unit_type' => 'wrap',
            'image_url' => '/media/wraps/wrap.jpg',
        ]);

        $this->amberMist = Product::create([
            'name' => 'Atelier Rosewood & Amber Mist',
            'sku' => 'NB-DEC-ARA-04',
            'description' => 'Rosewood amber mist.',
            'price' => 1500,
            'stock' => 90,
            'category' => 'bundle',
            'unit_type' => 'bottle',
            'image_url' => '/media/flowers/rosewood_mist.svg',
        ]);

        $this->merlotWine = Product::create([
            'name' => 'Premium South African Merlot',
            'sku' => 'NB-HMP-PSM-01',
            'description' => 'Merlot wine.',
            'price' => 4500,
            'stock' => 50,
            'category' => 'giftings',
            'unit_type' => 'bottle',
            'image_url' => '/media/wines/wine.jpg',
        ]);

        $this->trufflesChoc = Product::create([
            'name' => 'Artisanal Belgian Truffles Box',
            'sku' => 'NB-HMP-ABT-04',
            'description' => 'Truffles.',
            'price' => 2800,
            'stock' => 120,
            'category' => 'giftings',
            'unit_type' => 'box',
            'image_url' => '/media/chocolates/chocolate.jpg',
        ]);

        $this->necklaceJewel = Product::create([
            'name' => 'Bespoke Gold Pearl Necklace',
            'sku' => 'NB-JWL-GPN-01',
            'description' => 'Necklace.',
            'price' => 15000,
            'stock' => 20,
            'category' => 'giftings',
            'unit_type' => 'jewelry',
            'image_url' => '/media/jewelry/necklace.jpg',
        ]);

        $this->handCurationProduct = Product::create([
            'name' => 'Atelier Hand Curation Service',
            'sku' => 'NB-SRV-HCS-01',
            'description' => 'Professional hand-curation by our master florists.',
            'price' => 1500,
            'cost_price' => 200,
            'stock' => 9999,
            'category' => 'specialization',
            'unit_type' => 'arrangement',
            'grade' => 'Premium Service',
            'image_url' => '/media/services/hand_curation.jpg',
        ]);
    }

    public function test_curation_builder_page_is_accessible(): void
    {
        $response = $this->get('/curate');
        $response->assertStatus(200);
        $response->assertSee('Curation Studio');
    }

    public function test_curation_builder_initial_state(): void
    {
        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->assertSet('selectedStems', [
                $this->redRoses->id => 0,
                $this->whiteLilies->id => 0
            ])
            ->assertSet('selectedWrappingId', null)
            ->assertSet('selectedMistId', null)
            ->assertSee('Naivasha Volcanic Red Roses (Grade A)');
    }

    public function test_subtotal_calculation_with_stems_wrapping_and_gifts(): void
    {
        // 12 stems * 300 = 3,600 KSH
        // Selected Wrap = Kraft Paper (500 KSH)
        // Selected Mist = Amber Mist (1,500 KSH)
        // Selected Gift = Merlot (4,500 KSH)
        // Hand Curation Service (Medium Curation, 12 stems) = 350 KSH
        // Expected total = 3600 + 500 + 1500 + 4500 + 350 = 10,450 KSH

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->call('adjustStemQuantity', $this->redRoses->id, 12) // 0 + 12 = 12 stems
            ->call('selectWrapping', $this->kraftWrap->id)
            ->call('selectMist', $this->amberMist->id)
            ->call('adjustGiftQuantity', $this->merlotWine->id, 1)
            ->assertSet('subtotal', 10450);
    }

    public function test_adding_curation_to_cart(): void
    {
        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->call('adjustStemQuantity', $this->redRoses->id, 12) // 0 + 12 = 12 stems
            ->call('selectWrapping', $this->kraftWrap->id)
            ->call('selectMist', $this->amberMist->id)
            ->call('adjustGiftQuantity', $this->merlotWine->id, 1)
            ->set('curationOccasion', 'anniversary')
            ->call('addToCart')
            ->assertRedirect(route('storefront'));

        $cart = session()->get('noir_bloom_cart');

        $this->assertArrayHasKey($this->redRoses->id . '-standard', $cart);
        $this->assertEquals(12, $cart[$this->redRoses->id . '-standard']);

        $this->assertArrayHasKey($this->kraftWrap->id . '-standard', $cart);
        $this->assertEquals(1, $cart[$this->kraftWrap->id . '-standard']);

        $this->assertArrayHasKey($this->amberMist->id . '-standard', $cart);
        $this->assertEquals(1, $cart[$this->amberMist->id . '-standard']);

        $this->assertArrayHasKey($this->merlotWine->id . '-standard', $cart);
        $this->assertEquals(1, $cart[$this->merlotWine->id . '-standard']);

        $this->assertArrayHasKey($this->handCurationProduct->id . '-standard', $cart);
        $this->assertEquals(1, $cart[$this->handCurationProduct->id . '-standard']);

        $customizations = session()->get('noir_bloom_customizations');
        $this->assertNotNull($customizations);
        $this->assertEquals('anniversary', $customizations['curation_occasion']);
    }

    public function test_active_step_navigation(): void
    {
        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->assertSet('activeStep', 1)
            ->set('activeStep', 2)
            ->assertSet('activeStep', 2)
            ->set('activeStep', 5)
            ->assertSet('activeStep', 5);
    }

    public function test_card_message_with_print_preference_customizations(): void
    {
        $stem = Product::where('category', 'stems')->first();

        Livewire::test(\App\Livewire\CurationBuilder::class)
            ->set('selectedStems.' . $stem->id, 2)
            ->set('hasCard', true)
            ->set('cardMessage', 'With love and best wishes!')
            ->set('cardPrintPreference', 'typography')
            ->call('addToCart')
            ->assertRedirect(route('storefront'));

        $customizations = session()->get('noir_bloom_customizations');
        $this->assertNotNull($customizations);
        $this->assertEquals('With love and best wishes!', $customizations['card_message']);
        $this->assertEquals('typography', $customizations['card_print_preference']);
    }
}
