@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

<div 
    x-data="{ 
        theme: localStorage.getItem('nb_theme') || 'onyx'
    }" 
    x-init="
        $watch('theme', val => { 
            localStorage.setItem('nb_theme', val); 
            document.documentElement.className = val; 
            document.documentElement.setAttribute('data-theme', val);
            const bgColors = {
                'onyx': '#050507',
                'champagne': '#FAF7F0',
                'rose': '#15060A'
            };
            const textColors = {
                'onyx': '#F4F4F5',
                'champagne': '#1C1917',
                'rose': '#FCE7EC'
            };
            document.documentElement.style.backgroundColor = bgColors[val];
            document.documentElement.style.color = textColors[val];
        });
    "
    :class="{
        'bg-[#050507] text-[#F4F4F5]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne',
        'bg-[#15060A] text-[#FCE7EC]': theme === 'rose'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-hidden"
>
    <!-- 3D Flower Ambient Animation Canvas -->
    <canvas id="flower-ambient-canvas" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Fine Grain Noise Overlay -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-80"></div>
    
    <!-- Cohesive Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne',
            'bg-[#15060A]/75 border-[#2D0D19]/30 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group"
    >
        <!-- Gold Accent Bottom Glow Line -->
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-[#C5A880]/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 select-none cursor-pointer">
                <span class="text-[11px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
                <span :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
            </a>
            
            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Theme Switcher Pill -->
                <div class="hidden lg:flex items-center space-x-1 border border-neutral-500/10 rounded-full bg-neutral-500/5 p-1 select-none">
                    <button @click="theme = 'onyx'" :class="theme === 'onyx' ? 'bg-[#C5A880] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Onyx</span>
                    </button>
                    <button @click="theme = 'champagne'" :class="theme === 'champagne' ? 'bg-[#B59A7A] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Champagne</span>
                    </button>
                    <button @click="theme = 'rose'" :class="theme === 'rose' ? 'bg-[#B76E79] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Rose</span>
                    </button>
                </div>

                <!-- Return to Shop -->
                <a href="/" class="hover:text-neutral-300 transition-colors flex items-center space-x-1.5 text-xs font-mono font-medium tracking-wider">
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Back to Shop</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-8xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col lg:flex-row gap-8 z-10 relative">
        
        <!-- Left Sidebar Navigation -->
        <aside 
            :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" 
            class="w-full lg:w-64 shrink-0 sticky top-28 p-6 border rounded-[32px] backdrop-blur-md space-y-6 text-left transition-all duration-500 shadow-sm self-start h-auto"
        >
            <div class="border-b border-neutral-500/10 pb-4">
                <span class="text-[12px] font-mono uppercase tracking-[0.2em] text-neutral-500 block">Workspace Profile</span>
                <h4 :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="text-sm font-semibold uppercase tracking-wider mt-1">Dashboard Portal</h4>
            </div>

            <!-- Tab Buttons -->
            <div class="space-y-1.5">
                <span class="text-[11px] font-mono uppercase tracking-widest text-neutral-500 block mb-2">Member Profiles</span>
                <button wire:click="setTab('client')" 
                        :class="activeTab === 'client' ? 'bg-neutral-500/10 text-[#C5A880] font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                        class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                    </svg>
                    <span>Client Portal</span>
                </button>
                <button wire:click="setTab('partner')" 
                        :class="activeTab === 'partner' ? 'bg-neutral-500/10 text-[#C5A880] font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                        class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <span>Partner Circle</span>
                </button>
                <button wire:click="setTab('logistics')" 
                        :class="activeTab === 'logistics' ? 'bg-neutral-500/10 text-[#C5A880] font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                        class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <path d="M10 17l5-5-5-5M13.8 12H3M21 3v18" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Logistics Hub</span>
                </button>
            </div>

            <!-- Quick Admin ERP Access if admin or staff -->
            @if(auth()->user()->isStaff())
                <div class="border-t border-neutral-500/10 pt-4 space-y-3">
                    <span class="text-[11px] font-mono uppercase tracking-widest text-[#C5A880] block font-bold">Admin Controls</span>
                    <a href="/admin" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full mt-2 py-2 rounded-xl text-[11px] font-mono uppercase tracking-wider font-semibold transition-all cursor-pointer text-center block shadow-sm">
                        Admin ERP Dashboard
                    </a>
                </div>
            @endif

            <!-- Sign Out -->
            <div class="border-t border-neutral-500/10 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-neutral-300 py-2.5 text-[10px] font-mono uppercase tracking-[0.2em] rounded-full transition-all cursor-pointer">
                        [ Sign Out ]
                    </button>
                </form>
            </div>
        </aside>

        <!-- Right Content Area -->
        <div class="flex-1 w-full space-y-8 mb-20 text-left">
            
            <!-- CLIENT PORTAL TAB -->
            @if($activeTab === 'client')
                <div class="space-y-6">
                    <!-- Title -->
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Client Personal Ledger</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Configure profile settings, view loyalty circle status, and manage purchase records.</p>
                    </div>

                    <!-- Loyalty Stats Header Card -->
                    <div :class="theme === 'champagne' ? 'bg-neutral-100 text-neutral-900 border-neutral-200' : 'bg-neutral-900/30 text-white border-neutral-900'" class="border p-6 rounded-3xl grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono uppercase text-neutral-500 tracking-wider">Account Member</span>
                            <span class="text-lg font-medium block">{{ $user->name }}</span>
                            <span class="text-xs text-neutral-400 font-mono block">{{ $user->email }}</span>
                        </div>
                        <div class="space-y-1 font-mono">
                            <span class="text-[10px] uppercase text-neutral-500 tracking-wider">Atelier Loyalty Points</span>
                            <span class="text-2xl text-[#C5A880] font-bold block">{{ number_format($user->loyalty_points) }} PTS</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono uppercase text-neutral-500 tracking-wider">Loyalty Circle Tier</span>
                            <span class="bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono text-[10px] uppercase tracking-widest px-3 py-1 rounded-full inline-block">
                                {{ $user->loyalty_tier }} Member
                            </span>
                        </div>
                    </div>

                    <!-- Profile Form & Address Configuration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Form -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] space-y-4">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Personal Details</h4>
                            
                            @if(session('success_profile'))
                                <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_profile') }}</div>
                            @endif

                            <form wire:submit.prevent="updateProfile" class="space-y-4 text-xs font-mono">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Full Name</label>
                                    <input type="text" wire:model="name" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                                    @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Email Address</label>
                                    <input type="email" wire:model="email" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                                    @error('email') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Phone Line</label>
                                    <input type="text" wire:model="phone_number" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">eTIMS KRA PIN</label>
                                    <input type="text" wire:model="kra_pin" placeholder="A000000000Z" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm uppercase">
                                    @error('kra_pin') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Hub Region</label>
                                    <select wire:model="default_region" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                                        <option value="Nairobi">Nairobi</option>
                                        <option value="Kiambu">Kiambu</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Delivery Coordinates/Landmark</label>
                                    <input type="text" wire:model="default_delivery_address" placeholder="Estate, Complex, Street Name" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500 text-sm">
                                    @error('default_delivery_address') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-full transition-all duration-300 font-bold uppercase cursor-pointer">
                                    Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Update Password Form -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] space-y-4 self-start">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Security Protocol</h4>
                            
                            @if(session('success_password'))
                                <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl mb-3">{{ session('success_password') }}</div>
                            @elseif(session('error_password'))
                                <div class="p-3 border border-dashed border-rose-850 bg-rose-950/20 text-rose-400 text-xs font-mono rounded-xl mb-3">{{ session('error_password') }}</div>
                            @endif

                            <form wire:submit.prevent="updatePassword" class="space-y-4 text-xs font-mono">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Current Password</label>
                                    <input type="password" wire:model="current_password" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">New Password</label>
                                    <input type="password" wire:model="new_password" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500">
                                    @error('new_password') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Confirm New Password</label>
                                    <input type="password" wire:model="new_password_confirmation" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500">
                                </div>
                                <button type="submit" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-full transition-all duration-300 font-bold uppercase cursor-pointer">
                                    Update Security Key
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order History Logs -->
                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] space-y-4">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Curation Purchase Records Matrix</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs font-mono">
                                <thead>
                                    <tr class="text-neutral-500 border-b border-neutral-500/10">
                                        <th class="py-3 px-2">Order ID</th>
                                        <th class="py-3 px-2">Timestamp</th>
                                        <th class="py-3 px-2">Items Curation</th>
                                        <th class="py-3 px-2">Status</th>
                                        <th class="py-3 px-2 text-right">Sum Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userOrders as $ord)
                                        <tr class="border-b border-neutral-500/5 hover:bg-neutral-500/5 transition-colors">
                                            <td class="py-4 px-2 text-white font-semibold">#NB-ORD-{{ str_pad($ord->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td class="py-4 px-2 text-neutral-450">{{ $ord->created_at->format('d M Y H:i') }}</td>
                                            <td class="py-4 px-2 text-neutral-400">
                                                @foreach($ord->products as $p)
                                                    {{ $p->pivot->quantity }}x {{ $p->name }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="py-4 px-2">
                                                @php
                                                    $stCls = match($ord->status) {
                                                        'pending' => 'text-amber-400 bg-amber-950/20 border-amber-900/40',
                                                        'approved' => 'text-blue-400 bg-blue-950/20 border-blue-900/40',
                                                        'processing' => 'text-violet-400 bg-violet-950/20 border-violet-900/40',
                                                        'delivered' => 'text-emerald-400 bg-emerald-950/20 border-emerald-900/40',
                                                        default => 'text-neutral-400 bg-neutral-950/20 border-neutral-900/40'
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md {{ $stCls }} font-bold">
                                                    {{ $ord->status }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right text-amber-500 font-semibold">{{ number_format($ord->total_amount) }} KSH</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-neutral-500 font-light">No order records cataloged. Browse the storefront catalog to place your first dispatch request.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- PARTNER TAB -->
            @if($activeTab === 'partner')
                <div class="space-y-6">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Grower & Florist Circle</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Submit grower wholesale logistics partnerships and view discounted bulk catalog rates.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Bulk discounts catalog info -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] lg:col-span-2 space-y-4 self-start">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Partner Bulk Stems Wholesale Rates</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs font-mono">
                                    <thead>
                                        <tr class="text-neutral-500 border-b border-neutral-500/10">
                                            <th class="py-2.5">Stem Type</th>
                                            <th class="py-2.5">Pack Standard Volume</th>
                                            <th class="py-2.5 text-right">Standard Rate</th>
                                            <th class="py-2.5 text-right">Partner Rate (-20%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b border-neutral-500/5">
                                            <td class="py-3 text-white">Naomi Red Rose Stems</td>
                                            <td class="py-3 text-neutral-400">100 Stems Bundle</td>
                                            <td class="py-3 text-right">25,000 KSH</td>
                                            <td class="py-3 text-right text-emerald-500 font-semibold">20,000 KSH</td>
                                        </tr>
                                        <tr class="border-b border-neutral-500/5">
                                            <td class="py-3 text-white">White Gypsophila Million Star</td>
                                            <td class="py-3 text-neutral-400">50 Stems Bundle</td>
                                            <td class="py-3 text-right">9,000 KSH</td>
                                            <td class="py-3 text-right text-emerald-500 font-semibold">7,200 KSH</td>
                                        </tr>
                                        <tr class="border-b border-neutral-500/5">
                                            <td class="py-3 text-white">Bell-shaped Clematis Amazing</td>
                                            <td class="py-3 text-neutral-400">40 Stems Bundle</td>
                                            <td class="py-3 text-right">12,800 KSH</td>
                                            <td class="py-3 text-right text-emerald-500 font-semibold">10,240 KSH</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Inquiry form -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] space-y-4">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Partner Application</h4>
                            
                            @if($partnerSubmitted)
                                <div class="p-4 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-2xl">
                                    <span class="block font-bold text-center mb-1">INQUIRY RECEIVED</span>
                                    Sarah Lavoine Odhiambo (Design Lead) will coordinate logistics parameters via secure channels inside 24 business hours.
                                </div>
                            @else
                                <form wire:submit.prevent="submitPartnerRequest" class="space-y-4 text-xs font-mono">
                                    <div class="space-y-1.5">
                                        <label class="text-neutral-500 uppercase">Grower/Company Legal Name</label>
                                        <input type="text" wire:model="partner_company" placeholder="e.g. Rift Valley Growers Ltd" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500">
                                        @error('partner_company') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-neutral-500 uppercase">Product Specialization Interest</label>
                                        <select wire:model="partner_product_interest" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-neutral-500">
                                            <option value="wholesale_stems">Wholesale Bulk Stem Supply</option>
                                            <option value="event_supply">Strategic Event Floristry Contracts</option>
                                            <option value="florist_partner">Bespoke Florist Partner Network</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-neutral-500 uppercase">Cover Proposal Message</label>
                                        <textarea rows="4" wire:model="partner_message" placeholder="Details of grower capacity, stem grades, and delivery logic..." :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F12] border-neutral-800 text-white'" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:border-neutral-500"></textarea>
                                        @error('partner_message') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-full transition-all duration-300 font-bold uppercase cursor-pointer">
                                        Submit Partner Application
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- LOGISTICS TAB -->
            @if($activeTab === 'logistics')
                <div class="space-y-6">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Drivers & Riders Dispatch</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Manage dispatch runs, retrieve coordinates, and update live M-Pesa order delivery coordinates.</p>
                    </div>

                    @if(session('success_logistics'))
                        <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_logistics') }}</div>
                    @endif

                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="border p-6 rounded-[32px] space-y-4">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Assigned Dispatch Runs Sheet</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs font-mono">
                                <thead>
                                    <tr class="text-neutral-500 border-b border-neutral-500/10">
                                        <th class="py-2.5 px-2">Order Run</th>
                                        <th class="py-2.5 px-2">Recipient / Contact</th>
                                        <th class="py-2.5 px-2">Delivery Anchor Coordinates</th>
                                        <th class="py-2.5 px-2">Delivery Details</th>
                                        <th class="py-2.5 px-2">Current Status</th>
                                        <th class="py-2.5 px-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignedRuns as $run)
                                        <tr class="border-b border-neutral-500/5 hover:bg-neutral-500/5 transition-colors">
                                            <td class="py-4 px-2 text-white font-semibold">#NB-ORD-{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td class="py-4 px-2 text-neutral-300">
                                                @if($run->is_gift)
                                                    <span class="text-amber-500 font-bold block">[GIFT RECIPIENT]</span>
                                                    <span class="block">{{ $run->recipient_name }}</span>
                                                    <span class="text-[10px] text-neutral-400 block">{{ $run->recipient_phone }}</span>
                                                @else
                                                    <span class="block">{{ $run->client->contact_name }}</span>
                                                    <span class="text-[10px] text-neutral-400 block">{{ $run->client->phone }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-2 text-neutral-300">
                                                <span class="bg-neutral-500/10 border border-neutral-500/20 px-2 py-0.5 rounded text-[10px] text-amber-500 block mb-1 text-center font-bold">
                                                    {{ $run->client->region }} Metrop.
                                                </span>
                                                <span class="text-xs leading-relaxed block max-w-xs">{{ $run->client->delivery_address }}</span>
                                            </td>
                                            <td class="py-4 px-2 text-neutral-450">
                                                <span class="block">Packaging: {{ str_replace('Delivery Package: ', '', $run->special_instructions ?? 'STANDARD') }}</span>
                                                <span class="text-[10px] text-neutral-500 block mt-0.5">
                                                    @foreach($run->products as $p)
                                                        {{ $p->pivot->quantity }}x {{ $p->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </td>
                                            <td class="py-4 px-2">
                                                @php
                                                    $runCls = match($run->status) {
                                                        'pending' => 'text-amber-400 bg-amber-950/20 border-amber-900/40',
                                                        'approved' => 'text-blue-400 bg-blue-950/20 border-blue-900/40',
                                                        'processing' => 'text-violet-400 bg-violet-950/20 border-violet-900/40',
                                                        'delivered' => 'text-emerald-400 bg-emerald-950/20 border-emerald-900/40',
                                                        default => 'text-neutral-400 bg-neutral-950/20 border-neutral-900/40'
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md {{ $runCls }} font-bold">
                                                    {{ $run->status }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right">
                                                <div class="flex items-center justify-end space-x-1.5">
                                                    @if($run->status !== 'processing' && $run->status !== 'delivered')
                                                        <button wire:click="updateLogisticsStatus({{ $run->id }}, 'processing')" class="bg-violet-850 hover:bg-violet-750 text-white px-2.5 py-1 rounded text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer">
                                                            Dispatch
                                                        </button>
                                                    @endif
                                                    @if($run->status !== 'delivered')
                                                        <button wire:click="updateLogisticsStatus({{ $run->id }}, 'delivered')" class="bg-emerald-800 hover:bg-emerald-700 text-white px-2.5 py-1 rounded text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer">
                                                            Deliver
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-neutral-500 font-light">No dispatch schedules cataloged in active database registers.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Script for background canvas animation (Reynolds Flocking Boids) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('flower-ambient-canvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let width = canvas.width = window.innerWidth;
                let height = canvas.height = window.innerHeight;

                window.addEventListener('resize', () => {
                    width = canvas.width = window.innerWidth;
                    height = canvas.height = window.innerHeight;
                });

                const particleCount = 60;
                const particles = [];
                const mouse = { x: -1000, y: -1000, active: false };

                window.addEventListener('mousemove', (e) => {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;
                    mouse.active = true;
                });

                window.addEventListener('mouseleave', () => {
                    mouse.active = false;
                });

                const getPetalColors = () => {
                    const activeTheme = localStorage.getItem('nb_theme') || 'onyx';
                    if (activeTheme === 'rose') {
                        return ['#EC4899', '#F472B6', '#F43F5E', '#C58B9F', '#E5C1CD'];
                    } else if (activeTheme === 'champagne') {
                        return ['#B59A7A', '#D4AF37', '#E5C1CD', '#FFFFFF', '#D48EA1'];
                    } else {
                        return ['#C5A880', '#A78BFA', '#8B5CF6', '#4B5563', '#B76E79'];
                    }
                };

                for (let i = 0; i < particleCount; i++) {
                    const angle = Math.random() * Math.PI * 2;
                    const speed = Math.random() * 0.8 + 0.3;
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: Math.cos(angle) * speed,
                        vy: Math.sin(angle) * speed,
                        r: Math.random() * 8 + 4,
                        d: Math.random() * particleCount,
                        angle: Math.random() * 360,
                        rotationSpeed: Math.random() * 0.8 - 0.4,
                        type: Math.random() > 0.4 ? 'petal' : 'flower'
                    });
                }

                function drawFlower(x, y, radius, petals, color, angle) {
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(angle * Math.PI / 180);
                    ctx.fillStyle = color;
                    ctx.beginPath();
                    for (let i = 0; i < petals; i++) {
                        ctx.rotate(Math.PI * 2 / petals);
                        ctx.ellipse(0, -radius, radius * 0.5, radius * 0.9, 0, 0, Math.PI * 2);
                    }
                    ctx.fill();
                    ctx.fillStyle = 'rgba(255,255,255,0.4)';
                    ctx.beginPath();
                    ctx.arc(0, 0, radius * 0.25, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }

                function drawPetal(x, y, radius, color, angle) {
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(angle * Math.PI / 180);
                    ctx.fillStyle = color;
                    ctx.beginPath();
                    ctx.moveTo(0, 0);
                    ctx.quadraticCurveTo(-radius * 0.8, -radius * 1.2, 0, -radius * 2);
                    ctx.quadraticCurveTo(radius * 0.8, -radius * 1.2, 0, 0);
                    ctx.closePath();
                    ctx.fill();
                    ctx.restore();
                }

                function animate() {
                    ctx.clearRect(0, 0, width, height);
                    const colors = getPetalColors();

                    particles.forEach((p, idx) => {
                        p.color = colors[idx % colors.length] + '55';

                        if (p.type === 'flower') {
                            drawFlower(p.x, p.y, p.r, 5, p.color, p.angle);
                        } else {
                            drawPetal(p.x, p.y, p.r, p.color, p.angle);
                        }

                        // Basic Reynolds Flocking
                        let flockVx = 0; let flockVy = 0;
                        let flockX = 0; let flockY = 0;
                        let avoidX = 0; let avoidY = 0;
                        let neighbors = 0; let closeNeighbors = 0;

                        const visualRange = 80;
                        const minDistance = 25;

                        particles.forEach(other => {
                            if (other === p) return;
                            let dx = other.x - p.x;
                            let dy = other.y - p.y;
                            let dist = Math.sqrt(dx*dx + dy*dy);
                            if (dist < visualRange) {
                                flockX += other.x; flockY += other.y;
                                flockVx += other.vx; flockVy += other.vy;
                                neighbors++;
                                if (dist < minDistance) {
                                    avoidX -= dx; avoidY -= dy;
                                    closeNeighbors++;
                                }
                            }
                        });

                        let ax = 0; let ay = 0;
                        if (neighbors > 0) {
                            ax += (flockX / neighbors - p.x) * 0.003;
                            ay += (flockY / neighbors - p.y) * 0.003;
                            ax += (flockVx / neighbors - p.vx) * 0.02;
                            ay += (flockVy / neighbors - p.vy) * 0.02;
                        }
                        if (closeNeighbors > 0) {
                            ax += avoidX * 0.04;
                            ay += avoidY * 0.04;
                        }

                        if (mouse.active) {
                            let mDx = p.x - mouse.x;
                            let mDy = p.y - mouse.y;
                            let mDist = Math.sqrt(mDx*mDx + mDy*mDy);
                            if (mDist < 180) {
                                let force = (180 - mDist) / 180;
                                let angle = Math.atan2(mDy, mDx);
                                ax += Math.cos(angle) * force * 1.2;
                                ay += Math.sin(angle) * force * 1.2;
                            }
                        }

                        p.vx += ax; p.vy += ay;
                        let speed = Math.sqrt(p.vx*p.vx + p.vy*p.vy);
                        const minS = 0.4; const maxS = 1.8;
                        if (speed > maxS) {
                            p.vx = (p.vx/speed)*maxS; p.vy = (p.vy/speed)*maxS;
                        } else if (speed < minS) {
                            if (speed === 0) {
                                p.vx = Math.random()*minS; p.vy = Math.random()*minS;
                            } else {
                                p.vx = (p.vx/speed)*minS; p.vy = (p.vy/speed)*minS;
                            }
                        }

                        p.x += p.vx; p.y += p.vy;
                        p.angle = Math.atan2(p.vy, p.vx) * (180 / Math.PI) + 90;

                        const margin = p.r * 2 + 10;
                        if (p.x < -margin) p.x = width + margin;
                        else if (p.x > width + margin) p.x = -margin;
                        if (p.y < -margin) p.y = height + margin;
                        else if (p.y > height + margin) p.y = -margin;
                    });

                    requestAnimationFrame(animate);
                }
                animate();
            }
        });
    </script>
</div>
