<div
    x-data="{ showPanel: @entangle('showDetail') }"
    class="space-y-8"
>
    {{-- ─── Page Header ─── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] font-mono text-neutral-600">Client Registry</p>
            <h2 class="text-xl font-light tracking-wide text-white mt-1">Client Management</h2>
            <p class="text-xs text-neutral-500 font-light mt-1">Manage your corporate and individual client accounts across all regions.</p>
        </div>
        
        <button
            wire:click="openCreateModal"
            class="flex items-center space-x-2 bg-white text-black px-4 py-2.5 text-xs font-mono uppercase tracking-wider rounded-sm hover:bg-neutral-200 transition-colors"
        >
            <span>+ Add Client</span>
        </button>
    </div>

    {{-- ─── Stats Row ─── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Clients --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Clients</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($totalClients) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">All registered accounts</p>
        </div>

        {{-- Corporate Accounts --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Corporate Accounts</p>
            <div class="flex items-center space-x-3 mt-2">
                <p class="text-2xl font-light text-white">{{ number_format($corporateClients) }}</p>
                <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm bg-amber-950/40 text-amber-400 border border-amber-900/30 font-mono">KRA</span>
            </div>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">With KRA PIN on file</p>
        </div>

        {{-- Nairobi Region --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Nairobi Region</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($nairobiClients) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Metro accounts</p>
        </div>

        {{-- Kiambu Region --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Kiambu Region</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($kiambuClients) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Greater Kiambu</p>
        </div>
    </div>

    {{-- ─── Filters Bar ─── --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
            {{-- Search --}}
            <div class="relative flex-1 w-full lg:max-w-sm group">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search clients (e.g. type:corporate spent>5000)..."
                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm pl-9 pr-3 py-2 text-xs text-white placeholder-neutral-600 focus:outline-none focus:border-neutral-700 focus:ring-1 focus:ring-amber-500/20 transition-all duration-300 font-mono"
                />
                <div class="absolute left-3 top-2.5 flex items-center justify-center pointer-events-none">
                    <svg wire:loading.remove wire:target="search" class="w-3.5 h-3.5 text-neutral-600 transition-all duration-300 group-focus-within:text-amber-500 group-focus-within:scale-115" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <svg wire:loading wire:target="search" class="w-3.5 h-3.5 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            {{-- Type Filter --}}
            <div class="flex items-center space-x-1">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mr-2">Type</span>
                @foreach (['all' => 'All', 'corporate' => 'Corporate', 'individual' => 'Individual'] as $value => $label)
                    <button
                        wire:click="$set('typeFilter', '{{ $value }}')"
                        class="px-3 py-1.5 text-[10px] uppercase tracking-[0.15em] font-mono rounded-sm transition-colors
                            {{ $typeFilter === $value
                                ? 'bg-white/10 text-white border border-neutral-700'
                                : 'text-neutral-500 hover:text-neutral-300 border border-transparent hover:border-neutral-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Region Filter --}}
            <div class="flex items-center space-x-1">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mr-2">Region</span>
                @foreach (['all' => 'All', 'Nairobi' => 'Nairobi', 'Kiambu' => 'Kiambu'] as $value => $label)
                    <button
                        wire:click="$set('regionFilter', '{{ $value }}')"
                        class="px-3 py-1.5 text-[10px] uppercase tracking-[0.15em] font-mono rounded-sm transition-colors
                            {{ $regionFilter === $value
                                ? 'bg-white/10 text-white border border-neutral-700'
                                : 'text-neutral-500 hover:text-neutral-300 border border-transparent hover:border-neutral-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Segment Filter --}}
            <div class="flex items-center space-x-1">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mr-2">Segment</span>
                @foreach (['all' => 'All', 'vip' => 'VIP', 'lapsed' => 'Lapsed', 'active' => 'Active', 'new' => 'New'] as $value => $label)
                    <button
                        wire:click="$set('segmentFilter', '{{ $value }}')"
                        class="px-3 py-1.5 text-[10px] uppercase tracking-[0.15em] font-mono rounded-sm transition-colors
                            {{ $segmentFilter === $value
                                ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30'
                                : 'text-neutral-500 hover:text-neutral-300 border border-transparent hover:border-neutral-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>


    {{-- ─── Client Table ─── --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50 text-neutral-500">
                        <th wire:click="sortBy('contact_name')" class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors">
                            <div class="flex items-center space-x-1">
                                <span>Client</span>
                                @if ($sortField === 'contact_name')
                                    <span class="text-amber-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="p-5 font-medium">Contact</th>
                        <th wire:click="sortBy('region')" class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors">
                            <div class="flex items-center space-x-1">
                                <span>Region</span>
                                @if ($sortField === 'region')
                                    <span class="text-amber-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="p-5 font-medium">Orders</th>
                        <th class="p-5 font-medium">Total Spent</th>
                        <th class="p-5 font-medium">Type</th>
                        <th class="p-5 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($clients as $client)
                        <tr
                            wire:click="viewClient({{ $client->id }})"
                            class="hover:bg-neutral-900/30 transition-colors cursor-pointer"
                        >
                            {{-- Client Name --}}
                            <td class="p-5">
                                <span class="text-white font-normal block">{{ $client->contact_name }}</span>
                                @if ($client->company_name)
                                    <span class="text-[11px] text-neutral-500 block mt-0.5">{{ $client->company_name }}</span>
                                @endif
                            </td>

                            {{-- Contact --}}
                            <td class="p-5">
                                <span class="text-neutral-300 text-xs block">{{ $client->email }}</span>
                                <span class="text-neutral-500 text-xs font-mono block mt-0.5">{{ $client->phone }}</span>
                            </td>

                            {{-- Region --}}
                            <td class="p-5">
                                @if ($client->region)
                                    <span class="text-[10px] tracking-[0.15em] uppercase px-2 py-1 rounded-sm font-mono
                                        {{ $client->region === 'Nairobi'
                                            ? 'bg-emerald-950/30 text-emerald-400 border border-emerald-900/30'
                                            : 'bg-blue-950/30 text-blue-400 border border-blue-900/30' }}">
                                        {{ $client->region }}
                                    </span>
                                @else
                                    <span class="text-neutral-600 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Orders --}}
                            <td class="p-5 font-mono text-xs text-neutral-300">
                                {{ $client->orders_count }}
                            </td>

                            {{-- Total Spent --}}
                            <td class="p-5 font-mono text-xs">
                                <span class="text-white">Ksh {{ number_format($client->total_spent ?? 0) }}</span>
                            </td>

                            {{-- Type Badge --}}
                            <td class="p-5">
                                @if (!empty($client->kra_pin))
                                    <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm font-mono bg-amber-950/40 text-amber-400 border border-amber-900/30">
                                        Corporate
                                    </span>
                                @else
                                    <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm font-mono bg-neutral-900/60 text-neutral-500 border border-neutral-800">
                                        Individual
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="p-5" wire:click.stop>
                                <div class="flex items-center space-x-3">
                                    <button
                                        wire:click="viewClient({{ $client->id }})"
                                        class="text-[10px] tracking-[0.15em] uppercase font-mono text-neutral-500 hover:text-amber-400 transition-colors"
                                    >
                                        View
                                    </button>
                                    <button
                                        wire:click="openEditModal({{ $client->id }})"
                                        class="text-[10px] tracking-[0.15em] uppercase font-mono text-neutral-500 hover:text-white transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        wire:click="confirmDelete({{ $client->id }})"
                                        class="text-[10px] tracking-[0.15em] uppercase font-mono text-neutral-500 hover:text-rose-400 transition-colors"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-neutral-500 font-light">
                                No clients found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($clients->hasPages())
            <div class="border-t border-neutral-900 px-5 py-4">
                {{ $clients->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    {{-- ─── Client Detail Slide-out Panel ─── --}}
    {{-- Backdrop --}}
    <div
        x-show="showPanel"
        x-transition:enter="transition-opacity duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$wire.closeDetail()"
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm"
        x-cloak
    ></div>

    {{-- Panel --}}
    <div
        x-show="showPanel"
        x-transition:enter="transition-transform duration-300 ease-out"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-50 w-full max-w-lg overflow-y-auto bg-[#0C0C0E] border-l border-neutral-900 shadow-2xl"
        x-cloak
    >
        @if ($selectedClient)
            <div class="p-6 lg:p-8 space-y-6">
                {{-- Panel Header --}}
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-light text-white tracking-wide">{{ $selectedClient->contact_name }}</h3>
                        @if ($selectedClient->company_name)
                            <p class="text-xs text-neutral-500 mt-0.5">{{ $selectedClient->company_name }}</p>
                        @endif
                        <div class="mt-2">
                            @if (!empty($selectedClient->kra_pin))
                                <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm font-mono bg-amber-950/40 text-amber-400 border border-amber-900/30">
                                    Corporate
                                </span>
                            @else
                                <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm font-mono bg-neutral-900/60 text-neutral-500 border border-neutral-800">
                                    Individual
                                </span>
                            @endif
                        </div>
                    </div>
                    <button
                        wire:click="closeDetail"
                        class="text-neutral-500 hover:text-white transition-colors p-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Client Info Card --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 space-y-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 border-b border-neutral-900 pb-2">Client Information</p>

                    <div class="grid grid-cols-2 gap-4">
                        @if ($selectedClient->company_name)
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Company</p>
                                <p class="text-sm text-neutral-200 mt-0.5">{{ $selectedClient->company_name }}</p>
                            </div>
                        @endif

                        @if ($selectedClient->kra_pin)
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">KRA PIN</p>
                                <p class="text-sm text-neutral-200 font-mono mt-0.5">{{ $selectedClient->kra_pin }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Contact Name</p>
                            <p class="text-sm text-neutral-200 mt-0.5">{{ $selectedClient->contact_name }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Email</p>
                            <p class="text-sm text-neutral-200 mt-0.5">{{ $selectedClient->email }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Phone</p>
                            <p class="text-sm text-neutral-200 font-mono mt-0.5">{{ $selectedClient->phone }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Region</p>
                            <p class="text-sm text-neutral-200 mt-0.5">{{ $selectedClient->region ?? '—' }}</p>
                        </div>
                    </div>

                    @if ($selectedClient->delivery_address)
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Delivery Address</p>
                            <p class="text-sm text-neutral-200 mt-0.5">{{ $selectedClient->delivery_address }}</p>
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 text-center">
                        <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Total Spent</p>
                        <p class="text-sm font-mono text-white mt-1">Ksh {{ number_format($selectedClient->total_spent) }}</p>
                    </div>
                    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 text-center">
                        <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Orders</p>
                        <p class="text-sm font-mono text-white mt-1">{{ $selectedClient->order_count }}</p>
                    </div>
                    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4 text-center">
                        <p class="text-[10px] uppercase tracking-[0.15em] font-mono text-neutral-600">Last Order</p>
                        <p class="text-xs font-mono text-neutral-300 mt-1">
                            {{ $selectedClient->last_order_date ? \Carbon\Carbon::parse($selectedClient->last_order_date)->format('d M Y') : '—' }}
                        </p>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5 space-y-3">
                    <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 border-b border-neutral-900 pb-2">Recent Orders</p>

                    @forelse ($selectedClient->orders as $order)
                        <div class="border border-neutral-900/50 rounded-sm p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="font-mono text-xs text-white font-medium">#NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-950/40 text-amber-400 border-amber-900/30',
                                            'approved' => 'bg-blue-950/40 text-blue-400 border-blue-900/30',
                                            'processing' => 'bg-purple-950/40 text-purple-400 border-purple-900/30',
                                            'delivered' => 'bg-emerald-950/40 text-emerald-400 border-emerald-900/30',
                                            'cancelled' => 'bg-neutral-900/60 text-neutral-500 border-neutral-800',
                                        ];
                                        $colorClass = $statusColors[$order->status] ?? $statusColors['pending'];
                                    @endphp
                                    <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm font-mono border {{ $colorClass }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <span class="font-mono text-xs text-white">Ksh {{ number_format($order->total_amount) }}</span>
                            </div>

                            <p class="text-[10px] text-neutral-600 font-mono">{{ $order->created_at->format('d M Y · H:i') }}</p>

                            {{-- Products Summary --}}
                            @if ($order->products->isNotEmpty())
                                <div class="space-y-0.5">
                                    @foreach ($order->products as $product)
                                        <p class="text-[11px] text-neutral-400">
                                            <span class="font-mono text-neutral-600">[{{ $product->pivot->quantity }}x]</span>
                                            {{ $product->name }}
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-neutral-600 text-center py-4">No orders on record.</p>
                    @endforelse
                </div>

                {{-- Account Meta --}}
                <div class="text-center pt-2 border-t border-neutral-900/50">
                    <p class="text-[10px] font-mono text-neutral-700">
                        Account Created: {{ $selectedClient->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Create/Edit Client Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-lg p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-6">{{ $isEditing ? 'Edit Client Record' : 'Add New Client' }}</h3>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Contact Name</label>
                            <input 
                                wire:model="contact_name" 
                                type="text" 
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('contact_name') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Company Name</label>
                            <input 
                                wire:model="company_name" 
                                type="text" 
                                placeholder="Optional"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('company_name') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Email Address</label>
                            <input 
                                wire:model="email" 
                                type="email" 
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('email') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Phone Number</label>
                            <input 
                                wire:model="phone" 
                                type="text" 
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('phone') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">KRA PIN</label>
                            <input 
                                wire:model="kra_pin" 
                                type="text" 
                                placeholder="Optional"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('kra_pin') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Region</label>
                            <select 
                                wire:model="region" 
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-neutral-400 focus:outline-none focus:border-neutral-600 font-mono cursor-pointer"
                            >
                                <option value="Nairobi">Nairobi</option>
                                <option value="Kiambu">Kiambu</option>
                            </select>
                            @error('region') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-1">Delivery Address</label>
                        <textarea 
                            wire:model="delivery_address" 
                            rows="3" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                        ></textarea>
                        @error('delivery_address') <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="resetForm"
                            class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >
                            Reset
                        </button>
                        <button 
                            type="button" 
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-white text-black px-6 py-2 text-xs font-mono uppercase tracking-wider rounded-sm hover:bg-neutral-200 transition-colors"
                        >
                            Save Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Client Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-sm p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
                <h3 class="text-base font-light tracking-widest text-white uppercase mb-4">Delete Client Record</h3>
                <p class="text-xs text-neutral-400 font-light leading-relaxed mb-6">
                    Are you sure you want to permanently delete this client? Linked orders will remain but will be set as unassigned to client.
                </p>

                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="deleteClient"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 text-xs font-mono uppercase tracking-wider rounded-sm transition-colors"
                    >
                        Delete Client
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
