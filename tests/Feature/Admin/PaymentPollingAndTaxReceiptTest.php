<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Client;
use App\Models\EtimsInvoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentPollingAndTaxReceiptTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Client $client;
    private Branch $branch;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create admin user
        $this->admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        // 2. Create physical branch safely
        $this->branch = Branch::where('code', 'NB-NBO')->first() ?: Branch::create([
            'name' => 'Nairobi Central Atelier',
            'code' => 'NB-NBO',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        // 3. Create client account
        $clientUser = User::factory()->create([
            'account_tier' => UserRole::Retail,
        ]);
        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => '123 Atelier Way',
            'kra_pin' => 'A001234567Z'
        ]);

        // 4. Create an order
        $this->order = Order::create([
            'client_id' => $this->client->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 15000,
            'service_fee_amount' => 1000,
            'status' => 'pending',
            'is_gift' => false,
        ]);
    }

    /**
     * Test M-Pesa storefront Livewire status polling.
     */
    public function test_mpesa_polling_transitions_payment_status_correctly(): void
    {
        // Create a pending payment tracking record
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'pending',
            'merchant_request_id' => 'ws_CO_0102_12345',
            'checkout_request_id' => 'ws_CO_0102_12345',
        ]);

        // Test Livewire component
        $component = Livewire::test(\App\Livewire\Storefront::class)
            ->set('activePaymentId', $payment->id)
            ->set('paymentStatus', 'pending')
            ->set('trackedOrderId', $this->order->id);

        // 1. Initial poll keeps it pending
        $component->call('checkPaymentStatus')
            ->assertSet('paymentStatus', 'pending');

        // 2. Mark payment completed in database
        $payment->update([
            'status' => 'completed',
            'mpesa_receipt_number' => 'QHD48SJ93K',
        ]);

        // Poll again - should transition to completed
        $component->call('checkPaymentStatus')
            ->assertSet('paymentStatus', 'completed')
            ->assertSet('mpesaReceiptNumber', 'QHD48SJ93K');

        // 3. Test failed transition
        $payment2 = Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'pending',
        ]);

        $component->set('activePaymentId', $payment2->id)
            ->set('paymentStatus', 'pending');

        $payment2->update([
            'status' => 'failed',
            'result_description' => 'User cancelled STK push.',
        ]);

        $component->call('checkPaymentStatus')
            ->assertSet('paymentStatus', 'failed')
            ->assertSet('mpesaErrorMessage', 'User cancelled STK push.');

        // 4. Test 60-second expiration timeout
        $paymentTimeout = Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'pending',
        ]);
        
        // Backdate payment creation time beyond 60 seconds
        $paymentTimeout->created_at = now()->subSeconds(70);
        $paymentTimeout->save();

        $component->set('activePaymentId', $paymentTimeout->id)
            ->set('paymentStatus', 'pending');

        $component->call('checkPaymentStatus')
            ->assertSet('paymentStatus', 'failed')
            ->assertSet('mpesaErrorMessage', 'STK authorization prompt expired. Please try again.');

        $this->assertEquals('failed', $paymentTimeout->fresh()->status);
    }

    /**
     * Test printable receipts access rules and rendering format.
     */
    public function test_printable_receipt_route_access_and_rendering(): void
    {
        // 1. Attempt access when payment is not complete -> expect 403 Forbidden
        $response = $this->get(route('receipt.download', $this->order->id));
        $response->assertStatus(403);

        // 2. Complete the payment
        Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'completed',
            'mpesa_receipt_number' => 'REC-12345',
        ]);

        // Create eTIMS invoice
        $invoice = EtimsInvoice::create([
            'order_id' => $this->order->id,
            'internal_invoice_number' => 'INV-2026-00001',
            'cu_invoice_number' => 'KRA-TIMS-CU-1234567890',
            'gross_amount' => 15000,
            'taxable_amount' => 12931,
            'vat_amount' => 2069,
            'status' => 'transmitted',
            'kra_qr_url' => 'https://etims.kra.go.ke/verify?id=INV-2026-00001',
        ]);

        // Access download route -> expect 200 OK (using cryptographically signed URL)
        $response = $this->get(\Illuminate\Support\Facades\URL::signedRoute('receipt.download', ['order' => $this->order->id]));
        $response->assertStatus(200);

        // Assert rendered contents
        $response->assertSee('Noir & Bloom', false);
        $response->assertSee('Jane Doe');
        $response->assertSee('A001234567Z'); // KRA PIN
        $response->assertSee('REC-12345'); // M-Pesa Receipt
        $response->assertDontSee('KRA-TIMS-CU-1234567890'); // CU number removed from end-user receipt
    }

    /**
     * Test streamed tax audit CSV ledger exports.
     */
    public function test_tax_ledger_audit_csv_export_streaming(): void
    {
        // Require auth
        $this->actingAs($this->admin);

        // Create an eTIMS invoice with a completed payment
        Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'completed',
            'mpesa_receipt_number' => 'REC-9999',
        ]);

        EtimsInvoice::create([
            'order_id' => $this->order->id,
            'internal_invoice_number' => 'INV-2026-99999',
            'cu_invoice_number' => 'KRA-TIMS-CU-9999999999',
            'gross_amount' => 15000,
            'taxable_amount' => 12931,
            'vat_amount' => 2069,
            'status' => 'transmitted',
        ]);

        // Run Livewire component tax ledger test
        $response = Livewire::test(\App\Livewire\Admin\TaxIndex::class)
            ->call('exportAudits');

        // Verify Stream Download
        $response->assertStatus(200);
        
        $downloadFile = $response->effects['download'] ?? null;
        $this->assertNotNull($downloadFile, 'Livewire download event was not triggered.');
        $this->assertStringContainsString('kra_etims_audit_', $downloadFile['name']);
        $this->assertEquals('text/csv', $downloadFile['contentType'] ?? 'text/csv');
    }

    /**
     * Test dynamic region-based address suggestions and address clearing.
     */
    public function test_storefront_region_address_suggestions_and_clearing(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class)
            ->set('region', 'Nairobi')
            ->set('delivery_address', 'Riverside Drive, Office Park Complexes, Nairobi');

        $suggestions = $component->instance()->getAddressSuggestions();
        $this->assertContains('Riverside Drive, Office Park Complexes, Nairobi', $suggestions);
        $this->assertNotContains('Limuru, Tea Estate Curation Ridge, Limuru, Kiambu', $suggestions);

        // Change region to Kiambu
        $component->set('region', 'Kiambu');
        $component->assertSet('delivery_address', ''); // Should be cleared!

        $suggestionsKiambu = $component->instance()->getAddressSuggestions();
        $this->assertContains('Limuru, Tea Estate Curation Ridge, Limuru, Kiambu', $suggestionsKiambu);
        $this->assertNotContains('Riverside Drive, Office Park Complexes, Nairobi', $suggestionsKiambu);
    }

    /**
     * Test admin edit payment status modal saves and triggers order state transitions.
     */
    public function test_admin_payment_status_editing_and_order_transitions(): void
    {
        $this->actingAs($this->admin);

        // Create a failed payment
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 15000,
            'status' => 'failed',
            'result_description' => 'STK push rejected',
        ]);

        $this->assertEquals('pending', $this->order->status);

        // Edit status to completed
        Livewire::test(\App\Livewire\Admin\PaymentIndex::class)
            ->call('openEditModal', $payment->id)
            ->assertSet('showEditModal', true)
            ->assertSet('editingStatus', 'failed')
            ->set('editingStatus', 'completed')
            ->set('editingReceiptNumber', 'MPESA_RECON_999')
            ->set('editingResultDesc', 'Manual override success')
            ->call('savePaymentStatus')
            ->assertHasNoErrors()
            ->assertSet('showEditModal', false);

        // Assert payment is updated in DB
        $payment = $payment->fresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertEquals('MPESA_RECON_999', $payment->mpesa_receipt_number);
        $this->assertEquals('Manual override success', $payment->result_description);

        // Assert parent order is approved
        $this->assertEquals('approved', $this->order->fresh()->status);

        // Now edit status back to failed
        Livewire::test(\App\Livewire\Admin\PaymentIndex::class)
            ->call('openEditModal', $payment->id)
            ->set('editingStatus', 'failed')
            ->set('editingResultDesc', 'Manual cancel')
            ->call('savePaymentStatus')
            ->assertHasNoErrors();

        // Assert payment is failed
        $this->assertEquals('failed', $payment->fresh()->status);
        // Assert parent order is cancelled!
        $this->assertEquals('cancelled', $this->order->fresh()->status);
    }

    /**
     * Test that M-Pesa STK push reference uses product name instead of order number.
     */
    public function test_stk_push_reference_uses_product_name(): void
    {
        // 1. Create a product
        $product = \App\Models\Product::create([
            'name' => 'Heritage Rose Bouquet',
            'sku' => 'NB-ROS-001',
            'price' => 5000,
            'cost_price' => 2000,
            'stock' => 10,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        // Attach product to order
        $this->order->products()->attach($product->id, ['quantity' => 1, 'price_at_sale' => 5000]);

        // Create a payment
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'phone_number' => '0712345678',
            'amount' => 5000,
            'status' => 'pending',
        ]);

        // Mock the MpesaService to verify it receives the correct reference
        $this->mock(\App\Services\MpesaService::class, function ($mock) {
            $mock->shouldReceive('sendStkPush')
                ->once()
                ->with('0712345678', 5000, 'HeritageRose')
                ->andReturn([
                    'MerchantRequestID' => 'ws_CO_123',
                    'CheckoutRequestID' => 'ws_CO_123',
                    'ResponseCode' => '0',
                ]);
        });

        // Run the SendMpesaStkPushJob synchronously
        \App\Jobs\SendMpesaStkPushJob::dispatchSync($payment->id, '0712345678', 5000, $this->order->id);

        $this->assertEquals('ws_CO_123', $payment->fresh()->checkout_request_id);
    }
}
