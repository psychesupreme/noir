<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\BranchProductStock;
use App\Models\Product;
use App\Models\InventoryLog;
use Livewire\Component;
use Livewire\WithPagination;

class BranchIndex extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $statusFilter = 'all'; // all, active, inactive
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingBranchId = null;

    // Form fields
    public string $name = '';
    public string $code = '';
    public string $location_city = '';
    public bool $is_active = true;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingBranchId = null;

    // Transfer Stock state
    public bool $showTransferModal = false;
    public ?int $transferSourceBranchId = null;
    public ?int $transferDestBranchId = null;
    public ?int $transferProductId = null;
    public int $transferQuantity = 1;

    protected function rules(): array
    {
        $codeRule = 'required|string|max:20';
        if ($this->editingBranchId) {
            $codeRule .= '|unique:branches,code,' . $this->editingBranchId;
        } else {
            $codeRule .= '|unique:branches,code';
        }

        return [
            'name' => 'required|string|min:3|max:255',
            'code' => $codeRule,
            'location_city' => 'required|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $branchId): void
    {
        $branch = Branch::findOrFail($branchId);
        $this->editingBranchId = $branch->id;
        $this->name = $branch->name;
        $this->code = $branch->code;
        $this->location_city = $branch->location_city;
        $this->is_active = (bool)$branch->is_active;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->isEditing && $this->editingBranchId) {
            $branch = Branch::findOrFail($this->editingBranchId);
            $branch->update($validated);
        } else {
            Branch::create($validated);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $branchId): void
    {
        $this->deletingBranchId = $branchId;
        $this->showDeleteModal = true;
    }

    public function deleteBranch(): void
    {
        if ($this->deletingBranchId) {
            Branch::destroy($this->deletingBranchId);
        }
        $this->showDeleteModal = false;
        $this->deletingBranchId = null;
    }

    public function toggleStatus(int $branchId): void
    {
        $branch = Branch::findOrFail($branchId);
        $branch->update(['is_active' => !$branch->is_active]);
    }

    public function openTransferModal(int $sourceBranchId): void
    {
        $this->resetErrorBag();
        $this->transferSourceBranchId = $sourceBranchId;
        $this->transferDestBranchId = Branch::where('id', '!=', $sourceBranchId)->first()?->id;
        $this->transferProductId = Product::orderBy('name')->first()?->id;
        $this->transferQuantity = 1;
        $this->showTransferModal = true;
    }

    public function saveTransfer(): void
    {
        $this->validate([
            'transferDestBranchId' => 'required|exists:branches,id|different:transferSourceBranchId',
            'transferProductId' => 'required|exists:products,id',
            'transferQuantity' => 'required|integer|min:1',
        ]);

        \DB::transaction(function () {
            $sourceStock = BranchProductStock::where('branch_id', $this->transferSourceBranchId)
                ->where('product_id', $this->transferProductId)
                ->first();

            $available = $sourceStock ? $sourceStock->stock : 0;

            if ($available < $this->transferQuantity) {
                $this->addError('transferQuantity', "Insufficient stock at source branch. Available: {$available} units.");
                throw new \Exception("Insufficient stock");
            }

            $product = Product::findOrFail($this->transferProductId);
            $sourceBranch = Branch::findOrFail($this->transferSourceBranchId);
            $destBranch = Branch::findOrFail($this->transferDestBranchId);

            // 1. Decrement source branch stock
            $sourceStock->stock -= $this->transferQuantity;
            $sourceStock->save();

            // 2. Increment destination branch stock
            $destStock = BranchProductStock::firstOrCreate(
                ['branch_id' => $this->transferDestBranchId, 'product_id' => $this->transferProductId],
                ['stock' => 0]
            );
            $destStock->stock += $this->transferQuantity;
            $destStock->save();

            // 3. Log Source movement
            InventoryLog::create([
                'product_id' => $this->transferProductId,
                'branch_id' => $this->transferSourceBranchId,
                'user_id' => auth()->id(),
                'quantity_before' => $available,
                'quantity_after' => $sourceStock->stock,
                'adjustment' => -$this->transferQuantity,
                'reason' => "Transfer OUT to {$destBranch->code}",
            ]);

            // 4. Log Destination movement
            InventoryLog::create([
                'product_id' => $this->transferProductId,
                'branch_id' => $this->transferDestBranchId,
                'user_id' => auth()->id(),
                'quantity_before' => $destStock->stock - $this->transferQuantity,
                'quantity_after' => $destStock->stock,
                'adjustment' => $this->transferQuantity,
                'reason' => "Transfer IN from {$sourceBranch->code}",
            ]);

            // 5. Product stock remains identical, but force cache/sync update
            $product->syncGlobalStock();
        });

        $this->showTransferModal = false;
        session()->flash('message', 'Inter-branch stock transfer completed successfully.');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingBranchId', 'name', 'code', 'location_city', 'is_active', 'isEditing'
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        $query = Branch::query();

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('location_city', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        // Fetch branches for modal options
        $allBranches = Branch::all();
        // Fetch products that have stock in the source branch
        $sourceProducts = [];
        if ($this->transferSourceBranchId) {
            $sourceProducts = Product::whereHas('branchStocks', function ($bq) {
                $bq->where('branch_id', $this->transferSourceBranchId)->where('stock', '>', 0);
            })->orderBy('name')->get();
        }

        return view('livewire.admin.branch-index', [
            'branches' => $query->paginate(12),
            'allBranches' => $allBranches,
            'sourceProducts' => $sourceProducts,
            'totalBranches' => Branch::count(),
            'activeBranches' => Branch::where('is_active', true)->count(),
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | Branches']);
    }
}
