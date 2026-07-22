<div class="min-h-screen bg-[#0A0A0A] text-neutral-100 font-sans antialiased">
    <header class="border-b border-neutral-900 bg-[#0F0F0F]/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-xs uppercase tracking-[0.3em] text-neutral-600 font-mono">ERP Ops</span>
                <span class="text-neutral-800">/</span>
                <h1 class="text-sm font-light tracking-widest text-white uppercase">Order Ledger</h1>
            </div>
            <a href="/" class="text-xs font-mono text-neutral-500 hover:text-white transition-colors uppercase">[ Storefront ]</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extralight tracking-tight text-white">Incoming Requisitions</h2>
                <p class="text-xs text-neutral-500 font-light mt-1">Real-time procurement logs for Nairobi and Kiambu distribution hubs.</p>
            </div>
            <div class="bg-[#0F0F0F] border border-neutral-900 rounded px-4 py-2 font-mono text-xs text-neutral-400">
                Total Logs: <span class="text-white">{{ $orders->total() }}</span>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2 relative group">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search ledger (e.g. status:approved amount>5000)..."
                    class="w-full bg-[#0F0F0F] border border-neutral-900 rounded pl-10 pr-4 py-2.5 text-xs text-neutral-300 placeholder-neutral-600 focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 transition-all duration-300 font-mono"
                >
                <div class="absolute left-3 top-3 flex items-center justify-center pointer-events-none">
                    <!-- Smooth animated Search Icon -->
                    <svg wire:loading.remove wire:target="search" class="w-4 h-4 text-neutral-600 transition-all duration-300 group-focus-within:text-amber-500 group-focus-within:scale-115" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <!-- Spinning Load Icon -->
                    <svg wire:loading wire:target="search" class="w-4 h-4 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full bg-[#0F0F0F] border border-neutral-900 rounded px-4 py-2.5 text-xs text-neutral-300 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="processing">Processing</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Branch Filter -->
            <div>
                <select 
                    wire:model.live="branchFilter"
                    class="w-full bg-[#0F0F0F] border border-neutral-900 rounded px-4 py-2.5 text-xs text-neutral-300 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Branches</option>
                    <option value="unassigned">Unassigned</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->code }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-[#0F0F0F] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-neutral-900 text-[#555] text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                            <th class="p-6 font-medium">Requisition Reference</th>
                            <th class="p-6 font-medium">Client / Node</th>
                            <th class="p-6 font-medium">Curation Specification</th>
                            <th class="p-6 font-medium">Value & eTIMS Stamp</th>
                            <th class="p-6 font-medium">Fulfillment Control</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                        @forelse($orders as $order)
                            <tr class="hover:bg-neutral-900/20 transition-colors">
                                <td class="p-6 font-mono text-xs">
                                    <span class="text-white block font-semibold">#NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-neutral-600 text-[10px] mt-1 block">{{ $order->created_at->format('d M Y H:i') }}</span>
                                </td>

                                <td class="p-6">
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-white font-normal block">{{ $order->client->company_name ?? 'Individual Account' }}</span>
                                            <span class="text-xs text-neutral-400 block">{{ $order->client->contact_name }} &bull; {{ $order->client->phone }}</span>
                                        </div>
                                        
                                        <div class="pt-1 flex items-center space-x-2">
                                            <span class="text-[9px] uppercase font-mono text-neutral-500">Node:</span>
                                            <select 
                                                wire:change="updateBranch({{ $order->id }}, $event.target.value)"
                                                class="bg-[#0A0A0A] border border-neutral-950 rounded px-1.5 py-1 text-[11px] text-neutral-400 focus:outline-none focus:border-neutral-800 font-mono cursor-pointer"
                                            >
                                                <option value="">Unassigned</option>
                                                @foreach($branches as $b)
                                                    <option value="{{ $b->id }}" {{ $order->branch_id == $b->id ? 'selected' : '' }}>
                                                        {{ $b->name }} ({{ $b->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6 max-w-sm">
                                    <div class="space-y-2">
                                        <div class="space-y-1 text-xs">
                                            @foreach($order->products as $product)
                                                <div class="text-neutral-300">
                                                    <span class="font-mono text-neutral-500">[{{ $product->pivot->quantity }}x]</span> 
                                                    {{ $product->name }} 
                                                    <span class="text-neutral-600 font-mono text-[11px]">({{ number_format($product->pivot->price_at_sale) }} Ksh)</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($order->special_instructions)
                                            <div class="bg-[#0A0A0A] border border-neutral-900 p-2.5 rounded text-xs text-neutral-400 font-light italic">
                                                <span class="text-[10px] uppercase tracking-wider text-rose-950 block font-mono font-bold not-italic mb-0.5">Instructions:</span>
                                                "{{ $order->special_instructions }}"
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-6 font-mono">
                                    <span class="text-sm text-white tracking-tight block font-semibold">{{ number_format($order->total_amount) }} KSH</span>
                                    
                                    <div class="mt-2 text-[10px]">
                                        @if($order->etimsInvoice)
                                            @if($order->etimsInvoice->status === 'transmitted')
                                                <span class="text-emerald-500 font-medium block">eTIMS Transmitted</span>
                                                <span class="text-neutral-600 block text-[9px] mt-0.5 truncate max-w-[15px]" title="{{ $order->etimsInvoice->cu_invoice_number }}">
                                                    {{ $order->etimsInvoice->cu_invoice_number }}
                                                </span>
                                            @else
                                                <span class="text-rose-400 block font-medium">eTIMS Failed</span>
                                            @endif
                                        @else
                                            <span class="text-neutral-600 block">Unfiscalized</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="w-1.5 h-1.5 rounded-full {{ 
                                                $order->status === 'delivered' ? 'bg-emerald-500 shadow-[0_0_8px_#10B981]' : (
                                                $order->status === 'cancelled' ? 'bg-neutral-700' : 
                                                'bg-amber-500 shadow-[0_0_8px_#F59E0B]') 
                                            }}"></span>
                                            
                                            <select 
                                                wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                                class="bg-[#0A0A0A] border border-neutral-800 rounded px-2.5 py-1.5 text-xs text-neutral-300 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                            >
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>

                                        <div>
                                            <a href="{{ route('courier.delivery-handover', $order) }}" target="_blank" class="inline-flex items-center space-x-1 text-[10px] font-mono font-semibold text-[#C5A880] hover:text-white bg-[#C5A880]/10 hover:bg-[#C5A880]/20 border border-[#C5A880]/25 px-2.5 py-1 rounded-lg transition-all">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <span>Courier PoD</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-neutral-500 font-light">
                                    No transaction records found in the current ledger context.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6 font-mono text-xs">
            {{ $orders->links() }}
        </div>
    </main>
</div>