{{-- ═══════════════════════════════════════════════════════════════════════════
     NOIR & BLOOM — ERP COMMAND CENTRE
     Ultra-luxury admin dashboard · Real-time operational intelligence
     ═══════════════════════════════════════════════════════════════════════════ --}}
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
         ROW 1 — REVENUE CARDS
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Today Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 group hover:border-neutral-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Today</span>
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
            </div>
            <p class="text-2xl font-mono text-white tracking-tight">{{ number_format($todayRevenue) }}</p>
            <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH</p>
        </div>

        {{-- Week Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 group hover:border-neutral-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">This Week</span>
                <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <p class="text-2xl font-mono text-white tracking-tight">{{ number_format($weekRevenue) }}</p>
            <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH</p>
        </div>

        {{-- Month Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 group hover:border-neutral-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">This Month</span>
                <svg class="w-4 h-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
            </div>
            <p class="text-2xl font-mono text-white tracking-tight">{{ number_format($monthRevenue) }}</p>
            <p class="text-[10px] font-mono text-neutral-600 mt-1 tracking-wider">KSH</p>
        </div>

        {{-- All-Time Revenue --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 group hover:border-neutral-800 transition-colors relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-950/10 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-amber-600">All Time</span>
                    <svg class="w-4 h-4 text-amber-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <p class="text-2xl font-mono text-amber-400 tracking-tight">{{ number_format($allTimeRevenue) }}</p>
                <p class="text-[10px] font-mono text-amber-800 mt-1 tracking-wider">KSH · LIFETIME</p>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         ROW 2 — STATS SUMMARY
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Total Orders --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Orders</span>
            <p class="text-3xl font-mono text-white mt-2">{{ number_format($totalOrders) }}</p>
            <div class="flex items-center space-x-3 mt-3">
                @php
                    $statusDots = [
                        'pending'    => ['color' => 'bg-amber-500',   'label' => 'Pending'],
                        'approved'   => ['color' => 'bg-blue-500',    'label' => 'Approved'],
                        'processing' => ['color' => 'bg-violet-500',  'label' => 'Processing'],
                        'delivered'  => ['color' => 'bg-emerald-500', 'label' => 'Delivered'],
                        'cancelled'  => ['color' => 'bg-neutral-600', 'label' => 'Cancelled'],
                    ];
                @endphp
                @foreach ($statusDots as $status => $dot)
                    @if (($ordersByStatus[$status] ?? 0) > 0)
                        <div class="flex items-center space-x-1" title="{{ $dot['label'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot['color'] }}"></span>
                            <span class="text-[10px] font-mono text-neutral-500">{{ $ordersByStatus[$status] ?? 0 }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Total Clients --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Clients</span>
            <p class="text-3xl font-mono text-white mt-2">{{ number_format($totalClients) }}</p>
            <div class="flex items-center space-x-2 mt-3">
                <svg class="w-3.5 h-3.5 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span class="text-[10px] font-mono text-neutral-600">Registered accounts</span>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Products</span>
            <div class="flex items-center space-x-3 mt-2">
                <p class="text-3xl font-mono text-white">{{ number_format($totalProducts) }}</p>
                @if ($lowStockProducts->count() > 0)
                    <span class="px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider bg-rose-950/50 text-rose-400 border border-rose-900/30">
                        {{ $lowStockProducts->count() }} low stock
                    </span>
                @endif
            </div>
            <div class="flex items-center space-x-2 mt-3">
                <svg class="w-3.5 h-3.5 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                <span class="text-[10px] font-mono text-neutral-600">Active catalog items</span>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         ROW 3 — RECENT ORDERS + ALERTS PANEL
         ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- ── Left: Recent Orders Table (wider) ──────────────────────────── --}}
        <div class="xl:col-span-2 bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-900/60">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Recent Orders</span>
                <a href="/admin/orders" class="text-[10px] font-mono uppercase tracking-wider text-amber-700 hover:text-amber-500 transition-colors">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto">
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
        </div>

        {{-- ── Right: Alerts & Quick Info ──────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Low Stock Alerts --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm">
                <div class="px-5 py-4 border-b border-neutral-900/60 flex items-center justify-between">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Low Stock Alerts</span>
                    @if ($lowStockProducts->count() > 0)
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    @endif
                </div>
                <div class="px-5 py-3 space-y-2 max-h-48 overflow-y-auto">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between py-1.5">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-neutral-300 truncate">{{ $product->name }}</p>
                                <p class="text-[10px] font-mono text-neutral-600">{{ $product->sku }}</p>
                            </div>
                            <span class="text-xs font-mono {{ $product->stock === 0 ? 'text-rose-400' : 'text-amber-500' }} ml-3 shrink-0">
                                {{ $product->stock }} left
                            </span>
                        </div>
                    @empty
                        <p class="text-[10px] text-neutral-600 font-light py-2">All products well stocked ✓</p>
                    @endforelse
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm">
                <div class="px-5 py-4 border-b border-neutral-900/60">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Payment Summary</span>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs text-neutral-400">Completed</span>
                        </div>
                        <span class="text-sm font-mono text-white">{{ $completedPayments }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-xs text-neutral-400">Pending</span>
                        </div>
                        <span class="text-sm font-mono text-white">{{ $pendingPayments }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="text-xs text-neutral-400">Failed</span>
                        </div>
                        <span class="text-sm font-mono text-white">{{ $failedPayments }}</span>
                    </div>
                </div>
            </div>

            {{-- Top Selling Products --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm">
                <div class="px-5 py-4 border-b border-neutral-900/60">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Top Sellers</span>
                </div>
                <div class="px-5 py-3 space-y-2">
                    @forelse ($topProducts->take(3) as $index => $product)
                        <div class="flex items-center justify-between py-1.5">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <span class="text-[10px] font-mono {{ $index === 0 ? 'text-amber-500' : 'text-neutral-600' }}">
                                    #{{ $index + 1 }}
                                </span>
                                <p class="text-xs text-neutral-300 truncate">{{ $product->name }}</p>
                            </div>
                            <span class="text-[10px] font-mono text-neutral-500 ml-3 shrink-0">
                                {{ $product->total_sold }} sold
                            </span>
                        </div>
                    @empty
                        <p class="text-[10px] text-neutral-600 font-light py-2">No sales data yet</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         ROW 4 — ORDER PIPELINE VISUALIZATION
         ═══════════════════════════════════════════════════════════════════════ --}}
    @php
        $pipelineStatuses = ['pending', 'approved', 'processing', 'delivered'];
        $pipelineTotal    = collect($pipelineStatuses)->sum(fn($s) => $ordersByStatus[$s] ?? 0);
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

            @php
                $pipelineConfig = [
                    'pending'    => ['icon' => '◷', 'color' => 'amber',   'label' => 'Pending'],
                    'approved'   => ['icon' => '✓', 'color' => 'blue',    'label' => 'Approved'],
                    'processing' => ['icon' => '⟳', 'color' => 'violet',  'label' => 'Processing'],
                    'delivered'  => ['icon' => '✦', 'color' => 'emerald', 'label' => 'Delivered'],
                ];
            @endphp

            @foreach ($pipelineConfig as $status => $cfg)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $pct   = $pipelineTotal > 0 ? round(($count / $pipelineTotal) * 100) : 0;

                    $colorMap = [
                        'amber'   => ['ring' => 'ring-amber-800/50',   'bg' => 'bg-amber-950/30',   'text' => 'text-amber-400',   'icon' => 'text-amber-500'],
                        'blue'    => ['ring' => 'ring-blue-800/50',    'bg' => 'bg-blue-950/30',    'text' => 'text-blue-400',    'icon' => 'text-blue-500'],
                        'violet'  => ['ring' => 'ring-violet-800/50',  'bg' => 'bg-violet-950/30',  'text' => 'text-violet-400',  'icon' => 'text-violet-500'],
                        'emerald' => ['ring' => 'ring-emerald-800/50', 'bg' => 'bg-emerald-950/30', 'text' => 'text-emerald-400', 'icon' => 'text-emerald-500'],
                    ];
                    $c = $colorMap[$cfg['color']];
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
