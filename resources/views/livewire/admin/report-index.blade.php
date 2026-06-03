<div class="min-h-screen text-neutral-100 font-sans antialiased">
    <style>
        @keyframes draw-line {
            from { stroke-dashoffset: 1000; }
            to { stroke-dashoffset: 0; }
        }
        .animate-chart-line {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: draw-line 2.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        @keyframes grow-vertical {
            from { height: 0%; }
            to { height: var(--target-height); }
        }
        .animate-grow-vertical {
            animation: grow-vertical 1.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }
    </style>

    {{-- Header --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">Reports & Business Intelligence</h1>
                <p class="text-xs text-neutral-500 font-light mt-1">Review operational performance metrics, revenue growth rates, and branch hub analytics.</p>
            </div>
            
            {{-- Date Range Filters --}}
            <div class="flex items-center space-x-3 bg-[#0F0F12] border border-neutral-900 rounded px-4 py-2">
                <div class="flex items-center space-x-2">
                    <span class="text-[9px] uppercase tracking-wider font-mono text-neutral-500">From</span>
                    <input 
                        wire:model.live="startDate" 
                        type="date" 
                        class="bg-[#0A0A0A] border border-neutral-800 rounded px-2.5 py-1 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                </div>
                <span class="text-neutral-700">&bull;</span>
                <div class="flex items-center space-x-2">
                    <span class="text-[9px] uppercase tracking-wider font-mono text-neutral-500">To</span>
                    <input 
                        wire:model.live="endDate" 
                        type="date" 
                        class="bg-[#0A0A0A] border border-neutral-800 rounded px-2.5 py-1 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        {{-- Card 1 --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Fulfillment Revenue</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ number_format($totalSales) }} <span class="text-xs text-neutral-600">KSH</span></span>
        </div>
        
        {{-- Card 2 --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Total Orders</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ $orderCount }} <span class="text-xs text-neutral-600">REQS</span></span>
        </div>

        {{-- Card 3 --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Average Ticket Size</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ number_format($avgTicket) }} <span class="text-xs text-neutral-600">KSH</span></span>
        </div>

        {{-- Card 4 --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block">Active Client Nodes</span>
            <span class="text-2xl font-light text-white font-mono mt-3 block">{{ $activeClients }}</span>
        </div>
    </div>

    {{-- Reports Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Report 1: Sales Trend (Line Chart) --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white">Daily Revenue Flow</h3>
                    <p class="text-[10px] text-neutral-500 font-light mt-0.5">Value trajectory plotted across the designated period.</p>
                </div>
            </div>

            @php
                $maxTotal = max(1, $dailySalesTrend->max('total') ?? 1);
                $pointsCount = $dailySalesTrend->count();
                $points = [];
                $path = '';
                $fillPath = '';
                
                if ($pointsCount > 1) {
                    foreach ($dailySalesTrend as $index => $trend) {
                        $x = ($index / ($pointsCount - 1)) * 500;
                        $y = 150 - (($trend->total / $maxTotal) * 120); // 150px height viewport, offsets
                        $points[] = "$x,$y";
                    }
                    $path = "M " . implode(" L ", $points);
                    $fillPath = $path . " L 500,150 L 0,150 Z";
                }
            @endphp

            <div class="relative w-full pt-4">
                @if ($pointsCount > 1)
                    <svg class="w-full h-48" viewBox="0 0 500 150" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chart-fill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#D4AF37" stop-opacity="0.15"/>
                                <stop offset="100%" stop-color="#D4AF37" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        
                        <!-- Area Fill -->
                        <path d="{{ $fillPath }}" fill="url(#chart-fill)"></path>
                        
                        <!-- Line drawing -->
                        <path d="{{ $path }}" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-chart-line"></path>
                    </svg>
                    
                    {{-- Grid bounds helper --}}
                    <div class="flex justify-between text-[8px] font-mono text-neutral-600 mt-2">
                        <span>{{ Carbon\Carbon::parse($startDate)->format('d M') }}</span>
                        <span>Mid Period</span>
                        <span>{{ Carbon\Carbon::parse($endDate)->format('d M') }}</span>
                    </div>
                @else
                    <div class="h-48 border border-dashed border-neutral-900 flex flex-col items-center justify-center text-neutral-600 font-mono text-xs">
                        <span>Insufficient ledger records to plot trend.</span>
                        <span class="text-[10px] text-neutral-700 mt-1">Try expanding the range of tracking dates.</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Report 2: Category Breakdown & Hub Volumes --}}
        <div class="space-y-6">
            
            {{-- Category Breakdown (Stacked Chart) --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
                <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white mb-4">Curation Categories</h3>
                
                @php
                    $catTotal = array_sum($categoryBreakdown);
                    $retailPct = $catTotal > 0 ? (int) (($categoryBreakdown['retail'] / $catTotal) * 100) : 0;
                    $wholesalePct = $catTotal > 0 ? (int) (($categoryBreakdown['wholesale'] / $catTotal) * 100) : 0;
                    $giftingPct = $catTotal > 0 ? (int) (($categoryBreakdown['gifting'] / $catTotal) * 100) : 0;
                @endphp

                <div class="space-y-4">
                    {{-- Stacked Progress bar --}}
                    <div class="h-2.5 w-full bg-neutral-950 rounded-sm overflow-hidden flex">
                        <div class="h-full bg-neutral-300 transition-all duration-500" style="width: {{ $retailPct }}%" title="Retail: {{ $retailPct }}%"></div>
                        <div class="h-full bg-amber-500 transition-all duration-500" style="width: {{ $wholesalePct }}%" title="Wholesale: {{ $wholesalePct }}%"></div>
                        <div class="h-full bg-rose-700 transition-all duration-500" style="width: {{ $giftingPct }}%" title="Gifting: {{ $giftingPct }}%"></div>
                    </div>

                    {{-- Legend & values --}}
                    <div class="space-y-2 font-mono text-xs">
                        <div class="flex justify-between items-center text-neutral-300">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-neutral-300"></span>
                                <span>Retail Showroom</span>
                            </div>
                            <span class="text-white">{{ number_format($categoryBreakdown['retail']) }} KSH <span class="text-neutral-500 text-[10px]">({{ $retailPct }}%)</span></span>
                        </div>
                        
                        <div class="flex justify-between items-center text-neutral-300">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Wholesale Stems</span>
                            </div>
                            <span class="text-white">{{ number_format($categoryBreakdown['wholesale']) }} KSH <span class="text-neutral-500 text-[10px]">({{ $wholesalePct }}%)</span></span>
                        </div>

                        <div class="flex justify-between items-center text-neutral-300">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-rose-700"></span>
                                <span>Custom Hampers</span>
                            </div>
                            <span class="text-white">{{ number_format($categoryBreakdown['gifting']) }} KSH <span class="text-neutral-500 text-[10px]">({{ $giftingPct }}%)</span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hub Performance (Comparison Columns) --}}
            <div class="bg-[#0F0F12] border border-neutral-900 rounded p-6">
                <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white mb-4">Hub Fulfillment Comparison</h3>
                
                @php
                    $maxHubRev = max(1, $hubPerformance->max('revenue'));
                @endphp

                <div class="flex items-end justify-around h-32 pt-4">
                    @foreach ($hubPerformance as $hub)
                        @php
                            $pct = (int) (($hub->revenue / $maxHubRev) * 100);
                        @endphp
                        <div class="flex flex-col items-center group w-1/3">
                            <span class="text-[9px] font-mono text-neutral-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 mb-2">
                                {{ number_format($hub->revenue / 1000, 1) }}k
                            </span>
                            
                            {{-- Vertical Column --}}
                            <div class="w-8 bg-neutral-900 border border-neutral-800 rounded-t-sm relative overflow-hidden h-24 flex items-end">
                                <div 
                                    class="w-full bg-amber-500/80 border-t border-amber-400/50 animate-grow-vertical" 
                                    style="--target-height: {{ $pct }}%; height: 0%;"
                                ></div>
                            </div>
                            
                            <span class="text-[10px] font-mono uppercase tracking-wider text-white mt-3">{{ $hub->code }}</span>
                            <span class="text-[8px] text-neutral-500 font-light mt-0.5 truncate max-w-[80px]" title="{{ $hub->name }}">{{ $hub->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    {{-- Corporate Profit & Loss (P&L) Statement --}}
    <div class="mt-8 bg-[#0F0F12] border border-neutral-900 rounded p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-neutral-900 pb-4">
            <div>
                <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white">Corporate Profit & Loss (P&L) Statement</h3>
                <p class="text-[10px] text-neutral-500 font-light mt-0.5">Summary of operational revenues, cost margins, and net profitability metrics.</p>
            </div>
            <button 
                wire:click="exportPL" 
                class="bg-amber-500 hover:bg-amber-400 text-neutral-950 px-4 py-2 rounded text-[10px] font-mono uppercase tracking-wider font-semibold shadow-md transition-all duration-300 flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-3.5 h-3.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4m4-5l5 5 5-5m-5 5V3"></path>
                </svg>
                <span>Export P&L CSV</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 font-mono text-xs">
            <div class="bg-[#0A0A0A] border border-neutral-900 rounded p-4 flex flex-col justify-between">
                <div>
                    <span class="text-neutral-500 block uppercase tracking-wider text-[9px]">Gross Revenue</span>
                    <span class="text-neutral-400 text-[10px] font-light block mt-0.5">Total approved sales volume</span>
                </div>
                <div class="text-lg text-emerald-400 font-semibold mt-4">
                    KES {{ number_format($totalSales) }}
                </div>
            </div>

            <div class="bg-[#0A0A0A] border border-neutral-900 rounded p-4 flex flex-col justify-between">
                <div>
                    <span class="text-neutral-500 block uppercase tracking-wider text-[9px]">Cost of Goods Sold (COGS)</span>
                    <span class="text-neutral-400 text-[10px] font-light block mt-0.5">Product units acquisition cost</span>
                </div>
                <div class="text-lg text-rose-500 font-semibold mt-4">
                    - KES {{ number_format($cogs) }}
                </div>
            </div>

            <div class="bg-[#0A0A0A] border border-neutral-900 rounded p-4 flex flex-col justify-between">
                <div>
                    <span class="text-neutral-500 block uppercase tracking-wider text-[9px]">Inventory Wastage</span>
                    <span class="text-neutral-400 text-[10px] font-light block mt-0.5">Spoiled/damaged product costs</span>
                </div>
                <div class="text-lg text-rose-500 font-semibold mt-4">
                    - KES {{ number_format($wastage) }}
                </div>
            </div>

            <div class="bg-[#0A0A0A] border border-neutral-900 rounded p-4 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute right-0 top-0 h-16 w-16 bg-gradient-to-bl from-amber-500/5 to-transparent pointer-events-none"></div>
                <div>
                    <span class="text-neutral-500 block uppercase tracking-wider text-[9px]">Net Operating Profit</span>
                    <span class="text-neutral-400 text-[10px] font-light block mt-0.5">Residual corporate earnings</span>
                </div>
                <div class="text-lg font-bold mt-4 {{ $netProfit >= 0 ? 'text-[#D4AF37]' : 'text-red-500' }}">
                    KES {{ number_format($netProfit) }}
                </div>
            </div>
        </div>

        {{-- Profitability Margin Progress Bar --}}
        @php
            $profitMargin = $totalSales > 0 ? (int)(($netProfit / $totalSales) * 100) : 0;
            $progressColor = $profitMargin >= 30 ? 'bg-emerald-500' : ($profitMargin >= 10 ? 'bg-amber-500' : 'bg-rose-500');
        @endphp
        <div class="mt-6 bg-[#0A0A0A] border border-neutral-900 rounded p-4">
            <div class="flex justify-between items-center text-[10px] font-mono text-neutral-400 mb-2">
                <span>Atelier Net Profit Margin Indicator</span>
                <span class="font-bold {{ $profitMargin >= 0 ? 'text-emerald-400' : 'text-rose-500' }}">{{ $profitMargin }}% Margin</span>
            </div>
            <div class="w-full bg-neutral-950 h-2 rounded overflow-hidden">
                <div class="h-full {{ $progressColor }} transition-all duration-1000" style="width: {{ max(0, min(100, $profitMargin)) }}%"></div>
            </div>
            <div class="flex justify-between text-[8px] font-mono text-neutral-600 mt-1">
                <span>0% Break-Even</span>
                <span>25% Target Margin</span>
                <span>50%+ Elite Tier</span>
            </div>
        </div>
    </div>
</div>
