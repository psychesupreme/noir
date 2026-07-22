<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CourierDeliveryPoDTest extends TestCase
{
    use RefreshDatabase;

    protected User $staffUser;
    protected Client $client;
    protected Order $order;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->staffUser = User::factory()->create([
            'email' => 'courier@noirbloom.com',
            'account_tier' => UserRole::Staff,
        ]);

        $this->client = Client::create([
            'contact_name' => 'Lady Elizabeth',
            'email' => 'elizabeth@luxury.co.ke',
            'phone' => '0712999888',
            'region' => 'Nairobi',
            'delivery_address' => 'Villa Rosa Kempinski, Presidential Suite',
        ]);

        $this->product = Product::create([
            'name' => 'Royal Naivasha Rose Arrangement',
            'sku' => 'NB-[#ROYAL-ROSE]',
            'price' => 15000,
            'stock' => 20,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        $this->order = Order::create([
            'client_id' => $this->client->id,
            'total_amount' => 15000,
            'status' => 'processing',
            'is_gift' => true,
            'recipient_name' => 'Countess Sarah',
            'recipient_phone' => '0722111333',
            'special_instructions' => 'Handle with care. Silk ribbon tied at base.',
        ]);

        $this->order->products()->attach($this->product->id, [
            'quantity' => 1,
            'price_at_sale' => 15000,
            'size' => 'grand',
        ]);
    }

    public function test_courier_delivery_handover_page_loads_for_authorized_staff(): void
    {
        $response = $this->actingAs($this->staffUser)
            ->get(route('courier.delivery-handover', $this->order));

        $response->assertStatus(200);
        $response->assertSee('Order #NB-ORD-' . str_pad($this->order->id, 4, '0', STR_PAD_LEFT));
        $response->assertSee('Countess Sarah');
        $response->assertSee('Villa Rosa Kempinski');
        $response->assertSee('Royal Naivasha Rose Arrangement');
    }

    public function test_courier_delivery_handover_requires_authentication(): void
    {
        $response = $this->get(route('courier.delivery-handover', $this->order));
        $response->assertRedirect('/login');
    }

    public function test_courier_can_upload_pod_photo_and_mark_as_delivered(): void
    {
        $fakePhoto = UploadedFile::fake()->create('pod_delivery_handover.jpg', 100, 'image/jpeg');

        Livewire::actingAs($this->staffUser)
            ->test(\App\Livewire\Courier\DeliveryHandover::class, ['order' => $this->order])
            ->set('photo', $fakePhoto)
            ->set('courier_notes', 'Handed over directly to Countess Sarah at Presidential Suite.')
            ->call('markAsDelivered')
            ->assertHasNoErrors()
            ->assertSet('isDelivered', true);

        $this->order->refresh();

        $this->assertEquals('delivered', $this->order->status);
        $this->assertNotNull($this->order->pod_photo_path);
        $this->assertNotNull($this->order->delivered_at);
        $this->assertEquals('Handed over directly to Countess Sarah at Presidential Suite.', $this->order->courier_notes);

        Storage::disk('public')->assertExists($this->order->pod_photo_path);
    }
}
