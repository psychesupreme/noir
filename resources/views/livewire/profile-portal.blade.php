@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

<div 
    x-data="{ 
        theme: (localStorage.getItem('nb_theme') === 'onyx' || localStorage.getItem('nb_theme') === 'champagne') ? localStorage.getItem('nb_theme') : 'onyx',
        hoverTheme: null,
        selectedOrder: null,
        changeTheme(targetTheme) {
            if (this.theme === targetTheme) return;
            this.theme = targetTheme;
        }
    }" 
    x-init="
        $watch('theme', val => { 
            localStorage.setItem('nb_theme', val); 
            document.documentElement.className = val; 
            document.documentElement.setAttribute('data-theme', val);
            const bgColors = {
                'onyx': '#050507',
                'champagne': '#FAF7F0'
            };
            const textColors = {
                'onyx': '#E4E4E7',
                'champagne': '#1C1C20'
            };
            document.documentElement.style.backgroundColor = bgColors[val];
            document.documentElement.style.color = textColors[val];
        });

        // Listen for theme settings changes to align site theme automatically
        window.addEventListener('theme-settings-changed', e => {
            theme = e.detail;
        });
    "
    :class="{
        'bg-[#050507] text-[#E4E4E7]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-x-hidden storefront-grain"
>
    <!-- Interactive SVG ambient floral background overlay -->
    <svg id="flower-ambient-svg" class="fixed inset-0 w-full h-full pointer-events-none z-0 opacity-15 overflow-hidden" style="perspective: 800px; transform-style: preserve-3d;"></svg>

    <!-- Fine Linen Organic Grid Overlay -->
    <div class="absolute inset-0 pointer-events-none fine-linen z-0 opacity-[0.035]"></div>
    
    <!-- Luxury Cohesive Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-900/40 shadow-2xl': theme === 'onyx',
            'bg-white/70 border-neutral-200/60 shadow-md': theme === 'champagne'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-xl border rounded-full z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group theme-section"
    >
        <!-- Gold Accent Bottom Glow Line -->
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-[#C5A880]/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 animate-nav-item select-none cursor-pointer" style="animation-delay: 100ms;">
                <span class="text-[11px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
                <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
            </a>
            <!-- Spacing column to balance layout -->
            <div class="flex-1 hidden md:block"></div>

            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Navigation links -->
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 animate-nav-item select-none cursor-pointer" style="animation-delay: 200ms;">Services</a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-4 py-1.5 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer {{ request()->routeIs('curate') ? 'border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880] font-semibold' : 'border-[#C5A880]/30 hover:border-[#C5A880] hover:bg-[#C5A880]/5 text-[#C5A880]' }}"
                   style="animation-delay: 250ms;">
                   Curation Studio
                </a>

                <!-- Theme Switcher Pill (2 options, desktop only) -->
                <div class="hidden lg:flex items-center space-x-1 border rounded-full p-1 animate-nav-item select-none relative" 
                     :class="theme === 'champagne' ? 'border-neutral-250/60 bg-neutral-100/50' : 'border-neutral-500/10 bg-neutral-500/5'"
                     style="animation-delay: 300ms;">
                    <button @click="changeTheme('onyx')" 
                            @mouseenter="hoverTheme = 'onyx'" 
                            @mouseleave="hoverTheme = null" 
                            :class="theme === 'onyx' ? 'bg-[#C5A880] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-[#C5A880] hover:bg-[#C5A880]/10'" 
                            class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Onyx</span>
                    </button>
                    <button @click="changeTheme('champagne')" 
                            @mouseenter="hoverTheme = 'champagne'" 
                            @mouseleave="hoverTheme = null" 
                            :class="theme === 'champagne' ? 'bg-[#B59A7A] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-[#B59A7A] hover:bg-[#B59A7A]/10'" 
                            class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Champagne</span>
                    </button>

                    <!-- Theme Hover Preview Popover Card -->
                    <div x-show="hoverTheme !== null"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                         :class="theme === 'champagne' ? 'bg-white/80 border-neutral-200/60 text-neutral-900 shadow-xl backdrop-blur-xl' : 'bg-[#0F0F12]/95 border-neutral-850 text-white shadow-2xl backdrop-blur-xl'"
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-3.5 w-64 rounded-2xl border p-4 text-left z-50 text-xs space-y-3"
                         style="display: none;"
                    >
                        <div x-show="hoverTheme === 'onyx'" class="space-y-2">
                            <span class="font-bold text-[#C5A880] tracking-wider uppercase text-[10px] block">Onyx Theme</span>
                            <p class="text-neutral-450 text-[11px] leading-relaxed font-light">Midnight Obsidian & Deep Velvet. A high-contrast dark aesthetic built for dramatic evening luxury.</p>
                        </div>
                        <div x-show="hoverTheme === 'champagne'" class="space-y-2">
                            <span class="font-bold text-[#B59A7A] tracking-wider uppercase text-[10px] block">Champagne Theme</span>
                            <p class="text-neutral-500 text-[11px] leading-relaxed font-light">Warm Alabaster & Gilded Gold. A soft, light mode reflecting sunlit mornings in the flower atelier.</p>
                        </div>
                    </div>
                </div>

                <!-- Collapsed mobile theme switcher -->
                <button @click="changeTheme(theme === 'onyx' ? 'champagne' : 'onyx')" 
                        :class="theme === 'champagne' ? 'border-neutral-250/60 bg-neutral-100/50 hover:text-black' : 'border-neutral-500/10 bg-neutral-500/5 hover:text-neutral-200'"
                        class="lg:hidden transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border rounded-full animate-nav-item"
                        style="animation-delay: 300ms;"
                        title="Cycle Theme"
                >
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="5" />
                        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.77l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </button>

                <!-- Modern SVG shopping bag cart button -->
                <a href="/?open_cart=true" 
                        :class="theme === 'champagne' ? 'border-neutral-250/60 bg-neutral-100/50 hover:text-black' : 'border-neutral-500/10 bg-neutral-500/5 hover:text-neutral-200'"
                        class="transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border rounded-full animate-nav-item" 
                        style="animation-delay: 400ms;"
                        title="View Curation Drawer"
                >
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#C5A880] text-black text-[10px] font-bold font-sans tracking-none shadow-[0_0_8px_rgba(197,168,128,0.5)]">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Profile Portal Button -->
                <div x-data="{ profileMenuOpen: false }" class="relative inline-block text-left animate-nav-item" style="animation-delay: 500ms;">
                    @auth
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                class="hover:border-[#C5A880]/60 border transition-all cursor-pointer select-none w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-900 via-neutral-950 to-neutral-900 shadow-md border-[#C5A880]"
                                title="Profile Portal Options"
                        >
                            <span class="text-[10px] font-mono font-bold tracking-wider text-[#C5A880] uppercase">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                            </span>
                        </button>
                    @endauth

                    <!-- Popover Dropdown Card -->
                    <div 
                        x-show="profileMenuOpen" 
                        @click.away="profileMenuOpen = false" 
                        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-350"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-3"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-250"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-3"
                        :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250/60 text-neutral-900 shadow-xl backdrop-blur-xl' : 'bg-[#0F0F12]/95 border-neutral-850 text-white shadow-2xl backdrop-blur-xl'"
                        class="absolute right-0 mt-3.5 w-80 rounded-3xl border p-5 text-left z-50 text-xs space-y-4"
                        style="display: none;"
                    >
                        <div class="flex items-center space-x-3 pb-3 border-b border-neutral-500/10">
                            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-900 via-neutral-950 to-neutral-900 border-2 border-[#C5A880]/30 shadow-md overflow-hidden relative">
                                @if($gender === 'male')
                                    <svg class="w-full h-full text-amber-500/80 bg-neutral-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="4" /><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" /><path d="M9 5c1.5-1 3.5-1 5 0M9.5 4.5c1-1.2 3-1.2 4 0" stroke-width="1" /></svg>
                                @elseif($gender === 'female')
                                    <svg class="w-full h-full text-pink-400/80 bg-neutral-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="3.5" /><path d="M6 21v-1.5a4.5 4.5 0 0 1 4.5-4.5h3a4.5 4.5 0 0 1 4.5 4.5v1.5" /><path d="M8 8.5c0-4 3-5 4-5s4 1 4 5M7.5 9c-.5 2 0 4 .5 5.5M16.5 9c.5 2 0 4-.5 5.5" stroke-linecap="round" /></svg>
                                @elseif($gender === 'trans' || $gender === 'other')
                                    <svg class="w-full h-full text-violet-400/80 bg-neutral-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="3.8" /><path d="M6 21v-1.8a4.2 4.2 0 0 1 4.2-4.2h3.6a4.2 4.2 0 0 1 4.2 4.2v1.8" /><path d="M12 2v2M12 12v2M10 3h4M9.5 13h5" stroke-linecap="round" /><circle cx="12" cy="12" r="1.5" /></svg>
                                @else
                                    <svg class="w-full h-full text-neutral-500 bg-neutral-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="9" r="4" /><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" /></svg>
                                @endif
                            </div>
                            <div class="truncate">
                                <span class="font-semibold block text-sm tracking-wide" :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-neutral-450 block font-mono tracking-tight">{{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        <div class="space-y-2.5 font-sans py-1">
                            <div class="flex items-center space-x-2 text-[11px] text-neutral-450">
                                <svg class="w-3.5 h-3.5 text-[#C5A880]/80 stroke-current fill-none shrink-0" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 7.92z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="font-mono">{{ auth()->user()->phone_number ?: 'Not Provided' }}</span>
                            </div>

                            <div class="flex items-start space-x-2 text-[11px] text-neutral-450">
                                <svg class="w-3.5 h-3.5 text-[#C5A880]/80 stroke-current fill-none shrink-0 mt-0.5" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="leading-relaxed truncate">
                                    <span class="font-bold text-[9px] uppercase tracking-wider text-[#C5A880]/80 block">Main Address</span>
                                    <span class="block truncate" title="{{ auth()->user()->default_delivery_address }}">{{ auth()->user()->default_delivery_address ?: 'No Address Set' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-neutral-500/10">
                            <button @click="profileMenuOpen = false; $wire.setTab('details')" class="block w-full text-center bg-[#C5A880] text-black font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] hover:bg-[#B59A7A] transition-all">
                                View Profile
                            </button>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-center text-neutral-500 hover:text-rose-500 font-mono text-[9px] uppercase tracking-wider pt-1.5 block cursor-pointer bg-transparent border-none">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-8xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col lg:flex-row gap-8 z-10 relative">
        
        <!-- Left Sidebar Navigation -->
        <aside 
            :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" 
            class="w-full lg:w-64 shrink-0 sticky top-28 p-6 border rounded-[32px] backdrop-blur-xl space-y-6 text-left transition-all duration-500 self-start h-auto"
        >
            <div class="border-b border-neutral-500/10 pb-4">
                <span class="text-[10px] font-mono uppercase tracking-[0.25em] text-neutral-500 block">Atelier Personal Portal</span>
                <h4 :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="text-xs font-semibold uppercase tracking-widest mt-1">Dashboard Portal</h4>
            </div>

            <!-- Tab Buttons -->
            <div class="space-y-1.5">
                <span class="text-[11px] font-mono uppercase tracking-widest text-neutral-500 block mb-2">Workspace Controls</span>
                
                <!-- Details -->
                <button wire:click="setTab('details')" 
                        :class="activeTab === 'details' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-neutral-250 hover:bg-neutral-500/5'"
                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Personal Details</span>
                </button>

                <!-- Security -->
                <button wire:click="setTab('security')" 
                        :class="activeTab === 'security' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-neutral-250 hover:bg-neutral-500/5'"
                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span>Security & Privacy</span>
                </button>

                <!-- Orders -->
                <button wire:click="setTab('orders')" 
                        :class="activeTab === 'orders' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-neutral-250 hover:bg-neutral-500/5'"
                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span>Order History</span>
                </button>

                <!-- Wishlist -->
                <button wire:click="setTab('wishlist')" 
                        :class="activeTab === 'wishlist' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-neutral-250 hover:bg-neutral-500/5'"
                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span>Wishlist</span>
                </button>

                <!-- Settings -->
                <button wire:click="setTab('settings')" 
                        :class="activeTab === 'settings' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-neutral-250 hover:bg-neutral-500/5'"
                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Atelier Settings</span>
                </button>

                <!-- Logistics tab (only visible for Drivers/Staff) -->
                @if(auth()->user()->isStaff())
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#C5A880] block pt-3 mb-1">Staff Section</span>
                    <button wire:click="setTab('logistics')" 
                            :class="activeTab === 'logistics' ? 'bg-[#C5A880]/15 text-[#C5A880] font-semibold border-l-2 border-[#C5A880]' : 'text-neutral-450 hover:text-[#C5A880] hover:bg-neutral-500/5'"
                            class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                            <path d="M10 17l5-5-5-5M13.8 12H3M21 3v18" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Logistics Hub</span>
                    </button>
                    <a href="/admin" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full mt-2 py-2 rounded-xl text-[10px] font-mono uppercase tracking-wider font-semibold transition-all cursor-pointer text-center block shadow-sm">
                        Admin Dashboard
                    </a>
                @endif
            </div>

            <!-- Sign Out -->
            <div class="border-t border-neutral-500/10 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-transparent border border-rose-500/30 hover:border-rose-500 text-rose-400 hover:text-rose-500 hover:bg-rose-500/5 py-2.5 rounded-xl font-mono text-[10px] uppercase tracking-widest font-semibold transition-all duration-300 cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>[ Sign Out ]</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Right Content Area -->
        <div class="flex-1 w-full space-y-8 mb-20 text-left">
            
            <!-- DETAILS TAB -->
            @if($activeTab === 'details')
                <div class="space-y-6 animate-card-fade-in" x-data="{ gender: @entangle('gender'), name: @entangle('name') }">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Personal Details</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Configure user profile customization properties, email contacts, and default hub regions.</p>
                    </div>

                    <!-- Dynamic Profile Header Card with Real-time Gender Avatar Selector -->
                    <div :class="theme === 'champagne' ? 'bg-white/50 border-neutral-200 text-neutral-900' : 'bg-neutral-900/30 text-white border-neutral-900/40'" class="border p-6 rounded-[32px] flex flex-col sm:flex-row gap-6 items-center backdrop-blur-md">
                        <div class="relative group shrink-0">
                            <!-- SVG Dynamic Avatar Wrapper -->
                            <div class="w-20 h-20 rounded-full flex items-center justify-center border-2 border-[#C5A880]/30 shadow-lg relative overflow-hidden transition-all duration-500">
                                <div x-show="gender === 'male'" class="w-full h-full">
                                    <svg class="w-full h-full text-amber-500/80 bg-neutral-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="4" /><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" /><path d="M9 5c1.5-1 3.5-1 5 0M9.5 4.5c1-1.2 3-1.2 4 0" stroke-width="1" /></svg>
                                </div>
                                <div x-show="gender === 'female'" style="display: none;" class="w-full h-full">
                                    <svg class="w-full h-full text-pink-400/80 bg-neutral-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="3.5" /><path d="M6 21v-1.5a4.5 4.5 0 0 1 4.5-4.5h3a4.5 4.5 0 0 1 4.5 4.5v1.5" /><path d="M8 8.5c0-4 3-5 4-5s4 1 4 5M7.5 9c-.5 2 0 4 .5 5.5M16.5 9c.5 2 0 4-.5 5.5" stroke-linecap="round" /></svg>
                                </div>
                                <div x-show="gender === 'trans' || gender === 'other'" style="display: none;" class="w-full h-full">
                                    <svg class="w-full h-full text-violet-400/80 bg-neutral-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="3.8" /><path d="M6 21v-1.8a4.2 4.2 0 0 1 4.2-4.2h3.6a4.2 4.2 0 0 1 4.2 4.2v1.8" /><path d="M12 2v2M12 12v2M10 3h4M9.5 13h5" stroke-linecap="round" /><circle cx="12" cy="12" r="1.5" /></svg>
                                </div>
                                <div x-show="!gender || gender === ''" style="display: none;" class="w-full h-full">
                                    <svg class="w-full h-full text-neutral-500 bg-neutral-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="9" r="4" /><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" /></svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-center sm:text-left space-y-1">
                            <span class="text-[9px] font-mono uppercase text-neutral-500 tracking-wider">Account Member</span>
                            <span class="text-lg font-serif italic block font-semibold" x-text="name || 'Atelier Member'"></span>
                            <span class="text-xs text-[#C5A880] font-mono block">Tier: {{ $user->loyalty_tier }} Member &bull; {{ number_format($user->loyalty_points) }} PTS</span>
                        </div>
                    </div>

                    <!-- Profile Form & Optional Configuration -->
                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Personal Information Parameters</h4>
                        
                        @if(session('success_profile'))
                            <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_profile') }}</div>
                        @endif

                        <form wire:submit.prevent="updateProfile" class="space-y-4 text-xs font-mono">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Full Name</label>
                                    <input type="text" wire:model="name" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Email Address</label>
                                    <input type="email" wire:model="email" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('email') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Phone Number</label>
                                    <input type="text" wire:model="phone_number" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('phone_number') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">KRA PIN (Optional)</label>
                                    <input type="text" wire:model="kra_pin" placeholder="A000000000Z" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans uppercase">
                                    @error('kra_pin') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Gender (Optional)</label>
                                    <select wire:model="gender" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-3 py-2 focus:outline-none text-sm font-sans cursor-pointer">
                                        <option value="">Choose Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="trans">Transgender</option>
                                        <option value="other">Other / Rather Not Say</option>
                                    </select>
                                    @error('gender') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Date of Birth (Optional)</label>
                                    <input type="date" wire:model="dob" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('dob') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Hub Region</label>
                                    <select wire:model="default_region" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-3 py-2 focus:outline-none text-sm font-sans cursor-pointer">
                                        <option value="Nairobi">Nairobi</option>
                                        <option value="Kiambu">Kiambu</option>
                                    </select>
                                    @error('default_region') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Delivery Coordinates/Landmark</label>
                                    <input type="text" wire:model="default_delivery_address" placeholder="Estate, Complex, Street Name" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('default_delivery_address') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5 md:col-span-2" wire:ignore>
                                    <label class="text-neutral-500 uppercase block">Interactive Location Locator Map (Click/drag marker to update coordinates for free)</label>
                                    <div 
                                        x-data="{
                                            map: null,
                                            marker: null,
                                            searchQuery: '',
                                            searchResults: [],
                                            initMap() {
                                                let lat = -1.2921;
                                                let lng = 36.8219;
                                                
                                                let match = $wire.default_delivery_address.match(/(-?\d+\.\d+),\s*(-?\d+\.\d+)/);
                                                if (match) {
                                                    lat = parseFloat(match[1]);
                                                    lng = parseFloat(match[2]);
                                                }

                                                setTimeout(() => {
                                                    this.map = L.map('profile-map').setView([lat, lng], 13);
                                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                        attribution: '&copy; OpenStreetMap contributors'
                                                    }).addTo(this.map);
                                                    
                                                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                                                    
                                                    this.marker.on('dragend', (e) => {
                                                        let position = this.marker.getLatLng();
                                                        this.updateCoords(position.lat, position.lng);
                                                    });
                                                    
                                                    this.map.on('click', (e) => {
                                                        this.marker.setLatLng(e.latlng);
                                                        this.updateCoords(e.latlng.lat, e.latlng.lng);
                                                    });

                                                    // ResizeObserver ensures Leaflet renders properly inside modal transitions/tabs
                                                    const observer = new ResizeObserver(() => {
                                                        this.map.invalidateSize();
                                                    });
                                                    observer.observe(document.getElementById('profile-map'));
                                                }, 400);

                                                $watch('$wire.default_region', (val) => {
                                                    let centerCoords = val === 'Kiambu' ? [-1.1578, 36.8407] : [-1.2921, 36.8219];
                                                    this.map.setView(centerCoords, 13);
                                                    this.marker.setLatLng(centerCoords);
                                                    this.updateCoords(centerCoords[0], centerCoords[1]);
                                                });
                                            },
                                            searchQueryClean() {
                                                return this.searchQuery.trim();
                                            },
                                            searchLocation() {
                                                let q = this.searchQueryClean();
                                                if (!q) return;
                                                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=5`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        this.searchResults = data;
                                                    })
                                                    .catch(() => {});
                                            },
                                            selectResult(res) {
                                                let lat = parseFloat(res.lat);
                                                let lng = parseFloat(res.lon);
                                                this.map.setView([lat, lng], 14);
                                                this.marker.setLatLng([lat, lng]);
                                                this.updateCoords(lat, lng);
                                                this.searchResults = [];
                                                this.searchQuery = res.display_name.split(',').slice(0, 3).join(',').trim();
                                            },
                                            updateCoords(lat, lng) {
                                                let coords = lat.toFixed(6) + ', ' + lng.toFixed(6);
                                                $wire.default_delivery_address = coords;
                                                
                                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        if (data && data.display_name) {
                                                            let shortName = data.display_name.split(',').slice(0, 3).join(',').trim();
                                                            $wire.default_delivery_address = coords + ' (' + shortName + ')';
                                                        }
                                                    })
                                                    .catch(() => {});
                                            }
                                        }"
                                        x-init="initMap()"
                                        class="relative"
                                    >
                                        <!-- Map Search Autocomplete Input -->
                                        <div class="relative z-40 mb-2">
                                            <div class="flex gap-2">
                                                <input 
                                                    type="text" 
                                                    x-model="searchQuery" 
                                                    @keydown.enter.prevent="searchLocation()"
                                                    placeholder="Search location (e.g. Westlands)..." 
                                                    :class="theme === 'champagne' ? 'bg-white border-neutral-250 text-black placeholder-neutral-400' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600'"
                                                    class="flex-1 border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-neutral-400 font-sans"
                                                >
                                                <button 
                                                    type="button" 
                                                    @click="searchLocation()"
                                                    :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-850' : 'bg-white text-black hover:bg-neutral-200'"
                                                    class="px-3.5 py-1.5 rounded-xl text-[10px] font-mono uppercase tracking-wider cursor-pointer font-bold"
                                                >
                                                    Find
                                                </button>
                                            </div>
                                            <!-- Autocomplete Results Dropdown -->
                                            <div 
                                                x-show="searchResults.length > 0" 
                                                @click.away="searchResults = []"
                                                :class="theme === 'champagne' ? 'bg-[#FAF7F0] border-neutral-250 text-neutral-900 shadow-lg' : 'bg-[#0F0F12] border-neutral-800 text-white shadow-lg'"
                                                class="absolute left-0 right-0 z-[1000] mt-1 max-h-48 overflow-y-auto border rounded-xl text-xs"
                                                style="display: none;"
                                            >
                                                <template x-for="res in searchResults" :key="res.place_id">
                                                    <div 
                                                        @click="selectResult(res)"
                                                        :class="theme === 'champagne' ? 'hover:bg-neutral-200/50' : 'hover:bg-white/10'"
                                                        class="px-3 py-2 cursor-pointer transition-colors border-b last:border-b-0 border-neutral-500/10 font-sans"
                                                        x-text="res.display_name"
                                                    ></div>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <!-- Leaflet Map Container -->
                                        <div 
                                            id="profile-map"
                                            class="w-full h-48 rounded-2xl overflow-hidden border border-neutral-500/10 mt-1 z-10"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer">
                                Save Profile Changes
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- SECURITY TAB -->
            @if($activeTab === 'security')
                <div class="space-y-6 animate-card-fade-in">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Security & Privacy</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Configure authentication access logs, reset security keys, and manage logins.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Update Password Form -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Reset Security Credentials</h4>
                            
                            @if(session('success_password'))
                                <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl mb-3">{{ session('success_password') }}</div>
                            @elseif(session('error_password'))
                                <div class="p-3 border border-dashed border-rose-850 bg-rose-950/20 text-rose-400 text-xs font-mono rounded-xl mb-3">{{ session('error_password') }}</div>
                            @endif

                            <form wire:submit.prevent="updatePassword" class="space-y-4 text-xs font-mono">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Current Password</label>
                                    <input type="password" wire:model="current_password" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">New Password</label>
                                    <input type="password" wire:model="new_password" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('new_password') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Confirm New Password</label>
                                    <input type="password" wire:model="new_password_confirmation" :class="theme === 'champagne' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                </div>
                                <button type="submit" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer">
                                    Update Security Key
                                </button>
                            </form>
                        </div>

                        <!-- Session Logs (Mock Security Details) -->
                        <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Active Security Audit Logs</h4>
                            
                            <div class="space-y-3.5 text-xs font-mono">
                                <div class="p-3 bg-neutral-500/5 border border-neutral-500/10 rounded-2xl space-y-1">
                                    <div class="flex justify-between font-semibold"><span class="text-neutral-450">Current Browser Session</span><span class="text-emerald-500">ACTIVE</span></div>
                                    <div class="text-[10px] text-neutral-500">IP Coordinate: {{ request()->ip() }} &bull; Nairobi Hub</div>
                                    <div class="text-[10px] text-neutral-500">Device Platform: {{ request()->userAgent() }}</div>
                                </div>
                                <div class="p-3 bg-neutral-500/5 border border-neutral-500/10 rounded-2xl space-y-1 opacity-70">
                                    <div class="flex justify-between font-semibold"><span class="text-neutral-450">Fulfillment Web Token</span><span class="text-neutral-500">STABLE</span></div>
                                    <div class="text-[10px] text-neutral-500">Token Type: Bearer OAuth SSHv2</div>
                                    <div class="text-[10px] text-neutral-500">Refreshed: 15 minutes ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ORDERS TAB -->
            @if($activeTab === 'orders')
                <div class="space-y-6 animate-card-fade-in">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Order History</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Audit previous curation dispatches, trace real-time fulfillment milestones, and retrieve invoices.</p>
                    </div>

                    <!-- Order History Logs -->
                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Purchase Ledger Records Matrix</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs font-mono">
                                <thead>
                                    <tr class="text-neutral-500 border-b border-neutral-500/10">
                                        <th class="py-3 px-2">Order Reference</th>
                                        <th class="py-3 px-2">Timestamp</th>
                                        <th class="py-3 px-2">Items Curation</th>
                                        <th class="py-3 px-2">Status</th>
                                        <th class="py-3 px-2 text-right">Sum Total</th>
                                        <th class="py-3 px-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userOrders as $ord)
                                        <tr class="border-b border-neutral-500/5 hover:bg-neutral-500/5 transition-colors">
                                            <td class="py-4 px-2 font-semibold">#NB-ORD-{{ str_pad($ord->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td class="py-4 px-2 text-neutral-450">{{ $ord->created_at->format('d M Y H:i') }}</td>
                                            <td class="py-4 px-2 text-neutral-450">
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
                                                        default => 'text-neutral-450 bg-neutral-950/20 border-neutral-900/40'
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md {{ $stCls }} font-bold">
                                                    {{ $ord->status }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right text-amber-500 font-semibold">{{ number_format($ord->total_amount) }} KSH</td>
                                            <td class="py-4 px-2 text-right">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- Timeline Tracking Trigger -->
                                                    <button @click="selectedOrder = { id: {{ $ord->id }}, status: '{{ $ord->status }}', created: '{{ $ord->created_at->format('d M Y H:i') }}', total: '{{ number_format($ord->total_amount) }}', instructions: '{{ addslashes($ord->special_instructions) }}' }" class="bg-neutral-500/10 hover:bg-neutral-500/20 px-2.5 py-1.5 rounded-lg text-[9px] font-bold uppercase cursor-pointer transition-all border border-neutral-500/10">
                                                        Track
                                                    </button>
                                                    
                                                    <!-- Invoice download (only for completed or credit terms) -->
                                                    @php
                                                        $completedPayment = $ord->payments->first(fn($p) => $p->status === 'completed');
                                                        $arInvoice = \App\Models\AccountsReceivableInvoice::where('order_id', $ord->id)->first();
                                                    @endphp
                                                    @if($completedPayment || $arInvoice)
                                                        <a href="{{ URL::signedRoute('receipt.download', ['order' => $ord->id]) }}" target="_blank" class="bg-[#C5A880] hover:bg-[#B59A7A] text-black px-2.5 py-1.5 rounded-lg text-[9px] font-bold uppercase transition-all block">
                                                            Invoice
                                                        </a>
                                                    @else
                                                        <span class="text-[9px] text-neutral-500 font-light block italic">Pending Pay</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-neutral-500 font-light">No order records cataloged. Browse the storefront showroom to place your first dispatch request.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Beautiful Timeline Tracking Dialog Modal (Alpine-controlled) -->
                <div x-show="selectedOrder !== null" @click="selectedOrder = null" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-md flex items-center justify-center p-4" style="display: none;">
                    <div @click.stop :class="theme === 'champagne' ? 'bg-white/95 border-neutral-250 text-neutral-900' : 'bg-[#0F0F12]/95 border-neutral-850 text-white'" class="w-full max-w-md border rounded-[32px] p-6 space-y-6 shadow-2xl backdrop-blur-xl">
                        <div class="flex items-center justify-between border-b border-neutral-500/10 pb-3">
                            <div>
                                <span class="text-[9px] font-mono uppercase tracking-widest text-[#C5A880] block" x-text="'Timeline - #NB-ORD-' + String(selectedOrder?.id).padStart(4, '0')"></span>
                                <h4 class="text-sm font-serif italic" x-text="'Status: ' + String(selectedOrder?.status).toUpperCase()"></h4>
                            </div>
                            <button @click="selectedOrder = null" class="text-neutral-500 hover:text-neutral-300 p-1">✕</button>
                        </div>

                        <!-- Timeline steps representation -->
                        <div class="space-y-4 text-xs">
                            <!-- Step 1 -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-neutral-950 flex items-center justify-center font-bold text-[9px]">✓</div>
                                    <div class="w-0.5 h-8 bg-emerald-500"></div>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="font-bold block text-[11px]">Order Dispatched to Atelier</span>
                                    <p class="text-[10px] text-neutral-450 font-light" x-text="'System audit recorded at ' + selectedOrder?.created"></p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div :class="(selectedOrder?.status !== 'pending') ? 'bg-emerald-500 text-neutral-950' : 'bg-neutral-800 text-neutral-500'" class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px]" x-text="(selectedOrder?.status !== 'pending') ? '✓' : '2'"></div>
                                    <div :class="(selectedOrder?.status !== 'pending') ? 'bg-emerald-500' : 'bg-neutral-800'" class="w-0.5 h-8"></div>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="font-bold block text-[11px]">Remittance Verification & Approval</span>
                                    <p class="text-[10px] text-neutral-450 font-light" x-text="(selectedOrder?.status !== 'pending') ? 'Payment parameters verified & order approved' : 'Awaiting payment confirmation'"></p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div :class="(selectedOrder?.status === 'processing' || selectedOrder?.status === 'delivered') ? 'bg-emerald-500 text-neutral-950' : 'bg-neutral-800 text-neutral-500'" class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px]" x-text="(selectedOrder?.status === 'processing' || selectedOrder?.status === 'delivered') ? '✓' : '3'"></div>
                                    <div :class="(selectedOrder?.status === 'processing' || selectedOrder?.status === 'delivered') ? 'bg-emerald-500' : 'bg-neutral-800'" class="w-0.5 h-8"></div>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="font-bold block text-[11px]">Floral Styling & Curation</span>
                                    <p class="text-[10px] text-neutral-450 font-light" x-text="(selectedOrder?.status === 'processing' || selectedOrder?.status === 'delivered') ? 'Arrangement curated by master florists' : 'Queued for styling'"></p>
                                </div>
                            </div>
                            <!-- Step 4 -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div :class="(selectedOrder?.status === 'delivered') ? 'bg-emerald-500 text-neutral-950' : 'bg-neutral-800 text-neutral-500'" class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px]" x-text="(selectedOrder?.status === 'delivered') ? '✓' : '4'"></div>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="font-bold block text-[11px]">Courier Concierge Hand-off</span>
                                    <p class="text-[10px] text-neutral-450 font-light" x-text="(selectedOrder?.status === 'delivered') ? 'Delivered successfully at coordinates' : 'Awaiting delivery route hand-off'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-neutral-500/10 pt-4 text-[10px] font-mono leading-relaxed space-y-1">
                            <span class="text-neutral-500 block uppercase">Special Instructions/Details:</span>
                            <p class="text-neutral-350 italic font-sans" x-text="selectedOrder?.instructions || 'Standard delivery package settings'"></p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- WISHLIST TAB -->
            @if($activeTab === 'wishlist')
                <div class="space-y-6 animate-card-fade-in">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">My Wishlist</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Review saved luxury arrangements, stems, and hampers. Quick-curate them directly to your active cart.</p>
                    </div>

                    @if(session('success_wishlist'))
                        <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_wishlist') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($wishlistProducts as $prod)
                            <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 hover:bg-white/75 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 hover:bg-[#0C0C0E]/70 shadow-2xl'" class="border p-4.5 rounded-[28px] space-y-3 transition-all duration-300 relative group backdrop-blur-xl flex flex-col justify-between h-auto">
                                <div class="space-y-3">
                                    <!-- Image Frame -->
                                    <div class="w-full h-44 rounded-2xl overflow-hidden bg-neutral-950 relative border border-neutral-900/20 shadow-inner">
                                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                                        
                                        <!-- Remove icon button -->
                                        <button wire:click="removeFromWishlist({{ $prod->id }})" class="absolute top-2 right-2 bg-black/60 hover:bg-rose-900/80 text-white rounded-full p-2.5 cursor-pointer backdrop-blur-md transition-all shadow-md" title="Remove from Wishlist">
                                            <svg class="w-3.5 h-3.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                                <path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-1">
                                        <span class="text-[9px] uppercase tracking-widest text-emerald-800 font-mono font-bold block">{{ str_replace('_', ' ', $prod->category) }}</span>
                                        <h4 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-sm font-serif italic truncate">{{ $prod->name }}</h4>
                                        <p class="text-[10px] text-neutral-450 line-clamp-2 leading-relaxed font-light font-sans">{{ $prod->description }}</p>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-neutral-500/10 flex items-center justify-between mt-auto">
                                    <span class="text-xs font-mono font-bold text-amber-500">{{ number_format($prod->price) }} KSH</span>
                                    <button wire:click="addToCurationFromWishlist({{ $prod->id }})" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="px-4 py-1.5 rounded-lg text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer transition-all shadow-sm">
                                        Curate
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full border border-dashed border-neutral-500/10 p-12 text-center rounded-[32px] space-y-4">
                                <span class="text-4xl block text-neutral-550">♡</span>
                                <p class="text-neutral-450 font-light text-xs font-sans max-w-sm mx-auto">Your luxury wishlist is currently empty. Explore the showroom catalog to save your favorite fresh stems and hampers.</p>
                                <a href="/" class="inline-block bg-[#C5A880] hover:bg-[#B59A7A] text-black font-mono font-bold uppercase tracking-wider text-[10px] px-6 py-2.5 rounded-xl transition-all shadow-md">
                                    Browse Showroom
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- SETTINGS TAB -->
            @if($activeTab === 'settings')
                <div class="space-y-6 animate-card-fade-in">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Atelier Settings</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Configure automated notifications, marketing preferences, and preferred default user interface theme settings.</p>
                    </div>

                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-6 backdrop-blur-xl">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Personal Preferences</h4>
                        
                        @if(session('success_settings'))
                            <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_settings') }}</div>
                        @endif

                        <div class="space-y-4 text-xs font-mono">
                            <!-- Theme selector setting -->
                            <div class="space-y-2 border-b border-neutral-500/5 pb-4">
                                <label class="text-neutral-400 uppercase font-semibold">Preferred Workspace Default Theme</label>
                                <div class="grid grid-cols-2 gap-4 pt-1 max-w-sm">
                                    <button type="button" wire:click="$set('preferred_theme', 'onyx')" :class="preferred_theme === 'onyx' ? 'border-[#C5A880] bg-[#C5A880]/15 text-[#C5A880] font-bold' : 'border-neutral-500/20 text-neutral-500 hover:text-neutral-300'" class="p-3 border rounded-2xl cursor-pointer text-center transition-all">
                                        Onyx (Dark)
                                    </button>
                                    <button type="button" wire:click="$set('preferred_theme', 'champagne')" :class="preferred_theme === 'champagne' ? 'border-[#B59A7A] bg-[#B59A7A]/15 text-[#B59A7A] font-bold' : 'border-neutral-500/20 text-neutral-500 hover:text-neutral-300'" class="p-3 border rounded-2xl cursor-pointer text-center transition-all">
                                        Champagne (Light)
                                    </button>
                                </div>
                            </div>

                            <!-- Notification Toggles -->
                            <div class="space-y-3.5">
                                <label class="text-neutral-400 uppercase font-semibold block mb-1">Communication Toggles</label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" wire:model="notification_email" class="w-4 h-4 rounded text-[#C5A880] focus:ring-0 bg-[#0F0F12] border-neutral-800 cursor-pointer">
                                    <span class="text-neutral-450 group-hover:text-neutral-300 transition-colors">Deliver printable Proforma Invoices automatically via email</span>
                                </label>

                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" wire:model="notification_sms" class="w-4 h-4 rounded text-[#C5A880] focus:ring-0 bg-[#0F0F12] border-neutral-800 cursor-pointer">
                                    <span class="text-neutral-450 group-hover:text-neutral-300 transition-colors">Receive live SMS alerts detailing concierge routing dispatch runs</span>
                                </label>

                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" wire:model="notification_concierge" class="w-4 h-4 rounded text-[#C5A880] focus:ring-0 bg-[#0F0F12] border-neutral-800 cursor-pointer">
                                    <span class="text-neutral-450 group-hover:text-neutral-300 transition-colors">Grant early exclusive VIP access to concierge rosewood stem drops</span>
                                </label>

                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" wire:model="notification_newsletter" class="w-4 h-4 rounded text-[#C5A880] focus:ring-0 bg-[#0F0F12] border-neutral-800 cursor-pointer">
                                    <span class="text-neutral-450 group-hover:text-neutral-300 transition-colors">Subscribe to the weekly Atelier Bulletin design digest</span>
                                </label>
                            </div>

                            <button type="button" wire:click="updateSettings" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer mt-4">
                                Save Atelier Settings
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- LOGISTICS TAB (STAFF RUNS) -->
            @if($activeTab === 'logistics' && auth()->user()->isStaff())
                <div class="space-y-6 animate-card-fade-in">
                    <div class="border-b border-neutral-500/10 pb-4">
                        <h3 class="text-3xl font-serif italic tracking-wider leading-tight">Drivers & Riders Dispatch</h3>
                        <p class="text-xs text-neutral-500 font-light mt-1">Manage dispatch runs, retrieve coordinates, and update live M-Pesa order delivery coordinates.</p>
                    </div>

                    @if(session('success_logistics'))
                        <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_logistics') }}</div>
                    @endif

                    <div :class="theme === 'champagne' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
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
                                            <td class="py-4 px-2 font-semibold">#NB-ORD-{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td class="py-4 px-2 text-neutral-300">
                                                @if($run->is_gift)
                                                    <span class="text-amber-500 font-bold block">[GIFT RECIPIENT]</span>
                                                    <span class="block">{{ $run->recipient_name }}</span>
                                                    <span class="text-[10px] text-neutral-450 block">{{ $run->recipient_phone }}</span>
                                                @else
                                                    <span class="block">{{ $run->client->contact_name }}</span>
                                                    <span class="text-[10px] text-neutral-450 block">{{ $run->client->phone }}</span>
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
                                                        default => 'text-neutral-450 bg-neutral-950/20 border-neutral-900/40'
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 border text-[9px] uppercase tracking-wider rounded-md {{ $runCls }} font-bold">
                                                    {{ $run->status }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right">
                                                <div class="flex items-center justify-end space-x-1.5">
                                                    @if($run->status !== 'processing' && $run->status !== 'delivered')
                                                        <button wire:click="updateLogisticsStatus({{ $run->id }}, 'processing')" class="bg-violet-850 hover:bg-violet-750 text-white px-2.5 py-1.5 rounded text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer">
                                                            Dispatch
                                                        </button>
                                                    @endif
                                                    @if($run->status !== 'delivered')
                                                        <button wire:click="updateLogisticsStatus({{ $run->id }}, 'delivered')" class="bg-emerald-800 hover:bg-emerald-700 text-white px-2.5 py-1.5 rounded text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer">
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

    <!-- Fully-Featured Luxury Atelier Footer -->
    <footer 
        :class="{
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'onyx',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'champagne'
        }"
        class="border-t mt-20 py-10 px-6 transition-colors duration-500 z-10 relative theme-section"
    >
        <div class="max-w-5xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-left">
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors">Noir & Bloom</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><a href="/?collection=retail" class="hover:underline">Bespoke Retail Arrays</a></li>
                    <li><a href="/?collection=wholesale" class="hover:underline">Wholesale Graded Stems</a></li>
                    <li><a href="/?collection=giftings" class="hover:underline">Luxury Giftings</a></li>
                    <li><a href="/profile-portal" class="hover:underline">Atelier Loyalty Circle</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><span class="block text-neutral-500">Operating Hours</span> Mon &mdash; Sat: 07:00 &mdash; 20:00</li>
                    <li>Sunday: 09:00 &mdash; 17:00</li>
                    <li class="pt-2"><span class="block text-neutral-500 font-mono text-[11px] uppercase tracking-wider">Hotline Direct</span> +254 (0) 712 345 678</li>
                    <li>concierge@noirbloom.co.ke</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Atelier Bulletin</h5>
                <p class="text-xs font-light leading-relaxed">
                    Subscribe for seasonal curation updates, wholesale catalog changes, and exclusive releases.
                </p>
                <div class="flex items-center space-x-2 pt-1">
                    <input 
                        type="email" 
                        placeholder="you@company.co.ke" 
                        :class="theme === 'champagne' ? 'bg-white border-neutral-300 text-black placeholder-neutral-400 focus:border-neutral-500' : 'bg-neutral-900/60 border-neutral-800 text-white placeholder-neutral-700 focus:border-neutral-700'"
                        class="flex-1 text-xs px-3.5 py-2.5 border rounded-xl focus:outline-none transition-all"
                    >
                    <button 
                        :class="theme === 'champagne' ? 'bg-neutral-950 text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                        class="px-4 py-2.5 text-[11px] font-mono uppercase tracking-wider font-semibold rounded-full transition-all"
                    >
                        Join
                    </button>
                </div>
            </div>
        </div>

        <div :class="theme === 'champagne' ? 'border-neutral-200/60 text-neutral-500' : 'border-neutral-900 text-neutral-600'" class="max-w-5xl w-full mx-auto border-t mt-10 pt-6 flex flex-col md:flex-row justify-between items-center text-[12px] font-mono uppercase tracking-wider gap-4">
            <p>&copy; {{ date('Y') }} Atelier Noir & Bloom. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-neutral-400">Terms of Curation</a>
                <a href="#" class="hover:text-neutral-400">Logistics Policy</a>
                <a href="#" class="hover:text-neutral-400">Invoice Request</a>
            </div>
        </div>
    </footer>
</div>
