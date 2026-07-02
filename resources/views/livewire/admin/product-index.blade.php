<div class="min-h-screen text-neutral-100 font-sans antialiased">

    {{-- ─── Top Bar ─── --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            {{-- Title + Stats --}}
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <h2 class="text-2xl font-extralight tracking-tight text-white">Product Catalog</h2>
                    <span class="text-[10px] font-mono tracking-wider bg-neutral-800 text-neutral-400 px-2.5 py-1 rounded-sm">
                        {{ $totalProducts }} items
                    </span>
                    @if ($lowStockCount > 0)
                        <span class="text-[10px] font-mono tracking-wider bg-rose-950/40 text-rose-400 border border-rose-900/30 px-2.5 py-1 rounded-sm animate-pulse">
                            ⚠ {{ $lowStockCount }} low stock
                        </span>
                    @endif
                </div>
                <p class="text-xs text-neutral-500 font-light">Luxury floral inventory & curation management.</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search catalog..."
                        class="bg-[#0A0A0A] border border-neutral-800 rounded-sm pl-9 pr-4 py-2 text-sm text-white placeholder-neutral-600 focus:outline-none focus:border-neutral-600 w-56 transition-colors"
                    >
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>

                {{-- Category Filter --}}
                <select
                    wire:model.live="categoryFilter"
                    class="bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-neutral-400 focus:outline-none focus:border-neutral-600 cursor-pointer"
                >
                    <option value="all">All Categories</option>
                    <option value="stems">Stems</option>
                    <option value="bundle">Bundle</option>
                    <option value="bouquet">Bouquet</option>
                    <option value="giftings">Giftings</option>
                    <option value="wines">Wines</option>
                    <option value="chocolate">Chocolate</option>
                    <option value="specialization">Specialization</option>
                </select>

                {{-- Stock Trail --}}
                <button
                    wire:click="openStockLogModal"
                    class="flex items-center space-x-2 border border-neutral-850 text-neutral-400 px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm hover:text-white hover:border-neutral-600 transition-colors mr-1"
                >
                    <span>Stock Ledger</span>
                </button>

                {{-- Add Product --}}
                <button
                    wire:click="openCreateModal"
                    class="flex items-center space-x-2 bg-white text-black px-4 py-2 text-xs font-medium tracking-wider uppercase rounded-sm hover:bg-neutral-200 transition-colors"
                >
                    <span class="text-base leading-none">+</span>
                    <span>Add Product</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Product Table ─── --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50 text-neutral-500">
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('name')">
                            <span class="flex items-center space-x-1">
                                <span>Product</span>
                                @if ($sortField === 'name')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium">Category</th>
                        <th class="p-5 font-medium">Grade</th>
                        <th class="p-5 font-medium">Unit</th>
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('price')">
                            <span class="flex items-center space-x-1">
                                <span>Price (Ksh)</span>
                                @if ($sortField === 'price')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium">Cost / Margin</th>
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('stock')">
                            <span class="flex items-center space-x-1">
                                <span>Stock</span>
                                @if ($sortField === 'stock')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($products as $product)
                        <tr class="hover:bg-neutral-900/20 transition-colors" wire:key="product-{{ $product->id }}">
                            {{-- Product Name + SKU --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-3">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-sm object-cover border border-neutral-800">
                                    @else
                                        <div class="w-10 h-10 rounded-sm bg-neutral-900 border border-neutral-800 flex items-center justify-center text-neutral-600 text-xs">✿</div>
                                    @endif
                                    <div>
                                        <span class="text-white font-normal block">{{ $product->name }}</span>
                                        @if ($product->sku)
                                            <span class="text-[10px] font-mono text-neutral-600 mt-0.5 block tracking-wider">{{ $product->sku }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Category Badge --}}
                            <td class="p-5">
                                @php
                                    $catStyles = match($product->category) {
                                        'stems' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'bouquet', 'bouquets' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'giftings', 'hampers' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        'bundle', 'home_decor' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        'specializtion', 'specialization', 'specializations' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                        default => 'bg-neutral-500/10 text-neutral-400 border-neutral-500/20',
                                    };
                                @endphp
                                <span class="inline-block {{ $catStyles }} border px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider">
                                    {{ $product->category ?? 'uncategorized' }}
                                </span>
                            </td>

                            {{-- Grade --}}
                            <td class="p-5">
                                @if ($product->grade)
                                    <span class="inline-block bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider">
                                        {{ $product->grade }}
                                    </span>
                                @else
                                    <span class="text-neutral-700 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Unit Type --}}
                            <td class="p-5">
                                <span class="text-xs text-neutral-400 font-mono uppercase tracking-wider">{{ $product->unit_type ?? 'arrangement' }}</span>
                            </td>

                            {{-- Price --}}
                            <td class="p-5 font-mono">
                                <span class="text-white font-medium tracking-tight">{{ number_format($product->price) }}</span>
                                <span class="text-neutral-600 text-[10px] ml-1">Ksh</span>
                            </td>

                            {{-- Cost / Margin --}}
                            <td class="p-5 font-mono text-xs">
                                <div class="text-neutral-400">Cost: Ksh {{ number_format($product->cost_price) }}</div>
                                <div class="text-neutral-500 mt-1">Margin: <span class="text-emerald-400 font-semibold">{{ $product->margin_percent }}%</span></div>
                            </td>

                            {{-- Stock with branch breakdown --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-2">
                                    <span class="font-mono text-sm min-w-[2rem] text-left font-semibold
                                        {{ $product->stock <= 5 ? 'text-rose-400' : ($product->stock <= 10 ? 'text-amber-400' : 'text-white') }}
                                    ">{{ $product->stock }}</span>
                                    <button
                                        wire:click="openAdjustModal({{ $product->id }})"
                                        class="text-[9px] uppercase font-mono tracking-wider border border-neutral-850 px-1.5 py-0.5 rounded-sm bg-neutral-900 hover:bg-neutral-800 text-amber-500 hover:text-amber-400 transition-colors"
                                    >Adjust</button>
                                </div>
                                <div class="text-[9px] font-mono text-neutral-600 uppercase mt-1.5 space-y-0.5">
                                    @foreach($product->branchStocks as $bs)
                                        <div>{{ substr($bs->branch->name, 0, 3) }}: {{ $bs->stock }}</div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-2">
                                    <button
                                        wire:click="openEditModal({{ $product->id }})"
                                        class="px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider text-neutral-400 bg-neutral-900/50 border border-neutral-800 rounded-sm hover:text-white hover:border-neutral-600 transition-colors"
                                    >Edit</button>
                                    <button
                                        wire:click="confirmDelete({{ $product->id }})"
                                        class="px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider text-rose-500/60 bg-rose-950/20 border border-rose-900/20 rounded-sm hover:text-rose-400 hover:border-rose-800/40 transition-colors"
                                    >Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-16 text-center">
                                <div class="text-neutral-600">
                                    <span class="text-3xl block mb-3">✿</span>
                                    <span class="text-sm font-light">No products found in the current catalog context.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-neutral-900 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Stock Ledger Modal --}}
    @if ($showStockLogModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-4xl p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200 max-h-[85vh] flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-base font-light tracking-widest text-white uppercase">Stock Audit Trail</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Live tracking ledger of all manual and system-triggered inventory changes.</p>
                    </div>
                    <button 
                        wire:click="closeStockLogModal"
                        class="text-neutral-500 hover:text-white transition-colors"
                    >
                        ✕ Close
                    </button>
                </div>

                {{-- Stock Table --}}
                <div class="border border-neutral-900 rounded-sm overflow-hidden flex-1 overflow-y-auto mb-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-900 text-neutral-500 text-[9px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50 font-mono">
                                <th class="p-4">Timestamp</th>
                                <th class="p-4">Product Name</th>
                                <th class="p-4">Branch</th>
                                <th class="p-4 font-mono text-center">Prev</th>
                                <th class="p-4 font-mono text-center">Adj</th>
                                <th class="p-4 font-mono text-center">New</th>
                                <th class="p-4">Reason Details</th>
                                <th class="p-4">Staff Member</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/60 text-xs font-mono">
                            @forelse ($inventoryLogs as $log)
                                <tr class="hover:bg-neutral-950/40">
                                    <td class="p-4 text-neutral-500 font-light">
                                        {{ $log->created_at->format('d M Y H:i:s') }}
                                    </td>
                                    <td class="p-4 text-white">
                                        {{ $log->product->name ?? 'Deleted Product' }}
                                    </td>
                                    <td class="p-4 text-neutral-400 font-light">
                                        {{ $log->branch->code ?? 'Global' }}
                                    </td>
                                    <td class="p-4 text-center text-neutral-500">
                                        {{ $log->quantity_before }}
                                    </td>
                                    <td class="p-4 text-center font-bold {{ $log->adjustment > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $log->adjustment > 0 ? '+' : '' }}{{ $log->adjustment }}
                                    </td>
                                    <td class="p-4 text-center text-neutral-200">
                                        {{ $log->quantity_after }}
                                    </td>
                                    <td class="p-4 text-neutral-400 font-light">
                                        {{ $log->reason }}
                                    </td>
                                    <td class="p-4 text-neutral-500 font-light">
                                        {{ $log->user->email ?? 'System Engine' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-neutral-600 font-mono">
                                        No inventory audit records captured so far.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-2 text-[10px]">
                    {{ $inventoryLogs->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    @endif

    {{-- ─── Create/Edit Modal ─── --}}
    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            x-data="{ open: @entangle('showModal') }"
            x-show="open"
            x-cloak
        >
            {{-- Overlay --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                wire:click="$set('showModal', false)"
            ></div>

            {{-- Modal --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-2xl mx-4 bg-[#0F0F12] border border-neutral-800 rounded-sm shadow-2xl max-h-[90vh] overflow-y-auto"
            >
                {{-- Modal Header --}}
                <div class="border-b border-neutral-900 px-6 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-white tracking-wider uppercase">
                            {{ $isEditing ? 'Edit Product' : 'New Product' }}
                        </h3>
                        <p class="text-[10px] text-neutral-600 font-mono tracking-wider mt-1">
                            {{ $isEditing ? 'UPDATE CATALOG ENTRY' : 'ADD TO LUXURY CATALOG' }}
                        </p>
                    </div>
                    <button
                        wire:click="$set('showModal', false)"
                        class="text-neutral-600 hover:text-white transition-colors text-lg"
                    >&times;</button>
                </div>

                {{-- Form --}}
                <form wire:submit="save" class="p-6 space-y-5">

                    {{-- Row: Name + SKU --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Product Name *</label>
                            <input
                                wire:model="name"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="Heritage Rose Bouquet"
                            >
                            @error('name') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">SKU</label>
                            <input
                                wire:model="sku"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                placeholder="NB-ROS-001"
                            >
                            <span class="text-[9px] text-neutral-600 mt-1 block">(Leave empty to generate automatically)</span>
                            @error('sku') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Row: Category + Sub-category + Unit Type + Size Unit + Branch --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Category *</label>
                            <select
                                wire:model.live="category"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                            >
                                @foreach($categoriesDictionary as $key => $info)
                                    <option value="{{ $key }}">{{ $info['name'] }}</option>
                                @endforeach
                                <option value="bundle">Bundle (Legacy)</option>
                                <option value="specialization">Specialization (Legacy)</option>
                            </select>
                            @error('category') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Sub-category</label>
                            @php
                                $mappedCategory = $category;
                                if ($category === 'bundle') $mappedCategory = 'bundles';
                                if ($category === 'specialization') $mappedCategory = 'services';
                                $subcats = $categoriesDictionary[$mappedCategory]['subcategories'] ?? [];
                            @endphp
                            @if(count($subcats) > 0)
                                <select
                                    wire:model.live="subcategory"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                                >
                                    @foreach(array_keys($subcats) as $subName)
                                        <option value="{{ $subName }}">{{ $subName }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input
                                    wire:model="subcategory"
                                    type="text"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                    placeholder="Enter sub-category"
                                >
                            @endif
                            @error('subcategory') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Unit Type *</label>
                            @php
                                $unitOptions = $subcats[$subcategory] ?? [];
                            @endphp
                            @if(count($unitOptions) > 0)
                                <select
                                    wire:model="unit_type"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                                >
                                    @foreach($unitOptions as $unitOption)
                                        <option value="{{ $unitOption }}">{{ ucfirst($unitOption) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select
                                    wire:model="unit_type"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                                >
                                    <option value="arrangement">Arrangement</option>
                                    <option value="stem">Stem</option>
                                    <option value="bundle">Bundle</option>
                                    <option value="hamper">Hamper</option>
                                    <option value="bottle">Bottle</option>
                                    <option value="grams">Grams</option>
                                    <option value="kg">Kilograms (kg)</option>
                                    <option value="litres">Litres</option>
                                    <option value="oz">Ounces (oz)</option>
                                    <option value="size">Size (S/M/L)</option>
                                </select>
                            @endif
                            @error('unit_type') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Size / Measurement Unit</label>
                            <input
                                wire:model="size_unit"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="e.g. litres, grams, pieces"
                            >
                            @error('size_unit') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            @if (!$isEditing)
                                <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Fulfilling Branch *</label>
                                <select
                                    wire:model="selectedBranchId"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                                >
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedBranchId') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                            @else
                                <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Fulfilling Branch</label>
                                <div class="text-xs text-neutral-550 py-2.5 font-mono">Managed per hub</div>
                            @endif
                        </div>
                    </div>

                    {{-- Row: Grade + Price --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Grade</label>
                            <input
                                wire:model="grade"
                                type="text"
                                list="existing-gradings"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="Grade A (Export) or choose..."
                            >
                            <datalist id="existing-gradings">
                                @foreach($existingGradings as $g)
                                    <option value="{{ $g }}"></option>
                                @endforeach
                            </datalist>
                            @error('grade') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Selling Price (Ksh) *</label>
                            <input
                                wire:model="price"
                                type="number"
                                min="1"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                placeholder="4500"
                            >
                            @error('price') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Row: Cost Price --}}
                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Cost Price (Ksh) *</label>
                        <input
                            wire:model="cost_price"
                            type="number"
                            min="0"
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                            placeholder="2000"
                        >
                        @error('cost_price') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Row: Stock + Image URL + File Upload --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Stock *</label>
                            <input
                                wire:model="stock"
                                type="number"
                                min="0"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                placeholder="25"
                            >
                            @error('stock') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Image URL</label>
                            <input
                                wire:model="image_url"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="/storage/products/..."
                            >
                            @error('image_url') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Or Upload Image</label>
                            <div 
                                x-data="{ isUploading: false, progress: 0 }"
                                x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                                class="relative"
                            >
                                <input
                                    wire:model="image_file"
                                    type="file"
                                    accept="image/*"
                                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-600 transition-colors file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-mono file:bg-neutral-800 file:text-white hover:file:bg-neutral-700 cursor-pointer"
                                >
                                <div x-show="isUploading" class="w-full bg-neutral-900 rounded-full h-1 mt-2 overflow-hidden" style="display: none;">
                                    <div class="bg-amber-500 h-1 transition-all duration-300" :style="`width: ${progress}%`"></div>
                                </div>
                                <div wire:loading wire:target="image_file" class="text-amber-500 text-[10px] font-mono mt-1">
                                    Uploading...
                                </div>
                            </div>
                            @error('image_file') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Description</label>
                        <textarea
                            wire:model="description"
                            rows="3"
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors resize-none"
                            placeholder="A hand-curated arrangement of premium Kenyan roses..."
                        ></textarea>
                        @error('description') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Size Variations Editor --}}
                    <div class="border-t border-neutral-900 pt-4 mt-2">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs uppercase tracking-[0.2em] font-mono text-neutral-400 font-semibold">Size Variations</h4>
                            <button
                                type="button"
                                wire:click="addSizeVariation"
                                class="px-3 py-1 bg-neutral-900 border border-neutral-800 text-[10px] font-mono uppercase tracking-wider text-[#C5A880] hover:bg-neutral-800 rounded-sm transition-colors cursor-pointer"
                            >
                                + Add Size
                            </button>
                        </div>

                        @if(empty($sizesList))
                            <p class="text-[11px] font-mono text-neutral-600 italic">No custom size variations defined. (Will default to standard/deluxe/grand models or static pricing).</p>
                        @else
                            <div class="space-y-3">
                                @foreach($sizesList as $idx => $size)
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 items-end bg-[#0A0A0A] p-2 border border-neutral-900 rounded-sm">
                                        <div class="md:col-span-2">
                                            <label class="text-[9px] uppercase tracking-wider font-mono text-neutral-500 block mb-1">Size Name (e.g. 750ml, Size 8)</label>
                                            <input
                                                wire:model="sizesList.{{ $idx }}.name"
                                                type="text"
                                                class="w-full bg-black border border-neutral-800 rounded-sm px-2 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                                placeholder="750ml"
                                            >
                                            @error("sizesList.{$idx}.name") <span class="text-rose-400 text-[9px] font-mono mt-0.5 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-[9px] uppercase tracking-wider font-mono text-neutral-500 block mb-1">Price (Ksh)</label>
                                            <input
                                                wire:model="sizesList.{{ $idx }}.price"
                                                type="number"
                                                min="0"
                                                class="w-full bg-black border border-neutral-800 rounded-sm px-2 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                                placeholder="3000"
                                            >
                                            @error("sizesList.{$idx}.price") <span class="text-rose-400 text-[9px] font-mono mt-0.5 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-[9px] uppercase tracking-wider font-mono text-neutral-500 block mb-1">Cost (Ksh)</label>
                                            <input
                                                wire:model="sizesList.{{ $idx }}.cost_price"
                                                type="number"
                                                min="0"
                                                class="w-full bg-black border border-neutral-800 rounded-sm px-2 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                                placeholder="1500"
                                            >
                                            @error("sizesList.{$idx}.cost_price") <span class="text-rose-400 text-[9px] font-mono mt-0.5 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <label class="text-[9px] uppercase tracking-wider font-mono text-neutral-500 block mb-1">Stock</label>
                                                <input
                                                    wire:model="sizesList.{{ $idx }}.stock"
                                                    type="number"
                                                    min="0"
                                                    class="w-full bg-black border border-neutral-800 rounded-sm px-2 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                                    placeholder="50"
                                                >
                                                @error("sizesList.{$idx}.stock") <span class="text-rose-400 text-[9px] font-mono mt-0.5 block">{{ $message }}</span> @enderror
                                            </div>
                                            <button
                                                type="button"
                                                wire:click="removeSizeVariation({{ $idx }})"
                                                class="p-1.5 border border-neutral-800 text-rose-500 hover:bg-neutral-900 rounded-sm transition-colors mt-4 shrink-0 cursor-pointer"
                                                title="Remove Size"
                                            >
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>



                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-900">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-5 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-sm hover:bg-neutral-200 transition-colors"
                        >
                            {{ $isEditing ? 'Update Product' : 'Create Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ─── Delete Confirmation Modal ─── --}}
    @if ($showDeleteModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            x-data="{ open: @entangle('showDeleteModal') }"
            x-show="open"
            x-cloak
        >
            {{-- Overlay --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                wire:click="$set('showDeleteModal', false)"
            ></div>

            {{-- Modal --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-md mx-4 bg-[#0F0F12] border border-neutral-800 rounded-sm shadow-2xl"
            >
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-sm bg-rose-950/30 border border-rose-900/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-rose-400 text-sm">⚠</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-white tracking-wide">Confirm Deletion</h3>
                            <p class="text-xs text-neutral-500 mt-2 leading-relaxed">
                                This product will be permanently removed from the Noir & Bloom catalog. This action cannot be undone. Any associated order history will be preserved.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-neutral-900">
                        <button
                            wire:click="$set('showDeleteModal', false)"
                            class="px-5 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >Cancel</button>
                        <button
                            wire:click="deleteProduct"
                            class="px-5 py-2 bg-rose-600 text-white text-xs font-medium uppercase tracking-wider rounded-sm hover:bg-rose-500 transition-colors"
                        >Delete Product</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ─── Manual Stock Adjustment Modal ─── --}}
    @if ($showAdjustModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300 font-sans">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-md p-6 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white mb-6">Manual Stock Adjustment</h3>
                
                <form wire:submit.prevent="saveAdjustment" class="space-y-4">
                    {{-- Branch Selection --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5 font-mono">Select Fulfilling Branch</label>
                        <select 
                            wire:model="adjustBranchId" 
                            class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                        >
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('adjustBranchId') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Adjustment Quantity --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5 font-mono">Adjustment Quantity (+/-)</label>
                        <input 
                            wire:model="adjustAmount" 
                            type="number" 
                            class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono text-center"
                            placeholder="e.g. 10 or -5"
                        >
                        @error('adjustAmount') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Reason --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5 font-mono">Audit Log Reason</label>
                        <input 
                            wire:model="adjustReason" 
                            type="text" 
                            class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono"
                            placeholder="Manual recount, stock damage, transfer..."
                        >
                        @error('adjustReason') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-neutral-900 mt-6 font-mono">
                        <button
                            type="button"
                            wire:click="$set('showAdjustModal', false)"
                            class="px-5 py-2 text-xs uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-black text-xs font-medium uppercase tracking-wider rounded-sm transition-colors"
                        >Apply Stock</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
