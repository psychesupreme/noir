{{-- ═══════════════════════════════════════════════════════════════════════
     NOIR & BLOOM — ERP COMMAND CENTRE
     Ultra-luxury admin dashboard · Real-time operational intelligence
     ═══════════════════════════════════════════════════════════════════════ --}}
<div wire:poll.30s class="space-y-8">

    {{-- ── Page Header ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extralight tracking-tight text-white">Command Centre</h2>
            <p class="text-xs text-neutral-500 font-light mt-1">Operational overview · Auto-refreshes every 30 seconds</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[10px] font-mono uppercase tracking-[0.2em] text-neutral-600">Live</span>
        </div>
    </div>



    {{-- ═══════════════════════════════════════════════════════════════════════
         FINANCIAL INTELLIGENCE OVERVIEW
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Today Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 hover:border-neutral-800 transition-all hover:scale-[1.01] hover:shadow-lg hover:shadow-black/50 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Today's Revenue</span>
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <p class="text-2xl font-mono text-white tracking-tight">{{ number_format($todayRevenue) }}</p>
                <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH · REALTIME SALES</p>
            </div>
        </div>

        {{-- Month Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 hover:border-neutral-800 transition-all hover:scale-[1.01] hover:shadow-lg hover:shadow-black/50 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">This Month</span>
                    <svg class="w-4 h-4 text-neutral-700 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
                <p class="text-2xl font-mono text-white tracking-tight">{{ number_format($monthRevenue) }}</p>
                <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH · MONTH TO DATE</p>
            </div>
        </div>

        {{-- Gross Margin --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 hover:border-neutral-800 transition-all hover:scale-[1.01] hover:shadow-lg hover:shadow-black/50 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Gross Margin %</span>
                    <svg class="w-4 h-4 text-emerald-700 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
                <p class="text-2xl font-mono text-emerald-400 tracking-tight">{{ $monthMarginPercent }}%</p>
                <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH {{ number_format($monthMargin) }} EARNED</p>
            </div>
        </div>

        {{-- All-Time Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 hover:border-neutral-800 transition-all hover:scale-[1.01] hover:shadow-lg hover:shadow-black/50 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-950/15 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-amber-500">All Time</span>
                    <svg class="w-4 h-4 text-amber-800 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <p class="text-2xl font-mono text-amber-400 tracking-tight">{{ number_format($allTimeRevenue) }}</p>
                <p class="text-[10px] font-mono text-amber-800 mt-1 tracking-wider">KSH · LIFETIME CUMULATIVE</p>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         OPERATIONAL SUMMARY METRICS
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">

        {{-- Month COGS --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 hover:border-neutral-800 transition-all">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Month COGS</span>
            <p class="text-xl font-mono text-white mt-1 tracking-tight">{{ number_format($monthCogs) }}</p>
            <p class="text-[9px] font-mono text-neutral-600 mt-1 uppercase tracking-wider">Cost of Goods Sold</p>
        </div>

        {{-- Wastage Cost --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 hover:border-neutral-800 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-rose-500">Floral Wastage</span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
            </div>
            <p class="text-xl font-mono text-rose-400 mt-1 tracking-tight">{{ number_format($monthWastage) }}</p>
            <p class="text-[9px] font-mono text-neutral-600 mt-1 uppercase tracking-wider">Spoilage / Shrinkage</p>
        </div>

        {{-- Total Orders --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 hover:border-neutral-800 transition-all">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Orders</span>
            <p class="text-xl font-mono text-white mt-1 tracking-tight">{{ number_format($totalOrders) }}</p>
            <div class="flex items-center space-x-1.5 mt-1.5">
                @php
                    $statusDots = [
                        'pending'    => ['color' => 'bg-amber-500',   'label' => 'P'],
                        'approved'   => ['color' => 'bg-blue-500',    'label' => 'A'],
                        'processing' => ['color' => 'bg-violet-500',  'label' => 'Pr'],
                        'delivered'  => ['color' => 'bg-emerald-500', 'label' => 'D'],
                    ];
                @endphp
                @foreach ($statusDots as $status => $dot)
                    @if (($ordersByStatus[$status] ?? 0) > 0)
                        <div class="flex items-center space-x-0.5" title="{{ $status }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot['color'] }}"></span>
                            <span class="text-[9px] font-mono text-neutral-500">{{ $ordersByStatus[$status] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Total Clients --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 hover:border-neutral-800 transition-all">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Clients</span>
            <p class="text-xl font-mono text-white mt-1 tracking-tight">{{ number_format($totalClients) }}</p>
            <p class="text-[9px] font-mono text-neutral-600 mt-1 uppercase tracking-wider">Registered Accounts</p>
        </div>

        {{-- Total Products --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 hover:border-neutral-800 transition-all">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Products</span>
            <div class="flex items-center justify-between mt-1">
                <p class="text-xl font-mono text-white tracking-tight">{{ number_format($totalProducts) }}</p>
                @if ($lowBranchStocks->count() > 0)
                    <span class="px-1.5 py-0.5 rounded text-[8px] font-mono uppercase tracking-wider bg-rose-950/40 text-rose-400 border border-rose-900/20">
                        {{ $lowBranchStocks->count() }} Alert
                    </span>
                @endif
            </div>
            <p class="text-[9px] font-mono text-neutral-600 mt-1 uppercase tracking-wider">Catalog Items</p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         MAIN OPERATIONAL DATALISTS (ROW 3)
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- ── Recent Orders Table & Card Stack (Responsive) ── --}}
        <div class="xl:col-span-2 bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-900/60">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Recent Orders</span>
                <a href="/admin/orders" class="text-[10px] font-mono uppercase tracking-wider text-amber-700 hover:text-amber-500 transition-colors">
                    View All →
                </a>
            </div>

            {{-- Tablet & Desktop View (Traditional Table) --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-neutral-900/60 text-[10px] uppercase tracking-[0.2em] text-neutral-600 bg-[#0A0A0A]/30">
                            <th class="px-5 py-3 font-medium font-mono">Order #</th>
                            <th class="px-5 py-3 font-medium font-mono">Client</th>
                            <th class="px-5 py-3 font-medium font-mono text-right">Total</th>
                            <th class="px-5 py-3 font-medium font-mono text-center">Status</th>
                            <th class="px-5 py-3 font-medium font-mono text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-neutral-900/20 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="text-xs font-mono text-white">#NB-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-xs text-neutral-300">{{ $order->client->contact_name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-xs font-mono text-white">{{ number_format($order->total_amount) }}</span>
                                    <span class="text-[9px] font-mono text-neutral-600 ml-0.5">KSH</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $badgeColors = [
                                            'pending'    => 'bg-amber-950/50 text-amber-400 border-amber-900/40',
                                            'approved'   => 'bg-blue-950/50 text-blue-400 border-blue-900/40',
                                            'processing' => 'bg-violet-950/50 text-violet-400 border-violet-900/40',
                                            'delivered'  => 'bg-emerald-950/50 text-emerald-400 border-emerald-900/40',
                                            'cancelled'  => 'bg-neutral-900/50 text-neutral-500 border-neutral-800/40',
                                        ];
                                        $badgeClass = $badgeColors[$order->status] ?? 'bg-neutral-900/50 text-neutral-500 border-neutral-800/40';
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider border {{ $badgeClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-[10px] font-mono text-neutral-600">{{ $order->created_at->format('d M · H:i') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-neutral-600 text-xs font-light">
                                    No orders in the system yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List (No horizontal scrolling) --}}
            <div class="block sm:hidden divide-y divide-neutral-900/60">
                @forelse ($recentOrders as $order)
                    @php
                        $badgeColors = [
                            'pending'    => 'bg-amber-950/50 text-amber-400 border-amber-900/40',
                            'approved'   => 'bg-blue-950/50 text-blue-400 border-blue-900/40',
                            'processing' => 'bg-violet-950/50 text-violet-400 border-violet-900/40',
                            'delivered'  => 'bg-emerald-950/50 text-emerald-400 border-emerald-900/40',
                            'cancelled'  => 'bg-neutral-900/50 text-neutral-500 border-neutral-800/40',
                        ];
                        $badgeClass = $badgeColors[$order->status] ?? 'bg-neutral-900/50 text-neutral-500 border-neutral-800/40';
                    @endphp
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono text-white font-semibold">#NB-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="inline-block px-2 py-0.5 rounded text-[8px] font-mono uppercase tracking-wider border {{ $badgeClass }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-baseline text-xs">
                            <span class="text-neutral-400 font-light">{{ $order->client->contact_name ?? '—' }}</span>
                            <span class="text-neutral-500 font-mono text-[10px]">{{ $order->created_at->format('d M, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-1">
                            <span class="text-[9px] uppercase tracking-wider text-neutral-600 font-mono">Total Due:</span>
                            <span class="text-xs font-mono text-amber-500 font-bold">{{ number_format($order->total_amount) }} KSH</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-neutral-600 text-xs font-light">
                        No orders in the system yet.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── Top Selling Products (Balanced 1/3 width) ── --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm flex flex-col justify-between overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-900/60">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Top Sellers</span>
            </div>
            <div class="px-5 py-3 divide-y divide-neutral-900/30 flex-1 flex flex-col justify-around">
                @forelse ($topProducts->take(4) as $index => $product)
                    <div class="flex items-center justify-between py-2.5">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            <span class="text-[10px] font-mono {{ $index === 0 ? 'text-amber-500 font-bold' : 'text-neutral-600' }}">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs text-neutral-300 truncate font-light">{{ $product->name }}</p>
                                <p class="text-[9px] font-mono text-neutral-600 truncate uppercase tracking-wider mt-0.5">{{ $product->sku }} · {{ number_format($product->price) }} KSH</p>
                            </div>
                        </div>
                        <div class="text-right ml-3 shrink-0">
                            <span class="text-xs font-mono text-white font-semibold">
                                {{ $product->total_sold }}
                            </span>
                            <span class="text-[9px] font-mono text-neutral-600 block">units sold</span>
                        </div>
                    </div>
                @empty
                    <p class="text-[10px] text-neutral-600 font-light py-2">No sales data yet</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         INVENTORY, PROCUREMENT & PAYMENT ALERTS (ROW 4)
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Low Branch Stock Alerts --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-900/60 flex items-center justify-between">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Branch Stock Alerts</span>
                @if ($lowBranchStocks->count() > 0)
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                @endif
            </div>
            <div class="px-5 py-3 space-y-2 max-h-[195px] overflow-y-auto scrollbar-thin">
                @forelse ($lowBranchStocks as $bs)
                    <div class="flex items-center justify-between py-1.5 border-b border-neutral-900/30 last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-neutral-300 truncate font-light">{{ $bs->product->name }}</p>
                            <p class="text-[9px] font-mono text-neutral-600 uppercase tracking-wider mt-0.5">{{ $bs->branch->name }}</p>
                        </div>
                        <span class="text-xs font-mono {{ $bs->stock <= $bs->min_stock_level ? 'text-rose-400 font-medium' : 'text-neutral-400' }} ml-3 shrink-0">
                            {{ $bs->stock }} / {{ $bs->min_stock_level }}
                        </span>
                    </div>
                @empty
                    <p class="text-[10px] text-neutral-600 font-light py-2">All branch items well stocked ✓</p>
                @endforelse
            </div>
        </div>

        {{-- Purchase Order Pipeline --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden flex flex-col justify-between">
            <div class="px-5 py-4 border-b border-neutral-900/60 flex items-center justify-between">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Purchase Ordering</span>
                @if($pendingDeliveriesCount > 0)
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                @endif
            </div>
            <div class="px-5 py-4 space-y-4 font-mono flex-1 flex flex-col justify-around">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-neutral-400 font-light">Open Purchase Orders</span>
                    <span class="text-sm text-white font-semibold">{{ $openPosCount }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-neutral-400 font-light">Pending Deliveries</span>
                    <span class="text-sm text-blue-400 font-semibold">{{ $pendingDeliveriesCount }}</span>
                </div>
                <div class="h-1 bg-neutral-950 rounded-full overflow-hidden flex">
                    @php
                        $poProgress = $openPosCount > 0 ? round(($pendingDeliveriesCount / $openPosCount) * 100) : 0;
                    @endphp
                    <div class="bg-blue-500 h-full rounded-full" style="width: {{ max($poProgress, 15) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden flex flex-col justify-between">
            <div class="px-5 py-4 border-b border-neutral-900/60">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Payment Statuses</span>
            </div>
            <div class="px-5 py-3 space-y-2 flex-1 flex flex-col justify-around">
                <div class="flex items-center justify-between p-1.5 border-l-2 border-emerald-500 bg-emerald-950/10 rounded-sm">
                    <span class="text-xs text-neutral-400 font-light">Completed</span>
                    <span class="text-xs font-mono font-semibold text-emerald-400">{{ $completedPayments }}</span>
                </div>
                <div class="flex items-center justify-between p-1.5 border-l-2 border-amber-500 bg-amber-950/10 rounded-sm">
                    <span class="text-xs text-neutral-400 font-light">Pending</span>
                    <span class="text-xs font-mono font-semibold text-amber-400">{{ $pendingPayments }}</span>
                </div>
                <div class="flex items-center justify-between p-1.5 border-l-2 border-rose-500 bg-rose-950/10 rounded-sm">
                    <span class="text-xs text-neutral-400 font-light">Failed</span>
                    <span class="text-xs font-mono font-semibold text-rose-400">{{ $failedPayments }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         ORDER PIPELINE PIPELINE (ROW 5)
         ═══════════════════════════════════════════════════════════════════════ --}}
    @php
        $pipelineStatuses = ['pending', 'approved', 'processing', 'delivered'];
        $pipelineTotal    = collect($pipelineStatuses)->sum(fn($s) => $ordersByStatus[$s] ?? 0);
        
        $pipelineConfig = [
            'pending'    => ['icon' => '◷', 'color' => 'amber',   'label' => 'Pending'],
            'approved'   => ['icon' => '✓', 'color' => 'blue',    'label' => 'Approved'],
            'processing' => ['icon' => '⟳', 'color' => 'violet',  'label' => 'Processing'],
            'delivered'  => ['icon' => '✦', 'color' => 'emerald', 'label' => 'Delivered'],
        ];

        $colorMap = [
            'amber'   => ['ring' => 'ring-amber-800/50',   'bg' => 'bg-amber-950/30',   'text' => 'text-amber-400',   'icon' => 'text-amber-500'],
            'blue'    => ['ring' => 'ring-blue-800/50',    'bg' => 'bg-blue-950/30',    'text' => 'text-blue-400',    'icon' => 'text-blue-500'],
            'violet'  => ['ring' => 'ring-violet-800/50',  'bg' => 'bg-violet-950/30',  'text' => 'text-violet-400',  'icon' => 'text-violet-500'],
            'emerald' => ['ring' => 'ring-emerald-800/50', 'bg' => 'bg-emerald-950/30', 'text' => 'text-emerald-400', 'icon' => 'text-emerald-500'],
        ];
    @endphp
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Order Pipeline</span>
            <span class="text-[10px] font-mono text-neutral-600">{{ $pipelineTotal }} in pipeline</span>
        </div>

        {{-- Desktop Pipeline --}}
        <div class="hidden sm:flex items-center justify-between relative">
            {{-- Connecting line --}}
            <div class="absolute top-1/2 left-0 right-0 h-px bg-gradient-to-r from-amber-900/40 via-amber-700/20 to-emerald-900/40 -translate-y-1/2 z-0"></div>

            @foreach ($pipelineConfig as $status => $cfg)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $pct   = $pipelineTotal > 0 ? round(($count / $pipelineTotal) * 100) : 0;
                    $c     = $colorMap[$cfg['color']];
                @endphp

                <div class="relative z-10 flex flex-col items-center flex-1">
                    {{-- Node --}}
                    <div class="w-14 h-14 rounded-full {{ $c['bg'] }} ring-1 {{ $c['ring'] }} flex items-center justify-center mb-3 backdrop-blur-sm">
                        <span class="text-lg {{ $c['icon'] }}">{{ $cfg['icon'] }}</span>
                    </div>

                    {{-- Count --}}
                    <p class="text-xl font-mono text-white">{{ $count }}</p>

                    {{-- Label & Percentage --}}
                    <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mt-1">{{ $cfg['label'] }}</p>
                    <p class="text-[10px] font-mono {{ $c['text'] }} mt-0.5">{{ $pct }}%</p>

                    {{-- Arrow connector (skip last) --}}
                    @if (!$loop->last)
                        <div class="absolute top-7 -right-4 text-neutral-800 hidden lg:block">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Mobile Pipeline (stacked) --}}
        <div class="sm:hidden space-y-3">
            @foreach ($pipelineConfig as $status => $cfg)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $pct   = $pipelineTotal > 0 ? round(($count / $pipelineTotal) * 100) : 0;
                    $c     = $colorMap[$cfg['color']];
                @endphp
                <div class="flex items-center space-x-4 {{ $c['bg'] }} rounded-sm px-4 py-3 ring-1 {{ $c['ring'] }}">
                    <span class="text-lg {{ $c['icon'] }}">{{ $cfg['icon'] }}</span>
                    <div class="flex-1">
                        <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">{{ $cfg['label'] }}</p>
                        <div class="flex items-baseline space-x-2 mt-0.5">
                            <span class="text-lg font-mono text-white">{{ $count }}</span>
                            <span class="text-[10px] font-mono {{ $c['text'] }}">{{ $pct }}%</span>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <svg class="w-3 h-3 text-neutral-700 rotate-90" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pipeline progress bar --}}
        <div class="mt-6 h-1 bg-neutral-900 rounded-full overflow-hidden flex">
            @foreach ($pipelineConfig as $status => $cfg)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $pct   = $pipelineTotal > 0 ? ($count / $pipelineTotal) * 100 : 0;
                    $barColors = [
                        'amber'   => 'bg-amber-600',
                        'blue'    => 'bg-blue-600',
                        'violet'  => 'bg-violet-600',
                        'emerald' => 'bg-emerald-600',
                    ];
                @endphp
                @if ($pct > 0)
                    <div class="{{ $barColors[$cfg['color']] }}" style="width: {{ $pct }}%"></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ── Footer Timestamp ────────────────────────────────────────────────── --}}
    <div class="text-center py-4">
        <p class="text-[10px] font-mono tracking-[0.2em] text-neutral-700">
            Last refreshed · {{ now()->timezone('Africa/Nairobi')->format('d M Y · H:i:s') }} EAT
        </p>
    </div>

</div>
