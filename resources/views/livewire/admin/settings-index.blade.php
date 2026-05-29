<div class="min-h-screen text-neutral-100 font-sans antialiased">
    <style>
        .cog-icon {
            transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .cog-container:hover .cog-icon {
            transform: rotate(90deg);
        }
    </style>

    {{-- Header --}}
    <div class="mb-10 cog-container">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center space-x-4">
                {{-- Rotating Cog Icon --}}
                <div class="p-2.5 bg-[#0F0F12] border border-neutral-900 rounded-sm">
                    <svg class="w-6 h-6 text-amber-500 cog-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extralight tracking-tight text-white uppercase">System Settings & Controls</h1>
                    <p class="text-xs text-neutral-500 font-light mt-1">Configure global application defaults, manage Safaricom endpoints, and assign system access roles.</p>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 px-4 py-2 text-xs font-mono rounded-sm animate-pulse">
                    ✓ {{ session('message') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Tabs Navigation Row --}}
    <div class="flex border-b border-neutral-900 mb-10 text-xs uppercase tracking-widest font-mono">
        <button 
            wire:click="$set('activeTab', 'general')"
            class="px-6 py-3 border-b-2 transition-all duration-300 {{ $activeTab === 'general' ? 'border-amber-500 text-white' : 'border-transparent text-neutral-500 hover:text-neutral-300' }}"
        >
            General Defaults
        </button>
        <button 
            wire:click="$set('activeTab', 'credentials')"
            class="px-6 py-3 border-b-2 transition-all duration-300 {{ $activeTab === 'credentials' ? 'border-amber-500 text-white' : 'border-transparent text-neutral-500 hover:text-neutral-300' }}"
        >
            Integration APIs
        </button>
        <button 
            wire:click="$set('activeTab', 'users')"
            class="px-6 py-3 border-b-2 transition-all duration-300 {{ $activeTab === 'users' ? 'border-amber-500 text-white' : 'border-transparent text-neutral-500 hover:text-neutral-300' }}"
        >
            Access & Roles
        </button>
    </div>

    {{-- Tabs Content --}}
    <div class="max-w-3xl">

        {{-- Tab 1: General Settings --}}
        @if ($activeTab === 'general')
            <div class="bg-[#0F0F12] border border-neutral-900 rounded p-8 animate-in fade-in slide-in-from-bottom-2 duration-300">
                <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white mb-6">Global ERP Configurations</h3>
                
                <form wire:submit.prevent="saveGeneral" class="space-y-6">
                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Application Identifier</label>
                        <input 
                            wire:model="appName" 
                            type="text" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                        >
                        @error('appName') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">Standard VAT Rate (%)</label>
                            <input 
                                wire:model="defaultTaxRate" 
                                type="number" 
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                            >
                            @error('defaultTaxRate') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">M-Pesa Operating Mode</label>
                            <select 
                                wire:model="mpesaEnv"
                                class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-neutral-400 focus:outline-none focus:border-neutral-600 font-mono cursor-pointer"
                            >
                                <option value="sandbox">Sandbox / Staging</option>
                                <option value="production">Active Production</option>
                            </select>
                            @error('mpesaEnv') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-white text-black text-xs font-mono uppercase tracking-widest px-6 py-2.5 rounded hover:bg-neutral-200 transition-colors"
                        >
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Tab 2: Credentials Settings --}}
        @if ($activeTab === 'credentials')
            <div class="bg-[#0F0F12] border border-neutral-900 rounded p-8 animate-in fade-in slide-in-from-bottom-2 duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white">M-Pesa API integration vault</h3>
                    
                    {{-- Masking toggle --}}
                    <button 
                        wire:click="$toggle('maskCredentials')"
                        class="text-[9px] font-mono tracking-widest uppercase text-neutral-500 hover:text-white transition-colors"
                    >
                        {{ $maskCredentials ? '[ Show secrets ]' : '[ Hide secrets ]' }}
                    </button>
                </div>

                <form wire:submit.prevent="saveCredentials" class="space-y-6">
                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">M-Pesa Consumer Key</label>
                        <input 
                            wire:model="mpesaKey" 
                            type="{{ $maskCredentials ? 'password' : 'text' }}" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                        >
                        @error('mpesaKey') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">M-Pesa Consumer Secret</label>
                        <input 
                            wire:model="mpesaSecret" 
                            type="{{ $maskCredentials ? 'password' : 'text' }}" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                        >
                        @error('mpesaSecret') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] uppercase tracking-[0.2em] font-mono text-neutral-500 block mb-2">M-Pesa LNM Passkey</label>
                        <input 
                            wire:model="mpesaPasskey" 
                            type="{{ $maskCredentials ? 'password' : 'text' }}" 
                            class="w-full bg-[#0A0A0A] border border-neutral-800 rounded px-4 py-2.5 text-xs text-white focus:outline-none focus:border-neutral-600 font-mono"
                        >
                        @error('mpesaPasskey') <span class="text-[10px] text-rose-500 font-mono mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-white text-black text-xs font-mono uppercase tracking-widest px-6 py-2.5 rounded hover:bg-neutral-200 transition-colors"
                        >
                            Save Credentials
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Tab 3: Access Roles Manager --}}
        @if ($activeTab === 'users')
            <div class="bg-[#0F0F12] border border-neutral-900 rounded p-8 animate-in fade-in slide-in-from-bottom-2 duration-300 max-w-full">
                <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-xs font-mono uppercase tracking-[0.2em] text-white">System user directory</h3>
                        <p class="text-[10px] text-neutral-500 font-light mt-0.5">Manage permissions and modify administrative/customer role tiers.</p>
                    </div>

                    {{-- User search bar --}}
                    <div class="relative group">
                        <input 
                            wire:model.live.debounce.250ms="userSearch" 
                            type="text" 
                            placeholder="Filter users..."
                            class="bg-[#0A0A0A] border border-neutral-850 rounded pl-8 pr-3 py-1.5 text-xs text-white focus:outline-none focus:border-neutral-700 w-44 transition-colors font-mono"
                        >
                        <svg class="absolute left-2.5 top-2.5 w-3.5 h-3.5 text-neutral-600 group-focus-within:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                </div>

                {{-- User list table --}}
                <div class="border border-neutral-950 rounded-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-950 text-neutral-500 text-[9px] uppercase tracking-[0.2em] bg-[#0A0A0A]/30 font-mono">
                                <th class="p-4">Staff Member / Email</th>
                                <th class="p-4">Contact Phone</th>
                                <th class="p-4">Designated Access Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-950 text-xs font-mono">
                            @forelse ($users as $user)
                                <tr class="hover:bg-neutral-950/20">
                                    {{-- User info --}}
                                    <td class="p-4">
                                        <span class="text-white block font-medium">{{ $user->name }}</span>
                                        <span class="text-neutral-500 text-[10px] block mt-0.5">{{ $user->email }}</span>
                                    </td>

                                    {{-- Phone --}}
                                    <td class="p-4 text-neutral-400">
                                        {{ $user->phone_number ?: 'No Phone' }}
                                    </td>

                                    {{-- Role badge + update --}}
                                    <td class="p-4">
                                        <select 
                                            wire:change="updateUserRole({{ $user->id }}, $event.target.value)"
                                            class="bg-[#0A0A0A] border border-neutral-850 rounded-sm px-2 py-1.5 text-[11px] text-neutral-300 focus:outline-none focus:border-neutral-700 cursor-pointer w-full font-mono uppercase tracking-wider"
                                        >
                                            @foreach ($availableRoles as $role)
                                                <option value="{{ $role->value }}" {{ $user->account_tier === $role ? 'selected' : '' }}>
                                                    {{ $role->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-neutral-600 font-mono">
                                        No registered user accounts match search context.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 font-mono text-[10px]">
                    {{ $users->links() }}
                </div>
            </div>
        @endif

    </div>
</div>
