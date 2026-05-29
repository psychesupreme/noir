<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Occasion;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_status_endpoint(): void
    {
        $response = $this->getJson('/api/v1/status');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'operational',
                'service' => 'Noir & Bloom API',
            ]);
    }

    public function test_api_token_issue_and_revocation(): void
    {
        $user = User::factory()->create([
            'email' => 'api@test.com',
            'password' => bcrypt('password123'),
            'account_tier' => UserRole::Retail,
        ]);

        // 1. Invalid credentials
        $response = $this->postJson('/api/v1/auth/token', [
            'email' => 'api@test.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(401);

        // 2. Valid credentials
        $response = $this->postJson('/api/v1/auth/token', [
            'email' => 'api@test.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'email', 'loyalty_tier']]);

        $token = $response->json('token');

        // 3. Authenticated request (Logout)
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/auth/logout');

        $logoutResponse->assertStatus(200)
            ->assertJson(['message' => 'Token revoked successfully.']);
    }

    public function test_api_catalog_endpoints(): void
    {
        // Setup occasions and products
        $occasion = Occasion::create([
            'name' => 'Mother\'s Day',
            'slug' => 'mothers-day',
            'accent_color' => '#FFC0CB',
        ]);

        $product1 = Product::create([
            'name' => 'Luxury Red Roses Arrangement',
            'sku' => 'ROS-001',
            'price' => 7500,
            'stock' => 15,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);
        $product1->occasions()->attach($occasion->id);

        $product2 = Product::create([
            'name' => 'Wholesale Tulips',
            'sku' => 'TUL-002',
            'price' => 3000,
            'stock' => 50,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
        ]);

        // 1. List products (paginated)
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');

        // 2. Filter by category
        $response = $this->getJson('/api/v1/products?category=wholesale');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'TUL-002');

        // 3. Filter by occasion
        $response = $this->getJson('/api/v1/products?occasion=mothers-day');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'ROS-001');

        // 4. Search term
        $response = $this->getJson('/api/v1/products?search=roses');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'ROS-001');

        // 5. Product specs show
        $response = $this->getJson('/api/v1/products/' . $product1->id);
        $response->assertStatus(200)
            ->assertJsonPath('sku', 'ROS-001');
    }

    public function test_api_checkout_requires_auth(): void
    {
        $response = $this->postJson('/api/v1/checkout', []);
        $response->assertStatus(401);
    }

    public function test_api_checkout_insufficient_stock(): void
    {
        $user = User::factory()->create([
            'email' => 'buyer@test.com',
            'password' => bcrypt('password123'),
            'account_tier' => UserRole::Retail,
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Gold Premium Bouquet',
            'sku' => 'GOL-001',
            'price' => 15000,
            'stock' => 2, // low stock
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/checkout', [
            'checkout_type' => 'standard',
            'full_name' => 'Buyer Test',
            'email' => 'buyer@test.com',
            'phone' => '0711223344',
            'delivery_address' => '123 Residency St',
            'region' => 'Nairobi',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5 // exceeds stock of 2
                ]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['items']]);
    }

    public function test_api_checkout_corporate_e_tims_validation(): void
    {
        $user = User::factory()->create([
            'email' => 'corp@test.com',
            'password' => bcrypt('password123'),
            'account_tier' => UserRole::Wholesale,
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Export Hydrangeas',
            'sku' => 'HYD-001',
            'price' => 8000,
            'stock' => 10,
            'category' => 'wholesale',
            'unit_type' => 'bundle',
        ]);

        // Corporate checkout without KRA PIN or company name should fail validation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/checkout', [
            'checkout_type' => 'corporate',
            'full_name' => 'Corporate Agent',
            'email' => 'corp@test.com',
            'phone' => '0711223344',
            'delivery_address' => 'Naivasha Office Park',
            'region' => 'Nairobi',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kra_pin', 'company_name']);
    }

    public function test_api_checkout_success_deducts_stock_awards_loyalty_and_transmits_e_tims(): void
    {
        $email = 'member@test.com';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt('password123'),
            'account_tier' => UserRole::Retail,
            'loyalty_points' => 10,
        ]);
        $token = $user->createToken('test')->plainTextToken;

        Branch::create([
            'name' => 'Nairobi Atelier',
            'code' => 'NBI-01',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Premium Orchid Vase',
            'sku' => 'ORC-001',
            'price' => 10000,
            'stock' => 20,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/checkout', [
            'checkout_type' => 'corporate',
            'company_name' => 'Noir Bloom Gifting LLC',
            'kra_pin' => 'P012345678Q',
            'full_name' => 'Loyal Member',
            'email' => $email,
            'phone' => '0711223344',
            'delivery_address' => 'Upperhill Suite 4',
            'region' => 'Nairobi',
            'is_gift' => true,
            'recipient_name' => 'Gifting Target',
            'recipient_phone' => '0799887766',
            'special_instructions' => 'Include gold foil ribbon card.',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('order.status', 'approved')
            ->assertJsonPath('order.is_gift', true)
            ->assertJsonPath('order.recipient_name', 'Gifting Target')
            ->assertJsonStructure(['message', 'order' => ['id', 'total_amount', 'etims_invoice', 'client']]);

        // 1. Verify Stock decremented (20 - 2 = 18)
        $product->refresh();
        $this->assertEquals(18, $product->stock);

        // 2. Verify Loyalty points awarded
        // order total amount: 10000 * 2 + 500 (fee) = 20500 Ksh.
        // points earned: 20500 / 100 = 205 points.
        // user had 10 points initially, should now have 215.
        $user->refresh();
        $this->assertEquals(215, $user->loyalty_points);

        // 3. Verify eTIMS invoice transmitted
        $orderId = $response->json('order.id');
        $order = Order::find($orderId);
        $this->assertNotNull($order->etimsInvoice);
        $this->assertEquals('transmitted', $order->etimsInvoice->status);
        $this->assertEquals('P012345678Q', $order->client->kra_pin);
    }
}
