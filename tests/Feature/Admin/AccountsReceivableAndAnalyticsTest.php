<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\AccountsReceivableInvoice;
use App\Models\AccountsReceivablePayment;
use App\Models\WastageLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccountsReceivableAndAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $corporateUser;
    private Client $corporateClient;
    private Branch $branch;
    private Product $roseBouquet;

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

        // 3. Create Corporate User and Client
        $this->corporateUser = User::factory()->create([
            'account_tier' => UserRole::Corporate,
        ]);

        $this->corporateClient = Client::create([
            'user_id' => $this->corporateUser->id,
            'contact_name' => 'B2B Procurement',
            'company_name' => 'Corporate Corp',
            'email' => 'corporate@corp.co.ke',
            'phone' => '0711222333',
            'region' => 'Nairobi',
            'delivery_address' => 'Corporate Suites Plaza',
            'kra_pin' => 'P051234567A',
            'payment_terms' => 'net_30',
            'credit_limit' => 200000,
            'outstanding_balance' => 0,
        ]);

        // 4. Create Product
        $this->roseBouquet = Product::create([
            'name' => 'Grand Curation Bouquet',
            'sku' => 'NB-GRA-BQT',
            'price' => 10000,
            'cost_price' => 4000,
            'stock' => 50,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);
    }

    public function test_corporate_credit_checkout_flow_and_ar_invoice_creation(): void
    {
        $this->actingAs($this->corporateUser);

        // Put product in session cart
        session()->put('noir_bloom_cart', [
            $this->roseBouquet->id . '-standard' => 2 // Total 20,000 KSH
        ]);

        // Run Storefront checkout
        $component = Livewire::test(\App\Livewire\Storefront::class)
            ->set('checkoutType', 'corporate')
            ->set('paymentMethod', 'net_30')
            ->set('full_name', 'B2B Procurement')
            ->set('company_name', 'Corporate Corp')
            ->set('email', 'corporate@corp.co.ke')
            ->set('phone', '0711222333')
            ->set('kra_pin', 'P051234567A')
            ->set('region', 'Nairobi')
            ->set('delivery_address', 'Corporate Suites Plaza')
            ->set('delivery_type', 'standard')
            ->set('deliveryCity', 'Nairobi')
            ->set('deliveryDate', now()->addDay()->format('Y-m-d'))
            ->call('submitCurationRequest');

        $component->assertHasNoErrors();
        $component->assertSet('orderSubmitted', true);
        $component->assertSet('paymentStatus', 'completed');

        // Check order was created and approved
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('approved', $order->status);
        $this->assertEquals(20300, $order->total_amount);

        // Check client outstanding balance incremented
        $this->corporateClient->refresh();
        $this->assertEquals(20300, $this->corporateClient->outstanding_balance);

        // Check AR invoice was created
        $this->assertDatabaseHas('accounts_receivable_invoices', [
            'order_id' => $order->id,
            'client_id' => $this->corporateClient->id,
            'amount_due' => 20300,
            'amount_paid' => 0,
            'status' => 'unpaid',
        ]);
    }

    public function test_corporate_credit_limits_validation_at_checkout(): void
    {
        $this->actingAs($this->corporateUser);

        // Set high balance or low credit limit to trigger failure
        $this->corporateClient->update([
            'credit_limit' => 15000,
            'outstanding_balance' => 10000,
        ]);

        session()->put('noir_bloom_cart', [
            $this->roseBouquet->id . '-standard' => 1 // 10,000 KSH order + 10,000 existing > 15,000 limit
        ]);

        $component = Livewire::test(\App\Livewire\Storefront::class)
            ->set('checkoutType', 'corporate')
            ->set('paymentMethod', 'net_30')
            ->set('full_name', 'B2B Procurement')
            ->set('company_name', 'Corporate Corp')
            ->set('email', 'corporate@corp.co.ke')
            ->set('phone', '0711222333')
            ->set('kra_pin', 'P051234567A')
            ->set('region', 'Nairobi')
            ->set('delivery_address', 'Corporate Suites Plaza')
            ->set('delivery_type', 'standard')
            ->set('deliveryCity', 'Nairobi')
            ->set('deliveryDate', now()->addDay()->format('Y-m-d'))
            ->call('submitCurationRequest');

        $component->assertHasErrors(['paymentMethod']);
        $this->assertEquals(10000, $this->corporateClient->fresh()->outstanding_balance); // Unchanged
    }

    public function test_accounts_receivable_ledger_payment_paydown(): void
    {
        $this->actingAs($this->admin);

        // Create an approved Net 30 order manually and its AR invoice
        $order = Order::create([
            'client_id' => $this->corporateClient->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 50000,
            'service_fee_amount' => 0,
            'status' => 'approved',
        ]);

        $this->corporateClient->increment('outstanding_balance', 50000);

        $arInvoice = AccountsReceivableInvoice::create([
            'order_id' => $order->id,
            'client_id' => $this->corporateClient->id,
            'amount_due' => 50000,
            'amount_paid' => 0,
            'due_at' => now()->addDays(30),
            'status' => 'unpaid',
        ]);

        // Manual paydown of 30,000 KSH via Livewire
        Livewire::test(\App\Livewire\Admin\AccountsReceivableIndex::class)
            ->call('openPaymentModal', $arInvoice->id)
            ->assertSet('selectedInvoiceId', $arInvoice->id)
            ->set('paymentAmount', 30000)
            ->set('paymentMethod', 'bank_transfer')
            ->set('paymentReference', 'REF-BANK-1234')
            ->call('recordPayment')
            ->assertHasNoErrors()
            ->assertSet('showPaymentModal', false);

        // Verify database updates
        $arInvoice->refresh();
        $this->assertEquals(30000, $arInvoice->amount_paid);
        $this->assertEquals('partially_paid', $arInvoice->status);
        $this->assertEquals(20000, $this->corporateClient->fresh()->outstanding_balance);

        $this->assertDatabaseHas('accounts_receivable_payments', [
            'ar_invoice_id' => $arInvoice->id,
            'amount' => 30000,
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF-BANK-1234',
        ]);

        // Complete the paydown with remaining 20,000 KSH
        Livewire::test(\App\Livewire\Admin\AccountsReceivableIndex::class)
            ->call('openPaymentModal', $arInvoice->id)
            ->set('paymentAmount', 20000)
            ->set('paymentMethod', 'cheque')
            ->set('paymentReference', 'CHQ-5678')
            ->call('recordPayment')
            ->assertHasNoErrors();

        $arInvoice->refresh();
        $this->assertEquals(50000, $arInvoice->amount_paid);
        $this->assertEquals('paid', $arInvoice->status);
        $this->assertEquals(0, $this->corporateClient->fresh()->outstanding_balance);
    }

    public function test_reports_analytics_calculation(): void
    {
        $this->actingAs($this->admin);

        // Create an approved order with products
        $order = Order::create([
            'client_id' => $this->corporateClient->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 30000,
            'service_fee_amount' => 2000,
            'status' => 'approved',
        ]);
        $order->products()->attach($this->roseBouquet->id, ['quantity' => 3, 'price_at_sale' => 10000]); // COGS = 3 * 4000 = 12000

        // Create a wastage log
        WastageLog::create([
            'branch_id' => $this->branch->id,
            'product_id' => $this->roseBouquet->id,
            'quantity' => 2,
            'reason' => 'Spoilage',
            'cost_estimate' => 8000, // 2 * 4000 = 8000
        ]);

        // Load ReportIndex
        Livewire::test(\App\Livewire\Admin\ReportIndex::class)
            ->assertViewHas('totalSales', 30000)
            ->assertViewHas('cogs', 12000)
            ->assertViewHas('wastage', 8000)
            ->assertViewHas('netProfit', 10000); // 30000 - 12000 - 8000 = 10000
    }

    public function test_reports_csv_pl_statement_download(): void
    {
        $this->actingAs($this->admin);

        // Create an approved order
        $order = Order::create([
            'client_id' => $this->corporateClient->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 10000,
            'service_fee_amount' => 0,
            'status' => 'approved',
        ]);
        $order->products()->attach($this->roseBouquet->id, ['quantity' => 1, 'price_at_sale' => 10000]);

        $response = Livewire::test(\App\Livewire\Admin\ReportIndex::class)
            ->call('exportPL');

        $response->assertStatus(200);
        $downloadFile = $response->effects['download'] ?? null;
        $this->assertNotNull($downloadFile);
        $this->assertStringContainsString('corporate_pl_statement_', $downloadFile['name']);
        $this->assertEquals('text/csv', $downloadFile['contentType']);
    }

    public function test_order_cancellation_cancels_etims_invoice(): void
    {
        $this->actingAs($this->admin);

        // Create order
        $order = Order::create([
            'client_id' => $this->corporateClient->id,
            'branch_id' => $this->branch->id,
            'total_amount' => 10000,
            'service_fee_amount' => 0,
            'status' => 'pending',
        ]);

        // Create etims invoice
        $etimsInvoice = \App\Models\EtimsInvoice::create([
            'order_id' => $order->id,
            'internal_invoice_number' => 'INV-TEST-0001',
            'gross_amount' => 10000,
            'taxable_amount' => 8621,
            'vat_amount' => 1379,
            'status' => 'transmitted',
        ]);

        // Approve order
        app(\App\Services\OrderService::class)->approve($order);
        $this->assertEquals('approved', $order->fresh()->status);

        // Cancel order
        app(\App\Services\OrderService::class)->cancel($order);

        // Verify both status fields are cancelled
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals('cancelled', $etimsInvoice->fresh()->status);
    }
}
