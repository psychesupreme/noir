<div class="min-h-screen text-neutral-100 font-sans antialiased">

    {{-- ─── Top Bar ─── --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            {{-- Title + Stats --}}
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <h2 class="text-2xl font-extralight tracking-tight text-white">Fulfillment Hubs & Branches</h2>
                    <span class="text-[10px] font-mono tracking-wider bg-neutral-800 text-neutral-400 px-2.5 py-1 rounded-sm">
                        {{ $totalBranches }} total
                    </span>
                    <span class="text-[10px] font-mono tracking-wider bg-emerald-950/40 text-emerald-400 border border-emerald-900/30 px-2.5 py-1 rounded-sm">
                        {{ $activeBranches }} active
                    </span>
                </div>
                <p class="text-xs text-neutral-500 font-light">Manage physical flower sourcing hubs, distribution centers, and retail boutiques.</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search hubs..."
                        class="bg-[#0A0A0A] border border-neutral-800 rounded-sm pl-9 pr-4 py-2 text-sm text-white placeholder-neutral-600 focus:outline-none focus:border-neutral-600 w-56 transition-colors"
                    >
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>

                {{-- Status Filter --}}
                <select
                    wire:model.live="statusFilter"
                    class="bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-neutral-400 focus:outline-none focus:border-neutral-600 cursor-pointer"
                >
                    <option value="all">All Statuses</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>

                {{-- Add Branch --}}
                <button
                    wire:click="openCreateModal"
                    class="flex items-center space-x-2 bg-white text-black px-4 py-2 text-xs font-medium tracking-wider uppercase rounded-sm hover:bg-neutral-200 transition-colors"
                >
                    <span class="text-base leading-none">+</span>
                    <span>Add Hub</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Branches Table ─── --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50 text-neutral-500">
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('name')">
                            <span class="flex items-center space-x-1">
                                <span>Hub Name</span>
                                @if ($sortField === 'name')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('code')">
                            <span class="flex items-center space-x-1">
                                <span>Branch Code</span>
                                @if ($sortField === 'code')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('location_city')">
                            <span class="flex items-center space-x-1">
                                <span>City/Location</span>
                                @if ($sortField === 'location_city')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium cursor-pointer hover:text-neutral-300 transition-colors" wire:click="sortBy('is_active')">
                            <span class="flex items-center space-x-1">
                                <span>Status</span>
                                @if ($sortField === 'is_active')
                                    <span class="text-amber-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </span>
                        </th>
                        <th class="p-5 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($branches as $branch)
                        <tr class="hover:bg-neutral-900/20 transition-colors" wire:key="branch-{{ $branch->id }}">
                            {{-- Name --}}
                            <td class="p-5">
                                <span class="text-white font-normal block">{{ $branch->name }}</span>
                            </td>

                            {{-- Code --}}
                            <td class="p-5">
                                <span class="text-xs text-neutral-400 font-mono uppercase tracking-wider">{{ $branch->code }}</span>
                            </td>

                            {{-- City --}}
                            <td class="p-5">
                                <span class="text-xs text-neutral-300">{{ $branch->location_city }}</span>
                            </td>

                            {{-- Status toggle --}}
                            <td class="p-5">
                                <button
                                    wire:click="toggleStatus({{ $branch->id }})"
                                    class="inline-flex items-center space-x-1.5 focus:outline-none"
                                >
                                    @if ($branch->is_active)
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-[9px] font-mono text-emerald-400 uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-neutral-600"></span>
                                        <span class="text-[9px] font-mono text-neutral-500 uppercase tracking-wider bg-neutral-900 border border-neutral-800 px-2 py-0.5 rounded">Inactive</span>
                                    @endif
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-2">
                                    <button
                                        wire:click="openEditModal({{ $branch->id }})"
                                        class="px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider text-neutral-400 bg-neutral-900/50 border border-neutral-800 rounded-sm hover:text-white hover:border-neutral-600 transition-colors"
                                    >Edit</button>
                                    <button
                                        wire:click="confirmDelete({{ $branch->id }})"
                                        class="px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider text-rose-500/60 bg-rose-950/20 border border-rose-900/20 rounded-sm hover:text-rose-400 hover:border-rose-800/40 transition-colors"
                                    >Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center">
                                <div class="text-neutral-600">
                                    <span class="text-3xl block mb-3">🏪</span>
                                    <span class="text-sm font-light">No fulfillment hubs found in the system registry.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($branches->hasPages())
            <div class="border-t border-neutral-900 px-6 py-4">
                {{ $branches->links() }}
            </div>
        @endif
    </div>

    {{-- ─── Create/Edit Modal ─── --}}
    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            x-data="{ open: @entangle('showModal') }"
            x-show="open"
            x-cloak
        >
            {{-- Overlay --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                wire:click="$set('showModal', false)"
            ></div>

            {{-- Modal Content --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-lg mx-4 bg-[#0F0F12] border border-neutral-800 rounded-sm shadow-2xl max-h-[90vh] overflow-y-auto"
            >
                {{-- Header --}}
                <div class="border-b border-neutral-900 px-6 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-white tracking-wider uppercase">
                            {{ $isEditing ? 'Edit Hub Settings' : 'New Fulfillment Hub' }}
                        </h3>
                        <p class="text-[10px] text-neutral-600 font-mono tracking-wider mt-1">
                            {{ $isEditing ? 'UPDATE LOCATION METADATA' : 'PROVISION NEW BRANCH NODE' }}
                        </p>
                    </div>
                    <button
                        wire:click="$set('showModal', false)"
                        class="text-neutral-600 hover:text-white transition-colors text-lg"
                    >&times;</button>
                </div>

                {{-- Form --}}
                <form wire:submit="save" class="p-6 space-y-5">
                    {{-- Hub Name --}}
                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2 font-semibold">Hub / Branch Name *</label>
                        <input
                            wire:model="name"
                            type="text"
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                            placeholder="e.g. Westlands Atelier Hub"
                        >
                        @error('name') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Hub Code & City --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2 font-semibold font-mono">Branch Code *</label>
                            <input
                                wire:model="code"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors font-mono"
                                placeholder="e.g. NB-WLD"
                            >
                            @error('code') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2 font-semibold">Location / City *</label>
                            <input
                                wire:model="location_city"
                                type="text"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-600 transition-colors"
                                placeholder="e.g. Nairobi"
                            >
                            @error('location_city') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Active Checkbox --}}
                    <div class="flex items-center space-x-2 pt-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer group">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="rounded-sm border-neutral-700 bg-[#0A0A0A] text-amber-500 focus:ring-amber-500/30 focus:ring-offset-0 cursor-pointer"
                            >
                            <span class="text-xs text-neutral-400 group-hover:text-neutral-200 transition-colors">Mark Hub as Active (Enabled for order assignment)</span>
                        </label>
                        @error('is_active') <span class="text-rose-400 text-[10px] font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-900">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-5 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-sm hover:bg-neutral-200 transition-colors"
                        >
                            {{ $isEditing ? 'Update Hub' : 'Create Hub' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ─── Delete Confirmation Modal ─── --}}
    @if ($showDeleteModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            x-data="{ open: @entangle('showDeleteModal') }"
            x-show="open"
            x-cloak
        >
            {{-- Overlay --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                wire:click="$set('showDeleteModal', false)"
            ></div>

            {{-- Modal Content --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-md mx-4 bg-[#0F0F12] border border-neutral-800 rounded-sm shadow-2xl"
            >
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-sm bg-rose-950/30 border border-rose-900/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-rose-400 text-sm">⚠</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-white tracking-wide">Decommission Hub</h3>
                            <p class="text-xs text-neutral-500 mt-2 leading-relaxed">
                                This fulfillment branch will be permanently removed. This action cannot be undone. Make sure all pending assignments are routed to another hub before deleting.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-neutral-900">
                        <button
                            wire:click="$set('showDeleteModal', false)"
                            class="px-5 py-2 text-xs font-mono uppercase tracking-wider text-neutral-500 hover:text-white transition-colors"
                        >Cancel</button>
                        <button
                            wire:click="deleteBranch"
                            class="px-5 py-2 bg-rose-600 text-white text-xs font-medium uppercase tracking-wider rounded-sm hover:bg-rose-500 transition-colors"
                        >Delete Hub</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
