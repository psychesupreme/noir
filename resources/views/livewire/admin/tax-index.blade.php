<div class="min-h-screen text-neutral-100 font-sans antialiased">
    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">KRA eTIMS Tax Compliance Ledger</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Audit transmitted VAT invoices, review eTIMS error codes, and trigger manual compliance retries.</p>
            </div>
            <div>
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
            </div>
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Card 1: VAT Collected --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Total VAT (16%) Collected</span>
            <span class="text-2xl font-light text-emerald-400 font-mono mt-3 block">{{ number_format($totalVat) }} <span class="text-xs text-neutral-600">KSH</span></span>
            <div class="absolute right-4 bottom-4 text-emerald-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5m-18 4.75h16.5m-18 4.75h16.5m-18 4.75h16.5M2.25 12.5a60.27 60.27 0 0 1 15.797 2.1c.727.19 1.453-.35 1.453-1.1v-2.25" />
                </svg>
            </div>
        </div>

        {{-- Card 2: Successful --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Successful Transmissions</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ $successCount }}</span>
            <div class="absolute right-4 bottom-4 text-neutral-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Failed --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6 relative overflow-hidden group hover:border-neutral-800 transition-all duration-300 font-mono">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Pending Compliance Errors</span>
            <div class="flex items-baseline space-x-3 mt-3">
                <span class="text-2xl font-light text-rose-500 block">{{ $failedCount }}</span>
                @if($failedCount > 0)
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                @endif
            </div>
            <div class="absolute right-4 bottom-4 text-rose-500/10 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Intelligent Search --}}
        <div class="md:col-span-2 relative group">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search invoices (e.g. status:failed gross>10000 vat<1000)..."
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
                <option value="all">All Invoices</option>
                <option value="transmitted">Transmitted</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        {{-- Export Button --}}
        <div>
            <button 
                wire:click="exportAudits"
                class="w-full bg-[#0F0F12] border border-neutral-900 hover:border-neutral-800 rounded-sm px-4 py-2.5 text-xs text-amber-500 hover:text-amber-400 focus:outline-none font-mono cursor-pointer flex items-center justify-center space-x-2 transition-all duration-300"
            >
                <svg class="w-4 h-4 text-amber-600 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Audit CSV</span>
            </button>
        </div>
    </div>

    {{-- Invoices Ledger --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-neutral-500 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50">
                        <th class="p-6 font-medium">Invoice Number</th>
                        <th class="p-6 font-medium">Requisition Link</th>
                        <th class="p-6 font-medium">Recipient Client</th>
                        <th class="p-6 font-medium font-mono text-right">Taxable base</th>
                        <th class="p-6 font-medium font-mono text-right">VAT (16%)</th>
                        <th class="p-6 font-medium font-mono text-right">Gross total</th>
                        <th class="p-6 font-medium">KRA CU Reference</th>
                        <th class="p-6 font-medium">Fulfillment Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-neutral-950/40 transition-colors">
                            {{-- Number --}}
                            <td class="p-6 font-mono text-xs text-white font-semibold">
                                {{ $invoice->internal_invoice_number }}
                            </td>

                            {{-- Requisition --}}
                            <td class="p-6 font-mono text-xs">
                                <a href="/admin/orders?search=NB-ORD-{{ str_pad($invoice->order_id, 4, '0', STR_PAD_LEFT) }}" class="text-amber-500 hover:text-amber-400 transition-colors block">
                                    #NB-ORD-{{ str_pad($invoice->order_id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>

                            {{-- Client --}}
                            <td class="p-6">
                                @if ($invoice->order && $invoice->order->client)
                                    <span class="text-neutral-200 block font-normal">{{ $invoice->order->client->company_name ?: 'Individual Account' }}</span>
                                    <span class="text-neutral-500 text-xs block font-mono">{{ $invoice->order->client->kra_pin ?: 'NO PIN' }}</span>
                                @else
                                    <span class="text-neutral-600 block">N/A</span>
                                @endif
                            </td>

                            {{-- Taxable Base --}}
                            <td class="p-6 font-mono text-right text-neutral-400">
                                {{ number_format($invoice->taxable_amount) }}
                            </td>

                            {{-- VAT (16%) --}}
                            <td class="p-6 font-mono text-right text-emerald-400 font-medium">
                                {{ number_format($invoice->vat_amount) }}
                            </td>

                            {{-- Gross --}}
                            <td class="p-6 font-mono text-right text-white font-semibold">
                                {{ number_format($invoice->gross_amount) }}
                            </td>

                            {{-- CU / QR --}}
                            <td class="p-6 font-mono text-xs">
                                @if ($invoice->status === 'transmitted')
                                    <div class="space-y-1.5">
                                        <span class="text-emerald-500 block font-medium">✓ eTIMS Transmitted</span>
                                        <span class="text-[9px] text-neutral-500 block font-light truncate max-w-[130px]" title="{{ $invoice->cu_invoice_number }}">
                                            {{ $invoice->cu_invoice_number }}
                                        </span>
                                        @if ($invoice->kra_qr_url)
                                            <a href="{{ $invoice->kra_qr_url }}" target="_blank" class="text-[9px] tracking-wider text-amber-500 hover:underline block uppercase">[ KRA Portal ]</a>
                                        @endif
                                    </div>
                                @else
                                    <div class="space-y-1">
                                        <span class="text-rose-500 block font-medium">✕ Transmission Failed</span>
                                        @if ($invoice->error_log_payload)
                                            <p class="text-[9px] text-neutral-600 font-light truncate max-w-[150px]" title="{{ $invoice->error_log_payload }}">
                                                {{ $invoice->error_log_payload }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Retry --}}
                            <td class="p-6 text-center">
                                @if ($invoice->status !== 'transmitted')
                                    <button 
                                        wire:click="retryTransmission({{ $invoice->id }})"
                                        wire:loading.attr="disabled"
                                        class="flex items-center space-x-1.5 text-[10px] font-mono uppercase tracking-widest text-neutral-400 border border-neutral-800 rounded px-2.5 py-1.5 hover:text-white hover:border-neutral-600 transition-all duration-300 relative group overflow-hidden"
                                    >
                                        <!-- Spinning loader loop while processing -->
                                        <svg wire:loading wire:target="retryTransmission({{ $invoice->id }})" class="w-3 h-3 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <!-- Standard reload SVG icon -->
                                        <svg wire:loading.remove wire:target="retryTransmission({{ $invoice->id }})" class="w-3 h-3 text-neutral-500 transition-all duration-350 group-hover:rotate-180 group-hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        <span>Retry</span>
                                    </button>
                                @else
                                    <span class="text-neutral-700 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-neutral-500 font-light font-mono">
                                No tax compliant records available under this ledger scope.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 font-mono text-xs">
        {{ $invoices->links() }}
    </div>
</div>
