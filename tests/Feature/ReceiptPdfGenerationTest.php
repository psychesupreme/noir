<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Client;
use App\Models\EtimsInvoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptPdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Client $client;
    protected Product $product;
    protected Order $order;
    protected Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'client@luxurycorp.co.ke',
            'account_tier' => UserRole::Retail,
        ]);

        $this->client = Client::create([
            'user_id' => $this->user->id,
            'contact_name' => 'Lady Elizabeth',
            'email' => 'client@luxurycorp.co.ke',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Muthaiga Heights Suite 4',
            'kra_pin' => 'A009123847P',
        ]);

        $this->product = Product::create([
            'name' => 'Signature Rift Valley Bouquet',
            'sku' => 'NB-SIG-001',
            'price' => 12000,
            'stock' => 50,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);

        $this->order = Order::create([
            'client_id' => $this->client->id,
            'total_amount' => 12000,
            'status' => 'approved',
        ]);

        $this->order->products()->attach($this->product->id, [
            'quantity' => 1,
            'price_at_sale' => 12000,
            'size' => 'standard',
        ]);

        $this->payment = Payment::create([
            'order_id' => $this->order->id,
            'merchant_request_id' => 'MR-TEST-1001',
            'checkout_request_id' => 'CR-TEST-1001',
            'phone_number' => '0712345678',
            'amount' => 12000,
            'mpesa_receipt_number' => 'QWE1239081',
            'status' => 'completed',
        ]);
    }

    public function test_authorized_user_can_download_pdf_receipt_for_paid_order(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('receipt.download', $this->order));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_pdf_receipt_renders_with_etims_invoice_record(): void
    {
        EtimsInvoice::create([
            'order_id' => $this->order->id,
            'internal_invoice_number' => 'INV-2026-00001',
            'cu_serial_number' => 'KRA-ESD-991823',
            'cu_invoice_number' => 'KRA20260722001',
            'uti' => 'UTI-8912301923',
            'kra_qr_url' => 'https://etims.kra.go.ke/verify/UTI-8912301923',
            'gross_amount' => 12000,
            'taxable_amount' => 10345,
            'vat_amount' => 1655,
            'status' => 'transmitted',
            'raw_request_payload' => ['test' => true],
            'raw_response_payload' => ['code' => '00'],
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('receipt.download', $this->order));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_pdf_receipt_renders_without_etims_invoice_record(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('receipt.download', $this->order));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_html_format_parameter_renders_web_receipt_view(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('receipt.download', ['order' => $this->order, 'format' => 'html']));

        $response->assertStatus(200);
        $response->assertSee('PROFORMA INVOICE');
        $response->assertSee('Signature Rift Valley Bouquet');
    }
}
