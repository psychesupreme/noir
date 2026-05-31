<div 
    x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }" 
    class="space-y-8"
>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] font-mono text-neutral-600">Marketing & CRM Node</p>
            <h2 class="text-xl font-light tracking-wide text-white mt-1">Campaign Broadcasts</h2>
            <p class="text-xs text-neutral-500 font-light mt-1">Create, schedule, and execute Email and SMS marketing broadcasts for customer outreach.</p>
        </div>
        <button 
            wire:click="openCreateModal" 
            class="sm:self-start bg-white text-black hover:bg-neutral-200 transition-colors px-4 py-2 text-[10px] uppercase tracking-[0.2em] font-mono rounded-sm cursor-pointer select-none"
        >
            + Create Campaign
        </button>
    </div>

    {{-- Alert Messages --}}
    @if (session()->has('message'))
        <div class="bg-emerald-950/30 border border-emerald-900/40 text-emerald-400 p-4 rounded-sm text-xs font-mono flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button class="hover:text-white" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Campaigns --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Total Campaigns</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($totalCampaigns) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Drafts and broadcasts</p>
        </div>

        {{-- Active Schedules --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Active Schedules</p>
            <div class="flex items-center space-x-3 mt-2">
                <p class="text-2xl font-light text-white">{{ number_format($scheduledCampaigns) }}</p>
                <span class="animate-pulse w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Pending future send</p>
        </div>

        {{-- Email Sent --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">Email Deliveries</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($emailSent) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Simulated email runs</p>
        </div>

        {{-- SMS Sent --}}
        <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-5">
            <p class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500">SMS Deliveries</p>
            <p class="text-2xl font-light text-white mt-2">{{ number_format($smsSent) }}</p>
            <p class="text-[10px] text-neutral-600 font-mono mt-1">Simulated SMS dispatches</p>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm p-4">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
            {{-- Search --}}
            <div class="relative flex-1 w-full lg:max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-neutral-600 text-xs">⌕</span>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search campaigns..." 
                    class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm pl-8 pr-3 py-2 text-sm text-white placeholder-neutral-600 focus:outline-none focus:border-neutral-700 transition-colors"
                />
            </div>

            {{-- Channel Filter --}}
            <div class="flex items-center space-x-1">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mr-2">Channel</span>
                @foreach (['all' => 'All', 'email' => 'Email', 'sms' => 'SMS'] as $value => $label)
                    <button 
                        wire:click="$set('channelFilter', '{{ $value }}')"
                        class="px-3 py-1.5 text-[10px] uppercase tracking-[0.15em] font-mono rounded-sm transition-colors 
                            {{ $channelFilter === $value 
                                ? 'bg-white/10 text-white border border-neutral-700' 
                                : 'text-neutral-500 hover:text-neutral-300 border border-transparent hover:border-neutral-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Status Filter --}}
            <div class="flex items-center space-x-1">
                <span class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 mr-2">Status</span>
                @foreach (['all' => 'All', 'draft' => 'Draft', 'scheduled' => 'Scheduled', 'sent' => 'Sent'] as $value => $label)
                    <button 
                        wire:click="$set('statusFilter', '{{ $value }}')"
                        class="px-3 py-1.5 text-[10px] uppercase tracking-[0.15em] font-mono rounded-sm transition-colors 
                            {{ $statusFilter === $value 
                                ? 'bg-white/10 text-white border border-neutral-700' 
                                : 'text-neutral-500 hover:text-neutral-300 border border-transparent hover:border-neutral-800' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Campaign List Table --}}
    <div class="bg-[#0F0F12] border border-neutral-900 rounded-sm overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral-900 text-[10px] uppercase tracking-[0.2em] bg-[#0A0A0A]/50 text-neutral-500">
                        <th class="p-5 font-medium">Campaign</th>
                        <th class="p-5 font-medium">Channel</th>
                        <th class="p-5 font-medium">Scheduled / Sent Date</th>
                        <th class="p-5 font-medium">Status</th>
                        <th class="p-5 font-medium">Outreach Count</th>
                        <th class="p-5 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/60 text-sm font-light">
                    @forelse ($campaigns as $campaign)
                        <tr class="hover:bg-neutral-900/30 transition-colors">
                            {{-- Title / Subject --}}
                            <td class="p-5">
                                <span class="text-white font-normal block">{{ $campaign->title }}</span>
                                @if ($campaign->subject)
                                    <span class="text-[11px] text-neutral-500 block mt-0.5">{{ $campaign->subject }}</span>
                                @endif
                            </td>

                            {{-- Channel --}}
                            <td class="p-5 font-mono text-xs">
                                <span class="text-[10px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm 
                                    {{ $campaign->channel === 'email' 
                                        ? 'bg-blue-950/30 text-blue-400 border border-blue-900/30' 
                                        : 'bg-purple-950/30 text-purple-400 border border-purple-900/30' }}">
                                    {{ $campaign->channel }}
                                </span>
                            </td>

                            {{-- Scheduled/Sent Date --}}
                            <td class="p-5 text-neutral-300 font-mono text-xs">
                                {{ $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i') : 'Immediate' }}
                            </td>

                            {{-- Status --}}
                            <td class="p-5 font-mono text-xs">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-neutral-900/60 text-neutral-500 border-neutral-800',
                                        'scheduled' => 'bg-amber-950/40 text-amber-400 border-amber-900/30',
                                        'sent' => 'bg-emerald-950/40 text-emerald-400 border-emerald-900/30',
                                    ];
                                    $colorClass = $statusColors[$campaign->status] ?? $statusColors['draft'];
                                @endphp
                                <span class="text-[9px] tracking-[0.15em] uppercase px-2 py-0.5 rounded-sm border {{ $colorClass }}">
                                    {{ $campaign->status }}
                                </span>
                            </td>

                            {{-- Outreach --}}
                            <td class="p-5 font-mono text-xs text-neutral-300">
                                {{ $campaign->sent_count > 0 ? number_format($campaign->sent_count) . ' clients' : '—' }}
                            </td>

                            {{-- Actions --}}
                            <td class="p-5">
                                <div class="flex items-center space-x-4">
                                    @if($campaign->status !== 'sent')
                                        <button 
                                            wire:click="openEditModal({{ $campaign->id }})" 
                                            class="text-[10px] tracking-[0.15em] uppercase font-mono text-neutral-500 hover:text-white transition-colors"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="triggerSend({{ $campaign->id }})" 
                                            class="text-[10px] tracking-[0.15em] uppercase font-mono text-amber-500 hover:text-amber-400 transition-colors"
                                        >
                                            Send Now
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="confirmDelete({{ $campaign->id }})" 
                                        class="text-[10px] tracking-[0.15em] uppercase font-mono text-rose-500/80 hover:text-rose-400 transition-colors"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-neutral-500 font-light">
                                No campaigns registered in this CRM node.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($campaigns->hasPages())
            <div class="border-t border-neutral-900 px-5 py-4">
                {{ $campaigns->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    <div 
        x-show="showModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md"
        style="display: none;"
    >
        <div 
            @click.away="showModal = false" 
            class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-xl shadow-2xl"
        >
            <div class="border-b border-neutral-900 p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm uppercase tracking-[0.2em] text-white">
                        {{ $isEditing ? 'Modify Campaign Log' : 'Create Outreach Campaign' }}
                    </h3>
                    <span class="text-[9px] text-neutral-500 font-mono">CRM Broadcast Configuration Node</span>
                </div>
                <button 
                    @click="showModal = false" 
                    class="text-neutral-500 hover:text-white font-mono text-[10px] uppercase cursor-pointer"
                >
                    ✕
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-4 text-xs">
                {{-- Title --}}
                <div class="space-y-1">
                    <label class="text-[9px] uppercase tracking-[0.15em] font-mono text-neutral-500">Campaign Title</label>
                    <input 
                        wire:model="title" 
                        type="text" 
                        placeholder="e.g. Valentine's VIP Roses Outbox"
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 transition-colors"
                    />
                    @error('title') <span class="text-rose-400 font-mono text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Channel --}}
                    <div class="space-y-1">
                        <label class="text-[9px] uppercase tracking-[0.15em] font-mono text-neutral-500">Outreach Channel</label>
                        <select 
                            wire:model.live="channel"
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 transition-colors"
                        >
                            <option value="email">Email Broadcast</option>
                            <option value="sms">SMS Dispatch</option>
                        </select>
                        @error('channel') <span class="text-rose-400 font-mono text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Scheduled At --}}
                    <div class="space-y-1">
                        <label class="text-[9px] uppercase tracking-[0.15em] font-mono text-neutral-500">Schedule Launch (Optional)</label>
                        <input 
                            wire:model="scheduled_at" 
                            type="datetime-local" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 transition-colors"
                        />
                        @error('scheduled_at') <span class="text-rose-400 font-mono text-[10px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Subject (Only for Email) --}}
                @if ($channel === 'email')
                    <div class="space-y-1">
                        <label class="text-[9px] uppercase tracking-[0.15em] font-mono text-neutral-500">Email Subject Line</label>
                        <input 
                            wire:model="subject" 
                            type="text" 
                            placeholder="e.g. Exclusive Premium Naivasha Bloom Curations Just for You"
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 transition-colors"
                        />
                        @error('subject') <span class="text-rose-400 font-mono text-[10px]">{{ $message }}</span> @enderror
                    </div>
                @endif

                {{-- Content --}}
                <div class="space-y-1">
                    <label class="text-[9px] uppercase tracking-[0.15em] font-mono text-neutral-500">Campaign Content Body</label>
                    <textarea 
                        wire:model="content" 
                        rows="6"
                        placeholder="Write your email body or SMS message copy here..."
                        class="w-full bg-[#0A0A0A] border border-neutral-800 rounded-sm px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 transition-colors resize-none"
                    ></textarea>
                    @error('content') <span class="text-rose-400 font-mono text-[10px]">{{ $message }}</span> @enderror
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-900">
                    <button 
                        type="button" 
                        @click="showModal = false"
                        class="px-4 py-2 border border-neutral-850 text-neutral-400 hover:text-white rounded-sm font-mono text-[10px] uppercase tracking-wider transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-white text-black hover:bg-neutral-200 rounded-sm font-mono text-[10px] uppercase tracking-wider transition-colors cursor-pointer"
                    >
                        Save Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div 
        x-show="showDeleteModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md"
        style="display: none;"
    >
        <div class="bg-[#0F0F12] border border-neutral-800 rounded-sm w-full max-w-sm p-6 shadow-2xl">
            <h3 class="text-sm uppercase tracking-[0.2em] text-white">Delete Campaign Outreach?</h3>
            <p class="text-neutral-400 mt-2 font-light text-[11px]">This operation is irreversible. The campaign record will be permanently purged.</p>
            <div class="flex items-center justify-end space-x-3 mt-6">
                <button 
                    type="button"
                    @click="showDeleteModal = false"
                    wire:click="$set('showDeleteModal', false)"
                    class="px-3 py-1.5 border border-neutral-850 text-neutral-400 hover:text-white rounded-sm font-mono text-[10px] uppercase cursor-pointer"
                >
                    Cancel
                </button>
                <button 
                    wire:click="deleteCampaign"
                    class="px-3 py-1.5 bg-rose-700 hover:bg-rose-600 text-white rounded-sm font-mono text-[10px] uppercase cursor-pointer"
                >
                    Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>
