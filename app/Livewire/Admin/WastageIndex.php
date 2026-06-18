<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Product;
use App\Models\WastageLog;
use Livewire\Component;
use Livewire\WithPagination;

class WastageIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (request()->query('action') === 'create') {
            $this->openLogModal();
        }
    }

    public ?int $viewingWastageId = null;

    public function viewWastage(int $id): void
    {
        $this->viewingWastageId = $id;
    }

    public function closeView(): void
    {
        $this->viewingWastageId = null;
    }

    public string $search = '';
    public string $branchFilter = 'all';
    public string $reasonFilter = 'all';

    // Log Form
    public bool $showLogModal = false;
    public ?int $branch_id = null;
    public ?int $product_id = null;
    public int $quantity = 1;
    public string $reason = 'Spoilage';
    public string $notes = '';

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'reason' => 'required|string|in:Spoilage,Damaged,Expired,Design Class,Sample,Other',
        'notes' => 'nullable|string',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBranchFilter(): void
    {
        $this->resetPage();
    }

    public function updatingReasonFilter(): void
    {
        $this->resetPage();
    }

    public function openLogModal(): void
    {
        $this->resetErrorBag();
        $this->branch_id = Branch::where('is_active', true)->first()?->id ?: Branch::first()?->id;
        $this->product_id = null;
        $this->quantity = 1;
        $this->reason = 'Spoilage';
        $this->notes = '';
        $this->showLogModal = true;
    }

    public function logWastage(): void
    {
        $this->validate();

        \DB::transaction(function () {
            $product = Product::findOrFail($this->product_id);
            
            // Check if branch has sufficient stock
            $branchStock = $product->branchStocks()->where('branch_id', $this->branch_id)->first();
            $availableStock = $branchStock ? $branchStock->stock : 0;

            if ($availableStock < $this->quantity) {
                $this->addError('quantity', "Insufficient branch stock. Available stock at selected branch: {$availableStock}.");
                throw new \Exception("Insufficient stock");
            }

            $costEstimate = $product->cost_price * $this->quantity;

            // 1. Log wastage record
            WastageLog::create([
                'branch_id' => $this->branch_id,
                'product_id' => $this->product_id,
                'user_id' => auth()->id(),
                'quantity' => $this->quantity,
                'reason' => $this->reason,
                'notes' => $this->notes,
                'cost_estimate' => $costEstimate,
            ]);

            // 2. Decrement stock
            $product->stock = max(0, $product->stock - $this->quantity);
            $product->adjustment_reason = "Wastage: {$this->reason}";
            $product->adjustment_branch_id = $this->branch_id;
            $product->save();
        });

        $this->showLogModal = false;
        session()->flash('message', 'Wastage / Perishable write-off logged successfully.');
    }

    public function render()
    {
        $query = WastageLog::with(['product', 'branch', 'user']);

        if ($this->branchFilter !== 'all') {
            $query->where('branch_id', $this->branchFilter);
        }
        if ($this->reasonFilter !== 'all') {
            $query->where('reason', $this->reasonFilter);
        }

        if (!empty($this->search)) {
            $query->whereHas('product', function ($pq) {
                $pq->where('name', 'like', "%{$this->search}%");
            });
        }

        // Summary Stats (Current Month)
        $startOfMonth = now()->startOfMonth();
        $totalWastageValue = WastageLog::where('created_at', '>=', $startOfMonth)->sum('cost_estimate');
        
        $topWasted = WastageLog::select('product_id', \DB::raw('SUM(quantity) as total_qty'))
            ->where('created_at', '>=', $startOfMonth)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        // Spoilage vs Expired vs Damaged
        $breakdown = WastageLog::select('reason', \DB::raw('SUM(cost_estimate) as total_cost'))
            ->where('created_at', '>=', $startOfMonth)
            ->groupBy('reason')
            ->get();

        return view('livewire.admin.wastage-index', [
            'logs' => $query->latest()->paginate(10),
            'branches' => Branch::all(),
            'products' => Product::orderBy('name')->get(),
            'totalWastageValue' => $totalWastageValue,
            'topWastedProduct' => $topWasted ? $topWasted->product?->name . " ({$topWasted->total_qty} units)" : 'None',
            'breakdown' => $breakdown,
            'viewingWastage' => $this->viewingWastageId ? WastageLog::with(['product', 'branch', 'user'])->find($this->viewingWastageId) : null,
        ])->layout('components.layouts.admin', ['title' => 'Wastage & Perishables Log']);
    }
}
