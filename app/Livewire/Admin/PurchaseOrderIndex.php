<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderIndex extends Component
{
    use WithPagination;

    public ?int $viewingPoId = null;

    public function mount(): void
    {
        if (request()->query('action') === 'create') {
            $this->create();
        }
        if (request()->query('po_id')) {
            $this->viewPO((int)request()->query('po_id'));
        }
    }

    public function viewPO(int $id): void
    {
        $this->viewingPoId = $id;
    }

    public function closeView(): void
    {
        $this->viewingPoId = null;
    }

    public string $search = '';
    public string $statusFilter = 'all';
    public string $vendorFilter = 'all';
    public string $branchFilter = 'all';

    // Create Form
    public bool $showCreateModal = false;
    public ?int $vendor_id = null;
    public ?int $branch_id = null;
    public string $notes = '';
    public array $poItems = []; // Array of ['product_id', 'quantity_ordered', 'unit_cost']

    // Receive Form
    public bool $showReceiveModal = false;
    public ?PurchaseOrder $receivingPo = null;
    public array $receiveItems = []; // Array of ['item_id', 'product_id', 'product_name', 'qty_ordered', 'qty_received_before', 'qty_received_now']

    protected $rules = [
        'vendor_id' => 'required|exists:vendors,id',
        'branch_id' => 'required|exists:branches,id',
        'notes' => 'nullable|string',
        'poItems' => 'required|array|min:1',
        'poItems.*.product_id' => 'required|exists:products,id',
        'poItems.*.quantity_ordered' => 'required|integer|min:1',
        'poItems.*.unit_cost' => 'required|integer|min:0',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingVendorFilter(): void
    {
        $this->resetPage();
    }

    public function updatingBranchFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetErrorBag();
        $this->vendor_id = Vendor::active()->first()?->id;
        $this->branch_id = Branch::where('is_active', true)->first()?->id ?: Branch::first()?->id;
        $this->notes = '';
        $this->poItems = [
            ['product_id' => '', 'quantity_ordered' => 10, 'unit_cost' => 0]
        ];
        $this->showCreateModal = true;
    }

    public function addPoItem(): void
    {
        $this->poItems[] = ['product_id' => '', 'quantity_ordered' => 10, 'unit_cost' => 0];
    }

    public function removePoItem(int $index): void
    {
        unset($this->poItems[$index]);
        $this->poItems = array_values($this->poItems);
    }

    public function savePO(): void
    {
        $this->validate();

        \DB::transaction(function () {
            // Calculate total cost
            $totalCost = 0;
            foreach ($this->poItems as $item) {
                $totalCost += ($item['quantity_ordered'] * $item['unit_cost']);
            }

            $po = PurchaseOrder::create([
                'vendor_id' => $this->vendor_id,
                'branch_id' => $this->branch_id,
                'status' => 'draft',
                'total_cost' => $totalCost,
                'notes' => $this->notes,
            ]);

            foreach ($this->poItems as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity_ordered'],
                    'quantity_received' => 0,
                    'unit_cost' => $item['unit_cost'],
                ]);
            }
        });

        $this->showCreateModal = false;
        session()->flash('message', 'Purchase Order draft created successfully.');
    }

    public function markOrdered(int $id): void
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status !== 'draft') {
            return;
        }

        $po->update([
            'status' => 'ordered',
            'ordered_at' => now(),
        ]);

        session()->flash('message', "Purchase Order {$po->po_number} marked as Ordered.");
    }

    public function openReceiveModal(int $id): void
    {
        $this->resetErrorBag();
        $this->receivingPo = PurchaseOrder::with('items.product')->findOrFail($id);
        
        $this->receiveItems = [];
        foreach ($this->receivingPo->items as $item) {
            $this->receiveItems[] = [
                'item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'qty_ordered' => $item->quantity_ordered,
                'qty_received_before' => $item->quantity_received,
                'qty_received_now' => $item->quantity_ordered - $item->quantity_received, // Default to receiving remaining items
            ];
        }

        $this->showReceiveModal = true;
    }

    public function saveReceive(): void
    {
        $this->validate([
            'receiveItems' => 'required|array|min:1',
            'receiveItems.*.qty_received_now' => 'required|integer|min:0',
        ]);

        \DB::transaction(function () {
            $allFullyReceived = true;
            $anyReceived = false;

            foreach ($this->receiveItems as $itemData) {
                $item = PurchaseOrderItem::findOrFail($itemData['item_id']);
                $qtyReceivedNow = (int)$itemData['qty_received_now'];

                if ($qtyReceivedNow > 0) {
                    $anyReceived = true;
                    
                    // 1. Update received quantity on PO item
                    $newQtyReceivedTotal = $item->quantity_received + $qtyReceivedNow;
                    $item->update(['quantity_received' => $newQtyReceivedTotal]);

                    // 2. Adjust Product stock and cost price
                    $product = Product::findOrFail($item->product_id);
                    $oldStock = $product->stock;
                    $oldCost = $product->cost_price;
                    $unitCost = $item->unit_cost;

                    // Calculate weighted average cost
                    if ($oldStock + $qtyReceivedNow > 0) {
                        $weightedCost = (($oldCost * max(0, $oldStock)) + ($unitCost * $qtyReceivedNow)) / (max(0, $oldStock) + $qtyReceivedNow);
                        $product->cost_price = (int) round($weightedCost);
                    } else {
                        $product->cost_price = $unitCost;
                    }

                    // Increment aggregate stock, which will fire the product updating event
                    // and handle branch stock pivot update and ledger logging automatically!
                    $product->stock = $oldStock + $qtyReceivedNow;
                    $product->adjustment_reason = "PO Receipt: {$this->receivingPo->po_number}";
                    $product->adjustment_branch_id = $this->receivingPo->branch_id;
                    $product->save();
                }

                if ($item->quantity_received < $item->quantity_ordered) {
                    $allFullyReceived = false;
                }
            }

            // 3. Update PO Status
            if ($anyReceived) {
                $status = $allFullyReceived ? 'received' : 'partial';
                $this->receivingPo->update([
                    'status' => $status,
                    'received_at' => $allFullyReceived ? now() : $this->receivingPo->received_at,
                ]);
            }
        });

        $this->showReceiveModal = false;
        session()->flash('message', "Stock received and logged for PO {$this->receivingPo->po_number}.");
    }

    public function cancelPO(int $id): void
    {
        $po = PurchaseOrder::findOrFail($id);
        if (in_array($po->status, ['received', 'cancelled'])) {
            return;
        }

        $po->update(['status' => 'cancelled']);
        session()->flash('message', "Purchase Order {$po->po_number} cancelled.");
    }

    public function render()
    {
        $query = PurchaseOrder::with(['vendor', 'branch', 'items.product']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        if ($this->vendorFilter !== 'all') {
            $query->where('vendor_id', $this->vendorFilter);
        }
        if ($this->branchFilter !== 'all') {
            $query->where('branch_id', $this->branchFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('po_number', 'like', "%{$this->search}%")
                  ->orWhere('notes', 'like', "%{$this->search}%")
                  ->orWhereHas('vendor', function ($vq) {
                      $vq->where('name', 'like', "%{$this->search}%");
                  });
            });
        }

        return view('livewire.admin.purchase-order-index', [
            'purchaseOrders' => $query->latest()->paginate(10),
            'vendors' => Vendor::active()->get(),
            'branches' => Branch::all(),
            'products' => Product::orderBy('name')->get(),
            'viewingPo' => $this->viewingPoId ? PurchaseOrder::with(['vendor', 'branch', 'items.product'])->find($this->viewingPoId) : null,
        ])->layout('components.layouts.admin', ['title' => 'Purchase Orders']);
    }
}
