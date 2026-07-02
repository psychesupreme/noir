<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">Accounts Receivable & Credit Ledger</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Monitor corporate Net 30 balances, review aging reports, record payments, and edit credit profiles.</p>
            </div>
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
        </div>
    </div>

    {{-- Metrics Grid (Aging & AR Summaries) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        {{-- Total Outstanding AR --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Total Outstanding AR</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ number_format($totalOutstanding) }} <span class="text-xs text-neutral-600">KSH</span></span>
            <div class="absolute right-4 bottom-4 text-amber-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        {{-- Aging 0-30 Days --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-emerald-500 block">Aging: 0-30 Days (Current)</span>
            <span class="text-2xl font-light text-emerald-400 font-mono mt-3 block">{{ number_format($aging0to30) }} <span class="text-xs text-neutral-600">KSH</span></span>
        </div>

        {{-- Aging 31-60 Days --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-amber-500 block">Aging: 31-60 Days (Overdue)</span>
            <span class="text-2xl font-light text-amber-400 font-mono mt-3 block">{{ number_format($aging31to60) }} <span class="text-xs text-neutral-600">KSH</span></span>
        </div>

        {{-- Aging 61+ Days --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-rose-500 block">Aging: 60+ Days (Critical)</span>
            <span class="text-2xl font-light text-rose-400 font-mono mt-3 block">{{ number_format($aging60Plus) }} <span class="text-xs text-neutral-600">KSH</span></span>
        </div>
    </div>

    {{-- Main content split layout (Ledger vs. Credit Configs) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Ledger Table --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Filters & Search --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 relative group">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search AR Invoices..."
                        class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm pl-10 pr-4 py-2.5 text-xs text-neutral-300 placeholder-neutral-600 focus:outline-none focus:border-neutral-700 font-mono"
                    >
                    <div class="absolute left-3 top-3 flex items-center justify-center pointer-events-none">
                        <svg class="w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full bg-[#0F0F12] border border-neutral-900 rounded-sm px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-700 font-mono cursor-pointer"
                    >
                        <option value="all">All Invoices</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partially_paid">Partially Paid</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>

            {{-- Invoices Table --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                                <th class="p-4 font-medium">Invoice Ref</th>
                                <th class="p-4 font-medium">Due Date</th>
                                <th class="p-4 font-medium">Client / Company</th>
                                <th class="p-4 font-medium font-mono text-right">Amount</th>
                                <th class="p-4 font-medium font-mono text-right">Paid</th>
                                <th class="p-4 font-medium font-mono text-right">Balance</th>
                                <th class="p-4 font-medium">Status</th>
                                <th class="p-4 font-medium text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/60 text-xs font-light">
                            @forelse ($invoices as $invoice)
                                <tr class="hover:bg-neutral-950/40 transition-colors">
                                    <td class="p-4 font-mono text-white font-semibold">
                                        INV-{{ $invoice->created_at->format('Y') }}-{{ str_pad($invoice->order_id, 5, '0', STR_PAD_LEFT) }}
                                        <span class="block text-[9px] text-neutral-600 font-light mt-0.5">Order #NB-ORD-{{ str_pad($invoice->order_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="p-4 font-mono text-neutral-400">
                                        {{ $invoice->due_at->format('d M Y') }}
                                        @if ($invoice->status === 'unpaid' && $invoice->due_at->isPast())
                                            <span class="block text-[8px] text-rose-500 font-bold tracking-wider">OVERDUE</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="text-neutral-200 block font-normal">{{ $invoice->client->company_name ?: $invoice->client->contact_name }}</span>
                                        <span class="text-neutral-500 text-[9px] block font-mono">{{ $invoice->client->kra_pin ?: 'NO PIN' }}</span>
                                    </td>
                                    <td class="p-4 font-mono text-right text-neutral-400">{{ number_format($invoice->amount_due) }}</td>
                                    <td class="p-4 font-mono text-right text-emerald-500">{{ number_format($invoice->amount_paid) }}</td>
                                    <td class="p-4 font-mono text-right text-white font-semibold">{{ number_format($invoice->balance_due) }}</td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-mono uppercase tracking-wider inline-block {{
                                            $invoice->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : (
                                            $invoice->status === 'unpaid' ? ($invoice->due_at->isPast() ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-neutral-800 text-neutral-400 border border-neutral-700') : 
                                            'bg-amber-500/10 text-amber-400 border border-amber-500/20')
                                        }}">
                                            {{ $invoice->status === 'unpaid' && $invoice->due_at->isPast() ? 'overdue' : $invoice->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if ($invoice->status !== 'paid')
                                            <button 
                                                wire:click="openPaymentModal({{ $invoice->id }})"
                                                class="text-[9px] font-mono uppercase tracking-widest text-amber-500 border border-amber-900 rounded px-2 py-1 hover:text-white hover:border-amber-600 hover:bg-amber-950/20 transition-all duration-300"
                                            >
                                                Record Paydown
                                            </button>
                                        @else
                                            <span class="text-neutral-700 font-mono">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-neutral-500 font-light font-mono">
                                        No Accounts Receivable invoice logs available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="font-mono text-xs">
                {{ $invoices->links() }}
            </div>
        </div>

        {{-- B2B Client Limit Dashboard Manager --}}
        <div class="space-y-6">
            <h3 class="text-xs uppercase tracking-widest font-mono text-neutral-500">Corporate Credit Profiles</h3>
            <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 space-y-4 shadow-2xl">
                @forelse ($corporateClients as $client)
                    <div class="border-b border-neutral-900 pb-4 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-normal text-white text-xs">{{ $client->company_name ?: $client->contact_name }}</span>
                                <span class="block text-[9px] text-neutral-500 font-mono">Terms: {{ strtoupper($client->payment_terms) }}</span>
                            </div>
                            <button 
                                wire:click="openCreditModal({{ $client->id }})"
                                class="text-[9px] font-mono text-neutral-400 hover:text-amber-500 border border-neutral-800 hover:border-neutral-700 rounded px-2 py-1 transition-all"
                            >
                                Limit Settings
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3 text-[10px] font-mono">
                            <div>
                                <span class="text-neutral-600 block uppercase tracking-wider">Credit Limit</span>
                                <span class="text-neutral-300 block mt-0.5">{{ number_format($client->credit_limit) }} KSH</span>
                            </div>
                            <div>
                                <span class="text-neutral-600 block uppercase tracking-wider">Outstanding</span>
                                <span class="text-amber-500 block font-bold mt-0.5">{{ number_format($client->outstanding_balance) }} KSH</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-neutral-600 font-mono font-light text-center py-4">No corporate credit clients configured.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Record Payment Modal --}}
    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-md p-8 shadow-2xl relative">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-6 font-mono">Record Invoice Remittance</h3>
                
                <div class="mb-4">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Remittance Amount (KSH)</label>
                    <input 
                        type="number" 
                        wire:model="paymentAmount"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                    @error('paymentAmount')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Remittance Channel</label>
                    <select 
                        wire:model="paymentMethod"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                        <option value="bank_transfer">BANK DIRECT WIRE</option>
                        <option value="cheque">CORPORATE CHEQUE</option>
                        <option value="cash">DIRECT CASH PAYDOWN</option>
                        <option value="mpesa">M-PESA DEPOSIT</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Transaction Reference / Document No</label>
                    <input 
                        type="text" 
                        wire:model="paymentReference"
                        placeholder="e.g. Bank slip number, cheque number..."
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showPaymentModal', false)"
                        class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="recordPayment"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm transition-colors"
                    >
                        Record Remittance
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Adjust Credit Profile Modal --}}
    @if ($showCreditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-md p-8 shadow-2xl relative">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-6 font-mono">B2B Credit Profile Settings</h3>
                
                <div class="mb-4">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Approved Credit Limit (KSH)</label>
                    <input 
                        type="number" 
                        wire:model="creditLimit"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                    @error('creditLimit')
                        <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Billing Remittance Terms</label>
                    <select 
                        wire:model="paymentTerms"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                        <option value="net_30">NET 30 INVOICE ACCOUNT</option>
                        <option value="prepaid">PREPAID ONLY (M-PESA/CARD)</option>
                        <option value="cod">CASH ON DELIVERY (COD)</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showCreditModal', false)"
                        class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveCreditProfile"
                        class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm transition-colors"
                    >
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
