<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">Supplier & Vendor Directory</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Manage external wholesalers, track reliability ratings, and configure payment terms for purchase ordering.</p>
            </div>
            <div class="flex items-center gap-4">
                @if (session()->has('message'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 px-4 py-2 text-xs font-mono rounded-sm"
                    >
                        ✓ {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="bg-rose-950/40 border border-rose-900/30 text-rose-400 px-4 py-2 text-xs font-mono rounded-sm"
                    >
                        ✕ {{ session('error') }}
                    </div>
                @endif

                <button 
                    wire:click="create"
                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2.5 rounded-sm transition-all duration-300 transform active:scale-95 shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.3)]"
                >
                    + Add New Vendor
                </button>
            </div>
        </div>
    </div>

    @if($viewingVendorId && $viewingVendor)
        {{-- Detailed Supplier Profile View --}}
        <div class="mb-8 flex items-center justify-between">
            <button 
                wire:click="closeView" 
                class="border border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white px-4 py-2 rounded-sm text-xs font-mono uppercase tracking-wider transition-all cursor-pointer bg-transparent"
            >
                ← Back to Vendor List
            </button>
            <button 
                wire:click="edit({{ $viewingVendor->id }})"
                class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2 rounded-sm transition-all duration-300"
            >
                Edit Profile
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Who & Where --}}
            <div class="space-y-6 text-left">
                {{-- Who: Vendor Details --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-lg font-semibold text-white">{{ $viewingVendor->name }}</h2>
                        <span class="text-xs text-neutral-500 font-mono block mt-0.5">Supplier Profile</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Contact Representative</span>
                            <span class="text-neutral-200 text-sm font-sans font-medium">{{ $viewingVendor->contact_person }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Email Address</span>
                            <span class="text-neutral-200 block truncate">{{ $viewingVendor->email ?: 'Not Configured' }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Phone Contact</span>
                            <span class="text-neutral-200 block">{{ $viewingVendor->phone ?: 'Not Configured' }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Payment Terms</span>
                            <span class="inline-block text-[10px] uppercase font-mono px-2 py-0.5 mt-1 rounded bg-neutral-950 border border-neutral-800 text-neutral-400">
                                {{ $viewingVendor->payment_terms }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Reliability Status</span>
                            <div class="flex text-amber-500 mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $viewingVendor->reliability_rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-neutral-800">★</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Status</span>
                            @if ($viewingVendor->is_active)
                                <span class="text-emerald-500 text-xs uppercase tracking-wider block mt-1">● Active</span>
                            @else
                                <span class="text-neutral-500 text-xs uppercase tracking-wider block mt-1">● Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Where: Address --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-3">
                    <span class="text-[10px] uppercase tracking-wider text-neutral-500 block font-mono border-b border-neutral-900 pb-2">Fulfillment Location (Where)</span>
                    <p class="text-xs text-neutral-300 leading-relaxed font-mono">
                        {{ $viewingVendor->address ?: 'No physical address configured for this supplier.' }}
                    </p>
                </div>
            </div>

            {{-- Right Column: What & Pricing + History --}}
            <div class="lg:col-span-2 space-y-8 text-left">
                {{-- What & Pricing --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
                    <div class="p-6 border-b border-neutral-900 bg-[#0A0A0A]/50">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-white">Supplied Catalog & Pricing (What & Pricing)</h3>
                        <p class="text-[11px] text-neutral-500 font-light mt-1">List of items historically ordered from this vendor and their peak cost price.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/30">
                                    <th class="p-4 font-medium">Product / Variety</th>
                                    <th class="p-4 font-medium">SKU</th>
                                    <th class="p-4 font-medium">Max Cost Price</th>
                                    <th class="p-4 font-medium">Last Purchased</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/60 text-xs font-mono">
                                @forelse($vendorProducts as $item)
                                    @if($item->product)
                                        <tr class="hover:bg-neutral-950/40 transition-colors">
                                            <td class="p-4 text-white font-sans font-normal">{{ $item->product->name }}</td>
                                            <td class="p-4 text-neutral-400">{{ $item->product->sku }}</td>
                                            <td class="p-4 text-amber-500 font-semibold">{{ number_format($item->max_price) }} KSH</td>
                                            <td class="p-4 text-neutral-500">{{ \Carbon\Carbon::parse($item->last_ordered)->format('d M Y') }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-neutral-500 font-light font-mono">
                                            No purchase items logged for this supplier yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- History --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
                    <div class="p-6 border-b border-neutral-900 bg-[#0A0A0A]/50">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-white">Purchase Order History (History)</h3>
                        <p class="text-[11px] text-neutral-500 font-light mt-1">Historical track records of procurement orders and fulfillment statuses.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/30">
                                    <th class="p-4 font-medium">PO Code</th>
                                    <th class="p-4 font-medium">Branch</th>
                                    <th class="p-4 font-medium">Total Cost</th>
                                    <th class="p-4 font-medium">Order Date</th>
                                    <th class="p-4 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/60 text-xs font-mono">
                                @forelse($vendorHistory as $po)
                                    <tr class="hover:bg-neutral-950/40 transition-colors">
                                        <td class="p-4 text-white font-medium">
                                            <a href="{{ route('admin.purchase-orders', ['po_id' => $po->id]) }}" class="text-amber-500 hover:underline">
                                                #PO-{{ str_pad($po->id, 4, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td class="p-4 text-neutral-400 font-sans font-light">{{ $po->branch->name ?? 'N/A' }}</td>
                                        <td class="p-4 text-neutral-200">{{ number_format($po->total_cost) }} KSH</td>
                                        <td class="p-4 text-neutral-400">{{ $po->created_at->format('d M Y') }}</td>
                                        <td class="p-4">
                                            <span class="inline-block text-[9px] uppercase px-2 py-0.5 rounded font-bold 
                                                @if($po->status === 'received') bg-emerald-950 text-emerald-400 border border-emerald-900
                                                @elseif($po->status === 'cancelled') bg-rose-955 text-rose-400 border border-rose-900
                                                @elseif($po->status === 'draft') bg-neutral-900 text-neutral-400 border border-neutral-800
                                                @else bg-amber-955 text-amber-400 border border-amber-900 @endif">
                                                {{ $po->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-neutral-500 font-light font-mono">
                                            No purchase orders placed with this supplier yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Filters & Search --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="md:col-span-3 relative group">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search vendors by name, contact, email or phone..."
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm pl-10 pr-4 py-2.5 text-xs text-neutral-300 placeholder-neutral-600 focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 transition-all duration-300 font-mono"
                >
                <div class="absolute left-3 top-3 flex items-center justify-center pointer-events-none">
                    <svg class="w-4 h-4 text-neutral-600 transition-all duration-300 group-focus-within:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div>
                <select 
                    wire:model.live="activeFilter"
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Vendors</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
            </div>
        </div>

        {{-- Vendors Directory Table --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                            <th class="p-6 font-medium">Vendor Details</th>
                            <th class="p-6 font-medium">Primary Contact</th>
                            <th class="p-6 font-medium">Contact Details</th>
                            <th class="p-6 font-medium">Payment Terms</th>
                            <th class="p-6 font-medium">Reliability</th>
                            <th class="p-6 font-medium">Purchase Orders</th>
                            <th class="p-6 font-medium">Status</th>
                            <th class="p-6 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                        @forelse ($vendors as $vendor)
                            <tr class="hover:bg-neutral-950/40 transition-colors">
                                {{-- Vendor Details --}}
                                <td class="p-6">
                                    <a href="#" wire:click.prevent="viewVendor({{ $vendor->id }})" class="text-amber-500 hover:underline font-normal block">{{ $vendor->name }}</a>
                                    <span class="text-neutral-500 text-xs block truncate max-w-[200px]" title="{{ $vendor->address }}">{{ $vendor->address ?: 'No Address Stated' }}</span>
                                </td>

                                {{-- Contact Person --}}
                                <td class="p-6">
                                    <span class="text-neutral-200 block">{{ $vendor->contact_person }}</span>
                                </td>

                                {{-- Contact Details --}}
                                <td class="p-6 font-mono text-xs">
                                    <span class="text-neutral-400 block">{{ $vendor->email ?: '-' }}</span>
                                    <span class="text-neutral-500 block mt-0.5">{{ $vendor->phone ?: '-' }}</span>
                                </td>

                                {{-- Payment Terms --}}
                                <td class="p-6">
                                    <span class="inline-block text-[10px] tracking-wider uppercase font-mono px-2.5 py-1 rounded-sm bg-neutral-900 text-neutral-400 border border-neutral-800">
                                        {{ $vendor->payment_terms }}
                                    </span>
                                </td>

                                {{-- Reliability --}}
                                <td class="p-6">
                                    <div class="flex text-amber-500" title="{{ $vendor->reliability_rating }} of 5 rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $vendor->reliability_rating)
                                                <span class="text-xs">★</span>
                                            @else
                                                <span class="text-xs text-neutral-800">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                </td>

                                {{-- Purchase Orders --}}
                                <td class="p-6 font-mono text-neutral-400">
                                    {{ $vendor->purchaseOrders()->count() }} POs
                                </td>

                                {{-- Status --}}
                                <td class="p-6">
                                    @if ($vendor->is_active)
                                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500" title="Active"></span>
                                        <span class="text-xs font-mono text-emerald-500 uppercase tracking-wider ml-1.5">Active</span>
                                    @else
                                        <span class="inline-flex h-2 w-2 rounded-full bg-neutral-800" title="Inactive"></span>
                                        <span class="text-xs font-mono text-neutral-500 uppercase tracking-wider ml-1.5">Inactive</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="p-6 text-right space-x-2">
                                    <button 
                                        wire:click="edit({{ $vendor->id }})"
                                        class="text-xs text-neutral-400 hover:text-amber-500 transition-colors uppercase tracking-wider font-mono border border-neutral-800 hover:border-amber-500 px-2 py-1 rounded cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="delete({{ $vendor->id }})"
                                        onclick="confirm('Are you sure you want to delete this vendor?') || event.stopImmediatePropagation()"
                                        class="text-xs text-neutral-600 hover:text-rose-500 transition-colors uppercase tracking-wider font-mono border border-neutral-800 hover:border-rose-500 px-2 py-1 rounded cursor-pointer"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-neutral-500 font-light font-mono">
                                    No suppliers registered in the database directory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 font-mono text-xs">
            {{ $vendors->links() }}
        </div>
    @endif

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="inline-block align-middle bg-[#0B0B0E] relative z-10 border border-neutral-900 rounded-sm text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-6">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white mb-6">
                            {{ $isEditMode ? 'Edit Vendor Details' : 'Register New Vendor' }}
                        </h3>

                        <form wire:submit.prevent="save" class="space-y-4">
                            @if(!$isEditMode)
                            <div class="p-3 bg-neutral-900/50 border border-neutral-900 rounded-sm mb-4">
                                <label class="block text-[9px] uppercase tracking-wider text-[#C5A880] mb-2 font-mono font-bold">&bull; Auto-Fill Default Vendor Template</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="applyPreset('flower')" class="px-2.5 py-1.5 border border-emerald-800/40 bg-emerald-950/20 text-emerald-400 hover:bg-emerald-950/40 text-[10px] font-mono rounded-sm cursor-pointer select-none">
                                        [Flower Farm Preset]
                                    </button>
                                    <button type="button" wire:click="applyPreset('wine')" class="px-2.5 py-1.5 border border-purple-800/40 bg-purple-950/20 text-purple-400 hover:bg-purple-950/40 text-[10px] font-mono rounded-sm cursor-pointer select-none">
                                        [Wine Distributor]
                                    </button>
                                    <button type="button" wire:click="applyPreset('gifting')" class="px-2.5 py-1.5 border border-amber-800/40 bg-amber-950/20 text-[#C5A880] hover:bg-amber-950/40 text-[10px] font-mono rounded-sm cursor-pointer select-none">
                                        [Gifting / Box Depot]
                                    </button>
                                </div>
                            </div>
                            @endif
                            {{-- Name --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Supplier Company Name</label>
                                <input 
                                    wire:model="name" 
                                    type="text" 
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20"
                                >
                                @error('name') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Contact Person --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Primary Contact Representative</label>
                                <input 
                                    wire:model="contact_person" 
                                    type="text" 
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20"
                                >
                                @error('contact_person') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Email --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Email Address</label>
                                    <input 
                                        wire:model="email" 
                                        type="email" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 font-mono"
                                    >
                                    @error('email') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Phone Contact</label>
                                    <input 
                                        wire:model="phone" 
                                        type="text" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 font-mono"
                                    >
                                    @error('phone') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Address --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Physical Address</label>
                                <textarea 
                                    wire:model="address" 
                                    rows="2"
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20"
                                ></textarea>
                                @error('address') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Payment Terms --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Payment Terms</label>
                                    <select 
                                        wire:model="payment_terms" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                    >
                                        <option value="Cash on Delivery">Cash on Delivery</option>
                                        <option value="Net 7">Net 7</option>
                                        <option value="Net 14">Net 14</option>
                                        <option value="Net 30">Net 30</option>
                                        <option value="Net 60">Net 60</option>
                                    </select>
                                    @error('payment_terms') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Reliability Rating --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Reliability Rating (1-5)</label>
                                    <select 
                                        wire:model="reliability_rating" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                    >
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    @error('reliability_rating') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Active Switch --}}
                            <div class="flex items-center space-x-3 pt-2">
                                <input 
                                    wire:model="is_active" 
                                    type="checkbox" 
                                    id="is_active"
                                    class="rounded-sm bg-[#121216] border-neutral-900 text-amber-500 focus:ring-amber-500 focus:ring-opacity-20 cursor-pointer"
                                >
                                <label for="is_active" class="text-xs text-neutral-300 cursor-pointer select-none">Active Vendor (available for new POs)</label>
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex justify-end gap-3 pt-6 border-t border-neutral-900/60 mt-6">
                                <button 
                                    type="button" 
                                    wire:click="$set('showModal', false)"
                                    class="border border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white px-4 py-2 rounded-sm text-xs font-mono uppercase tracking-wider transition-all bg-transparent cursor-pointer"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2 rounded-sm transition-all duration-300"
                                >
                                    {{ $isEditMode ? 'Update' : 'Register' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
