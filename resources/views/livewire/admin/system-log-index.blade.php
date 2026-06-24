<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-medium tracking-wide uppercase text-text-primary flex items-center space-x-2">
                <span>System Auditing Log</span>
                <span class="text-[10px] tracking-[0.2em] font-mono px-2.5 py-0.5 rounded-full bg-neutral-900 border border-neutral-800 text-neutral-450 uppercase font-normal">
                    {{ $logs->total() }} entries
                </span>
            </h2>
            <p class="text-xs text-text-secondary mt-1">Audit log database captures active operations, auth events, payments, and compliance webhooks.</p>
        </div>
        
        <div>
            <button 
                onclick="confirm('Are you absolutely sure you want to truncate the system logs history? This operation is irreversible.') || event.stopImmediatePropagation()"
                wire:click="clearLogs" 
                class="px-4 py-2 bg-rose-950/20 hover:bg-rose-950/40 border border-rose-900/40 text-rose-300 rounded-lg text-xs font-mono uppercase tracking-widest transition-all cursor-pointer shadow-sm"
            >
                Truncate History Logs
            </button>
        </div>
    </div>

    <!-- Alert Success Flash Banner -->
    @if (session()->has('success'))
        <div class="bg-emerald-950/30 border border-emerald-900/50 text-emerald-300 px-4 py-3 rounded-xl text-xs font-light tracking-wide">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter toolbar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-bg-card border border-border-base/50 p-4 rounded-2xl shadow-sm">
        <!-- Search -->
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-text-secondary mb-1.5">Search logs</label>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search message, category, IP address..." 
                class="w-full bg-neutral-950 border border-neutral-900 rounded-lg px-3 py-2 text-xs font-light text-white focus:outline-none focus:border-amber-500 transition-colors"
            >
        </div>

        <!-- Level Filter -->
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-text-secondary mb-1.5">Filter Level</label>
            <select 
                wire:model.live="levelFilter" 
                class="w-full bg-neutral-950 border border-neutral-900 rounded-lg px-3 py-2 text-xs font-light text-white focus:outline-none focus:border-amber-500 transition-colors"
            >
                <option value="all">All Levels</option>
                <option value="info">Info</option>
                <option value="warning">Warning</option>
                <option value="error">Error</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-text-secondary mb-1.5">Filter Category</label>
            <select 
                wire:model.live="categoryFilter" 
                class="w-full bg-neutral-950 border border-neutral-900 rounded-lg px-3 py-2 text-xs font-light text-white focus:outline-none focus:border-amber-500 transition-colors"
            >
                <option value="all">All Categories</option>
                <option value="auth">Auth Events</option>
                <option value="payment">Payments</option>
                <option value="order">Orders</option>
                <option value="etims">eTIMS Compliance</option>
                <option value="system">System/General</option>
            </select>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-bg-card border border-border-base/50 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-light font-sans border-collapse">
                <thead>
                    <tr class="border-b border-border-base bg-neutral-900/20 text-neutral-400 font-mono text-[9px] uppercase tracking-wider">
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Message</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-base/40">
                    @forelse ($logs as $log)
                        @php
                            $lvlCls = match ($log->level) {
                                'error' => 'bg-red-950/40 text-red-400 border-red-900/40',
                                'warning' => 'bg-amber-950/40 text-amber-400 border-amber-900/40',
                                default => 'bg-blue-950/40 text-blue-400 border-blue-900/40'
                            };
                            $catCls = match ($log->category) {
                                'auth' => 'bg-emerald-950/20 text-emerald-400 border-emerald-900/30',
                                'payment' => 'bg-purple-950/20 text-purple-400 border-purple-900/30',
                                'order' => 'bg-teal-950/20 text-teal-400 border-teal-900/30',
                                'etims' => 'bg-rose-950/20 text-rose-400 border-rose-900/30',
                                default => 'bg-neutral-900/50 text-neutral-400 border-neutral-800'
                            };
                        @endphp
                        <tr x-data="{ expanded: false }" class="hover:bg-neutral-900/5 transition-colors">
                            <td class="py-3 px-4 font-mono text-[10px] text-neutral-500 whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md font-mono font-bold {{ $lvlCls }}">
                                    {{ $log->level }}
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md font-mono {{ $catCls }}">
                                    {{ $log->category }}
                                </span>
                            </td>
                            <td class="py-3 px-4 max-w-sm truncate text-text-primary" title="{{ $log->message }}">
                                {{ $log->message }}
                            </td>
                            <td class="py-3 px-4 font-mono text-[10px] text-neutral-500">
                                {{ $log->ip_address ?: 'System (CLI)' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                @if ($log->context)
                                     <button 
                                         @click="expanded = !expanded" 
                                         class="px-2.5 py-1 border border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white rounded text-[9px] font-mono uppercase tracking-wider cursor-pointer bg-transparent transition-all"
                                     >
                                        <span x-text="expanded ? 'Hide' : 'Context'"></span>
                                    </button>
                                @else
                                    <span class="text-neutral-600 text-[10px] font-mono">-</span>
                                @endif
                            </td>
                        </tr>
                        @if ($log->context)
                            <tr x-show="expanded" style="display: none;" class="bg-neutral-950/30">
                                <td colspan="6" class="p-4 font-mono text-[10px] text-neutral-400 border-t border-b border-border-base/50">
                                    <pre class="overflow-x-auto whitespace-pre-wrap max-w-4xl bg-neutral-950 border border-neutral-900 p-3 rounded-lg">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-text-secondary text-xs">
                                No system log entries found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
