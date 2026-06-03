<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $retailUser;
    private Client $retailClient;
    private Branch $branch;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $this->admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        // 2. Create Branch
        $this->branch = Branch::where('code', 'NB-NBO')->first() ?: Branch::create([
            'name' => 'Nairobi Central Atelier',
            'code' => 'NB-NBO',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        // 3. Create Retail Customer
        $this->retailUser = User::factory()->create([
            'account_tier' => UserRole::Retail,
        ]);

        $this->retailClient = Client::create([
            'user_id' => $this->retailUser->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => '123 Atelier Way',
        ]);

        // 4. Create Order with Completed Payment
        $this->order = Order::create([
            'client_id' => $this->retailClient->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 15000,
            'service_fee_amount' => 1000,
            'status' => 'approved',
        ]);

        // M-Pesa Completed Payment to allow download
        \App\Models\Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'completed',
            'mpesa_receipt_number' => 'REC-SECURE-999',
        ]);
    }

    /**
     * Test secure HTTP headers middleware is working.
     */
    public function test_secure_headers_middleware_is_applied(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Test receipt download protection against IDOR scraping.
     */
    public function test_receipt_download_idor_protection(): void
    {
        // 1. Unauthenticated, unsigned access -> 403 Forbidden
        $response = $this->get(route('receipt.download', $this->order->id));
        $response->assertStatus(403);

        // 2. Cryptographically signed URL access -> 200 OK
        $signedUrl = URL::signedRoute('receipt.download', ['order' => $this->order->id]);
        $response = $this->get($signedUrl);
        $response->assertStatus(200);

        // 3. Logged-in order owner, unsigned url -> 200 OK
        $this->actingAs($this->retailUser);
        $response = $this->get(route('receipt.download', $this->order->id));
        $response->assertStatus(200);

        // 4. Logged-in non-owner, unsigned url -> 403 Forbidden
        $otherUser = User::factory()->create(['account_tier' => UserRole::Retail]);
        $this->actingAs($otherUser);
        $response = $this->get(route('receipt.download', $this->order->id));
        $response->assertStatus(403);

        // 5. Logged-in Admin user, unsigned url -> 200 OK
        $this->actingAs($this->admin);
        $response = $this->get(route('receipt.download', $this->order->id));
        $response->assertStatus(200);
    }

    /**
     * Test logistics dashboard isolation and privilege checks.
     */
    public function test_logistics_portal_privilege_checks(): void
    {
        // 1. Retail client logged-in
        $this->actingAs($this->retailUser);
        $component = Livewire::test(\App\Livewire\ProfilePortal::class);
        $component->assertViewHas('assignedRuns', function ($runs) {
            return $runs->isEmpty(); // Customers get empty sets
        });

        // Try to update status as customer -> expect 403 Forbidden
        $component->call('updateLogisticsStatus', $this->order->id, 'delivered')
            ->assertStatus(403);
    }

    /**
     * Test logistics status updates succeed for internal staff.
     */
    public function test_logistics_status_succeeds_for_staff(): void
    {
        $this->actingAs($this->admin);
        $component = Livewire::test(\App\Livewire\ProfilePortal::class);

        $component->assertViewHas('assignedRuns', function ($runs) {
            return $runs->isNotEmpty(); // Staff can see logistics runs
        });

        // Call update status
        $component->call('updateLogisticsStatus', $this->order->id, 'delivered');
        $this->assertEquals('delivered', $this->order->fresh()->status);
    }

    /**
     * Test chat rate limiting restricts AI query spam.
     */
    public function test_chat_rate_limiting_triggers(): void
    {
        RateLimiter::clear('aura-chat:127.0.0.1');

        $component = Livewire::test(\App\Livewire\Storefront::class);

        // Send 15 inquiries (allowed)
        for ($i = 0; $i < 15; $i++) {
            $component->set('chatMessage', 'Hello Aura ' . $i);
            $component->call('sendChatMessage');
        }

        // 16th inquiry should be rate-limited and return warning text
        $component->set('chatMessage', 'Spamming chatbot');
        $component->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('too many requests', $lastBotMsg['text']);
    }
}
