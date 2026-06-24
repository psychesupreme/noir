<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">Wastage & Perishables Log</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Audit floral shrinkage, register inventory spoilage, and analyze write-off expenses across retail hubs.</p>
            </div>
            <div class="flex items-center gap-4">
                @if (session()->has('message'))
                    <div class="bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 px-4 py-2 text-xs font-mono rounded-sm animate-pulse">
                        ✓ {{ session('message') }}
                    </div>
                @endif

                <button 
                    wire:click="openLogModal"
                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2.5 rounded-sm transition-all duration-300 transform active:scale-95 shadow-[0_0_15px_rgba(245,158,11,0.1)]"
                >
                    + Log Floral Wastage
                </button>
            </div>
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Total Shrinkage --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Monthly Wastage Value</span>
            <span class="text-2xl font-light text-rose-400 font-mono mt-3 block">Ksh {{ number_format($totalWastageValue) }}</span>
            <div class="absolute right-4 bottom-4 text-rose-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
        </div>

        {{-- Top Shrinkage Item --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Top Spoilage Product</span>
            <span class="text-xl font-light text-white mt-3 block truncate font-mono uppercase">{{ $topWastedProduct }}</span>
            <div class="absolute right-4 bottom-4 text-neutral-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.656 48.656 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3M3 12c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M3 12l-3 3m3-3 3 3M9 5.25c0-.69.56-1.25 1.25-1.25h3.5c.69 0 1.25.56 1.25 1.25v2.25M9 5.25v2.25m6-2.25v2.25" />
                </svg>
            </div>
        </div>

        {{-- Wastage Breakdown --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Shrinkage By Reason</span>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse($breakdown as $item)
                    <span class="inline-block text-[9px] font-mono uppercase px-2 py-0.5 rounded-sm bg-neutral-900 border border-neutral-800 text-neutral-400">
                        {{ $item->reason }}: Ksh {{ number_format($item->total_cost) }}
                    </span>
                @empty
                    <span class="text-xs text-neutral-600 font-mono">No metrics available.</span>
                @endforelse
            </div>
        </div>
    </div>

    @if($viewingWastageId && $viewingWastage)
        {{-- Detailed Floral Waste Page --}}
        <div class="mb-8 flex items-center justify-between">
            <button 
                wire:click="closeView" 
                class="border border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white px-4 py-2 rounded-sm text-xs font-mono uppercase tracking-wider transition-all cursor-pointer bg-transparent"
            >
                ← Back to Wastage Logs
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
            {{-- Left Column: Product & Branch info --}}
            <div class="space-y-6">
                {{-- Product info --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Product Details</h2>
                        <span class="text-xs text-neutral-500 font-mono block mt-0.5">Written-Off Item</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Product Name</span>
                            <span class="text-neutral-200 text-sm font-sans font-medium">{{ $viewingWastage->product->name }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">SKU Code</span>
                            <span class="text-neutral-200 block">{{ $viewingWastage->product->sku }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Category</span>
                            <span class="text-neutral-300 block uppercase">{{ str_replace('_', ' ', $viewingWastage->product->category) }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Grade</span>
                            <span class="text-neutral-300 block">{{ $viewingWastage->product->grade ?: 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Unit Type</span>
                            <span class="text-neutral-300 block uppercase">{{ $viewingWastage->product->unit_type }}</span>
                        </div>
                    </div>
                </div>

                {{-- Branch location --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4">
                    <div class="border-b border-neutral-900 pb-3">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Reporting Hub</h2>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Branch Name</span>
                            <span class="text-neutral-200 text-sm font-sans font-medium">{{ $viewingWastage->branch->name }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Location City</span>
                            <span class="text-neutral-300 block">{{ $viewingWastage->branch->location_city }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Audit summary & Financial cost --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Wastage details card --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-6">
                    <div class="border-b border-neutral-900 pb-3 flex justify-between items-center">
                        <div>
                            <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Write-Off Audit Details</h2>
                            <p class="text-xs text-neutral-500 font-light mt-1">Audit profile log for wastage #WSG-{{ str_pad($viewingWastage->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2 py-0.5 rounded-sm bg-rose-955 text-rose-400 border border-rose-900">
                            {{ $viewingWastage->reason }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs font-mono">
                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Quantity Written Off</span>
                                <span class="text-white text-base font-bold">{{ $viewingWastage->quantity }} {{ $viewingWastage->product->unit_type }}s</span>
                            </div>
                            <div>
                                <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Logged By Staff</span>
                                <span class="text-neutral-300 text-sm font-sans">{{ $viewingWastage->user->name ?? 'System' }}</span>
                            </div>
                            <div>
                                <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1">Logged Date & Time</span>
                                <span class="text-neutral-400">{{ $viewingWastage->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>

                        <div class="bg-neutral-950 border border-neutral-900 p-4 rounded-sm flex flex-col justify-center">
                            <span class="text-neutral-500 block mb-1 text-[10px] uppercase tracking-wider">Estimated Financial Loss</span>
                            <span class="text-rose-400 text-2xl font-bold">
                                Ksh {{ number_format($viewingWastage->cost_estimate) }}
                            </span>
                            <span class="text-[9px] text-neutral-600 block mt-1.5">(Quantity * Product Unit Cost Price)</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-neutral-900 text-xs font-mono">
                        <span class="text-[9px] uppercase tracking-wider text-neutral-500 block mb-1.5">Wastage Explanatory Notes</span>
                        <p class="text-neutral-300 font-sans font-light leading-relaxed bg-neutral-950 p-4 border border-neutral-900 rounded-sm italic">
                            "{{ $viewingWastage->notes ?: 'No explanatory notes logged for this shrinkage.' }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Filters & Search --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2 relative group">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search wastage logs by product name..."
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm pl-10 pr-4 py-2.5 text-xs text-neutral-300 placeholder-neutral-600 focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 transition-all duration-300 font-mono"
                >
                <div class="absolute left-3 top-3 flex items-center justify-center pointer-events-none">
                    <svg class="w-4 h-4 text-neutral-600 transition-all duration-300 group-focus-within:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
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

            {{-- Reason Filter --}}
            <div>
                <select 
                    wire:model.live="reasonFilter"
                    class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                >
                    <option value="all">All Reasons</option>
                    <option value="Spoilage">Spoilage</option>
                    <option value="Damaged">Damaged</option>
                    <option value="Expired">Expired</option>
                    <option value="Design Class">Design Class</option>
                    <option value="Sample">Sample</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        {{-- Wastage Logs Table --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                            <th class="p-6 font-medium">Product details</th>
                            <th class="p-6 font-medium">Branch Location</th>
                            <th class="p-6 font-medium font-mono text-center">Qty Written Off</th>
                            <th class="p-6 font-medium">Reason Code</th>
                            <th class="p-6 font-medium font-mono text-right">Cost Estimate</th>
                            <th class="p-6 font-medium">Logged By</th>
                            <th class="p-6 font-medium text-right">Logging Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-neutral-950/40 transition-colors">
                                {{-- Product --}}
                                <td class="p-6">
                                    <a href="#" wire:click.prevent="viewWastage({{ $log->id }})" class="text-amber-500 hover:underline font-mono uppercase block font-semibold">{{ $log->product->name }}</a>
                                    <span class="text-neutral-500 text-xs block truncate max-w-[200px]" title="{{ $log->notes }}">{{ $log->notes ?: 'No description' }}</span>
                                </td>

                                {{-- Branch --}}
                                <td class="p-6">
                                    <span class="text-neutral-200 block">{{ $log->branch->name }}</span>
                                </td>

                                {{-- Quantity --}}
                                <td class="p-6 font-mono text-center text-white">
                                    {{ $log->quantity }} {{ $log->product->unit_type }}
                                </td>

                                {{-- Reason Badge --}}
                                <td class="p-6">
                                    @if (in_array($log->reason, ['Spoilage', 'Expired']))
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2 py-0.5 rounded-sm bg-rose-955/40 text-rose-400 border border-rose-900/30">
                                            {{ $log->reason }}
                                        </span>
                                    @elseif ($log->reason === 'Damaged')
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2 py-0.5 rounded-sm bg-amber-955/40 text-amber-400 border border-amber-900/30">
                                            {{ $log->reason }}
                                        </span>
                                    @elseif (in_array($log->reason, ['Design Class', 'Sample']))
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2 py-0.5 rounded-sm bg-blue-950/40 text-blue-400 border border-blue-900/30">
                                            {{ $log->reason }}
                                        </span>
                                    @else
                                        <span class="inline-block text-[9px] tracking-wider uppercase font-mono px-2 py-0.5 rounded-sm bg-neutral-900 text-neutral-400 border border-neutral-800">
                                            {{ $log->reason }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Cost Estimate --}}
                                <td class="p-6 font-mono text-right text-rose-400 font-semibold">
                                    Ksh {{ number_format($log->cost_estimate) }}
                                </td>

                                {{-- Logged By --}}
                                <td class="p-6">
                                    <span class="text-neutral-300 block">{{ $log->user->name ?? 'System' }}</span>
                                </td>

                                {{-- Logging Date --}}
                                <td class="p-6 text-right font-mono text-xs text-neutral-500">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-neutral-500 font-light font-mono">
                                    No wastage or perishable stock write-off logs in the database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 font-mono text-xs">
            {{ $logs->links() }}
        </div>
    @endif

    {{-- Log Wastage Modal --}}
    @if($showLogModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showLogModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="inline-block align-middle bg-[#0B0B0E] relative z-10 border border-neutral-900 rounded-sm text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="px-6 py-6">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white mb-6">
                            Log Perishable Spoilage & Wastage
                        </h3>

                        <form wire:submit.prevent="logWastage" class="space-y-4">
                            {{-- Branch --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Reporting Branch Location</label>
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

                            {{-- Product --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Perishable Product</label>
                                <select 
                                    wire:model="product_id" 
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                >
                                    <option value="">-- Choose Product --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} (Cost: Ksh {{ number_format($p->cost_price) }})</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Quantity --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Quantity Wasted</label>
                                    <input 
                                        wire:model="quantity" 
                                        type="number" 
                                        min="1"
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono text-center"
                                    >
                                    @error('quantity') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Reason --}}
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Reason Code</label>
                                    <select 
                                        wire:model="reason" 
                                        class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                                    >
                                        <option value="Spoilage">Spoilage</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Design Class">Design Class</option>
                                        <option value="Sample">Sample</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('reason') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-[10px] uppercase tracking-wider text-neutral-500 mb-1.5">Log Notes / Details</label>
                                <textarea 
                                    wire:model="notes" 
                                    rows="3"
                                    placeholder="Explain the cause of floral shrinkage (e.g. broken stems, withered roses, classroom demonstration)..."
                                    class="w-full bg-[#121216] border border-neutral-900 rounded-sm px-3.5 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20"
                                ></textarea>
                                @error('notes') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex justify-end gap-3 pt-6 border-t border-neutral-900/60 mt-6">
                                <button 
                                    type="button" 
                                    wire:click="$set('showLogModal', false)"
                                    class="border border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white px-4 py-2 rounded-sm text-xs font-mono uppercase tracking-wider transition-all bg-transparent cursor-pointer"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="bg-amber-500 hover:bg-amber-600 text-black text-xs font-semibold uppercase tracking-wider px-5 py-2 rounded-sm transition-all duration-300"
                                >
                                    Log & Deduct Stock
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
