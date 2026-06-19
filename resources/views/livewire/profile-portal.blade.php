@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

<div 
    x-data="{ 
        theme: (localStorage.getItem('nb_theme') === 'onyx' || localStorage.getItem('nb_theme') === 'champagne') ? localStorage.getItem('nb_theme') : 'onyx',
        hoverTheme: null,
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
                'onyx': '#0B0B0D',
                'champagne': '#FAF7F0'
            };
            const textColors = {
                'onyx': '#E4E4E7',
                'champagne': '#1C1C20'
            };
            document.documentElement.style.backgroundColor = bgColors[val];
            document.documentElement.style.color = textColors[val];
        });
    "
    :class="{
        'bg-[#0B0B0D] text-[#E4E4E7]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-hidden"
>
    <!-- 3D Flower Ambient Animation Canvas -->

    <canvas id="flower-ambient-canvas" wire:ignore x-data="canvasAmbient" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Fine Grain Noise Overlay -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-80"></div>
    
    <!-- Luxury Cohesive Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne',
            'bg-[#15060A]/75 border-[#2D0D19]/30 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group theme-section"
    >
        <!-- Gold Accent Bottom Glow Line -->
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-[#C5A880]/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 animate-nav-item select-none cursor-pointer" style="animation-delay: 100ms;">
                <span class="text-[11px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
                <span :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
            </a>
            <!-- Spacing column to balance layout -->
            <div class="flex-1 hidden md:block"></div>

            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Navigation links -->
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 animate-nav-item select-none cursor-pointer" style="animation-delay: 200ms;">Services</a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-4 py-1.5 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer {{ request()->routeIs('curate') ? 'border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880] font-semibold' : 'border-[#C5A880]/30 hover:border-[#C5A880] hover:bg-[#C5A880]/5 text-[#C5A880]' }}"
                   style="animation-delay: 250ms;">
                   Curate Your Arrangement
                </a>

                <!-- Theme Switcher Pill (2 options, desktop only) -->
                <div class="hidden lg:flex items-center space-x-1 border border-neutral-500/10 rounded-full bg-neutral-500/5 p-1 animate-nav-item select-none relative" style="animation-delay: 300ms;">
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
                         :class="theme === 'champagne' ? 'bg-white border-neutral-200 text-neutral-900 shadow-xl' : 'bg-[#0F0F12]/95 border-neutral-800 text-white shadow-2xl'"
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-3.5 w-64 rounded-2xl border p-4 text-left z-50 text-xs backdrop-blur-md space-y-3"
                         style="display: none;"
                    >
                        <!-- Onyx Preview Content -->
                        <div x-show="hoverTheme === 'onyx'" class="space-y-2">
                            <span class="font-bold text-[#C5A880] tracking-wider uppercase text-[10px] block">Onyx Theme</span>
                            <p class="text-neutral-450 text-[11px] leading-relaxed font-light">Midnight Obsidian & Deep Velvet. A high-contrast dark aesthetic built for dramatic evening luxury.</p>
                            <div class="flex items-center space-x-1 pt-1">
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#0B0B0D]" title="Background"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#C5A880]" title="Accent"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#E4E4E7]" title="Text"></span>
                            </div>
                        </div>

                        <!-- Champagne Preview Content -->
                        <div x-show="hoverTheme === 'champagne'" class="space-y-2">
                            <span class="font-bold text-[#B59A7A] tracking-wider uppercase text-[10px] block">Champagne Theme</span>
                            <p class="text-neutral-500 text-[11px] leading-relaxed font-light">Warm Alabaster & Gilded Gold. A soft, light mode reflecting sunlit mornings in the flower atelier.</p>
                            <div class="flex items-center space-x-1 pt-1">
                                <span class="w-4 h-4 rounded-full border border-neutral-200 bg-[#FAF7F0]" title="Background"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-200 bg-[#B59A7A]" title="Accent"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-200 bg-[#1C1917]" title="Text"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collapsed mobile theme switcher -->
                <button @click="changeTheme(theme === 'onyx' ? 'champagne' : 'onyx')" 
                        class="lg:hidden hover:text-neutral-200 transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item"
                        style="animation-delay: 300ms;"
                        title="Cycle Theme"
                >
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="5" />
                        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.77l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </button>

                <!-- Modern SVG shopping bag cart button redirecting to storefront open cart -->
                <a href="/?open_cart=true" 
                        class="hover:text-neutral-300 transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item animate-pulse" 
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

                <!-- Profile Portal Dropdown Card Popover -->
                <div x-data="{ profileMenuOpen: false }" class="relative inline-block text-left animate-nav-item" style="animation-delay: 500ms;">
                    @auth
                        <!-- Initials-based Monogram Avatar Button -->
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                class="hover:border-[#C5A880]/60 border border-neutral-500/20 transition-all cursor-pointer select-none w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-900 via-neutral-950 to-neutral-900 shadow-md border-[#C5A880]"
                                title="Profile Portal Options"
                        >
                            <span class="text-[10px] font-mono font-bold tracking-wider text-[#C5A880] uppercase">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                            </span>
                        </button>
                    @else
                        <!-- Log In / Sign In Button for Guests -->
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                :class="{
                                    'border-[#C5A880]/30 hover:border-[#C5A880] hover:shadow-[0_0_15px_rgba(197,168,128,0.25)]': theme === 'onyx',
                                    'border-[#B59A7A]/30 hover:border-[#B59A7A] hover:shadow-[0_0_15px_rgba(181,154,122,0.25)]': theme === 'champagne',
                                    'border-[#B76E79]/30 hover:border-[#B76E79] hover:shadow-[0_0_15px_rgba(183,110,121,0.25)]': theme === 'rose'
                                }"
                                class="transition-all duration-300 hover:scale-[1.03] cursor-pointer select-none px-4 h-8 flex items-center justify-center space-x-1.5 border rounded-full bg-neutral-500/5 text-[11px] font-sans font-light tracking-widest uppercase"
                                title="Log In or Sign In"
                        >
                            <svg class="w-3.5 h-3.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3M15 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="hidden sm:inline">Sign In</span>
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
                        :class="theme === 'champagne' ? 'bg-white border-neutral-200 text-neutral-900 shadow-xl' : 'bg-[#0F0F12]/95 border-neutral-900 text-white shadow-2xl'"
                        class="absolute right-0 mt-3.5 w-80 rounded-3xl border p-5 text-left z-50 text-xs backdrop-blur-md space-y-4"
                        style="display: none;"
                    >
                        @auth
                            <div class="flex items-center space-x-3 pb-3 border-b border-neutral-500/10">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-955 via-neutral-900 to-neutral-955 border-2 border-[#C5A880]/30 shadow-md">
                                    <span class="text-sm font-mono font-bold tracking-wider text-[#C5A880]">
                                        {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                                    </span>
                                </div>
                                <div class="truncate">
                                    <span class="font-semibold block text-sm tracking-wide" :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" x-text="'{{ auth()->user()->name }}'"></span>
                                    <span class="text-[10px] text-neutral-450 block font-mono tracking-tight" x-text="'{{ auth()->user()->email }}'"></span>
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

                            <div class="border-t border-neutral-500/10 pt-3 space-y-2 font-mono text-[11px]">
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-500">Tier:</span>
                                    <span class="text-[#C5A880] font-bold text-[10px] tracking-widest uppercase" x-text="'{{ auth()->user()->loyalty_tier }}'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-500">Loyalty Points:</span>
                                    <span class="text-neutral-400 font-bold" x-text="'{{ number_format(auth()->user()->loyalty_points) }} PTS'"></span>
                                </div>
                            </div>

                            <div class="space-y-2 pt-2">
                                <a href="/profile-portal" class="block w-full text-center bg-[#C5A880] text-black font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] hover:bg-[#B59A7A] transition-all">
                                    [ View Profile Dashboard ]
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-neutral-500 hover:text-rose-500 font-mono text-[9px] uppercase tracking-wider pt-1.5 block cursor-pointer bg-transparent border-none">
                                        [ Sign Out of Atelier ]
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-2.5 space-y-4">
                                <span class="font-serif text-xl italic block transition-colors duration-500"
                                      :class="{
                                          'text-[#C5A880]': theme === 'onyx',
                                          'text-[#B59A7A]': theme === 'champagne',
                                          'text-[#B76E79]': theme === 'rose'
                                      }">Atelier Loyalty Circle</span>
                                <p class="text-neutral-400 font-light text-[11px] leading-relaxed">Sign in to track orders, manage profiles, and earn rewards.</p>
                                <div class="flex flex-col gap-2.5 pt-1">
                                    <a href="/login" 
                                       :class="{
                                           'bg-[#C5A880] text-black hover:bg-[#B59A7A]': theme === 'onyx',
                                           'bg-[#B59A7A] text-white hover:bg-[#FAF7F0] hover:text-black border border-[#B59A7A]': theme === 'champagne',
                                           'bg-[#B76E79] text-white hover:bg-[#15060A] border border-[#B76E79]': theme === 'rose'
                                       }"
                                       class="font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] transition-all duration-300 hover:scale-[1.02] text-center block shadow-md"
                                    >
                                        Sign In
                                    </a>
                                    <a href="/register" 
                                       :class="{
                                           'border-neutral-800 text-neutral-400 hover:text-[#C5A880] hover:border-[#C5A880]': theme === 'onyx',
                                           'border-neutral-200 text-neutral-600 hover:text-[#B59A7A] hover:border-[#B59A7A]': theme === 'champagne',
                                           'border-[#2D121F] text-pink-300/60 hover:text-[#B76E79] hover:border-[#B76E79]': theme === 'rose'
                                       }"
                                       class="border font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] transition-all duration-300 hover:scale-[1.02] text-center block"
                                    >
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
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


    <!-- Fully-Featured Luxury Atelier Footer -->
    <footer 
        :class="{
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'onyx',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'champagne',
            'border-[#2D0D19]/40 bg-[#1D0C13] text-neutral-300': theme === 'rose'
        }"
        class="border-t mt-20 py-10 px-6 transition-colors duration-500 z-10 relative theme-section"
    >
        <div class="max-w-5xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-left">
            <!-- Col 1: Brand & Info -->
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors">Noir & Bloom</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
            </div>

            <!-- Col 2: Showroom Catalog Links -->
            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><a href="/?collection=retail" class="hover:underline">Bespoke Retail Arrays</a></li>
                    <li><a href="/?collection=wholesale" class="hover:underline">Wholesale Graded Stems</a></li>
                    <li><a href="/?collection=giftings" class="hover:underline">Luxury Giftings</a></li>
                    <li><a href="/profile-portal" class="hover:underline">Atelier Loyalty Circle</a></li>
                </ul>
            </div>

            <!-- Col 3: Hours & Support -->
            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><span class="block text-neutral-500">Operating Hours</span> Mon &mdash; Sat: 07:00 &mdash; 20:00</li>
                    <li>Sunday: 09:00 &mdash; 17:00</li>
                    <li class="pt-2"><span class="block text-neutral-500 font-mono text-[11px] uppercase tracking-wider">Hotline Direct</span> +254 (0) 712 345 678</li>
                    <li>concierge@noirbloom.co.ke</li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
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
                <a href="#" class="hover:text-neutral-400">eTIMS Verification</a>
            </div>
        </div>
    </footer>
</div>
