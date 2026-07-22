<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\EtimsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_status_approval_decrements_stock_and_awards_loyalty_points(): void
    {
        // 1. Setup Client and User with matching email
        $email = 'customer@test.com';
        $user = User::factory()->create([
            'email' => $email,
            'account_tier' => UserRole::Retail,
            'loyalty_points' => 10,
        ]);

        $client = Client::create([
            'contact_name' => 'Test Client',
            'email' => $email,
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Address',
        ]);

        // 2. Setup Product with initial stock
        $product = Product::create([
            'name' => 'Red Roses Pack',
            'sku' => 'ROS-001',
            'price' => 5000,
            'stock' => 20,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        // 3. Setup Order
        $order = Order::create([
            'client_id' => $client->id,
            'total_amount' => 5000,
            'status' => 'pending',
        ]);
        $order->products()->attach($product->id, ['quantity' => 2, 'price_at_sale' => 5000]);

        // 4. Trigger Status Update to Approved
        $etims = app(EtimsService::class);
        $orderIndex = new \App\Livewire\Admin\OrderIndex();
        $orderIndex->updateStatus($order->id, 'approved', $etims);

        // 5. Assert Stock was decremented (20 - 2 = 18)
        $product->refresh();
        $this->assertEquals(18, $product->stock);

        // 6. Assert Loyalty Points were awarded (10 + (5000/100) = 60)
        $user->refresh();
        $this->assertEquals(60, $user->loyalty_points);

        // 7. Assert Loyalty transaction was recorded
        $this->assertDatabaseHas('loyalty_transactions', [
            'user_id' => $user->id,
            'points' => 50,
            'type' => 'earn',
        ]);

        // 8. Assert eTIMS Invoice was created and status is transmitted
        $order->refresh();
        $this->assertNotNull($order->etimsInvoice);
        $this->assertEquals('transmitted', $order->etimsInvoice->status);
    }

    public function test_order_status_cancellation_reverts_stock_and_revokes_loyalty_points(): void
    {
        // 1. Setup Client and User with matching email
        $email = 'customer2@test.com';
        $user = User::factory()->create([
            'email' => $email,
            'account_tier' => UserRole::Retail,
            'loyalty_points' => 100,
        ]);

        $client = Client::create([
            'contact_name' => 'Test Client 2',
            'email' => $email,
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Address 2',
        ]);

        // 2. Setup Product with initial stock
        $product = Product::create([
            'name' => 'Pink Lilies Pack',
            'sku' => 'LIL-002',
            'price' => 3000,
            'stock' => 10,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        // 3. Setup Order in Approved status (stock already decremented and points awarded)
        $order = Order::create([
            'client_id' => $client->id,
            'total_amount' => 3000,
            'status' => 'approved',
        ]);
        $order->products()->attach($product->id, ['quantity' => 1, 'price_at_sale' => 3000]);

        // 4. Trigger Status Update to Cancelled
        $etims = app(EtimsService::class);
        $orderIndex = new \App\Livewire\Admin\OrderIndex();
        $orderIndex->updateStatus($order->id, 'cancelled', $etims);

        // 5. Assert Stock was incremented back (10 + 1 = 11)
        $product->refresh();
        $this->assertEquals(11, $product->stock);

        // 6. Assert Loyalty Points were deducted (100 - (3000/100) = 70)
        $user->refresh();
        $this->assertEquals(70, $user->loyalty_points);

        // 7. Assert Loyalty revocation transaction was recorded
        $this->assertDatabaseHas('loyalty_transactions', [
            'user_id' => $user->id,
            'points' => -30,
            'type' => 'redeem',
        ]);
    }
}
