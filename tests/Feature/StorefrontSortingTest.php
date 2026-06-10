<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StorefrontSortingTest extends TestCase
{
    use RefreshDatabase;

    private Product $productA;
    private Product $productB;
    private Product $productC;

    protected function setUp(): void
    {
        parent::setUp();

        // Product A: Expensive, Oldest, 0 orders
        $this->productA = Product::create([
            'name' => 'Limuru Orchid Suite',
            'sku' => 'STEM-A',
            'description' => 'Sophisticated arrangement',
            'price' => 12000,
            'stock' => 10,
            'category' => 'stems',
            'unit_type' => 'arrangement',
        ]);
        $this->productA->created_at = now()->subDays(3);
        $this->productA->save();

        // Product B: Cheapest, Middle Age, 1 order
        $this->productB = Product::create([
            'name' => 'Watamu Coral Palm',
            'sku' => 'STEM-B',
            'description' => 'Tropical stems',
            'price' => 5000,
            'stock' => 10,
            'category' => 'stems',
            'unit_type' => 'stem',
        ]);
        $this->productB->created_at = now()->subDays(2);
        $this->productB->save();

        // Product C: Medium Price, Newest, 2 orders
        $this->productC = Product::create([
            'name' => 'Naivasha Crimson Rose',
            'sku' => 'STEM-C',
            'description' => 'Volcanic red roses',
            'price' => 8000,
            'stock' => 10,
            'category' => 'stems',
            'unit_type' => 'stem',
        ]);
        $this->productC->created_at = now()->subDays(1);
        $this->productC->save();

        // Set up client and orders for popularity testing
        $client = Client::create([
            'contact_name' => 'Test Client',
            'email' => 'test@client.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Curation Avenue',
        ]);

        // Order 1 containing Product B and Product C
        $order1 = Order::create([
            'client_id' => $client->id,
            'total_amount' => 13000,
            'status' => 'completed',
        ]);
        $order1->products()->attach($this->productB->id, ['quantity' => 1, 'price_at_sale' => 5000]);
        $order1->products()->attach($this->productC->id, ['quantity' => 1, 'price_at_sale' => 8000]);

        // Order 2 containing only Product C
        $order2 = Order::create([
            'client_id' => $client->id,
            'total_amount' => 8000,
            'status' => 'completed',
        ]);
        $order2->products()->attach($this->productC->id, ['quantity' => 1, 'price_at_sale' => 8000]);
    }

    public function test_default_sorting_is_latest(): void
    {
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSet('sortBy', 'latest')
            ->assertViewHas('products', function ($products) {
                // Expected order: C, B, A (Newest to Oldest)
                return $products->count() === 3
                    && $products[0]->id === $this->productC->id
                    && $products[1]->id === $this->productB->id
                    && $products[2]->id === $this->productA->id;
            });
    }

    public function test_sorting_by_price_ascending(): void
    {
        Livewire::test(\App\Livewire\Storefront::class)
            ->set('sortBy', 'price_asc')
            ->assertViewHas('products', function ($products) {
                // Expected order: B (5000), C (8000), A (12000)
                return $products->count() === 3
                    && $products[0]->id === $this->productB->id
                    && $products[1]->id === $this->productC->id
                    && $products[2]->id === $this->productA->id;
            });
    }

    public function test_sorting_by_price_descending(): void
    {
        Livewire::test(\App\Livewire\Storefront::class)
            ->set('sortBy', 'price_desc')
            ->assertViewHas('products', function ($products) {
                // Expected order: A (12000), C (8000), B (5000)
                return $products->count() === 3
                    && $products[0]->id === $this->productA->id
                    && $products[1]->id === $this->productC->id
                    && $products[2]->id === $this->productB->id;
            });
    }

    public function test_sorting_by_popularity(): void
    {
        Livewire::test(\App\Livewire\Storefront::class)
            ->set('sortBy', 'popularity')
            ->assertViewHas('products', function ($products) {
                // Expected order: C (2 orders), B (1 order), A (0 orders)
                return $products->count() === 3
                    && $products[0]->id === $this->productC->id
                    && $products[1]->id === $this->productB->id
                    && $products[2]->id === $this->productA->id;
            });
    }

    public function test_pagination_resets_on_sort_change(): void
    {
        Livewire::test(\App\Livewire\Storefront::class)
            ->set('perPage', 10)
            ->set('sortBy', 'price_asc')
            ->assertSet('perPage', 6);
    }
}
