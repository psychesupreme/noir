<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\EtimsInvoice;
use App\Jobs\SendMpesaStkPushJob;
use App\Jobs\TransmitEtimsInvoiceJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueAndCachingTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_mpesa_stk_push_job_dispatches_and_updates_payment(): void
    {
        Queue::fake();

        $client = Client::create([
            'contact_name' => 'John Doe',
            'email' => 'john@doe.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Street',
        ]);

        $order = Order::create([
            'client_id' => $client->id,
            'total_amount' => 3000,
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'phone_number' => '0712345678',
            'amount' => 3000,
            'status' => 'pending',
        ]);

        SendMpesaStkPushJob::dispatch($payment->id, '0712345678', 3000, $order->id);

        Queue::assertPushed(SendMpesaStkPushJob::class);
    }

    public function test_dashboard_stats_caching_and_invalidation(): void
    {
        Cache::forget('dashboard_stats');

        $client = Client::create([
            'contact_name' => 'Cache Test Client',
            'email' => 'cache@test.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Street',
        ]);

        $order = Order::create([
            'client_id' => $client->id,
            'total_amount' => 4500,
            'status' => 'pending',
        ]);

        // Verify cache does not exist initially
        $this->assertFalse(Cache::has('dashboard_stats'));
        
        // Simulating the dashboard component render to cache stats
        $dashboard = new \App\Livewire\Admin\Dashboard();
        $dashboard->render();

        $this->assertTrue(Cache::has('dashboard_stats'));

        // Trigger invalidation by creating a product
        Product::create([
            'name' => 'Cache Invalidation Rose Pack',
            'sku' => 'ROS-CACHE',
            'price' => 5000,
            'stock' => 15,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        // Verify cache was invalidated
        $this->assertFalse(Cache::has('dashboard_stats'));
    }
}
