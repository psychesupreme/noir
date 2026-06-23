@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

<div 
    x-data="{ 
        theme: (() => {
            try {
                @auth
                    const pref = '{{ auth()->user()->settings["preferred_theme"] ?? "" }}';
                    if (pref) return pref;
                @endauth
                const stored = localStorage.getItem('nb_theme');
                return (stored === 'dark' || stored === 'light') ? stored : 'dark';
            } catch (e) {
                return 'light';
            }
        })(),
        hoverTheme: null,
        selectedOrder: null,
        profileOpen: false,
        notificationsOpen: false,
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
                'dark': '#050507',
                'light': '#FAF7F0',
            };
            const textColors = {
                'dark': '#E4E4E7',
                'light': '#1C1C20',
            };
            if (bgColors[val]) {
                document.documentElement.style.backgroundColor = bgColors[val];
            }
            if (textColors[val]) {
                document.documentElement.style.color = textColors[val];
            }
            @auth
                $wire.updatePreferredTheme(val);
            @endauth
        });

        // Listen for theme settings changes to align site theme automatically
        window.addEventListener('theme-settings-changed', e => {
            theme = e.detail;
        });
    "
    :class="{
        'bg-[#050507] text-[#E4E4E7]': theme === 'dark',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'light',
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-x-hidden storefront-grain"
>
    <!-- Interactive SVG ambient floral background overlay -->
    <svg id="flower-ambient-svg" class="fixed inset-0 w-full h-full pointer-events-none z-0 opacity-15 overflow-hidden" style="perspective: 800px; transform-style: preserve-3d;"></svg>

    <!-- Fine Linen Organic Grid Overlay -->
    <div class="absolute inset-0 pointer-events-none fine-linen z-0 opacity-[0.035]"></div>
    
    <!-- Luxury Cohesive Header -->
    <header 
        class="fixed top-0 inset-x-0 w-full h-24 z-50 transition-all duration-500 flex items-center shadow-md hover:shadow-lg group backdrop-blur-xl animate-layer-1 theme-section"
        :class="{
            'bg-[#050507]/80 border-b border-neutral-800/60 shadow-2xl text-white': theme === 'dark',
            'bg-[#FAF7F0]/80 border-b border-neutral-200 shadow-md text-neutral-900': theme === 'light',
        }"
    >
        {{-- Bottom Glow Line --}}
        <div class="absolute bottom-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent to-transparent"
             :class="{
                 'via-[#C5A880]/30': theme === 'dark',
                 'via-emerald-600/30': theme === 'light',
             }"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-center select-none cursor-pointer group/brand transition-transform duration-300 hover:scale-[1.02]">
                <div class="flex flex-col text-left leading-none">
                    <span class="text-[12px] font-mono tracking-[0.35em] uppercase font-bold brand-title-atelier transition-colors duration-500"
                          :class="{
                              'text-[#C5A880]': theme === 'dark',
                              'text-emerald-700': theme === 'light',
                          }">Atelier</span>
                    <span class="text-lg sm:text-xl md:text-2xl font-extrabold uppercase tracking-[0.18em] font-outfit mt-0.5 brand-title-main transition-colors duration-500"
                          :class="{
                              'text-white': theme === 'dark',
                              'text-neutral-900': theme === 'light',
                          }">Noir & Bloom</span>
                </div>
            </a>
            <!-- Spacing column to balance layout -->
            <div class="flex-1 hidden md:block"></div>

            <div class="flex items-center space-x-6 text-sm font-mono font-semibold uppercase tracking-widest text-neutral-400">
                <!-- Navigation links -->
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 animate-nav-item select-none cursor-pointer" style="animation-delay: 200ms;">Services</a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-5 py-2.5 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer {{ request()->routeIs('curate') ? 'border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880] font-semibold' : 'border-[#C5A880]/30 hover:border-[#C5A880] hover:bg-[#C5A880]/5 text-[#C5A880]' }}"
                   style="animation-delay: 250ms;">
                   Curation Studio
                </a>

                {{-- Theme Switcher Dropdown (Header) --}}
                <div x-data="{ themeMenuOpen: false }" class="relative inline-block text-left select-none animate-nav-item">
                    <button @click="themeMenuOpen = !themeMenuOpen" 
                            class="px-5 py-2.5 border rounded-full text-xs font-semibold tracking-[0.1em] transition-all flex items-center space-x-2 cursor-pointer"
                            :class="{
                                'border-neutral-800 bg-neutral-900/40 text-neutral-350 hover:text-white': theme === 'dark',
                                'border-neutral-250 bg-neutral-50 text-neutral-700 hover:text-neutral-900': theme === 'light',
                            }"
                    >
                        <span class="w-2.5 h-2.5 rounded-full"
                              :class="{
                                  'bg-[#C5A880]': theme === 'dark',
                                  'bg-emerald-600': theme === 'light',
                              }"></span>
                        <span class="uppercase font-mono text-xs tracking-widest" x-text="theme"></span>
                        <svg class="w-3.5 h-3.5 stroke-current fill-none transition-transform duration-300" :class="{ 'rotate-180': themeMenuOpen }" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <!-- Dropdown Panel -->
                    <div x-show="themeMenuOpen"
                         @click.away="themeMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         :class="{
                             'bg-[#0F0F12]/95 border-neutral-900 text-white shadow-2xl': theme === 'dark',
                             'bg-white/95 border-neutral-200 text-neutral-900 shadow-xl': theme === 'light',
                         }"
                         class="absolute right-0 mt-2.5 w-64 rounded-2xl border p-4.5 z-50 backdrop-blur-md space-y-3"
                         style="display: none;"
                    >
                        <div class="border-b border-neutral-500/10 pb-1.5">
                            <span class="text-[9px] font-mono uppercase tracking-[0.2em] text-neutral-500">Theme Swatches</span>
                        </div>
                        <div class="space-y-2 text-[11px] font-sans">
                            <!-- Onyx Option -->
                            <button @click="changeTheme('dark'); themeMenuOpen = false;" class="w-full flex items-center justify-between p-2 rounded-xl transition-all text-left cursor-pointer hover:bg-neutral-500/5">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-[#C5A880]"></span>
                                    <div>
                                        <span class="font-bold font-mono tracking-wider block text-[10px] uppercase">Onyx Theme</span>
                                        <span class="text-[8px] text-neutral-500 font-light block mt-0.5">Obsidian mode with deep gold accents.</span>
                                    </div>
                                </div>
                            </button>
                            <!-- Champagne Option -->
                            <button @click="changeTheme('light'); themeMenuOpen = false;" class="w-full flex items-center justify-between p-2 rounded-xl transition-all text-left cursor-pointer hover:bg-neutral-500/5">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                                    <div>
                                        <span class="font-bold font-mono tracking-wider block text-[10px] uppercase">Champagne Theme</span>
                                        <span class="text-[8px] text-neutral-500 font-light block mt-0.5">Creamy light mode with emerald highlights.</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Notification Bell button --}}
                <button @click="notificationsOpen = true" 
                        class="transition-colors cursor-pointer select-none relative w-11 h-11 flex items-center justify-center rounded-full shadow-sm" 
                        :class="{
                            'border border-neutral-700 bg-neutral-900/40 text-neutral-350 hover:text-[#C5A880]': theme === 'dark',
                            'border border-neutral-200 bg-neutral-50 text-neutral-700 hover:text-emerald-700': theme === 'light',
                        }"
                        title="View Notifications"
                >
                    <svg class="w-5.5 h-5.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($unreadNotificationsCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-white text-[10px] font-bold font-sans shadow-md animate-pulse"
                              :class="{
                                  'bg-[#C5A880]': theme === 'dark',
                                  'bg-emerald-600': theme === 'light',
                              }">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </button>

                <!-- Modern SVG shopping bag cart button redirecting to storefront open cart -->
                <a href="/?open_cart=true" 
                        class="transition-colors cursor-pointer select-none relative w-11 h-11 flex items-center justify-center rounded-full shadow-sm animate-nav-item" 
                        :class="{
                            'border border-neutral-700 bg-neutral-900/40 text-neutral-350 hover:text-[#C5A880]': theme === 'dark',
                            'border border-neutral-200 bg-neutral-50 text-neutral-700 hover:text-emerald-700': theme === 'light',
                        }"
                        style="animation-delay: 400ms;"
                        title="View Curation Drawer"
                >
                    <svg class="w-5.5 h-5.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-white text-[10px] font-bold font-sans shadow-md"
                              :class="{
                                  'bg-[#C5A880]': theme === 'dark',
                                  'bg-emerald-600': theme === 'light',
                              }">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Simplified Profile Portal Button -->
                <div class="relative inline-block text-left animate-nav-item" style="animation-delay: 500ms;">
                    @auth
                        <!-- Initials-based Monogram Avatar Button -->
                        <button @click="profileOpen = true" 
                                class="transition-all cursor-pointer select-none w-10 h-10 flex items-center justify-center rounded-full shadow-sm shadow-[#C5A880]/10 border border-[#C5A880]"
                                title="Profile Portal Options"
                        >
                            <span class="text-xs font-mono font-bold tracking-wider uppercase text-[#C5A880]">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                            </span>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-8xl w-full mx-auto px-6 pt-36 flex-1 flex flex-col lg:flex-row gap-8 z-10 relative">
        
        <!-- Left Sidebar Navigation -->
        <aside 
            :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" 
            class="w-full lg:w-64 shrink-0 sticky top-28 p-6 border rounded-[32px] backdrop-blur-xl space-y-6 text-left transition-all duration-500 self-start h-auto"
        >
            <div class="border-b border-neutral-500/10 pb-4">
                <span class="text-[10px] font-mono uppercase tracking-[0.25em] text-neutral-500 block">Atelier Personal Portal</span>
                <h4 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs font-semibold uppercase tracking-widest mt-1">Dashboard Portal</h4>
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
                    <a href="/admin" :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full mt-2 py-2 rounded-xl text-[10px] font-mono uppercase tracking-wider font-semibold transition-all cursor-pointer text-center block shadow-sm">
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
                    <div :class="theme === 'light' ? 'bg-white/50 border-neutral-200 text-neutral-900' : 'bg-neutral-900/30 text-white border-neutral-900/40'" class="border p-6 rounded-[32px] flex flex-col sm:flex-row gap-6 items-center backdrop-blur-md">
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
                    <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Personal Information Parameters</h4>
                        
                        @if(session('success_profile'))
                            <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_profile') }}</div>
                        @endif

                        <form wire:submit.prevent="updateProfile" class="space-y-4 text-xs font-mono">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Full Name</label>
                                    <input type="text" wire:model="name" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Email Address</label>
                                    <input type="email" wire:model="email" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('email') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Phone Number</label>
                                    <input type="text" wire:model="phone_number" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('phone_number') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">KRA PIN (Optional)</label>
                                    <input type="text" wire:model="kra_pin" placeholder="A000000000Z" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans uppercase">
                                    @error('kra_pin') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Gender (Optional)</label>
                                    <select wire:model="gender" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-3 py-2 focus:outline-none text-sm font-sans cursor-pointer">
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
                                    <input type="date" wire:model="dob" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('dob') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Hub Region</label>
                                    <select wire:model="default_region" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-3 py-2 focus:outline-none text-sm font-sans cursor-pointer">
                                        <option value="Nairobi">Nairobi</option>
                                        <option value="Kiambu">Kiambu</option>
                                    </select>
                                    @error('default_region') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Default Delivery Coordinates/Landmark</label>
                                    <input type="text" wire:model="default_delivery_address" placeholder="Estate, Complex, Street Name" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
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
                                                    :class="theme === 'light' ? 'bg-white border-neutral-250 text-black placeholder-neutral-400' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600'"
                                                    class="flex-1 border rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-neutral-400 font-sans"
                                                >
                                                <button 
                                                    type="button" 
                                                    @click="searchLocation()"
                                                    :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-850' : 'bg-white text-black hover:bg-neutral-200'"
                                                    class="px-3.5 py-1.5 rounded-xl text-[10px] font-mono uppercase tracking-wider cursor-pointer font-bold"
                                                >
                                                    Find
                                                </button>
                                            </div>
                                            <!-- Autocomplete Results Dropdown -->
                                            <div 
                                                x-show="searchResults.length > 0" 
                                                @click.away="searchResults = []"
                                                :class="theme === 'light' ? 'bg-[#FAF7F0] border-neutral-250 text-neutral-900 shadow-lg' : 'bg-[#0F0F12] border-neutral-800 text-white shadow-lg'"
                                                class="absolute left-0 right-0 z-[1000] mt-1 max-h-48 overflow-y-auto border rounded-xl text-xs"
                                                style="display: none;"
                                            >
                                                <template x-for="res in searchResults" :key="res.place_id">
                                                    <div 
                                                        @click="selectResult(res)"
                                                        :class="theme === 'light' ? 'hover:bg-neutral-200/50' : 'hover:bg-white/10'"
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

                            <button type="submit" :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer">
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
                        <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
                            <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Reset Security Credentials</h4>
                            
                            @if(session('success_password'))
                                <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl mb-3">{{ session('success_password') }}</div>
                            @elseif(session('error_password'))
                                <div class="p-3 border border-dashed border-rose-850 bg-rose-950/20 text-rose-400 text-xs font-mono rounded-xl mb-3">{{ session('error_password') }}</div>
                            @endif

                            <form wire:submit.prevent="updatePassword" class="space-y-4 text-xs font-mono">
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Current Password</label>
                                    <input type="password" wire:model="current_password" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">New Password</label>
                                    <input type="password" wire:model="new_password" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                    @error('new_password') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-neutral-500 uppercase">Confirm New Password</label>
                                    <input type="password" wire:model="new_password_confirmation" :class="theme === 'light' ? 'bg-white/80 border-neutral-250 text-black focus:border-[#B59A7A]' : 'bg-[#0F0F12] border-neutral-800 text-white focus:border-neutral-600'" class="w-full border rounded-xl px-4 py-2 focus:outline-none text-sm font-sans">
                                </div>
                                <button type="submit" :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer">
                                    Update Security Key
                                </button>
                            </form>
                        </div>

                        <!-- Session Logs (Mock Security Details) -->
                        <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
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

                    @if(session('success_orders'))
                        <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_orders') }}</div>
                    @endif

                    <!-- Order History Logs -->
                    <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
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
                                                    @if($ord->status === 'delivered')
                                                         @if($ord->rating)
                                                             <div class="flex flex-col items-end space-y-0.5">
                                                                 <div class="flex items-center space-x-1" title="Overall: {{ $ord->rating }}/5 (Feedback: {{ $ord->feedback }})">
                                                                     @for($i = 1; $i <= 5; $i++)
                                                                         <span class="text-xs {{ $i <= $ord->rating ? 'text-[#C5A880]' : 'text-neutral-600' }}">★</span>
                                                                     @endfor
                                                                 </div>
                                                                 @if($ord->product_rating || $ord->packaging_rating || $ord->delivery_rating)
                                                                     <span class="text-[8px] text-neutral-500 font-mono tracking-tight block">
                                                                         P:{{ $ord->product_rating ?: '-' }} | PK:{{ $ord->packaging_rating ?: '-' }} | D:{{ $ord->delivery_rating ?: '-' }}
                                                                     </span>
                                                                 @endif
                                                             </div>
                                                         @else
                                                             <button wire:click="openRatingModal({{ $ord->id }})" class="bg-[#C5A880]/15 hover:bg-[#C5A880]/30 text-[#C5A880] px-2.5 py-1.5 rounded-lg text-[9px] font-bold uppercase transition-all border border-[#C5A880]/20 cursor-pointer">
                                                                 Rate Order
                                                             </button>
                                                         @endif
                                                    @else
                                                        <!-- Timeline Tracking Trigger -->
                                                        <button @click="selectedOrder = { id: {{ $ord->id }}, status: '{{ $ord->status }}', created: '{{ $ord->created_at->format('d M Y H:i') }}', total: '{{ number_format($ord->total_amount) }}', instructions: '{{ addslashes($ord->special_instructions) }}', distance_km: '{{ $ord->getRouteDetails()['distance_km'] ?? '' }}', duration_min: '{{ $ord->getRouteDetails()['duration_min'] ?? '' }}', hub_name: '{{ $ord->getRouteDetails()['hub_name'] ?? '' }}' }" class="bg-neutral-500/10 hover:bg-neutral-500/20 px-2.5 py-1.5 rounded-lg text-[9px] font-bold uppercase cursor-pointer transition-all border border-neutral-500/10">
                                                            Track
                                                        </button>
                                                    @endif
                                                    
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
                    <div @click.stop :class="theme === 'light' ? 'bg-white/95 border-neutral-250 text-neutral-900' : 'bg-[#0F0F12]/95 border-neutral-850 text-white'" class="w-full max-w-md border rounded-[32px] p-6 space-y-6 shadow-2xl backdrop-blur-xl">
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

                        <div class="border-t border-neutral-500/10 pt-4 text-[10px] font-mono leading-relaxed space-y-1" x-show="selectedOrder?.distance_km">
                            <span class="text-neutral-500 block uppercase">Logistics & Route Approximation:</span>
                            <div class="text-neutral-350 font-sans space-y-0.5">
                                <div>Fulfilled from: <span class="font-semibold" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" x-text="selectedOrder?.hub_name"></span></div>
                                <div>Estimated driving distance: <span class="font-semibold" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" x-text="selectedOrder?.distance_km + ' km'"></span></div>
                                <div>Approximate driving duration: <span class="font-semibold" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" x-text="selectedOrder?.duration_min + ' minutes'"></span></div>
                            </div>
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
                            <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 hover:bg-white/75 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 hover:bg-[#0C0C0E]/70 shadow-2xl'" class="border p-4.5 rounded-[28px] space-y-3 transition-all duration-300 relative group backdrop-blur-xl flex flex-col justify-between h-auto">
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
                                        <h4 :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" class="text-sm font-serif italic truncate">{{ $prod->name }}</h4>
                                        <p class="text-[10px] text-neutral-450 line-clamp-2 leading-relaxed font-light font-sans">{{ $prod->description }}</p>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-neutral-500/10 flex items-center justify-between mt-auto">
                                    <span class="text-xs font-mono font-bold text-amber-500">{{ number_format($prod->price) }} KSH</span>
                                    <button wire:click="addToCurationFromWishlist({{ $prod->id }})" :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="px-4 py-1.5 rounded-lg text-[10px] font-mono uppercase tracking-wider font-semibold cursor-pointer transition-all shadow-sm">
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

                    <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-6 backdrop-blur-xl">
                        <h4 class="text-md font-mono uppercase tracking-wider text-[#C5A880] font-bold pb-2 border-b border-neutral-500/5">&bull; Personal Preferences</h4>
                        
                        @if(session('success_settings'))
                            <div class="p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl">{{ session('success_settings') }}</div>
                        @endif

                        <div class="space-y-4 text-xs font-mono">
                            <!-- Theme selector setting -->
                            <div class="space-y-2 border-b border-neutral-500/5 pb-4">
                                <label class="text-neutral-400 uppercase font-semibold">Preferred Workspace Default Theme</label>
                                <div class="grid grid-cols-2 gap-4 pt-1 max-w-sm">
                                    <button type="button" wire:click="$set('preferred_theme', 'dark')" :class="preferred_theme === 'dark' ? 'border-[#C5A880] bg-[#C5A880]/15 text-[#C5A880] font-bold' : 'border-neutral-500/20 text-neutral-500 hover:text-neutral-300'" class="p-3 border rounded-2xl cursor-pointer text-center transition-all">
                                        Onyx (Dark)
                                    </button>
                                    <button type="button" wire:click="$set('preferred_theme', 'light')" :class="preferred_theme === 'light' ? 'border-[#B59A7A] bg-[#B59A7A]/15 text-[#B59A7A] font-bold' : 'border-neutral-500/20 text-neutral-500 hover:text-neutral-300'" class="p-3 border rounded-2xl cursor-pointer text-center transition-all">
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

                            <button type="button" wire:click="updateSettings" :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full py-2.5 rounded-xl transition-all duration-300 font-bold uppercase cursor-pointer mt-4">
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

                    <div :class="theme === 'light' ? 'border-neutral-200 bg-white/45 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/40 shadow-2xl'" class="border p-6 rounded-[32px] space-y-4 backdrop-blur-xl">
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
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'dark',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'light'
        }"
        class="border-t mt-20 py-10 px-6 transition-colors duration-500 z-10 relative theme-section"
    >
        <div class="max-w-5xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-left">
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'light' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors">Noir & Bloom</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'light' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><a href="/?collection=retail" class="hover:underline">Bespoke Retail Arrays</a></li>
                    <li><a href="/?collection=wholesale" class="hover:underline">Wholesale Graded Stems</a></li>
                    <li><a href="/?collection=giftings" class="hover:underline">Luxury Giftings</a></li>
                    <li><a href="/profile-portal" class="hover:underline">Atelier Loyalty Circle</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'light' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><span class="block text-neutral-500">Operating Hours</span> Mon &mdash; Sat: 07:00 &mdash; 20:00</li>
                    <li>Sunday: 09:00 &mdash; 17:00</li>
                    <li class="pt-2"><span class="block text-neutral-500 font-mono text-[11px] uppercase tracking-wider">Hotline Direct</span> +254 (0) 712 345 678</li>
                    <li>concierge@noirbloom.co.ke</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 :class="theme === 'light' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Atelier Bulletin</h5>
                <p class="text-xs font-light leading-relaxed">
                    Subscribe for seasonal curation updates, wholesale catalog changes, and exclusive releases.
                </p>
                <div class="flex items-center space-x-2 pt-1">
                    <input 
                        type="email" 
                        placeholder="you@company.co.ke" 
                        :class="theme === 'light' ? 'bg-white border-neutral-300 text-black placeholder-neutral-400 focus:border-neutral-500' : 'bg-neutral-900/60 border-neutral-800 text-white placeholder-neutral-700 focus:border-neutral-700'"
                        class="flex-1 text-xs px-3.5 py-2.5 border rounded-xl focus:outline-none transition-all"
                    >
                    <button 
                        :class="theme === 'light' ? 'bg-neutral-950 text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                        class="px-4 py-2.5 text-[11px] font-mono uppercase tracking-wider font-semibold rounded-full transition-all"
                    >
                        Join
                    </button>
                </div>
            </div>
        </div>

        <div :class="theme === 'light' ? 'border-neutral-200/60 text-neutral-500' : 'border-neutral-900 text-neutral-600'" class="max-w-5xl w-full mx-auto border-t mt-10 pt-6 flex flex-col md:flex-row justify-between items-center text-[12px] font-mono uppercase tracking-wider gap-4">
            <p>&copy; {{ date('Y') }} Atelier Noir & Bloom. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-neutral-400">Terms of Curation</a>
                <a href="#" class="hover:text-neutral-400">Logistics Policy</a>
                <a href="#" class="hover:text-neutral-400">Invoice Request</a>
            </div>
        </div>
    </footer>

    <!-- Backdrop for Profile Modal -->
    <div x-show="profileOpen" @click="profileOpen = false" class="fixed inset-0 z-45 bg-black/40 backdrop-blur-xl" style="display: none;"></div>

    <!-- Profile Overlay Panel (Center Modal) -->
    <div 
        x-show="profileOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        :class="theme === 'light' ? 'bg-[#FAF7F0]/80 border-neutral-200 text-neutral-900 shadow-2xl' : 'bg-[#0F0F12]/90 border border-neutral-900 text-white shadow-2xl'"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[500px] max-h-[85vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl rounded-[32px] overflow-hidden"
        style="display: none;"
    >
        <div :class="theme === 'light' ? 'border-neutral-100' : 'border-neutral-900'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div>
                <h3 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Profile Portal</h3>
                <span class="text-[9px] text-neutral-500 font-light">Atelier Customer Account</span>
            </div>
            <button @click="profileOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Modal">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[calc(85vh-150px)] scrollbar-none">
            @auth
                @php
                    $client = \App\Models\Client::where('email', auth()->user()->email)->first();
                    $totalOrdersCount = $client ? $client->orders()->count() : 0;
                    $activeOrdersCount = $client ? $client->orders()->whereNotIn('status', ['delivered', 'cancelled'])->count() : 0;
                @endphp
                <!-- Profile Header Segment -->
                <div class="flex items-center space-x-4 pb-4 border-b border-neutral-500/10">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-950 via-neutral-900 to-neutral-955 border-2 shadow-md shrink-0"
                         :class="{
                             'border-[#C5A880]/40': theme === 'dark',
                             'border-emerald-600/40': theme === 'light',
                         }">
                        <span class="text-base font-mono font-bold tracking-wider"
                              :class="{
                                  'text-[#C5A880]': theme === 'dark',
                                  'text-emerald-800': theme === 'light',
                              }">
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                        </span>
                    </div>
                    <div class="truncate space-y-0.5">
                        <div class="flex items-center space-x-2">
                            <span class="font-serif italic text-base tracking-wide font-semibold block truncate" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'">{{ auth()->user()->name }}</span>
                            <span class="text-[8px] font-mono uppercase tracking-widest px-2 py-0.5 border rounded-full shrink-0"
                                  :class="{
                                      'border-[#C5A880]/30 bg-[#C5A880]/5 text-[#C5A880]': theme === 'dark',
                                      'border-emerald-600/30 bg-emerald-55 text-emerald-850': theme === 'light',
                                  }">
                                {{ auth()->user()->loyalty_tier }}
                            </span>
                        </div>
                        <span class="text-[10px] text-neutral-450 block font-mono tracking-tight">{{ auth()->user()->email }}</span>
                    </div>
                </div>

                <!-- Account Stats Grid -->
                <div class="grid grid-cols-3 gap-2.5 text-center py-2">
                    <div class="p-3 border border-neutral-500/5 rounded-2xl bg-neutral-500/5">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-500 block">Client Since</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="p-3 border border-neutral-500/5 rounded-2xl bg-neutral-500/5">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-500 block">Total Curations</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ $totalOrdersCount }}</span>
                    </div>
                    <div class="p-3 border border-neutral-500/5 rounded-2xl bg-neutral-500/5">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-500 block">Active dispatches</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ $activeOrdersCount }}</span>
                    </div>
                </div>

                <!-- Info Segment -->
                <div class="space-y-3 font-sans py-1 text-xs">
                    <div class="flex items-center space-x-2 text-neutral-400">
                        <span class="font-bold text-[9px] uppercase tracking-wider text-[#C5A880]/80">Phone:</span>
                        <span class="font-mono" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'">{{ auth()->user()->phone_number ?: 'Not Provided' }}</span>
                    </div>

                    <div class="leading-relaxed">
                        <span class="font-bold text-[9px] uppercase tracking-wider text-[#C5A880]/80 block">Main Address</span>
                        <span class="block truncate" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" title="{{ auth()->user()->default_delivery_address }}">{{ auth()->user()->default_delivery_address ?: 'No Address Set' }}</span>
                        @if(auth()->user()->default_region)
                            <span class="text-[9px] font-mono text-neutral-500 uppercase tracking-wider block mt-0.5">{{ auth()->user()->default_region }}</span>
                        @endif
                    </div>
                </div>

                <!-- Loyalty Points -->
                <div class="border-t border-neutral-500/10 pt-4 space-y-2 font-mono text-[11px]">
                    <div class="flex justify-between items-center">
                        <span class="text-neutral-500">Loyalty Balance:</span>
                        @if(auth()->user()->loyalty_points > 1)
                            <span class="loyalty-vip-glow bg-amber-500/10 border border-amber-500/30 text-amber-500 font-bold px-2.5 py-1 rounded-full text-[10px] flex items-center space-x-1">
                                <span class="relative flex h-1.5 w-1.5 shrink-0">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                </span>
                                <span class="loyalty-shimmer-text tracking-wide font-black">{{ number_format(auth()->user()->loyalty_points) }} PTS</span>
                            </span>
                        @else
                            <span class="text-neutral-450 font-bold">{{ number_format(auth()->user()->loyalty_points) }} PTS</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2.5 pt-4">
                    <button @click="profileOpen = false; $wire.setTab('details')" 
                       :class="{
                           'bg-[#C5A880] hover:bg-[#B59A7A] text-black': theme === 'dark',
                           'bg-emerald-800 hover:bg-emerald-950 text-white': theme === 'light',
                       }"
                       class="w-full text-center font-mono font-bold uppercase tracking-wider py-3 rounded-full text-[10px] flex items-center justify-center space-x-2 transition-all transform hover:scale-[1.01] shadow-md cursor-pointer border-none"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 18c-2.204 0-4.224-.788-5.79-2.104m0 0L3.75 18M12 13.5a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
                        </svg>
                        <span>View Profile Dashboard</span>
                    </button>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                :class="{
                                    'border-neutral-800 text-neutral-400 hover:text-rose-500 hover:border-rose-950': theme === 'dark',
                                    'border-neutral-200 text-neutral-500 hover:text-rose-600 hover:border-rose-250': theme === 'light',
                                }"
                                class="w-full border font-mono font-bold uppercase tracking-wider py-2.5 rounded-full text-[9px] flex items-center justify-center space-x-2 transition-all cursor-pointer bg-transparent"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            <span>Sign Out of Atelier</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <!-- Backdrop for Notifications Modal -->
    <div x-show="notificationsOpen" @click="notificationsOpen = false" class="fixed inset-0 z-45 bg-black/40 backdrop-blur-xl" style="display: none;"></div>

    <!-- Notifications Overlay Panel (Center Modal) -->
    <div 
        x-show="notificationsOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        :class="theme === 'light' ? 'bg-[#FAF7F0]/80 border-neutral-200 text-neutral-900 shadow-2xl' : 'bg-[#0F0F12]/90 border border-neutral-900 text-white shadow-2xl'"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[500px] max-h-[80vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl rounded-[32px] overflow-hidden"
        style="display: none;"
    >
        <div :class="theme === 'light' ? 'border-neutral-100' : 'border-neutral-900'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div>
                <h3 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Notification Log</h3>
                <span class="text-[9px] text-neutral-500 font-light">Inbox logs & system alerts</span>
            </div>
            <button @click="notificationsOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Modal">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="flex-1 flex flex-col justify-between overflow-hidden">
            <div class="flex-1 overflow-y-auto p-5 space-y-4 max-h-[calc(80vh-160px)] scrollbar-none">
                @auth
                    @forelse($notificationsList as $notif)
                        @php
                            $notifCls = match($notif['type'] ?? 'info') {
                                'success' => 'bg-emerald-500/10 border-emerald-500/25',
                                'warning' => 'bg-amber-500/10 border-amber-500/25',
                                'order' => 'bg-teal-500/10 border-teal-500/25',
                                default => 'bg-neutral-500/5 border-neutral-500/10'
                            };
                            $badgeCls = match($notif['type'] ?? 'info') {
                                'success' => 'text-emerald-500 bg-emerald-500/10 border-emerald-500/20',
                                'warning' => 'text-amber-500 bg-amber-500/10 border-amber-500/20',
                                'order' => 'text-teal-500 bg-teal-500/10 border-teal-500/20',
                                default => 'text-neutral-400 bg-neutral-500/10 border-neutral-500/20'
                            };
                        @endphp
                        <div x-data="{ expanded: false }" class="border p-4 rounded-2xl transition-all {{ $notifCls }} flex flex-col gap-2 relative group text-xs">
                            @if(!$notif['is_read'])
                                <span class="absolute top-4 right-4 w-2 h-2 rounded-full"
                                      :class="{
                                          'bg-[#C5A880]': theme === 'dark',
                                          'bg-emerald-600': theme === 'light',
                                      }"></span>
                            @endif
                            
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 border text-[8px] font-mono uppercase tracking-widest rounded-md {{ $badgeCls }}">
                                    {{ $notif['type'] ?? 'info' }}
                                </span>
                                <span class="text-[9px] font-mono text-neutral-500">
                                    {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-serif italic text-xs tracking-wide font-semibold text-text-primary">{{ $notif['title'] }}</h4>
                                <p @click="expanded = !expanded" class="text-[11px] text-neutral-450 leading-relaxed font-light mt-1 cursor-pointer" :class="{ 'line-clamp-2': !expanded }">
                                    {{ $notif['message'] }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2 pt-2 border-t border-neutral-500/5 mt-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                @if(!$notif['is_read'])
                                    <button wire:click="markNotificationAsRead({{ $notif['id'] }})" class="text-[9px] font-mono uppercase tracking-widest text-[#C5A880] hover:underline cursor-pointer">
                                        [ Mark Read ]
                                    </button>
                                @endif
                                <button wire:click="deleteNotification({{ $notif['id'] }})" class="text-[9px] font-mono uppercase tracking-widest text-neutral-500 hover:text-red-400 hover:underline cursor-pointer">
                                    [ Delete ]
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-neutral-500 text-xs flex flex-col items-center justify-center space-y-2">
                            <svg class="w-8 h-8 text-neutral-600 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Your notification log is currently clear.</span>
                        </div>
                    @endforelse
                @else
                    <div class="text-center py-12 text-neutral-500 text-xs">
                        Please sign in to view your notification logs.
                    </div>
                @endauth
            </div>
            
            @auth
                @if(count($notificationsList) > 0)
                    <div :class="theme === 'light' ? 'border-neutral-100' : 'border-neutral-900'" class="p-4 border-t bg-neutral-500/5 flex items-center justify-between shrink-0">
                        <button wire:click="clearAllNotifications" class="text-[10px] font-mono uppercase tracking-widest text-neutral-500 hover:text-red-400 cursor-pointer">
                            [ Clear All ]
                        </button>
                        <button @click="notificationsOpen = false" class="bg-[#C5A880] hover:bg-[#B59A7A] text-black text-[9px] font-mono font-bold uppercase tracking-widest px-4 py-2 rounded-xl transition-all cursor-pointer">
                            Dismiss
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Centered Rating Modal -->
    @if($ratingOrderId)
        <!-- Backdrop for Rating Modal -->
        <div class="fixed inset-0 z-45 bg-black/40 backdrop-blur-xl" wire:click="$set('ratingOrderId', null)"></div>

        <!-- Rating Overlay Panel (Center Modal) -->
        <div 
            :class="theme === 'light' ? 'bg-[#FAF7F0]/80 border-neutral-200 text-neutral-900 shadow-2xl' : 'bg-[#0F0F12]/90 border border-neutral-900 text-white shadow-2xl'"
            class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[500px] max-h-[90vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl border rounded-[32px] overflow-hidden"
        >
            <!-- Modal Header -->
            <div :class="theme === 'light' ? 'border-neutral-100' : 'border-neutral-900'" class="p-5 border-b flex items-center justify-between shrink-0">
                <div>
                    <h3 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Rate Order #NB-ORD-{{ $ratingOrderId }}</h3>
                    <span class="text-[9px] text-neutral-500 font-light">Share your feedback to help us bloom</span>
                </div>
                <button wire:click="$set('ratingOrderId', null)" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Modal">
                    <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 max-h-[calc(90vh-150px)] scrollbar-none">
                <!-- Overall Experience -->
                <div class="space-y-2">
                    <label class="text-[10px] font-mono uppercase tracking-wider text-neutral-450 block">Overall Experience</label>
                    <div class="flex items-center space-x-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('ratingOverall', {{ $i }})" class="text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer {{ $ratingOverall >= $i ? 'text-[#C5A880]' : 'text-neutral-600' }}">★</button>
                        @endfor
                    </div>
                </div>

                <!-- Product & Bloom Quality -->
                <div class="space-y-2">
                    <label class="text-[10px] font-mono uppercase tracking-wider text-neutral-450 block">Product & Bloom Quality</label>
                    <div class="flex items-center space-x-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('ratingProduct', {{ $i }})" class="text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer {{ $ratingProduct >= $i ? 'text-[#C5A880]' : 'text-neutral-600' }}">★</button>
                        @endfor
                    </div>
                </div>

                <!-- Packaging & Presentation -->
                <div class="space-y-2">
                    <label class="text-[10px] font-mono uppercase tracking-wider text-neutral-450 block">Packaging & Presentation</label>
                    <div class="flex items-center space-x-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('ratingPackaging', {{ $i }})" class="text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer {{ $ratingPackaging >= $i ? 'text-[#C5A880]' : 'text-neutral-600' }}">★</button>
                        @endfor
                    </div>
                </div>

                <!-- Delivery Speed & Concierge Courtesy -->
                <div class="space-y-2">
                    <label class="text-[10px] font-mono uppercase tracking-wider text-neutral-450 block">Delivery & Concierge Courtesy</label>
                    <div class="flex items-center space-x-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('ratingDelivery', {{ $i }})" class="text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer {{ $ratingDelivery >= $i ? 'text-[#C5A880]' : 'text-neutral-600' }}">★</button>
                        @endfor
                    </div>
                </div>

                <!-- Remarks -->
                <div class="space-y-2">
                    <label class="text-[10px] font-mono uppercase tracking-wider text-neutral-450 block">Additional Remarks</label>
                    <textarea wire:model="ratingComments" rows="3" placeholder="Tell us about your experience..."
                              :class="theme === 'light' ? 'bg-neutral-50 border-neutral-200 text-neutral-900 focus:border-emerald-600' : 'bg-neutral-900 border-neutral-800 text-white focus:border-[#C5A880]'"
                              class="w-full text-xs p-3 rounded-2xl border transition-colors focus:outline-none"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div :class="theme === 'light' ? 'border-neutral-100 bg-neutral-50/50' : 'border-neutral-900 bg-neutral-500/5'" class="p-4 border-t flex items-center justify-between shrink-0">
                <button wire:click="$set('ratingOrderId', null)" class="text-[10px] font-mono uppercase tracking-widest text-neutral-500 hover:text-red-400 cursor-pointer">
                    Cancel
                </button>
                <button wire:click="submitDetailedRating" class="bg-[#C5A880] hover:bg-[#B59A7A] text-black text-[9px] font-mono font-bold uppercase tracking-widest px-5 py-2.5 rounded-xl transition-all cursor-pointer">
                    Submit Feedback
                </button>
            </div>
        </div>
    @endif
</div>