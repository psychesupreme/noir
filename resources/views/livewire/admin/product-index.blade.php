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
                    <option value="retail">Retail</option>
                    <option value="wholesale">Wholesale</option>
                    <option value="gifting">Gifting</option>
                </select>

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
                                        'wholesale' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'gifting' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        default => 'bg-neutral-500/10 text-neutral-400 border-neutral-500/20',
                                    };
                                @endphp
                                <span class="inline-block {{ $catStyles }} border px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider">
                                    {{ $product->category ?? 'retail' }}
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

                            {{-- Stock with inline adjustment --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-2">
                                    <button
                                        wire:click="adjustStock({{ $product->id }}, -1)"
                                        class="w-6 h-6 flex items-center justify-center rounded-sm bg-neutral-900 border border-neutral-800 text-neutral-500 hover:text-white hover:border-neutral-600 transition-colors text-xs"
                                    >−</button>
                                    <span class="font-mono text-sm min-w-[2rem] text-center
                                        {{ $product->stock <= 5 ? 'text-rose-400 font-medium' : ($product->stock <= 10 ? 'text-amber-400' : 'text-white') }}
                                    ">{{ $product->stock }}</span>
                                    <button
                                        wire:click="adjustStock({{ $product->id }}, 1)"
                                        class="w-6 h-6 flex items-center justify-center rounded-sm bg-neutral-900 border border-neutral-800 text-neutral-500 hover:text-white hover:border-neutral-600 transition-colors text-xs"
                                    >+</button>
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
                            @error('sku') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Row: Category + Unit Type --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Category *</label>
                            <select
                                wire:model="category"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                            >
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                                <option value="gifting">Gifting</option>
                            </select>
                            @error('category') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Unit Type *</label>
                            <select
                                wire:model="unit_type"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 cursor-pointer"
                            >
                                <option value="arrangement">Arrangement</option>
                                <option value="stem">Stem</option>
                                <option value="bundle">Bundle</option>
                                <option value="hamper">Hamper</option>
                            </select>
                            @error('unit_type') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Row: Grade + Price --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Grade</label>
                            <input
                                wire:model="grade"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="Grade A (Export)"
                            >
                            @error('grade') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Price (Ksh) *</label>
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

                    {{-- Row: Stock + Image URL --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                type="url"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="https://..."
                            >
                            @error('image_url') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
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

                    {{-- Occasions Multi-Select --}}
                    @if ($occasions->count())
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-3">Occasions</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($occasions as $occasion)
                                    <label class="flex items-center space-x-2 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            value="{{ $occasion->id }}"
                                            wire:model="selectedOccasions"
                                            class="rounded-sm border-neutral-700 bg-[#0A0A0A] text-amber-500 focus:ring-amber-500/30 focus:ring-offset-0 cursor-pointer"
                                        >
                                        <span class="text-xs text-neutral-500 group-hover:text-neutral-300 transition-colors">{{ $occasion->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
</div>
