<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Occasion;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $categoryFilter = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingProductId = null;

    // Form fields
    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public int $price = 0;
    public int $stock = 0;
    public string $category = 'retail';
    public string $unit_type = 'arrangement';
    public ?string $grade = null;
    public ?string $image_url = null;
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
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:retail,wholesale,gifting',
            'unit_type' => 'required|in:arrangement,stem,bundle,hamper',
            'grade' => 'nullable|string|max:20',
            'image_url' => 'nullable|url|max:500',
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

    public function adjustStock(int $productId, int $amount): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['stock' => max(0, $product->stock + $amount)]);
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingProductId', 'name', 'sku', 'description',
            'price', 'stock', 'category', 'unit_type', 'grade',
            'image_url', 'selectedOccasions', 'isEditing'
        ]);
        $this->price = 0;
        $this->stock = 0;
        $this->category = 'retail';
        $this->unit_type = 'arrangement';
    }

    public function render()
    {
        $query = Product::with('occasions');

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
            'totalProducts' => Product::count(),
            'lowStockCount' => Product::where('stock', '<=', 10)->count(),
        ])->layout('components.layouts.admin');
    }
}
