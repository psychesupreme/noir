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

    public array $categoriesDictionary = [
        'stems' => [
            'name' => 'Stems & Blooms',
            'subcategories' => [
                'Roses' => ['stem', 'bunch'],
                'Lilies' => ['stem', 'bunch'],
                'Orchids' => ['stem', 'pot'],
            ]
        ],
        'bouquet' => [
            'name' => 'Curated Bouquets',
            'subcategories' => [
                'Hand-tied Bouquets' => ['arrangement', 'wrap'],
                'Boxed Arrangements' => ['box', 'arrangement'],
                'Dome Bouquets' => ['arrangement', 'dome'],
            ]
        ],
        'giftings' => [
            'name' => 'Luxury Gifts',
            'subcategories' => [
                'Wines & Champagnes' => ['bottle', 'case'],
                'Chocolates & Truffles' => ['box', 'grams'],
                'Home Scents & Mists' => ['bottle', 'set'],
            ]
        ],
        'home_decor' => [
            'name' => 'Aesthetic Home Decor',
            'subcategories' => [
                'Ceramic & Glass Vases' => ['piece', 'set'],
                'Aroma Diffusers' => ['piece', 'set'],
                'Luxury Candles' => ['piece', 'pack'],
            ]
        ],
        'services' => [
            'name' => 'Bespoke Floral Services',
            'subcategories' => [
                'Corporate Subscriptions' => ['rotation', 'month'],
                'Event Installations' => ['setup', 'event'],
                'Hand Curation Desk' => ['session', 'consultation'],
            ]
        ],
        'bundles' => [
            'name' => 'Cohesive Combo Packages',
            'subcategories' => [
                'Flowers & Wine Combo' => ['bundle', 'set'],
                'Sweet Romance Suite' => ['bundle', 'set'],
                'Sympathy Comfort Pack' => ['bundle', 'set'],
            ]
        ],
        'accessories' => [
            'name' => 'Curation Accessories',
            'subcategories' => [
                'Satin Ribbons' => ['roll', 'meter'],
                'Glitter & Spritz' => ['can', 'spray'],
                'Greeting Cards' => ['piece', 'pack'],
            ]
        ],
        'wholesale' => [
            'name' => 'Bulk Export Flowers',
            'subcategories' => [
                'Export Grade Roses' => ['bunch', 'crate'],
                'Foliage & Greens' => ['bunch', 'crate'],
                'Wholesale Lilies' => ['bunch', 'crate'],
            ]
        ],
        'preserves' => [
            'name' => 'Everlasting Preserved Flowers',
            'subcategories' => [
                'Infinity Roses' => ['piece', 'box'],
                'Dried Flower Bundles' => ['bunch', 'arrangement'],
                'Glass Dome Preserves' => ['dome', 'piece'],
            ]
        ],
        'subscriptions' => [
            'name' => 'Consumer Floral Plans',
            'subcategories' => [
                'Weekly Home Bouquets' => ['delivery', 'month'],
                'Bi-weekly Office Blooms' => ['delivery', 'month'],
                'Monthly Luxury Suites' => ['delivery', 'quarter'],
            ]
        ],
    ];

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
    public string $category = 'stems';
    public string $subcategory = '';
    public string $unit_type = 'arrangement';
    public string $size_unit = '';
    public ?string $grade = null;
    public ?string $image_url = null;
    public $image_file = null;
    public ?int $selectedBranchId = null;
    public array $sizesList = [];

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
            'category' => 'required|in:stems,bouquet,giftings,home_decor,services,bundles,accessories,wholesale,preserves,subscriptions,bundle,specialization',
            'subcategory' => 'nullable|string|max:50',
            'unit_type' => 'required|in:arrangement,stem,bundle,hamper,bottle,grams,kg,litres,oz,size,bunch,pot,wrap,box,dome,case,set,piece,pack,rotation,month,setup,event,session,consultation,roll,meter,can,spray,crate,delivery,quarter',
            'size_unit' => 'nullable|string|max:50',
            'grade' => 'nullable|string|max:20',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:2048',
            'selectedBranchId' => 'nullable|exists:branches,id',
            'sizesList' => 'nullable|array',
            'sizesList.*.name' => 'required|string|min:1|max:50',
            'sizesList.*.price' => 'required|integer|min:0',
            'sizesList.*.cost_price' => 'required|integer|min:0',
            'sizesList.*.stock' => 'required|integer|min:0',
        ];
    }

    public function addSizeVariation(): void
    {
        $this->sizesList[] = [
            'name' => '',
            'price' => 0,
            'cost_price' => 0,
            'stock' => 0,
        ];
    }

    public function removeSizeVariation(int $index): void
    {
        unset($this->sizesList[$index]);
        $this->sizesList = array_values($this->sizesList);
    }

    public function updatedCategory($value): void
    {
        $basePrice = $this->price ?: 1500;
        $baseCost = $this->cost_price ?: 600;
        $baseStock = $this->stock ?: 20;

        $mappedValue = $value;
        if ($value === 'bundle') {
            $mappedValue = 'bundles';
        } elseif ($value === 'specialization') {
            $mappedValue = 'services';
        }

        if (array_key_exists($mappedValue, $this->categoriesDictionary)) {
            $subcats = $this->categoriesDictionary[$mappedValue]['subcategories'];
            $firstSub = array_key_first($subcats);
            $this->subcategory = $firstSub;
            
            $units = $subcats[$firstSub];
            $this->unit_type = $units[0];
            $this->size_unit = $this->getDefaultSizeUnit($this->unit_type);

            $this->sizesList = $this->generateDefaultSizes($mappedValue, $this->subcategory, $this->unit_type, $basePrice, $baseCost, $baseStock);
        }
    }

    public function updatedSubcategory($value): void
    {
        $basePrice = $this->price ?: 1500;
        $baseCost = $this->cost_price ?: 600;
        $baseStock = $this->stock ?: 20;

        $mappedValue = $this->category;
        if ($this->category === 'bundle') {
            $mappedValue = 'bundles';
        } elseif ($this->category === 'specialization') {
            $mappedValue = 'services';
        }

        // Support backward compatibility for Wine / Chocolate / Hamper tests setting subcategory to old values
        if ($mappedValue === 'giftings') {
            $normalized = strtolower($value);
            if (str_contains($normalized, 'wine') || $normalized === 'wines') {
                $value = 'Wines & Champagnes';
            } elseif (str_contains($normalized, 'chocolate') || $normalized === 'chocolates') {
                $value = 'Chocolates & Truffles';
            } elseif (str_contains($normalized, 'hamper') || $normalized === 'hampers') {
                $value = 'Home Scents & Mists';
            }
        }

        if (array_key_exists($mappedValue, $this->categoriesDictionary)) {
            $subcats = $this->categoriesDictionary[$mappedValue]['subcategories'];
            if (array_key_exists($value, $subcats)) {
                $units = $subcats[$value];
                $this->unit_type = $units[0];
                $this->size_unit = $this->getDefaultSizeUnit($this->unit_type);
                $this->sizesList = $this->generateDefaultSizes($mappedValue, $value, $this->unit_type, $basePrice, $baseCost, $baseStock);
            }
        }
    }

    public function updatedUnitType($value): void
    {
        if ($this->category === 'giftings') {
            if ($value === 'hamper' || $value === 'hampers') {
                $this->subcategory = 'Home Scents & Mists';
            } elseif ($value === 'grams' || $value === 'chocolate') {
                $this->subcategory = 'Chocolates & Truffles';
            } elseif ($value === 'bottle' || $value === 'wines') {
                $this->subcategory = 'Wines & Champagnes';
            }
        }
    }

    private function getDefaultSizeUnit(string $unitType): string
    {
        return match ($unitType) {
            'stem', 'bunch', 'pot', 'arrangement', 'wrap', 'box', 'dome', 'piece', 'pack', 'set', 'roll', 'meter', 'can', 'spray', 'crate' => 'pieces',
            'bottle', 'delivery' => 'litres',
            'grams' => 'grams',
            'rotation', 'month', 'setup', 'event', 'session', 'consultation', 'quarter' => 'service',
            default => 'pieces',
        };
    }

    private function generateDefaultSizes(string $category, string $subcategory, string $unitType, int $basePrice, int $baseCost, int $baseStock): array
    {
        if ($category === 'stems') {
            return [
                ['name' => 'Single Stem', 'price' => $basePrice, 'cost_price' => $baseCost, 'stock' => $baseStock],
                ['name' => 'Bunch (5 Stems)', 'price' => $basePrice * 4, 'cost_price' => $baseCost * 4, 'stock' => (int)floor($baseStock / 5)],
                ['name' => 'Luxe (10 Stems)', 'price' => $basePrice * 8, 'cost_price' => $baseCost * 8, 'stock' => (int)floor($baseStock / 10)],
            ];
        }

        if ($category === 'giftings' && $subcategory === 'Wines & Champagnes') {
            return [
                ['name' => '375ml Half', 'price' => (int)floor($basePrice * 0.6), 'cost_price' => (int)floor($baseCost * 0.6), 'stock' => $baseStock],
                ['name' => '750ml Standard', 'price' => $basePrice, 'cost_price' => $baseCost, 'stock' => $baseStock],
                ['name' => '1.5L Magnum', 'price' => $basePrice * 2, 'cost_price' => $baseCost * 2, 'stock' => (int)floor($baseStock * 0.4)],
            ];
        }

        if ($category === 'giftings' && $subcategory === 'Chocolates & Truffles') {
            return [
                ['name' => '30g Petite', 'price' => (int)floor($basePrice * 0.3), 'cost_price' => (int)floor($baseCost * 0.3), 'stock' => $baseStock],
                ['name' => '100g Classic', 'price' => $basePrice, 'cost_price' => $baseCost, 'stock' => $baseStock],
                ['name' => '400g Grand', 'price' => $basePrice * 3, 'cost_price' => $baseCost * 3, 'stock' => (int)floor($baseStock * 0.4)],
            ];
        }

        return [
            ['name' => 'Classic', 'price' => $basePrice, 'cost_price' => $baseCost, 'stock' => $baseStock],
            ['name' => 'Deluxe', 'price' => (int)floor($basePrice * 1.5), 'cost_price' => (int)floor($baseCost * 1.5), 'stock' => (int)floor($baseStock * 0.7)],
            ['name' => 'Grand', 'price' => $basePrice * 2, 'cost_price' => $baseCost * 2, 'stock' => (int)floor($baseStock * 0.4)],
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
        $this->selectedBranchId = Branch::where('is_active', true)->first()?->id ?: Branch::first()?->id;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku ?? '';
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->cost_price = $product->cost_price;
        $this->stock = $product->stock;
        $this->category = $product->category ?? 'stems';
        $this->subcategory = $product->subcategory ?? '';
        $this->unit_type = $product->unit_type ?? 'arrangement';
        $this->size_unit = $product->size_unit ?? '';
        $this->grade = $product->grade;
        $this->image_url = $product->image_url;
        $this->sizesList = $product->sizes ?? [];
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

        $selectedBranch = $validated['selectedBranchId'] ?? null;
        unset($validated['selectedBranchId']);

        $sizes = $this->sizesList ?: null;
        if ($sizes) {
            $totalStock = 0;
            foreach ($sizes as $idx => $size) {
                $sizes[$idx]['price'] = (int)$size['price'];
                $sizes[$idx]['cost_price'] = (int)$size['cost_price'];
                $sizes[$idx]['stock'] = (int)$size['stock'];
                $totalStock += $sizes[$idx]['stock'];
            }
            $validated['sizes'] = $sizes;
            $validated['stock'] = $totalStock;
            $this->stock = $totalStock;
        } else {
            $validated['sizes'] = null;
        }
        unset($validated['sizesList']);

        if ($this->isEditing && $this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);
            $product->update($validated);
        } else {
            $product = new Product($validated);
            if ($selectedBranch) {
                $product->adjustment_branch_id = $selectedBranch;
            }
            $product->save();
        }

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
            'price', 'cost_price', 'stock', 'category', 'subcategory',
            'unit_type', 'size_unit', 'grade',
            'image_url', 'image_file', 'isEditing', 'selectedBranchId', 'sizesList'
        ]);
        $this->price = 0;
        $this->cost_price = 0;
        $this->stock = 0;
        $this->category = 'stems';
        $this->subcategory = '';
        $this->unit_type = 'arrangement';
        $this->size_unit = '';
        $this->sizesList = [];
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
        $query = Product::with(['branchStocks.branch']);

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
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | Products']);
    }
}

