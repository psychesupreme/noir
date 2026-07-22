<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\BranchProductStock;
use App\Models\Product;
use App\Models\User;
use App\Models\WastageLog;
use App\Services\StorefrontCacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class StorefrontCachingTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        StorefrontCacheService::flush();

        $this->branch = Branch::create([
            'name' => 'Nairobi Atelier Hub',
            'code' => 'NB-NBO-01',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'Imperial Rift Valley Orchids',
            'sku' => 'NB-IMP-ORC-01',
            'price' => 18000,
            'stock' => 25,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);
    }

    public function test_storefront_catalog_caching_and_subsequent_cache_hits(): void
    {
        // 1. First storefront request populates cache
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('Imperial Rift Valley Orchids')
            ->assertSee('18,000 KSH');

        // 2. Mutate DB directly bypassing Eloquent events to prove cache hit
        DB::table('products')
            ->where('id', $this->product->id)
            ->update(['price' => 99999]);

        // 3. Second storefront request returns cached data (18,000 KSH instead of 99,999 KSH)
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('18,000 KSH')
            ->assertDontSee('99,999 KSH');
    }

    public function test_product_update_event_flushes_storefront_cache(): void
    {
        // 1. Initial request to cache
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('18,000 KSH');

        // 2. Perform Eloquent update on product
        $this->product->update(['price' => 24000]);

        // 3. Next storefront request sees updated price 24,000 KSH
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('24,000 KSH')
            ->assertDontSee('18,000 KSH');
    }

    public function test_branch_stock_allocation_update_flushes_cache(): void
    {
        // 1. Initial request to cache
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('18,000 KSH');

        // 2. Update BranchProductStock allocation via Eloquent
        BranchProductStock::updateOrCreate(
            [
                'branch_id' => $this->branch->id,
                'product_id' => $this->product->id,
            ],
            [
                'stock' => 50,
                'min_stock_level' => 5,
            ]
        );

        // 3. Directly update product price via DB
        DB::table('products')
            ->where('id', $this->product->id)
            ->update(['price' => 30000]);

        // 4. Verify cache was invalidated, so new request reflects 30,000 KSH
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('30,000 KSH');
    }

    public function test_wastage_log_creation_flushes_cache(): void
    {
        $user = User::factory()->create();

        // 1. Initial request to cache
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('18,000 KSH');

        // 2. Log wastage event via Eloquent
        WastageLog::create([
            'branch_id' => $this->branch->id,
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'quantity' => 5,
            'reason' => 'Damaged during transit',
            'cost_estimate' => 5000,
        ]);

        // 3. Mutate DB directly
        DB::table('products')
            ->where('id', $this->product->id)
            ->update(['price' => 35000]);

        // 4. Verify cache was invalidated, next request gets updated price
        Livewire::test(\App\Livewire\Storefront::class)
            ->assertSee('35,000 KSH');
    }
}
