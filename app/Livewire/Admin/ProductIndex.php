<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Occasion;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ProductIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Filters
    public string $search = '';
    public string $categoryFilter = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingProductId = null;
    public bool $showStockLogModal = false;

    // Manual Adjust Modal state
    public bool $showAdjustModal = false;
    public ?int $adjustProductId = null;
    public ?int $adjustBranchId = null;
    public int $adjustAmount = 1;
    public string $adjustReason = 'Manual adjustment';

    // Form fields
    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public int $price = 0;
    public int $cost_price = 0;
    public int $stock = 0;
    public string $category = 'retail';
    public string $unit_type = 'arrangement';
    public ?string $grade = null;
    public ?string $image_url = null;
    public $image_file = null;
    public array $selectedOccasions = [];

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingProductId = null;

    protected function rules(): array
    {
        $skuRule = 'nullable|string|max:20';
        if ($this->editingProductId) {
            $skuRule .= '|unique:products,sku,' . $this->editingProductId;
        } else {
            $skuRule .= '|unique:products,sku';
        }

        return [
            'name' => 'required|string|min:3|max:255',
            'sku' => $skuRule,
            'description' => 'nullable|string|max:1000',
            'price' => 'required|integer|min:1',
            'cost_price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|in:retail,wholesale,gifting,uncategorized',
            'unit_type' => 'required|in:arrangement,stem,bundle,hamper',
            'grade' => 'nullable|string|max:20',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:2048',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
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

    public function openEditModal(int $productId): void
    {
        $product = Product::with('occasions')->findOrFail($productId);
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku ?? '';
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->cost_price = $product->cost_price;
        $this->stock = $product->stock;
        $this->category = $product->category ?? 'retail';
        $this->unit_type = $product->unit_type ?? 'arrangement';
        $this->grade = $product->grade;
        $this->image_url = $product->image_url;
        $this->selectedOccasions = $product->occasions->pluck('id')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->image_file) {
            $path = $this->image_file->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        unset($validated['image_file']);

        if ($this->isEditing && $this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);
            $product->update($validated);
        } else {
            $product = Product::create($validated);
        }

        $product->occasions()->sync($this->selectedOccasions);

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $productId): void
    {
        $this->deletingProductId = $productId;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(): void
    {
        if ($this->deletingProductId) {
            Product::destroy($this->deletingProductId);
        }
        $this->showDeleteModal = false;
        $this->deletingProductId = null;
    }

    public function openAdjustModal(int $productId): void
    {
        $this->resetErrorBag();
        $this->adjustProductId = $productId;
        $this->adjustBranchId = Branch::where('is_active', true)->first()?->id ?: Branch::first()?->id;
        $this->adjustAmount = 1;
        $this->adjustReason = 'Manual adjustment';
        $this->showAdjustModal = true;
    }

    public function saveAdjustment(): void
    {
        $this->validate([
            'adjustBranchId' => 'required|exists:branches,id',
            'adjustAmount' => 'required|integer',
            'adjustReason' => 'required|string|min:3|max:255',
        ]);

        $product = Product::findOrFail($this->adjustProductId);
        $product->adjustment_reason = $this->adjustReason;
        $product->adjustment_branch_id = $this->adjustBranchId;
        $product->stock = max(0, $product->stock + $this->adjustAmount);
        $product->save();

        $this->showAdjustModal = false;
        session()->flash('message', "Stock updated successfully for {$product->name}.");
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingProductId', 'name', 'sku', 'description',
            'price', 'cost_price', 'stock', 'category', 'unit_type', 'grade',
            'image_url', 'image_file', 'selectedOccasions', 'isEditing'
        ]);
        $this->price = 0;
        $this->cost_price = 0;
        $this->stock = 0;
        $this->category = 'retail';
        $this->unit_type = 'arrangement';
    }

    public function openStockLogModal(): void
    {
        $this->showStockLogModal = true;
    }

    public function closeStockLogModal(): void
    {
        $this->showStockLogModal = false;
    }

    public function render()
    {
        $query = Product::with(['occasions', 'branchStocks.branch']);

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.product-index', [
            'products' => $query->paginate(12),
            'occasions' => Occasion::all(),
            'branches' => Branch::all(),
            'totalProducts' => Product::count(),
            'lowStockCount' => Product::where('stock', '<=', 10)->count(),
            'inventoryLogs' => \App\Models\InventoryLog::with(['product', 'user', 'branch'])->latest()->paginate(10, pageName: 'stockLogPage'),
        ])->layout('components.layouts.admin');
    }
}

