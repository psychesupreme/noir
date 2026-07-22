<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\BranchProductStock;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\WastageLog;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SupplyChainTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Branch $branchNairobi;
    private Branch $branchKiambu;
    private Product $roseBouquet;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        // Create Branches
        $this->branchNairobi = Branch::where('code', 'NB-NBO')->first() ?: Branch::create([
            'name' => 'Nairobi Central Atelier',
            'code' => 'NB-NBO',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        $this->branchKiambu = Branch::where('code', 'NB-KBU')->first() ?: Branch::create([
            'name' => 'Kiambu Ridge Hub',
            'code' => 'NB-KBU',
            'location_city' => 'Kiambu',
            'is_active' => true,
        ]);

        // Create Product
        $this->roseBouquet = Product::create([
            'name' => 'Heritage Rose Bouquet',
            'sku' => 'NB-ROS-001',
            'price' => 5000,
            'cost_price' => 2000,
            'stock' => 15,
            'category' => 'retail',
            'unit_type' => 'arrangement',
        ]);
        // The booted event will have distributed 15 stock to the first branch (Nairobi Central Atelier)
    }

    public function test_admin_can_manage_vendors_via_livewire(): void
    {
        $this->actingAs($this->admin);

        // Verify Vendor Creation
        Livewire::test(\App\Livewire\Admin\VendorIndex::class)
            ->call('create')
            ->assertSet('showModal', true)
            ->set('name', 'Nairobi Sourcing Wholesaler')
            ->set('contact_person', 'Peter Sourcing')
            ->set('email', 'peter@wholesales.co.ke')
            ->set('phone', '0722000111')
            ->set('payment_terms', 'Net 30')
            ->set('reliability_rating', 4)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('vendors', [
            'name' => 'Nairobi Sourcing Wholesaler',
            'contact_person' => 'Peter Sourcing',
            'email' => 'peter@wholesales.co.ke',
            'payment_terms' => 'Net 30',
            'reliability_rating' => 4,
            'is_active' => true,
        ]);

        $vendor = Vendor::first();

        // Verify Vendor Editing
        Livewire::test(\App\Livewire\Admin\VendorIndex::class)
            ->call('edit', $vendor->id)
            ->assertSet('isEditMode', true)
            ->set('name', 'Nairobi Premium Wholesaler')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'name' => 'Nairobi Premium Wholesaler',
        ]);

        // Verify Vendor Deletion
        Livewire::test(\App\Livewire\Admin\VendorIndex::class)
            ->call('delete', $vendor->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }

    public function test_purchase_order_lifecycle_cogs_updates_and_stock_receipts(): void
    {
        $this->actingAs($this->admin);

        $vendor = Vendor::create([
            'name' => 'Highlands Flower Farm',
            'contact_person' => 'John Rose',
            'payment_terms' => 'Cash on Delivery',
        ]);

        // 1. Create Purchase Order draft via Livewire
        Livewire::test(\App\Livewire\Admin\PurchaseOrderIndex::class)
            ->call('create')
            ->assertSet('showCreateModal', true)
            ->set('vendor_id', $vendor->id)
            ->set('branch_id', $this->branchNairobi->id)
            ->set('notes', 'Premium batch order')
            ->set('poItems', [
                ['product_id' => $this->roseBouquet->id, 'quantity_ordered' => 10, 'unit_cost' => 1500] // KSH 1500 cost vs current 2000 cost
            ])
            ->call('savePO')
            ->assertHasNoErrors()
            ->assertSet('showCreateModal', false);

        $this->assertDatabaseHas('purchase_orders', [
            'vendor_id' => $vendor->id,
            'branch_id' => $this->branchNairobi->id,
            'status' => 'draft',
            'total_cost' => 15000,
        ]);

        $po = PurchaseOrder::first();

        // 2. Mark Ordered
        Livewire::test(\App\Livewire\Admin\PurchaseOrderIndex::class)
            ->call('markOrdered', $po->id)
            ->assertHasNoErrors();

        $this->assertEquals('ordered', $po->fresh()->status);
        $this->assertNotNull($po->fresh()->ordered_at);

        // 3. Receive stock & sync weighted average COGS
        // Nairobi starting stock: 15. Nairobi starting aggregate: 15. Cost: 2000.
        // Receiving 10 units at 1500 KSH.
        // New Cost calculation: ((2000 * 15) + (1500 * 10)) / (15 + 10) = (30000 + 15000) / 25 = 45000 / 25 = 1800 KSH.
        Livewire::test(\App\Livewire\Admin\PurchaseOrderIndex::class)
            ->call('openReceiveModal', $po->id)
            ->assertSet('showReceiveModal', true)
            ->set('receiveItems.0.qty_received_now', 10)
            ->call('saveReceive')
            ->assertHasNoErrors()
            ->assertSet('showReceiveModal', false);

        $freshPo = $po->fresh();
        $this->assertEquals('received', $freshPo->status);
        $this->assertNotNull($freshPo->received_at);

        // Verify product stocks are updated correctly
        $this->roseBouquet = $this->roseBouquet->fresh();
        $this->assertEquals(25, $this->roseBouquet->stock); // 15 + 10 = 25
        $this->assertEquals(1800, $this->roseBouquet->cost_price); // New weighted cost price is 1800

        // Verify Branch Product Stock pivot was updated
        $this->assertEquals(25, $this->branchNairobi->getStockForProduct($this->roseBouquet->id));

        // Verify Inventory Log ledger was updated
        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $this->roseBouquet->id,
            'branch_id' => $this->branchNairobi->id,
            'reason' => "PO Receipt: {$po->po_number}",
            'adjustment' => 10,
            'quantity_before' => 15,
            'quantity_after' => 25,
        ]);
    }

    public function test_wastage_log_stock_deductions_and_cost_estimation(): void
    {
        $this->actingAs($this->admin);

        // Total starting stock: 15 (Nairobi branch). Cost price: 2000.
        // Log 3 units wasted due to Spoilage at Nairobi branch.
        Livewire::test(\App\Livewire\Admin\WastageIndex::class)
            ->call('openLogModal')
            ->assertSet('showLogModal', true)
            ->set('branch_id', $this->branchNairobi->id)
            ->set('product_id', $this->roseBouquet->id)
            ->set('quantity', 3)
            ->set('reason', 'Spoilage')
            ->set('notes', 'Damaged during unloading')
            ->call('logWastage')
            ->assertHasNoErrors()
            ->assertSet('showLogModal', false);

        // Verify Wastage Log Database record
        $this->assertDatabaseHas('wastage_logs', [
            'branch_id' => $this->branchNairobi->id,
            'product_id' => $this->roseBouquet->id,
            'quantity' => 3,
            'reason' => 'Spoilage',
            'cost_estimate' => 6000, // 3 * 2000 = 6000 KSH
        ]);

        // Verify Stock Deductions
        $this->roseBouquet = $this->roseBouquet->fresh();
        $this->assertEquals(12, $this->roseBouquet->stock); // 15 - 3 = 12
        $this->assertEquals(12, $this->branchNairobi->getStockForProduct($this->roseBouquet->id));

        // Verify Inventory Log entry
        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $this->roseBouquet->id,
            'branch_id' => $this->branchNairobi->id,
            'adjustment' => -3,
            'reason' => 'Wastage: Spoilage',
        ]);
    }

    public function test_inter_branch_stock_transfers_and_ledgers(): void
    {
        $this->actingAs($this->admin);

        // Nairobi stock: 15. Kiambu stock: 0.
        // Transfer 5 units from Nairobi to Kiambu.
        Livewire::test(\App\Livewire\Admin\BranchIndex::class)
            ->call('openTransferModal', $this->branchNairobi->id)
            ->assertSet('showTransferModal', true)
            ->set('transferDestBranchId', $this->branchKiambu->id)
            ->set('transferProductId', $this->roseBouquet->id)
            ->set('transferQuantity', 5)
            ->call('saveTransfer')
            ->assertHasNoErrors()
            ->assertSet('showTransferModal', false);

        // Verify stocks are redistributed
        $this->assertEquals(10, $this->branchNairobi->getStockForProduct($this->roseBouquet->id)); // 15 - 5 = 10
        $this->assertEquals(5, $this->branchKiambu->getStockForProduct($this->roseBouquet->id));   // 0 + 5 = 5
        
        // Product aggregate stock should remain identical (15)
        $this->assertEquals(15, $this->roseBouquet->fresh()->stock);

        // Verify logs created for both branches
        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $this->roseBouquet->id,
            'branch_id' => $this->branchNairobi->id,
            'adjustment' => -5,
            'reason' => 'Transfer OUT to NB-KBU',
        ]);

        $this->assertDatabaseHas('inventory_logs', [
            'product_id' => $this->roseBouquet->id,
            'branch_id' => $this->branchKiambu->id,
            'adjustment' => 5,
            'reason' => 'Transfer IN from NB-NBO',
        ]);
    }
}
