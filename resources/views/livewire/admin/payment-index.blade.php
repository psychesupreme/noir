<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">M-Pesa Reconciliation Ledger</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Audit Daraja payment push callback statuses and issue simulated client refunds.</p>
            </div>
            @if (session()->has('message'))
                <div class="bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 px-4 py-2 text-xs font-mono rounded-sm animate-pulse">
                    ✓ {{ session('message') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Card 1: Volume --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Total M-Pesa Revenue</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ number_format($totalVolume) }} <span class="text-xs text-neutral-600">KSH</span></span>
            <div class="absolute right-4 bottom-4 text-amber-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        {{-- Card 2: Pending --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Pending Pushes</span>
            <div class="flex items-baseline space-x-3 mt-3">
                <span class="text-2xl font-light text-white font-mono block">{{ $pendingCount }}</span>
                @if($pendingCount > 0)
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                @endif
            </div>
            <div class="absolute right-4 bottom-4 text-amber-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Failed --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Failed Logs</span>
            <span class="text-2xl font-light text-rose-500 font-mono mt-3 block">{{ $failedCount }}</span>
            <div class="absolute right-4 bottom-4 text-rose-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Intelligent Search --}}
        <div class="md:col-span-3 relative group">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search ledger (e.g. status:completed amount>3000 receipt:LKJ)..."
                class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm pl-10 pr-4 py-2.5 text-xs text-neutral-300 placeholder-neutral-600 focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 transition-all duration-300 font-mono"
            >
            <div class="absolute left-3 top-3 flex items-center justify-center pointer-events-none">
                <svg wire:loading.remove wire:target="search" class="w-4 h-4 text-neutral-600 transition-all duration-300 group-focus-within:text-amber-500 group-focus-within:scale-115" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <svg wire:loading wire:target="search" class="w-4 h-4 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                        <th class="p-6 font-medium">Receipt / Timestamp</th>
                        <th class="p-6 font-medium">Order Reference</th>
                        <th class="p-6 font-medium">Client Info</th>
                        <th class="p-6 font-medium font-mono text-right">Transaction amount</th>
                        <th class="p-6 font-medium">Status / Log</th>
                        <th class="p-6 font-medium text-center">Fulfillment Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-neutral-950/40 transition-colors">
                            {{-- Receipt / Time --}}
                            <td class="p-6 font-mono text-xs">
                                <span class="text-white block font-semibold">{{ $payment->mpesa_receipt_number ?: 'STK-PENDING' }}</span>
                                <span class="text-neutral-600 text-[9px] mt-1 block">{{ $payment->created_at->format('d M Y H:i:s') }}</span>
                            </td>

                            {{-- Order Link --}}
                            <td class="p-6 font-mono text-xs">
                                @if($payment->order)
                                    <a href="/admin/orders?search=NB-ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}" class="text-amber-500 hover:text-amber-400 transition-colors block">
                                        #NB-ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                                    </a>
                                @else
                                    <span class="text-neutral-700 block">No linked order</span>
                                @endif
                            </td>

                            {{-- Client Info --}}
                            <td class="p-6">
                                @if($payment->order && $payment->order->client)
                                    <span class="text-neutral-200 block font-normal">{{ $payment->order->client->contact_name }}</span>
                                    <span class="text-neutral-500 text-xs block font-mono">{{ $payment->phone_number }}</span>
                                @else
                                    <span class="text-neutral-600 block">N/A</span>
                                @endif
                            </td>

                            {{-- Amount --}}
                            <td class="p-6 font-mono text-right text-white font-medium">
                                {{ number_format($payment->amount) }} KSH
                            </td>

                            {{-- Status Badge --}}
                            <td class="p-6">
                                <div class="space-y-1">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider inline-block {{
                                        $payment->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : (
                                        $payment->status === 'failed' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 
                                        'bg-amber-500/10 text-amber-400 border border-amber-500/20')
                                    }}">
                                        {{ $payment->status }}
                                    </span>
                                    @if ($payment->result_description)
                                        <p class="text-[10px] text-neutral-500 font-light truncate max-w-[200px]" title="{{ $payment->result_description }}">
                                            {{ $payment->result_description }}
                                        </p>
                                    @endif
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="p-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button 
                                        wire:click="openEditModal({{ $payment->id }})"
                                        class="text-[10px] font-mono uppercase tracking-widest text-neutral-400 border border-neutral-800 rounded px-2.5 py-1 hover:text-amber-500 hover:border-amber-950 hover:bg-amber-950/10 transition-all duration-300"
                                    >
                                        Edit Status
                                    </button>
                                    @if ($payment->status === 'completed')
                                        <button 
                                            wire:click="confirmRefund({{ $payment->id }})"
                                            class="text-[10px] font-mono uppercase tracking-widest text-neutral-400 border border-neutral-800 rounded px-2.5 py-1 hover:text-rose-400 hover:border-rose-950 hover:bg-rose-950/10 transition-all duration-300"
                                        >
                                            Refund Push
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-neutral-500 font-light">
                                No Daraja payment records found under this view scope.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 font-mono text-xs">
        {{ $payments->links() }}
    </div>

    {{-- Refund Simulation Modal --}}
    @if ($showRefundModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-md p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-6">Confirm Simulated Refund</h3>
                <p class="text-xs text-neutral-400 font-light leading-relaxed mb-6">
                    This triggers a simulated **M-Pesa B2C Refund request** to Safaricom Daraja API. The payment status will transition to `failed` and the parent order will be automatically `cancelled`. Inventory stock will be reverted.
                </p>

                <div class="mb-6">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Reason for Refund</label>
                    <textarea 
                        wire:model="refundReason" 
                        rows="3" 
                        placeholder="Customer cancelled order / insufficient flowers / logistics issues..."
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    ></textarea>
                    @error('refundReason')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showRefundModal', false)"
                        class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="processRefund"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm transition-colors"
                    >
                        Simulate Refund
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Payment Status Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-md p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-6 font-mono">Edit Payment Record</h3>
                
                <div class="mb-4">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Payment Status</label>
                    <select 
                        wire:model="editingStatus"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                        <option value="pending">PENDING</option>
                        <option value="completed">COMPLETED</option>
                        <option value="failed">FAILED</option>
                    </select>
                    @error('editingStatus')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">M-Pesa Receipt Number</label>
                    <input 
                        type="text" 
                        wire:model="editingReceiptNumber"
                        placeholder="e.g. REC-12345 (Required for completed payments)"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                    @error('editingReceiptNumber')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Result Description / Log</label>
                    <textarea 
                        wire:model="editingResultDesc" 
                        rows="3" 
                        placeholder="STK push successful / user canceled / manual admin override..."
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    ></textarea>
                    @error('editingResultDesc')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showEditModal', false)"
                        class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="savePaymentStatus"
                        class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm transition-colors"
                    >
                        Save Details
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
