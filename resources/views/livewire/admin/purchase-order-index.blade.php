<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">Purchase Orders (PO)</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Issue wholesale inventory requests, register incoming supply shipments, and track weighted average unit costs (COGS).</p>
            </div>
            <div class="flex items-center gap-4">
                @if (session()->has('message'))
                    <div class="bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 px-4 py-2 text-xs font-mono rounded-sm animate-pulse">
                        ✓ {{ session('message') }}
                    </div>
                @endif

                <button 
                    wire:click="create"
                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2.5 rounded-sm transition-all duration-300 transform active:scale-95 shadow-[0_0_15px_rgba(245,158,11,0.1)]"
                >
                    + Create Purchase Order
                </button>
            </div>
        </div>
    </div>    @if($viewingPoId && $viewingPo)
        {{-- Detailed Purchase Order Profile View --}}
        <div class="mb-8 flex items-center justify-between">
            <button 
                wire:click="closeView" 
                class="bg-neutral-900 hover:bg-neutral-800 text-neutral-400 hover:text-white text-xs font-mono uppercase tracking-wider px-4 py-2 rounded-sm border border-neutral-800 transition-all cursor-pointer"
            >
                ← Back to PO List
            </button>

            <div class="flex items-center gap-3">
                @if ($viewingPo->status === 'draft')
                    <button 
                        wire:click="markOrdered({{ $viewingPo->id }})"
                        class="text-xs text-blue-400 hover:text-blue-300 hover:bg-blue-900/20 font-mono border border-blue-900/30 px-4 py-2 rounded-sm cursor-pointer"
                    >
                        Issue Order
                    </button>
                @endif

                @if (in_array($viewingPo->status, ['ordered', 'partial']))
                    <button 
                        wire:click="openReceiveModal({{ $viewingPo->id }})"
                        class="text-xs text-emerald-400 hover:text-emerald-300 hover:bg-emerald-900/20 font-mono border border-emerald-900/30 px-4 py-2 rounded-sm cursor-pointer"
                    >
                        Receive Stock
                    </button>
                @endif

                @if (in_array($viewingPo->status, ['draft', 'ordered', 'partial']))
                    <button 
                        wire:click="cancelPO({{ $viewingPo->id }})"
                        onclick="confirm('Cancel this purchase order?') || event.stopImmediatePropagation()"
                        class="text-xs text-neutral-500 hover:text-rose-500 hover:bg-rose-950/10 font-mono border border-neutral-800 px-4 py-2 rounded-sm cursor-pointer"
                    >
                        Cancel PO
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Who, Where & Status --}}
            <div class="space-y-6 text-left">
                {{-- Who: Supplier Details --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Supplier Details (Who)</h2>
                        <span class="text-xs text-neutral-500 font-mono block mt-0.5">Procured From</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Supplier Company</span>
                            <span class="text-neutral-200 text-sm font-sans font-medium">{{ $viewingPo->vendor->name }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Contact Representative</span>
                            <span class="text-neutral-200 block">{{ $viewingPo->vendor->contact_person }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Contact Details</span>
                            <span class="text-neutral-300 block truncate">{{ $viewingPo->vendor->email ?: '-' }}</span>
                            <span class="text-neutral-400 block mt-0.5">{{ $viewingPo->vendor->phone ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Payment Terms</span>
                            <span class="inline-block text-[10px] uppercase font-mono px-2.5 py-0.5 mt-1 rounded bg-neutral-950 border border-neutral-800 text-neutral-400">
                                {{ $viewingPo->vendor->payment_terms }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Where: Destination Branch --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Destination Branch (Where)</h2>
                        <span class="text-xs text-neutral-500 font-mono block mt-0.5">Delivery Destination</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Atelier Branch</span>
                            <span class="text-neutral-200 text-sm font-sans font-medium">{{ $viewingPo->branch->name }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Location City</span>
                            <span class="text-neutral-300 block">{{ $viewingPo->branch->location_city }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes & Audit --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">PO Notes & Audit</h2>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Special Instructions / Notes</span>
                            <p class="text-neutral-300 font-sans font-light leading-relaxed">{{ $viewingPo->notes ?: 'No notes attached to this purchase order.' }}</p>
                        </div>
                        <div class="pt-2 border-t border-neutral-900 space-y-2">
                            <div>
                                <span class="text-[8px] uppercase tracking-wider text-neutral-500 block">Created At</span>
                                <span class="text-neutral-400 block">{{ $viewingPo->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                            @if($viewingPo->ordered_at)
                                <div>
                                    <span class="text-[8px] uppercase tracking-wider text-neutral-500 block">Ordered At</span>
                                    <span class="text-neutral-400 block">{{ $viewingPo->ordered_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                            @endif
                            @if($viewingPo->received_at)
                                <div>
                                    <span class="text-[8px] uppercase tracking-wider text-neutral-500 block">Fully Received At</span>
                                    <span class="text-emerald-500 block">{{ $viewingPo->received_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Itemized Line Items & Cost Summary --}}
            <div class="lg:col-span-2 space-y-8 text-left">
                {{-- Line Items --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
                    <div class="p-6 border-b border-neutral-900 bg-[#0A0A0A]/50 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Itemized Line Items (What)</h3>
                            <p class="text-xs text-neutral-500 font-light mt-1">Products and quantities defined in this purchase order request.</p>
                        </div>
                        <span class="inline-block text-[10px] uppercase font-mono px-3 py-1 rounded font-bold
                            @if($viewingPo->status === 'received') bg-emerald-950 text-emerald-400 border border-emerald-900
                            @elseif($viewingPo->status === 'cancelled') bg-rose-955 text-rose-400 border border-rose-900
                            @elseif($viewingPo->status === 'draft') bg-neutral-900 text-neutral-400 border border-neutral-800
                            @else bg-amber-955 text-amber-400 border border-amber-900 @endif">
                            {{ $viewingPo->status }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/30">
                                    <th class="p-4 font-medium">Product Name</th>
                                    <th class="p-4 font-medium">SKU</th>
                                    <th class="p-4 font-medium text-right">Qty Ordered</th>
                                    <th class="p-4 font-medium text-right">Qty Received</th>
                                    <th class="p-4 font-medium text-right">Unit Cost</th>
                                    <th class="p-4 font-medium text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/60 text-xs font-mono">
                                @foreach($viewingPo->items as $item)
                                    <tr class="hover:bg-neutral-950/40 transition-colors">
                                        <td class="p-4 text-white font-sans font-normal">{{ $item->product->name }}</td>
                                        <td class="p-4 text-neutral-400">{{ $item->product->sku }}</td>
                                        <td class="p-4 text-right text-neutral-300">{{ number_format($item->quantity_ordered) }}</td>
                                        <td class="p-4 text-right @if($item->quantity_received >= $item->quantity_ordered) text-emerald-400 @else text-neutral-500 @endif">{{ number_format($item->quantity_received) }}</td>
                                        <td class="p-4 text-right text-neutral-400">{{ number_format($item->unit_cost) }} KSH</td>
                                        <td class="p-4 text-right text-amber-500 font-semibold">{{ number_format($item->quantity_ordered * $item->unit_cost) }} KSH</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Cost Summary --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-white border-b border-neutral-900 pb-3">Financial Cost Summary</h3>
                    
                    <div class="grid grid-cols-2 gap-6 text-xs font-mono">
                        <div class="bg-neutral-950 border border-neutral-900 p-4 rounded-sm">
                            <span class="text-neutral-500 block mb-1">Estimated Total Cost</span>
                            <span class="text-amber-500 text-lg font-bold">
                                Ksh {{ number_format($viewingPo->total_cost) }}
                            </span>
                            <span class="text-[9px] text-neutral-600 block mt-1">(Ordered Qty * Unit Cost)</span>
                        </div>
                        
                        @php
                            $actualCost = $viewingPo->items->sum(fn($i) => $i->quantity_received * $i->unit_cost);
                        @endphp
                        <div class="bg-neutral-950 border border-neutral-900 p-4 rounded-sm">
                            <span class="text-neutral-500 block mb-1 font-semibold">Actual Received Cost</span>
                            <span class="text-emerald-500 text-lg font-bold">
                                Ksh {{ number_format($actualCost) }}
                            </span>
                            <span class="text-[9px] text-neutral-600 block mt-1">(Received Qty * Unit Cost)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Filters & Search --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2 relative group">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search by PO number, notes, vendor name..."
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
                    wire:model.live="statusFilter"
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="ordered">Ordered</option>
                    <option value="partial">Partially Received</option>
                    <option value="received">Fully Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            {{-- Vendor Filter --}}
            <div>
                <select 
                    wire:model.live="vendorFilter"
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Vendors</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Branch Filter --}}
            <div>
                <select 
                    wire:model.live="branchFilter"
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Purchase Orders Table --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                            <th class="p-6 font-medium">PO Code</th>
                            <th class="p-6 font-medium">Vendor</th>
                            <th class="p-6 font-medium">Destination Branch</th>
                            <th class="p-6 font-medium font-mono text-right">Estimated Cost</th>
                            <th class="p-6 font-medium">Order Status</th>
                            <th class="p-6 font-medium">Issuing Dates</th>
                            <th class="p-6 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                        @forelse ($purchaseOrders as $po)
                            <tr class="hover:bg-neutral-950/40 transition-colors">
                                {{-- PO Code --}}
                                <td class="p-6 font-mono text-xs text-white font-semibold">
                                    <a href="#" wire:click.prevent="viewPO({{ $po->id }})" class="text-amber-500 hover:underline">
                                        {{ $po->po_number }}
                                    </a>
                                </td>

                                {{-- Vendor --}}
                                <td class="p-6">
                                    <span class="text-neutral-200 block font-normal">{{ $po->vendor->name }}</span>
                                    <span class="text-neutral-500 text-xs block font-mono">{{ $po->vendor->contact_person }}</span>
                                </td>

                                {{-- Branch --}}
                                <td class="p-6">
                                    <span class="text-neutral-300 block font-normal">{{ $po->branch->name }}</span>
                                    <span class="text-neutral-500 text-xs block font-mono">{{ $po->branch->location_city }}</span>
                                </td>

                                {{-- Estimated Cost --}}
                                <td class="p-6 font-mono text-right text-emerald-400 font-semibold">
                                    Ksh {{ number_format($po->total_cost) }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="p-6">
                                    @if ($po->status === 'draft')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2.5 py-0.5 rounded-sm bg-neutral-900 text-neutral-400 border border-neutral-800">
                                            Draft
                                        </span>
                                    @elseif ($po->status === 'ordered')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2.5 py-0.5 rounded-sm bg-blue-955/40 text-blue-400 border border-blue-900/30">
                                            Ordered
                                        </span>
                                    @elseif ($po->status === 'partial')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2.5 py-0.5 rounded-sm bg-amber-955/40 text-amber-400 border border-amber-900/30 animate-pulse">
                                            Partial
                                        </span>
                                    @elseif ($po->status === 'received')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2.5 py-0.5 rounded-sm bg-emerald-955/40 text-emerald-400 border border-emerald-900/30">
                                            Received
                                        </span>
                                    @elseif ($po->status === 'cancelled')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2.5 py-0.5 rounded-sm bg-rose-955/40 text-rose-500 border border-rose-900/30">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>

                                {{-- Issuing Dates --}}
                                <td class="p-6 font-mono text-xs text-neutral-500">
                                    <span class="block">Created: {{ $po->created_at->format('Y-m-d') }}</span>
                                    @if($po->ordered_at)
                                        <span class="block text-neutral-400 mt-0.5">Ordered: {{ $po->ordered_at->format('Y-m-d') }}</span>
                                    @endif
                                    @if($po->received_at)
                                        <span class="block text-emerald-500 mt-0.5">Received: {{ $po->received_at->format('Y-m-d') }}</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="p-6 text-right space-y-1.5 whitespace-nowrap">
                                    @if ($po->status === 'draft')
                                        <button 
                                            wire:click="markOrdered({{ $po->id }})"
                                            class="w-full text-center text-xs text-blue-400 hover:text-blue-300 hover:bg-blue-900/20 transition-all uppercase tracking-wider font-mono border border-blue-900/30 px-3 py-1 rounded-sm block cursor-pointer"
                                        >
                                            Issue Order
                                        </button>
                                    @endif

                                    @if (in_array($po->status, ['ordered', 'partial']))
                                        <button 
                                            wire:click="openReceiveModal({{ $po->id }})"
                                            class="w-full text-center text-xs text-emerald-400 hover:text-emerald-300 hover:bg-emerald-900/20 transition-all uppercase tracking-wider font-mono border border-emerald-900/30 px-3 py-1 rounded-sm block cursor-pointer"
                                        >
                                            Receive Stock
                                        </button>
                                    @endif

                                    @if (in_array($po->status, ['draft', 'ordered', 'partial']))
                                        <button 
                                            wire:click="cancelPO({{ $po->id }})"
                                            onclick="confirm('Cancel this purchase order?') || event.stopImmediatePropagation()"
                                            class="w-full text-center text-xs text-neutral-600 hover:text-rose-500 hover:bg-rose-955/10 transition-all uppercase tracking-wider font-mono border border-neutral-900 px-3 py-1 rounded-sm block cursor-pointer"
                                        >
                                            Cancel
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Expanded PO Items --}}
                            <tr class="bg-black/10 border-b border-neutral-950">
                                <td colspan="7" class="px-8 py-3 text-xs">
                                    <div class="flex items-center space-x-6 text-[10px] uppercase font-mono text-neutral-500 tracking-wider">
                                        <span>Items:</span>
                                        @foreach($po->items as $item)
                                            <span>
                                                {{ $item->product->name }} 
                                                (Qty: <span class="text-neutral-300">{{ $item->quantity_received }}</span>/{{ $item->quantity_ordered }} 
                                                @ <span class="text-emerald-500 font-semibold">Ksh {{ number_format($item->unit_cost) }}</span>)
                                            </span>
                                        @endforeach
                                    </div>
                                    @if ($po->notes)
                                        <div class="mt-2 text-[10px] text-neutral-600 italic font-sans font-light">
                                            Note: {{ $po->notes }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-neutral-500 font-light font-mono">
                                    No purchase orders registered in the system database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 font-mono text-xs">
            {{ $purchaseOrders->links() }}
        </div>
    @endif

    {{-- Create Purchase Order Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showCreateModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="inline-block align-middle bg-[#0B0B0E] border border-neutral-900 rounded-sm text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-6 py-6">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white mb-6">
                            Create Purchase Order (Draft)
                        </h3>

                        <form wire:submit.prevent="savePO" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Vendor --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Supplier Vendor</label>
                                    <select 
                                        wire:model="vendor_id" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                    >
                                        @foreach($vendors as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }} (Term: {{ $v->payment_terms }})</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Branch --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Destination Atelier Branch</label>
                                    <select 
                                        wire:model="branch_id" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                    >
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- PO Line Items --}}
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <label class="text-[10px] uppercase tracking-wider text-neutral-500">Order Line Items</label>
                                    <button 
                                        type="button"
                                        wire:click="addPoItem"
                                        class="text-[10px] font-mono uppercase text-amber-500 hover:text-amber-400 transition-colors"
                                    >
                                        + Add Item
                                    </button>
                                </div>

                                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                    @foreach($poItems as $index => $item)
                                        <div class="flex items-end gap-3 bg-[#121216] border border-neutral-950 p-3.5 rounded-sm">
                                            {{-- Product Select --}}
                                            <div class="flex-1">
                                                <label class="block text-[9px] uppercase tracking-wider text-neutral-600 mb-1">Product</label>
                                                <select 
                                                    wire:model="poItems.{{ $index }}.product_id"
                                                    class="w-full bg-neutral-900 border border-neutral-800 rounded-sm px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                                >
                                                    <option value="">-- Choose Product --</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }} (Sell: Ksh {{ number_format($p->price) }})</option>
                                                    @endforeach
                                                </select>
                                                @error("poItems.{$index}.product_id") <span class="text-rose-500 text-[9px] mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- Quantity --}}
                                            <div class="w-24">
                                                <label class="block text-[9px] uppercase tracking-wider text-neutral-600 mb-1">Quantity</label>
                                                <input 
                                                    wire:model="poItems.{{ $index }}.quantity_ordered"
                                                    type="number"
                                                    min="1"
                                                    class="w-full bg-neutral-900 border border-neutral-800 rounded-sm px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono text-center"
                                                >
                                                @error("poItems.{$index}.quantity_ordered") <span class="text-rose-500 text-[9px] mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- Unit Cost --}}
                                            <div class="w-32">
                                                <label class="block text-[9px] uppercase tracking-wider text-neutral-600 mb-1">Unit Cost (KSH)</label>
                                                <input 
                                                    wire:model="poItems.{{ $index }}.unit_cost"
                                                    type="number"
                                                    min="0"
                                                    class="w-full bg-neutral-900 border border-neutral-800 rounded-sm px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono text-right"
                                                >
                                                @error("poItems.{$index}.unit_cost") <span class="text-rose-500 text-[9px] mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- Delete Button --}}
                                            @if(count($poItems) > 1)
                                                <button 
                                                    type="button"
                                                    wire:click="removePoItem({{ $index }})"
                                                    class="text-neutral-600 hover:text-rose-500 font-mono p-1.5 transition-colors"
                                                >
                                                    ✕
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Purchase Order Notes</label>
                                <textarea 
                                    wire:model="notes" 
                                    rows="2"
                                    placeholder="Enter details on delivery estimates, shipment contents, or vendor notes..."
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20"
                                ></textarea>
                                @error('notes') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex justify-end gap-3 pt-6 border-t border-neutral-900/60 mt-6">
                                <button 
                                    type="button" 
                                    wire:click="$set('showCreateModal', false)"
                                    class="bg-neutral-900 hover:bg-neutral-800 text-neutral-400 text-xs font-mono uppercase tracking-wider px-4 py-2 rounded-sm transition-colors"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2 rounded-sm transition-all duration-300"
                                >
                                    Create PO Draft
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Receive Purchase Order Modal --}}
    @if($showReceiveModal && $receivingPo)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showReceiveModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="inline-block align-middle bg-[#0B0B0E] border border-neutral-900 rounded-sm text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <div class="px-6 py-6">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white mb-2">
                            Receive Shipments for PO {{ $receivingPo->po_number }}
                        </h3>
                        <p class="text-[10px] text-neutral-500 font-mono tracking-wider uppercase mb-6">Destination branch: {{ $receivingPo->branch->name }}</p>

                        <form wire:submit.prevent="saveReceive" class="space-y-6">
                            <div class="space-y-4">
                                @foreach($receiveItems as $index => $item)
                                    <div class="flex items-center justify-between bg-[#121216] border border-neutral-950 p-4 rounded-sm">
                                        <div class="flex-1">
                                            <span class="text-neutral-200 block text-xs font-normal">{{ $item['product_name'] }}</span>
                                            <span class="text-[10px] text-neutral-500 font-mono block mt-1 uppercase tracking-wider">
                                                Ordered: {{ $item['qty_ordered'] }} | Previously Received: {{ $item['qty_received_before'] }}
                                            </span>
                                        </div>

                                        <div class="w-36 flex items-center space-x-2">
                                            <label class="text-[9px] uppercase tracking-wider text-neutral-600 whitespace-nowrap">Receive now:</label>
                                            <input 
                                                wire:model="receiveItems.{{ $index }}.qty_received_now"
                                                type="number"
                                                min="0"
                                                max="{{ $item['qty_ordered'] - $item['qty_received_before'] }}"
                                                class="w-20 bg-neutral-900 border border-neutral-800 rounded-sm px-2.5 py-1 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono text-center"
                                            >
                                        </div>
                                    </div>
                                    @error("receiveItems.{$index}.qty_received_now") <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                @endforeach
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex justify-end gap-3 pt-6 border-t border-neutral-900/60 mt-6">
                                <button 
                                    type="button" 
                                    wire:click="$set('showReceiveModal', false)"
                                    class="bg-neutral-900 hover:bg-neutral-800 text-neutral-400 text-xs font-mono uppercase tracking-wider px-4 py-2 rounded-sm transition-colors"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="bg-emerald-500 hover:bg-emerald-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2 rounded-sm transition-all duration-300"
                                >
                                    Receive Stock & Sync COGS
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
