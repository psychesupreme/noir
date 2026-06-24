<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderRoutingAndReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_timezone_is_nairobi()
    {
        $this->assertEquals('Africa/Nairobi', config('app.timezone'));
    }

    public function test_order_routing_approximator_with_coordinates()
    {
        // Mock OSRM API call
        Http::fake([
            'router.project-osrm.org/*' => Http::response([
                'routes' => [
                    [
                        'distance' => 12500, // 12.5 km
                        'duration' => 900,  // 15 mins
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create();
        $client = Client::create([
            'user_id' => $user->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => '-1.292100, 36.821900 (Riverside Office Park)',
        ]);

        $branch = Branch::updateOrCreate(
            ['code' => 'NB-NBO'],
            [
                'name' => 'Nairobi Central Atelier',
                'location_city' => 'Nairobi',
                'is_active' => true,
            ]
        );

        $order = Order::create([
            'client_id' => $client->id,
            'branch_id' => $branch->id,
            'total_amount' => 5000,
            'status' => 'pending',
        ]);

        $routeDetails = $order->getRouteDetails();

        $this->assertNotNull($routeDetails);
        $this->assertEquals(12.5, $routeDetails['distance_km']);
        $this->assertEquals(15, $routeDetails['duration_min']);
        $this->assertEquals('Nairobi Central Atelier (CBD)', $routeDetails['hub_name']);
    }

    public function test_order_routing_approximator_fallback()
    {
        // Mock OSRM API failure to trigger Harvesine fallback
        Http::fake([
            'router.project-osrm.org/*' => Http::response(null, 500)
        ]);

        $user = User::factory()->create();
        $client = Client::create([
            'user_id' => $user->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Kiambu',
            'delivery_address' => '-1.1444, 36.6853 (Tigoni Spot)',
        ]);

        $branch = Branch::updateOrCreate(
            ['code' => 'NB-KBU'],
            [
                'name' => 'Kiambu Ridge Hub',
                'location_city' => 'Kiambu',
                'is_active' => true,
            ]
        );

        $order = Order::create([
            'client_id' => $client->id,
            'branch_id' => $branch->id,
            'total_amount' => 5000,
            'status' => 'pending',
        ]);

        $routeDetails = $order->getRouteDetails();

        $this->assertNotNull($routeDetails);
        $this->assertEquals(0, $routeDetails['distance_km']);
        $this->assertEquals('Kiambu Ridge Hub (Tigoni)', $routeDetails['hub_name']);
    }

    public function test_reviews_database_relation_and_saving()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Luxury Red Roses',
            'sku' => 'NB-ROS-01',
            'price' => 3500,
            'stock' => 15,
            'category' => 'bouquets',
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'This is a premium, gorgeous bouquet!',
        ]);

        $this->assertCount(1, $product->reviews);
        $this->assertEquals(5.0, $product->averageRating);
        $this->assertEquals('This is a premium, gorgeous bouquet!', $product->reviews->first()->comment);
    }

    public function test_order_sizing_stock_constraints_on_approval_and_cancellation()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'user_id' => $user->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => '-1.292100, 36.821900',
        ]);

        $product1 = Product::create([
            'name' => 'Rose Bouquet',
            'sku' => 'NB-ROS-W1',
            'price' => 2000,
            'stock' => 20,
            'category' => 'bouquets',
        ]);

        $order = Order::create([
            'client_id' => $client->id,
            'total_amount' => 5000,
            'status' => 'pending',
        ]);

        $order->products()->attach($product1->id, [
            'quantity' => 1,
            'price_at_sale' => 3000,
            'size' => 'deluxe',
        ]);

        $invoice = new \App\Models\EtimsInvoice();
        $invoice->order_id = $order->id;
        $invoice->internal_invoice_number = 'INV-2026-0001';
        $invoice->gross_amount = 5000;
        $invoice->taxable_amount = 4310;
        $invoice->vat_amount = 690;
        $invoice->status = 'transmitted';
        $invoice->save();

        $orderService = app(\App\Services\OrderService::class);
        $orderService->approve($order);

        $product1->refresh();
        $this->assertEquals(18, $product1->stock);

        $orderService->cancel($order);

        $product1->refresh();
        $this->assertEquals(20, $product1->stock);
    }
}
