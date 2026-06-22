<div 
    x-data="curationStudio"
    :class="{
        'bg-[#0B0B0D] text-[#E4E4E7]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-x-clip"
>
    <style>
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .animate-shake {
      animation: shake 0.5s ease-in-out;
    }
    </style>
    <!-- Background overlay grains -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-30"></div>

    <!-- Luxury Cohesive Header -->
    <header 
        :class="{
            'bg-[#0B0B0D]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group theme-section"
    >
        <!-- Gold Accent Bottom Glow Line -->
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-[#C5A880]/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-center select-none cursor-pointer group/brand transition-transform duration-300 hover:scale-[1.02]">
                <div class="flex flex-col text-left leading-none">
                    <span class="text-[10px] font-mono tracking-[0.35em] uppercase font-bold brand-title-atelier transition-colors duration-500"
                          :class="{
                              'text-[#C5A880]': theme === 'onyx',
                              'text-emerald-700': theme === 'champagne',
                              'text-[#B76E79]': theme === 'rose'
                          }">Atelier</span>
                    <span class="text-base sm:text-lg md:text-xl font-extrabold uppercase tracking-[0.18em] font-outfit mt-0.5 brand-title-main transition-colors duration-500"
                          :class="{
                              'text-white': theme === 'onyx',
                              'text-neutral-900': theme === 'champagne',
                              'text-[#FCE7EC]': theme === 'rose'
                          }">Noir & Bloom</span>
                </div>
            </a>
            <!-- Spacing column to balance layout -->
            <div class="flex-1 hidden md:block"></div>

            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Navigation links -->
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 animate-nav-item select-none cursor-pointer" style="animation-delay: 200ms;">Services</a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-4 py-1.5 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880] font-semibold"
                   style="animation-delay: 250ms;">
                   Curation Studio
                </a>

                <!-- Theme Switcher Pill (2 options, desktop only) -->
                <div class="hidden lg:flex items-center space-x-1 border border-neutral-500/10 rounded-full bg-neutral-500/5 p-1 animate-nav-item select-none relative" style="animation-delay: 300ms;">
                    <button @click="theme = 'onyx'" 
                            @mouseenter="hoverTheme = 'onyx'" 
                            @mouseleave="hoverTheme = null" 
                            :class="theme === 'onyx' ? 'bg-[#C5A880] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-[#C5A880] hover:bg-[#C5A880]/10'" 
                            class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Onyx</span>
                    </button>
                    <button @click="theme = 'champagne'" 
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
                        </div>
                        <!-- Champagne Preview Content -->
                        <div x-show="hoverTheme === 'champagne'" class="space-y-2">
                            <span class="font-bold text-[#B59A7A] tracking-wider uppercase text-[10px] block">Champagne Theme</span>
                            <p class="text-neutral-500 text-[11px] leading-relaxed font-light">Warm Alabaster & Gilded Gold. A soft, light mode reflecting sunlit mornings in the flower atelier.</p>
                        </div>
                    </div>
                </div>

                <!-- Collapsed mobile theme switcher -->
                <button @click="theme = (theme === 'onyx' ? 'champagne' : 'onyx')" 
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
                    @if(($cartCount ?? 0) > 0)
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
                                class="hover:border-[#C5A880]/60 border border-neutral-500/20 transition-all cursor-pointer select-none w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-tr from-neutral-900 via-neutral-950 to-neutral-900 shadow-md"
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
                                <p class="text-neutral-450 font-light text-[11px] leading-relaxed">Sign in to track orders, manage profiles, and earn rewards.</p>
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

    <!-- Main split workspace -->
    <main class="flex-1 pt-24 pb-32 px-4 md:px-6 z-10 flex flex-col lg:flex-row lg:items-start gap-6 max-w-7xl mx-auto w-full">
        
        <!-- Left Panel: Fixed Visual Anchor & Invoice Summary (Locked on Desktop) -->
        <div class="w-full lg:w-5/12 lg:sticky lg:top-24 lg:h-[calc(100vh-8rem)] lg:max-h-[760px] lg:min-h-[580px] lg:overflow-y-auto scrollbar-none rounded-3xl border border-neutral-800/20 relative flex flex-col justify-between p-6 bg-black/10 backdrop-blur-md shadow-2xl">
            
            <div class="space-y-4">
                <!-- Visual Preview Header -->
                <div class="z-20 flex justify-between items-center w-full">
                    <span class="text-[10px] font-mono tracking-widest text-[#C5A880] uppercase">Visual Preview</span>
                    <div class="flex bg-black/45 border border-neutral-800/50 p-1 rounded-full text-[9px] font-mono">
                        <button @click="viewMode = 'arrangement'" :class="viewMode === 'arrangement' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-400 hover:text-white'" class="px-3 py-1 rounded-full transition-all cursor-pointer">Flowers</button>
                        <button @click="viewMode = 'presentation'" :class="viewMode === 'presentation' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-400 hover:text-white'" class="px-3 py-1 rounded-full transition-all cursor-pointer">Wrapping</button>
                    </div>
                </div>

                <!-- Preview Image Display -->
                <div class="w-full h-[320px] rounded-2xl border border-neutral-800/10 overflow-hidden relative flex items-center justify-center bg-black/20 shadow-inner">
                    <!-- Empty Desk State -->
                    <div x-show="isCurationEmpty() && !hoveredImage" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 z-10 transition-all duration-500">
                        <div class="w-12 h-12 rounded-full border border-neutral-850 flex items-center justify-center mb-4 bg-white/5 backdrop-blur-sm">
                            <span class="text-xl">✨</span>
                        </div>
                        <span class="text-xs font-mono tracking-widest text-[#C5A880] uppercase font-bold">Curation Desk Empty</span>
                        <p class="text-[10px] text-neutral-450 mt-2 max-w-[220px] leading-relaxed">Select fresh blooms or packaging options to begin designing your signature arrangement.</p>
                    </div>

                    <!-- Blurred Background Layer for Rich depth -->
                    <div x-show="!isCurationEmpty() || hoveredImage" class="absolute inset-0 scale-110 blur-xl opacity-30 pointer-events-none transition-all duration-500">
                        <img :src="hoveredImage || (viewMode === 'arrangement' ? getStemImage() : getWrapImage())" alt="" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Centered Contained Image -->
                    <img x-show="!isCurationEmpty() || hoveredImage" :src="hoveredImage || (viewMode === 'arrangement' ? getStemImage() : getWrapImage())" alt="Preview" class="relative z-10 w-full h-full object-contain p-4 transition-all duration-500">
                    
                    <!-- Hovered Item Label Overlay -->
                    <div x-show="hoveredName" x-transition class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-[9px] font-mono tracking-widest text-[#C5A880] uppercase z-20 border border-neutral-800/50">
                        Viewing: <span class="text-white" x-text="hoveredName"></span>
                    </div>
                </div>

                <!-- Atelier Receipt Ledger -->
                <div class="border-t border-neutral-800/30 pt-4 space-y-3">
                    <span class="text-[9px] uppercase tracking-[0.2em] font-mono text-[#C5A880] block font-bold">Atelier Curation Ledger</span>
                    
                    <div class="space-y-1.5 text-xs font-light max-h-[160px] lg:max-h-[220px] overflow-y-auto pr-1 scrollbar-none my-2">
                        <!-- Locked Hand Curation Service Fee -->
                        <div class="flex justify-between items-center text-neutral-300 py-1.5 border-b border-neutral-800/10 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium">Atelier Hand Curation Service</span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs">1,500 KSH</span>
                                <span class="text-neutral-600 text-[10px] ml-1 select-none cursor-not-allowed" title="Required service fee">🔒</span>
                            </div>
                        </div>

                        <!-- Curation Occasion -->
                        <div class="flex justify-between items-center text-neutral-300 py-1.5 border-b border-neutral-800/10 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="text-xs truncate font-medium">Occasion: <span class="font-semibold text-[#C5A880]" x-text="getOccasionLabel()"></span></span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="text-neutral-500 text-[10px] italic">Included</span>
                            </div>
                        </div>
                        <!-- Stems items -->
                        <template x-for="(qty, id) in localStems" :key="id">
                            <div x-show="qty > 0" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                                <div class="flex items-center space-x-2 truncate">
                                    <div class="flex items-center bg-black/40 rounded-full border border-neutral-800/50 p-0.5 space-x-1 shrink-0">
                                        <button @click="$wire.adjustStemQuantity(id, -1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">-</button>
                                        <span class="font-mono text-[#C5A880] text-[10px] font-bold px-1" x-text="qty"></span>
                                        <button @click="$wire.adjustStemQuantity(id, 1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">+</button>
                                    </div>
                                    <span class="text-xs truncate font-medium" x-text="getStemName(id)"></span>
                                </div>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="font-mono text-neutral-400 text-xs" x-text="(getStemPrice(id) * qty).toLocaleString() + ' KSH'"></span>
                                    <button @click="$wire.adjustStemQuantity(id, -qty)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                                </div>
                            </div>
                        </template>

                        <!-- Wrapping -->
                        <div x-show="selectedWrappingId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium" x-text="getWrappingName()"></span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs" x-text="getWrappingPrice().toLocaleString() + ' KSH'"></span>
                                <button type="button" @click="$wire.selectWrapping(null)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                            </div>
                        </div>

                        <!-- Glitter Accent -->
                        <div x-show="hasGlitter" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium">Glitter Petal Dusting</span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs" x-text="(400).toLocaleString() + ' KSH'"></span>
                                <button @click="$wire.toggleGlitter()" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                            </div>
                        </div>

                        <!-- Ribbon Accent -->
                        <div x-show="selectedRibbonId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium" x-text="getRibbonName() + ' Ribbon'"></span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs" x-text="getRibbonPrice().toLocaleString() + ' KSH'"></span>
                                <button @click="$wire.selectRibbon(selectedRibbonId)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                            </div>
                        </div>

                        <!-- Scent Mist -->
                        <div x-show="selectedMistId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium" x-text="'Fragrance Mist (' + getMistName() + ')'"></span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs" x-text="getMistPrice().toLocaleString() + ' KSH'"></span>
                                <button @click="$wire.selectMist(selectedMistId)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                            </div>
                        </div>

                        <!-- Gifts / Treats -->
                        <template x-for="(qty, id) in localGifts" :key="'gift-'+id">
                            <div x-show="qty > 0" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                                <div class="flex items-center space-x-2 truncate">
                                    <div class="flex items-center bg-black/40 rounded-full border border-neutral-800/50 p-0.5 space-x-1 shrink-0">
                                        <button @click="$wire.adjustGiftQuantity(id, -1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">-</button>
                                        <span class="font-mono text-[#C5A880] text-[10px] font-bold px-1" x-text="qty"></span>
                                        <button @click="$wire.adjustGiftQuantity(id, 1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">+</button>
                                    </div>
                                    <span class="text-xs truncate font-medium" x-text="getGiftName(id)"></span>
                                </div>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="font-mono text-neutral-400 text-xs" x-text="(getGiftPrice(id) * qty).toLocaleString() + ' KSH'"></span>
                                    <button @click="$wire.adjustGiftQuantity(id, -qty)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                                </div>
                            </div>
                        </template>

                        <!-- Greeting Calligraphy Note -->
                        <div x-show="hasCard" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                                <span class="text-xs truncate font-medium">Calligraphy Card Note</span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="font-mono text-neutral-400 text-xs" x-text="(500).toLocaleString() + ' KSH'"></span>
                                <button @click="$wire.toggleCard()" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                            </div>
                        </div>
                    </div>

                    <!-- Note text preview -->
                    <div x-show="hasCard && cardMessage.trim().length > 0" class="bg-amber-950/10 border border-amber-900/20 p-2.5 rounded-lg text-[10px] text-neutral-400 italic font-mono max-h-[60px] overflow-y-auto">
                        "&ldquo;<span x-text="cardMessage"></span>&rdquo;"
                    </div>
                </div>
            </div>

            <!-- Footer Details & Add to Cart -->
            <div class="border-t border-neutral-800/30 pt-4 mt-4 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[9px] uppercase tracking-wider text-neutral-450 block font-mono">Total Bouquet Value</span>
                        <span class="text-xl md:text-2xl font-mono font-semibold tracking-tight text-[#C5A880]">
                            <span x-text="subtotal.toLocaleString()"></span> KSH
                        </span>
                    </div>
                    <button 
                        wire:click="addToCart" 
                        class="px-6 py-3 rounded-full bg-white text-black hover:bg-neutral-200 transition-all duration-300 shadow-md font-semibold tracking-widest uppercase text-[10px] cursor-pointer select-none"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Panel: Scrollable Configuration flow -->
        <div class="w-full lg:w-7/12 space-y-12">
            
            <!-- Floating Navigation Quick-links -->
            <div :class="theme === 'champagne' ? 'bg-[#FAF7F0]/90 border-neutral-200 shadow-sm' : 'bg-[#09090D]/90 border-neutral-800/60 shadow-xl'" class="sticky top-20 backdrop-blur-md border p-1 rounded-full z-40 flex items-center justify-between text-[9px] font-mono tracking-widest uppercase overflow-x-auto scrollbar-none gap-1">
                <button 
                    @click="scrollToSection('section-occasion')" 
                    :class="activeSection === 'section-occasion' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>00 Occasion</span>
                    <span class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-occasion' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
                <button 
                    @click="scrollToSection('section-blooms')" 
                    :class="activeSection === 'section-blooms' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>01 Blooms</span>
                    <span x-show="getTotalStemCount() > 0" class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-blooms' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
                <button 
                    @click="scrollToSection('section-wrapping')" 
                    :class="activeSection === 'section-wrapping' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>02 Wrapping</span>
                    <span x-show="selectedWrappingId || hasGlitter || selectedRibbonId" class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-wrapping' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
                <button 
                    @click="scrollToSection('section-scent')" 
                    :class="activeSection === 'section-scent' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>03 Fragrance</span>
                    <span x-show="selectedMistId" class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-scent' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
                <button 
                    @click="scrollToSection('section-gifts')" 
                    :class="activeSection === 'section-gifts' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>04 Treats</span>
                    <span x-show="getTotalGiftCount() > 0" class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-gifts' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
                <button 
                    @click="scrollToSection('section-card')" 
                    :class="activeSection === 'section-card' ? 'bg-[#C5A880] text-black font-bold shadow-md' : 'text-neutral-400 hover:text-white'"
                    class="flex-1 py-2 px-3.5 text-center rounded-full transition-all duration-300 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1 focus:outline-none"
                >
                    <span>05 Note</span>
                    <span x-show="hasCard" class="text-[9px] font-bold text-emerald-500" :class="activeSection === 'section-card' ? 'text-black' : 'text-emerald-500'">✓</span>
                </button>
            </div>

            <!-- Error Alerts -->
            @if (session()->has('error'))
                <div class="bg-red-950/20 border border-red-900/50 p-4 rounded-2xl text-xs text-red-200 font-mono">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Livewire notification errors -->
            <div x-data="{ error: '' }" x-on:curation-error.window="error = $event.detail; setTimeout(function() { error = ''; }, 4000)">
                <div x-show="error" x-transition class="bg-red-950/20 border border-red-900/50 p-4 rounded-2xl text-xs text-red-200 font-mono" style="display:none;">
                    <span x-text="error"></span>
                </div>
            </div>

            <!-- Section 00: Curation Occasion -->
            <section id="section-occasion" class="space-y-6 scroll-mt-28">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">00 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Curation Occasion</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Select the occasion for this bespoke curation to tailor the design philosophy and personalization.</p>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <template x-for="occ in [
                        { id: 'birthday', label: 'Birthday Celebration', desc: 'Festive & vibrant hues' },
                        { id: 'anniversary', label: 'Anniversary & Love', desc: 'Romantic deep reds & pastels' },
                        { id: 'graduation', label: 'Graduation & Success', desc: 'Bright, celebratory blends' },
                        { id: 'romance', label: 'Romantic Gesture', desc: 'Classic expressions of passion' },
                        { id: 'vase_bundle', label: 'Vase Bundle (Home/Office)', desc: 'Elegant, structural defaults' },
                        { id: 'sympathy', label: 'Sympathy & Comfort', desc: 'Serene whites & soft tones' }
                    ]" :key="occ.id">
                        <button 
                            type="button"
                            @click="curationOccasion = occ.id; $wire.set('curationOccasion', occ.id);"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5 shadow-md': curationOccasion === occ.id,
                                'border-neutral-800/20 hover:border-neutral-700/30': curationOccasion !== occ.id,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne'
                            }"
                            class="flex flex-col text-left p-4 rounded-2xl border transition-all cursor-pointer group"
                        >
                            <span class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors" x-text="occ.label"></span>
                            <span class="text-[9px] text-neutral-455 mt-1 leading-normal" x-text="occ.desc"></span>
                        </button>
                    </template>
                </div>
            </section>

            <!-- Section 01: Fresh Blooms & Stems -->
            <section id="section-blooms" class="space-y-6 scroll-mt-28">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">01 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Select Fresh Blooms</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Select multiple flower varieties to customize your bouquet mix. Set quantities individually per stem.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($availableStems as $stem)
                        <div 
                            x-data="{ shake: false }"
                            @click="if ({{ $stem->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($stem->name) }} is currently out of stock.'); } else { $wire.adjustStemQuantity({{ $stem->id }}, 1); viewMode = 'arrangement'; }"
                            @mouseenter="if ({{ $stem->stock }} > 0) { hoveredImage = '{{ $stem->image_url }}'; hoveredName = '{{ addslashes($stem->name) }}'; viewMode = 'arrangement'; }"
                            @mouseleave="hoveredImage = null; hoveredName = null;"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (localStems[{{ $stem->id }}] || 0) > 0 && {{ $stem->stock }} > 0,
                                'border-neutral-800/20': !((localStems[{{ $stem->id }}] || 0) > 0) || {{ $stem->stock }} <= 0,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne',
                                'opacity-60 saturate-50': {{ $stem->stock }} <= 0,
                                'animate-shake border-red-500/50': shake,
                                'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $stem->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $stem->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $stem->stock }} > 0
                            }"
                            class="flex flex-col justify-between p-5 rounded-2xl border transition-all relative group cursor-pointer"
                        >
                            @if ($stem->stock <= 0)
                                <span class="absolute top-3 right-3 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-2 py-0.5 rounded-full">
                                    Out of Stock
                                </span>
                            @endif

                            <div class="flex gap-4">
                                <img src="{{ $stem->image_url }}" alt="{{ $stem->name }}" class="w-16 h-16 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                                <div>
                                    <h4 class="text-[9px] uppercase tracking-widest font-mono text-[#C5A880] font-bold">{{ $stem->grade ?: 'Grade A Premium' }}</h4>
                                    <h3 class="text-xs font-semibold mt-0.5 group-hover:text-[#C5A880] transition-colors leading-tight">{{ $stem->name }}</h3>
                                    <p class="text-[9px] text-neutral-455 mt-1 line-clamp-2 leading-relaxed">{{ $stem->description }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest font-mono text-neutral-400 block">Per Stem Price</span>
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($stem->price) }} KSH</span>
                                </div>

                                <!-- Quantity adjuster pill -->
                                <div @click.stop class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                    <button 
                                        type="button"
                                        @click.stop="if ({{ $stem->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($stem->name) }} is currently out of stock.'); } else { $wire.adjustStemQuantity({{ $stem->id }}, -1); viewMode = 'arrangement'; }"
                                        class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                    >-</button>
                                    <span class="font-mono font-medium min-w-[16px] text-center" x-text="localStems[{{ $stem->id }}] || 0"></span>
                                    <button 
                                        type="button"
                                        @click.stop="if ({{ $stem->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($stem->name) }} is currently out of stock.'); } else { $wire.adjustStemQuantity({{ $stem->id }}, 1); viewMode = 'arrangement'; }"
                                        class="w-6 h-6 flex items-center justify-center rounded-full text-[#C5A880] hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                    >+</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Section 02: Wrapping & Presentation Accents -->
            <section id="section-wrapping" class="space-y-6 scroll-mt-28">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">02 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Wrapping & Presentation Accents</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Select your preferred packaging wrapping paper or gift box, and add accents like custom ribbons or glitter.</p>

                <!-- Wrappings Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($availableWrappings as $wrap)
                        <div 
                            x-data="{ shake: false }"
                            @click="if ({{ $wrap->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wrap->name) }} is currently out of stock.'); } else { $wire.selectWrapping({{ $wrap->id }}); viewMode = 'presentation'; }"
                            @mouseenter="if ({{ $wrap->stock }} > 0) { hoveredImage = '{{ $wrap->image_url }}'; hoveredName = '{{ addslashes($wrap->name) }}'; viewMode = 'presentation'; }"
                            @mouseleave="hoveredImage = null; hoveredName = null;"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': selectedWrappingId == {{ $wrap->id }} && {{ $wrap->stock }} > 0,
                                'border-neutral-800/20': selectedWrappingId != {{ $wrap->id }} || {{ $wrap->stock }} <= 0,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne',
                                'opacity-60 saturate-50': {{ $wrap->stock }} <= 0,
                                'animate-shake border-red-500/50': shake,
                                'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $wrap->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $wrap->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $wrap->stock }} > 0
                            }"
                            class="flex items-center gap-4 p-4 rounded-2xl border transition-all cursor-pointer group relative"
                        >
                            @if ($wrap->stock <= 0)
                                <span class="absolute top-2 right-2 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-1.5 py-0.5 rounded-full">
                                    Out of Stock
                                </span>
                            @endif

                            <img src="{{ $wrap->image_url }}" alt="{{ $wrap->name }}" class="w-14 h-14 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $wrap->name }}</h4>
                                <p class="text-[9px] text-neutral-450 mt-0.5 line-clamp-2 leading-relaxed">{{ $wrap->description }}</p>
                                <p class="text-[10px] text-[#C5A880] font-mono mt-1 font-semibold">+{{ number_format($wrap->price) }} KSH</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Wrapping accents and additions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-neutral-800/15">
                    <!-- Glitter Accent Toggle -->
                    @if($glitterProduct)
                        <div 
                            @click="$wire.toggleGlitter()"
                            @mouseenter="hoveredImage = '{{ $glitterProduct->image_url }}'; hoveredName = '{{ addslashes($glitterProduct->name) }}';"
                            @mouseleave="hoveredImage = null; hoveredName = null;"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5': hasGlitter,
                                'border-neutral-800/20 hover:border-neutral-500/30': !hasGlitter,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne'
                            }"
                            class="flex items-center gap-4 p-4 rounded-2xl border transition-all cursor-pointer group"
                        >
                            <img src="{{ $glitterProduct->image_url }}" alt="{{ $glitterProduct->name }}" class="w-14 h-14 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $glitterProduct->name }}</h4>
                                <p class="text-[9px] text-neutral-450 mt-0.5 line-clamp-2 leading-relaxed">{{ $glitterProduct->description }}</p>
                                <p class="text-[10px] text-[#C5A880] font-mono mt-1 font-semibold">+{{ number_format($glitterProduct->price) }} KSH</p>
                            </div>
                            <!-- checkbox badge -->
                            <div class="w-5 h-5 rounded-full border border-neutral-600 flex items-center justify-center shrink-0" :class="hasGlitter ? 'bg-[#C5A880] border-transparent text-black' : ''">
                                <span x-show="hasGlitter" class="text-[10px] font-bold">✓</span>
                            </div>
                        </div>
                    @endif

                    <!-- Ribbon Accent Selectors -->
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-450 block font-bold">Atelier Ribbon Accent Color</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableRibbons as $ribbon)
                                @php
                                    if (str_contains(strtolower($ribbon->name), 'purple')) $colorName = 'Purple';
                                    elseif (str_contains(strtolower($ribbon->name), 'gold')) $colorName = 'Gold';
                                    elseif (str_contains(strtolower($ribbon->name), 'red')) $colorName = 'Red';
                                    else $colorName = 'Satin';
                                @endphp
                                <button 
                                    type="button"
                                    @click="$wire.selectRibbon({{ $ribbon->id }})"
                                    @mouseenter="hoveredImage = '{{ $ribbon->image_url }}'; hoveredName = '{{ addslashes($ribbon->name) }}';"
                                    @mouseleave="hoveredImage = null; hoveredName = null;"
                                    :class="{
                                        'bg-[#C5A880] text-black font-semibold border-transparent shadow-md': selectedRibbonId == {{ $ribbon->id }},
                                        'border-neutral-800/20 text-neutral-400 hover:text-white': selectedRibbonId != {{ $ribbon->id }},
                                        'bg-[#09090D]/40': theme !== 'champagne',
                                        'bg-white': theme === 'champagne'
                                    }"
                                    class="px-4 py-2 border rounded-full text-xs font-mono transition-all cursor-pointer focus:outline-none"
                                >
                                    {{ $colorName }} (+{{ number_format($ribbon->price) }} KSH)
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 03: Atelier Fragrance Mist -->
            <section id="section-scent" class="space-y-6 scroll-mt-28">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">03 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Atelier Fragrance Mist</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Select an signature mist to give your arrangement an exquisite perfume note upon delivery.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($availableMists as $mist)
                        <div 
                            x-data="{ shake: false }"
                            @click="if ({{ $mist->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($mist->name) }} is currently out of stock.'); } else { $wire.selectMist({{ $mist->id }}); }"
                            @mouseenter="if ({{ $mist->stock }} > 0) { hoveredImage = '{{ $mist->image_url }}'; hoveredName = '{{ addslashes($mist->name) }}'; }"
                            @mouseleave="hoveredImage = null; hoveredName = null;"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5': selectedMistId == {{ $mist->id }} && {{ $mist->stock }} > 0,
                                'border-neutral-800/20': selectedMistId != {{ $mist->id }} || {{ $mist->stock }} <= 0,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne',
                                'opacity-60 saturate-50': {{ $mist->stock }} <= 0,
                                'animate-shake border-red-500/50': shake,
                                'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $mist->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $mist->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $mist->stock }} > 0
                            }"
                            class="p-4 rounded-2xl border transition-all cursor-pointer relative group flex flex-col justify-between h-full"
                        >
                            @if ($mist->stock <= 0)
                                <span class="absolute top-2 right-2 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-1.5 py-0.5 rounded-full">
                                    Out of Stock
                                </span>
                            @endif

                            <div>
                                <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $mist->name }}</h4>
                                <p class="text-[9px] text-neutral-450 mt-1 leading-relaxed">{{ $mist->description }}</p>
                            </div>
                            <p class="text-[10px] text-[#C5A880] font-mono mt-3 font-semibold">+{{ number_format($mist->price) }} KSH</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Section 04: Accompanying Gifts & Luxury treats -->
            <section id="section-gifts" class="space-y-8 scroll-mt-28">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">04 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Accompanying Gifts & Luxury Treats</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Pair your floral arrangement with exquisite treats. Adjust quantities individually.</p>

                <!-- Wines & Champagnes -->
                <div class="space-y-4">
                    <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-450 block font-bold">1. Fine Wines & Champagnes</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($availableWines as $wine)
                            <div 
                                x-data="{ shake: false }"
                                @click="if ({{ $wine->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wine->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $wine->id }}, 1); }"
                                @mouseenter="if ({{ $wine->stock }} > 0) { hoveredImage = '{{ $wine->image_url }}'; hoveredName = '{{ addslashes($wine->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (localGifts[{{ $wine->id }}] || 0) > 0 && {{ $wine->stock }} > 0,
                                    'border-neutral-800/20': !(localGifts[{{ $wine->id }}] || 0) > 0 || {{ $wine->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $wine->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $wine->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $wine->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $wine->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group cursor-pointer"
                            >
                                @if ($wine->stock <= 0)
                                    <span class="absolute top-2 right-2 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-1.5 py-0.5 rounded-full">
                                        Out of Stock
                                    </span>
                                @endif

                                <div class="flex gap-4">
                                    <img src="{{ $wine->image_url }}" alt="{{ $wine->name }}" class="w-14 h-14 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $wine->name }}</h4>
                                        <p class="text-[9px] text-neutral-400 mt-0.5 line-clamp-2 leading-tight">{{ $wine->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($wine->price) }} KSH</span>
                                    
                                    <div @click.stop class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $wine->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wine->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $wine->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="localGifts[{{ $wine->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $wine->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wine->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $wine->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chocolates -->
                <div class="space-y-4 pt-4 border-t border-neutral-800/10">
                    <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-450 block font-bold">2. Gourmet Chocolates & Pralines</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($availableChocolates as $choc)
                            <div 
                                x-data="{ shake: false }"
                                @click="if ({{ $choc->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($choc->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $choc->id }}, 1); }"
                                @mouseenter="if ({{ $choc->stock }} > 0) { hoveredImage = '{{ $choc->image_url }}'; hoveredName = '{{ addslashes($choc->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (localGifts[{{ $choc->id }}] || 0) > 0 && {{ $choc->stock }} > 0,
                                    'border-neutral-800/20': !(localGifts[{{ $choc->id }}] || 0) > 0 || {{ $choc->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $choc->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $choc->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $choc->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $choc->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group cursor-pointer"
                            >
                                @if ($choc->stock <= 0)
                                    <span class="absolute top-2 right-2 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-1.5 py-0.5 rounded-full">
                                        Out of Stock
                                    </span>
                                @endif

                                <div class="flex gap-4">
                                    <img src="{{ $choc->image_url }}" alt="{{ $choc->name }}" class="w-14 h-14 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $choc->name }}</h4>
                                        <p class="text-[9px] text-neutral-450 mt-0.5 line-clamp-2 leading-tight">{{ $choc->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($choc->price) }} KSH</span>
                                    
                                    <div @click.stop class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $choc->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($choc->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $choc->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="localGifts[{{ $choc->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $choc->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($choc->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $choc->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Jewelry and Fragrances -->
                <div class="space-y-4 pt-4 border-t border-neutral-800/10">
                    <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-450 block font-bold">3. Luxury Jewelry & Fine Perfume</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($availableJewelry as $jewel)
                            <div 
                                x-data="{ shake: false }"
                                @click="if ({{ $jewel->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($jewel->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $jewel->id }}, 1); }"
                                @mouseenter="if ({{ $jewel->stock }} > 0) { hoveredImage = '{{ $jewel->image_url }}'; hoveredName = '{{ addslashes($jewel->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (localGifts[{{ $jewel->id }}] || 0) > 0 && {{ $jewel->stock }} > 0,
                                    'border-neutral-800/20': !(localGifts[{{ $jewel->id }}] || 0) > 0 || {{ $jewel->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $jewel->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $jewel->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $jewel->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $jewel->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group cursor-pointer"
                            >
                                @if ($jewel->stock <= 0)
                                    <span class="absolute top-2 right-2 bg-red-950/70 border border-red-900/50 text-red-400 text-[8px] font-mono uppercase tracking-widest px-1.5 py-0.5 rounded-full">
                                        Out of Stock
                                    </span>
                                @endif

                                <div class="flex gap-4">
                                    <img src="{{ $jewel->image_url }}" alt="{{ $jewel->name }}" class="w-14 h-14 object-cover rounded-xl shadow-md shrink-0 border border-neutral-800/10">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors truncate">{{ $jewel->name }}</h4>
                                        <p class="text-[9px] text-neutral-400 mt-0.5 line-clamp-2 leading-tight">{{ $jewel->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($jewel->price) }} KSH</span>
                                    
                                    <div @click.stop class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $jewel->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($jewel->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $jewel->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-450 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="localGifts[{{ $jewel->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click.stop="if ({{ $jewel->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($jewel->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $jewel->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-455 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Section 05: Greeting Card Note -->
            <section id="section-card" class="space-y-6 scroll-mt-28 pb-12">
                <div class="flex items-baseline space-x-3 border-b border-neutral-500/10 pb-3">
                    <span class="font-mono text-xs text-[#C5A880]">05 /</span>
                    <h2 class="font-serif italic text-2xl tracking-wide">Calligraphy Card Note</h2>
                </div>
                <p class="text-xs text-neutral-450 font-light mt-1">Include a handwritten calligraphy greeting card to make your luxury gift extra personal.</p>

                @if($cardProduct)
                    <div 
                        @click="$wire.toggleCard()"
                        @mouseenter="hoveredImage = '{{ $cardProduct->image_url }}'; hoveredName = '{{ addslashes($cardProduct->name) }}';"
                        @mouseleave="hoveredImage = null; hoveredName = null;"
                        :class="{
                            'border-[#C5A880] bg-[#C5A880]/5': hasCard,
                            'border-neutral-800/20 hover:border-neutral-500/30': !hasCard,
                            'bg-white/80': theme === 'champagne',
                            'bg-[#09090D]/40': theme !== 'champagne'
                        }"
                        class="flex items-center justify-between p-5 rounded-2xl border transition-all cursor-pointer group"
                    >
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full border border-neutral-800 flex items-center justify-center text-[#C5A880] bg-neutral-900/30 font-serif">✍</div>
                            <div>
                                <h4 class="text-xs font-semibold group-hover:text-[#C5A880] transition-colors">Include Luxury Handwritten Calligraphy Letter</h4>
                                <p class="text-[10px] text-[#C5A880] font-mono mt-0.5 font-semibold">+{{ number_format($cardProduct->price) }} KSH</p>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full border border-neutral-600 flex items-center justify-center shrink-0" :class="hasCard ? 'bg-[#C5A880] border-transparent text-black' : ''">
                            <span x-show="hasCard" class="text-[10px] font-bold">✓</span>
                        </div>
                    </div>
                @endif

                <!-- Card Message Editor box -->
                <div 
                    x-show="hasCard" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-3 pt-2"
                    style="display: none;"
                >
                    <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-450 block font-bold">Write Your Calligraphy Message</label>
                    <textarea 
                        wire:model.live="cardMessage"
                        placeholder="Type your birthday greeting or special note here... e.g. happy birthday"
                        rows="4"
                        :class="{
                            'bg-black/35 border-neutral-800 focus:border-[#C5A880]': theme !== 'champagne',
                            'bg-white border-neutral-350 focus:border-[#C5A880]': theme === 'champagne'
                        }"
                        class="w-full rounded-2xl p-4 text-xs font-light tracking-wide outline-none transition-colors border shadow-inner placeholder-neutral-500 font-sans"
                    ></textarea>
                </div>
            </section>
        </div>
    </main>

    <!-- Luxury Atelier Footer -->
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
            
            {{-- Social Media Icons --}}
            <div class="flex items-center space-x-3.5">
                {{-- Instagram --}}
                <a href="https://instagram.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.3)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.25)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.3)]': theme === 'rose'
                   }"
                   title="Follow us on Instagram">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                    </svg>
                </a>
                {{-- Facebook --}}
                <a href="https://facebook.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#1877F2] hover:border-[#1877F2] hover:shadow-[0_0_15px_rgba(24,119,242,0.3)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-[#1877F2] hover:border-[#1877F2] hover:shadow-[0_0_15px_rgba(24,119,242,0.25)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#1877F2] hover:border-[#1877F2] hover:shadow-[0_0_15px_rgba(24,119,242,0.3)]': theme === 'rose'
                   }"
                   title="Follow us on Facebook">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
                    </svg>
                </a>
                {{-- X (Twitter) --}}
                <a href="https://twitter.com/NoirAndBloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-white hover:border-white hover:shadow-[0_0_15px_rgba(255,255,255,0.2)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-black hover:border-black hover:shadow-[0_0_15px_rgba(0,0,0,0.15)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#FCE7EC] hover:border-[#FCE7EC] hover:shadow-[0_0_15px_rgba(252,231,236,0.2)]': theme === 'rose'
                   }"
                   title="Follow us on X (Twitter)">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                {{-- Pinterest --}}
                <a href="https://pinterest.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#E60023] hover:border-[#E60023] hover:shadow-[0_0_15px_rgba(230,0,35,0.3)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-[#E60023] hover:border-[#E60023] hover:shadow-[0_0_15px_rgba(230,0,35,0.25)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#E60023] hover:border-[#E60023] hover:shadow-[0_0_15px_rgba(230,0,35,0.3)]': theme === 'rose'
                   }"
                   title="Follow us on Pinterest">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.395-5.92 1.395-5.92s-.36-.715-.36-1.777c0-1.664.962-2.907 2.162-2.907 1.02 0 1.513.766 1.513 1.682 0 1.026-.65 2.558-.99 3.978-.282 1.187.592 2.155 1.764 2.155 2.113 0 3.738-2.23 3.738-5.447 0-2.848-2.049-4.839-4.969-4.839-3.385 0-5.372 2.54-5.372 5.163 0 1.023.392 2.122.882 2.719.098.118.113.22.083.342-.09.378-.292 1.189-.331 1.348-.052.21-.173.253-.399.148-1.492-.695-2.423-2.88-2.423-4.636 0-3.774 2.744-7.24 7.907-7.24 4.15 0 7.375 2.957 7.375 6.9 0 4.124-2.597 7.443-6.204 7.443-1.213 0-2.355-.63-2.744-1.373l-.747 2.847c-.269 1.027-.997 2.316-1.488 3.118 4.417 1.282 9.21.365 12.825-2.525C22.617 19.387 24 15.86 24 11.987 24 5.367 18.63 0 12.017 0z"/>
                    </svg>
                </a>
                {{-- WhatsApp --}}
                <a href="https://wa.me/254712345678" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#25D366] hover:border-[#25D366] hover:shadow-[0_0_15px_rgba(37,211,102,0.3)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-[#25D366] hover:border-[#25D366] hover:shadow-[0_0_15px_rgba(37,211,102,0.25)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#25D366] hover:border-[#25D366] hover:shadow-[0_0_15px_rgba(37,211,102,0.3)]': theme === 'rose'
                   }"
                   title="Chat with us on WhatsApp">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M19.005 3.175C17.252 1.42 14.927.453 12.443.453 7.429.453 3.353 4.53 3.353 9.544c0 1.602.418 3.167 1.213 4.544L2.247 22.25l8.36-2.193c1.332.726 2.828 1.11 4.363 1.112h.006c5.011 0 9.088-4.076 9.088-9.09 0-2.43-.946-4.714-2.703-6.471l-.356-.356zm-6.562 16.92c-1.442-.002-2.857-.388-4.095-1.116l-.294-.174-5.043 1.323 1.347-4.923-.19-.304c-.8-1.272-1.222-2.742-1.22-4.26.002-4.42 3.6-8.016 8.026-8.016 2.14 0 4.153.834 5.666 2.348 1.513 1.513 2.345 3.526 2.343 5.67-.004 4.42-3.601 8.018-8.026 8.018l-.534-.016zm4.414-6.027c-.242-.12-1.432-.707-1.654-.788-.222-.08-.383-.12-.544.12-.16.242-.624.788-.765.947-.14.16-.282.18-.523.06-.24-.12-1.018-.374-1.94-1.196-.718-.64-1.202-1.43-1.343-1.67-.14-.242-.015-.373.106-.493.11-.108.242-.282.363-.423.12-.14.16-.242.242-.403.08-.16.04-.302-.02-.423-.06-.12-.544-1.31-.746-1.794-.197-.473-.396-.408-.544-.416-.14-.007-.302-.007-.463-.007s-.423.06-.644.302c-.22.242-.845.826-.845 2.015 0 1.19.865 2.338.986 2.5.12.16 1.704 2.602 4.13 3.65.577.248.995.397 1.353.51.58.185 1.107.159 1.523.097.464-.068 1.432-.585 1.633-1.15.202-.564.202-1.047.14-1.15-.06-.102-.222-.162-.463-.282z"/>
                    </svg>
                </a>
            </div>
            
            <div class="flex space-x-6">
                <a href="#" :class="theme === 'champagne' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Terms of Curation</a>
                <a href="#" :class="theme === 'champagne' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Logistics Policy</a>
                <a href="#" :class="theme === 'champagne' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Invoice Request</a>
            </div>
        </div>
    </footer>

    <!-- Mobile Sticky Footer Bar (visible only on mobile/tablet) -->
    <div 
        :class="theme === 'champagne' ? 'bg-white/70 border-neutral-200 shadow-lg backdrop-blur-xl' : 'bg-[#0B0B0D]/75 border-neutral-800/20 shadow-2xl backdrop-blur-xl'"
        class="lg:hidden fixed bottom-0 inset-x-0 z-40 border-t backdrop-blur-md px-6 py-4 flex items-center justify-between transition-all duration-500"
    >
        <div>
            <span class="text-[8px] uppercase tracking-wider text-neutral-400 block font-mono">Total Value</span>
            <span class="text-base font-mono font-semibold tracking-tight text-[#C5A880]">
                <span x-text="subtotal.toLocaleString()"></span> KSH
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Quick View Ledger Trigger -->
            <button 
                type="button"
                @click="showMobileLedger = !showMobileLedger"
                class="px-3.5 py-2.5 rounded-full border border-neutral-800/60 text-[9px] font-mono uppercase tracking-wider text-neutral-450 hover:text-white"
            >
                <span x-text="showMobileLedger ? 'Hide details' : 'View details'"></span>
            </button>
            <button 
                type="button"
                wire:click="addToCart" 
                class="px-5 py-2.5 rounded-full bg-white text-black hover:bg-neutral-200 transition-all duration-300 shadow-md font-semibold tracking-widest uppercase text-[9px] cursor-pointer"
            >
                Add to Cart
            </button>
        </div>
    </div>

    <!-- Mobile Ledger Drawer Overlay -->
    <div 
        x-show="showMobileLedger"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm lg:hidden"
        style="display: none;"
        @click="showMobileLedger = false"
    >
        <!-- Drawer Content -->
        <div 
            @click.stop
            x-show="showMobileLedger"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            :class="theme === 'champagne' ? 'bg-[#FAF7F0]' : 'bg-[#09090D]'"
            class="absolute bottom-0 inset-x-0 rounded-t-3xl p-6 max-h-[80vh] overflow-y-auto border-t border-neutral-800/20 flex flex-col justify-between"
        >
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-neutral-800/10 pb-4 mb-4">
                <span class="text-xs font-mono tracking-widest text-[#C5A880] uppercase font-bold">Atelier Curation Ledger</span>
                <button type="button" @click="showMobileLedger = false" class="text-neutral-500 hover:text-white text-xs p-1">✕ Close</button>
            </div>

            <!-- Ledger list items -->
            <div class="space-y-2 text-xs font-light overflow-y-auto max-h-[40vh] mb-4 pr-1">
                <!-- Locked Hand Curation Service Fee -->
                <div class="flex justify-between items-center text-neutral-300 py-1.5 border-b border-neutral-800/10 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium">Atelier Hand Curation Service</span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs">1,500 KSH</span>
                        <span class="text-neutral-600 text-[10px] ml-1 select-none cursor-not-allowed">🔒</span>
                    </div>
                </div>

                <!-- Curation Occasion -->
                <div class="flex justify-between items-center text-neutral-300 py-1.5 border-b border-neutral-800/10 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="text-xs truncate font-medium">Occasion: <span class="font-semibold text-[#C5A880]" x-text="getOccasionLabel()"></span></span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="text-neutral-500 text-[10px] italic">Included</span>
                    </div>
                </div>

                <!-- Stems items -->
                <template x-for="(qty, id) in localStems" :key="id">
                    <div x-show="qty > 0" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                        <div class="flex items-center space-x-2 truncate">
                            <div class="flex items-center bg-black/40 rounded-full border border-neutral-800/50 p-0.5 space-x-1 shrink-0">
                                <button type="button" @click="$wire.adjustStemQuantity(id, -1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">-</button>
                                <span class="font-mono text-[#C5A880] text-[10px] font-bold px-1" x-text="qty"></span>
                                <button type="button" @click="$wire.adjustStemQuantity(id, 1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">+</button>
                            </div>
                            <span class="text-xs truncate font-medium" x-text="getStemName(id)"></span>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0">
                            <span class="font-mono text-neutral-400 text-xs" x-text="(getStemPrice(id) * qty).toLocaleString() + ' KSH'"></span>
                            <button type="button" @click="$wire.adjustStemQuantity(id, -qty)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                        </div>
                    </div>
                </template>

                <!-- Wrapping -->
                <div x-show="selectedWrappingId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium" x-text="getWrappingName()"></span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs" x-text="getWrappingPrice().toLocaleString() + ' KSH'"></span>
                        <button type="button" @click="$wire.selectWrapping(null)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                    </div>
                </div>

                <!-- Glitter Accent -->
                <div x-show="hasGlitter" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium">Glitter Petal Dusting</span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs" x-text="(400).toLocaleString() + ' KSH'"></span>
                        <button type="button" @click="$wire.toggleGlitter()" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                    </div>
                </div>

                <!-- Ribbon Accent -->
                <div x-show="selectedRibbonId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium" x-text="getRibbonName() + ' Ribbon'"></span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs" x-text="getRibbonPrice().toLocaleString() + ' KSH'"></span>
                        <button type="button" @click="$wire.selectRibbon(selectedRibbonId)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                    </div>
                </div>

                <!-- Scent Mist -->
                <div x-show="selectedMistId" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium" x-text="'Fragrance Mist (' + getMistName() + ')'"></span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs" x-text="getMistPrice().toLocaleString() + ' KSH'"></span>
                        <button type="button" @click="$wire.selectMist(selectedMistId)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                    </div>
                </div>

                <!-- Gifts -->
                <template x-for="(qty, id) in localGifts" :key="'gift-'+id">
                    <div x-show="qty > 0" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                        <div class="flex items-center space-x-2 truncate">
                            <div class="flex items-center bg-black/40 rounded-full border border-neutral-800/50 p-0.5 space-x-1 shrink-0">
                                <button type="button" @click="$wire.adjustGiftQuantity(id, -1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">-</button>
                                <span class="font-mono text-[#C5A880] text-[10px] font-bold px-1" x-text="qty"></span>
                                <button type="button" @click="$wire.adjustGiftQuantity(id, 1)" class="w-4 h-4 rounded-full hover:bg-[#C5A880] hover:text-black text-neutral-400 flex items-center justify-center text-[9px] transition-all cursor-pointer font-bold select-none border border-neutral-700/20">+</button>
                            </div>
                            <span class="text-xs truncate font-medium" x-text="getGiftName(id)"></span>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0">
                            <span class="font-mono text-neutral-400 text-xs" x-text="(getGiftPrice(id) * qty).toLocaleString() + ' KSH'"></span>
                            <button type="button" @click="$wire.adjustGiftQuantity(id, -qty)" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                        </div>
                    </div>
                </template>

                <!-- Greeting Card -->
                <div x-show="hasCard" class="flex justify-between items-center text-neutral-300 py-1 border-b border-neutral-800/10 last:border-0 hover:bg-white/5 px-2 rounded transition-colors">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="font-mono text-[#C5A880] text-xs font-semibold px-1">1x</span>
                        <span class="text-xs truncate font-medium">Calligraphy Card Note</span>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span class="font-mono text-neutral-400 text-xs" x-text="(500).toLocaleString() + ' KSH'"></span>
                        <button type="button" @click="$wire.toggleCard()" class="text-neutral-500 hover:text-red-400 transition cursor-pointer text-[10px] ml-1 select-none">✕</button>
                    </div>
                </div>
            </div>

            <!-- Bottom details -->
            <div class="border-t border-neutral-800/10 pt-4 flex items-center justify-between">
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-neutral-450 block font-mono">Current Total</span>
                    <span class="text-lg font-mono font-semibold tracking-tight text-[#C5A880]">
                        <span x-text="subtotal.toLocaleString()"></span> KSH
                    </span>
                </div>
                <button 
                    type="button"
                    @click="showMobileLedger = false" 
                    class="px-6 py-2.5 rounded-full bg-white text-black hover:bg-neutral-200 transition-all font-semibold tracking-widest uppercase text-[10px] cursor-pointer"
                >
                    Keep Customizing
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.data('curationStudio', function() {
                return {
                    theme: 'onyx',
                    viewMode: 'arrangement',
                    selectedStems: @entangle('selectedStems'),
                    selectedWrappingId: @entangle('selectedWrappingId'),
                    hasGlitter: @entangle('hasGlitter'),
                    selectedRibbonId: @entangle('selectedRibbonId'),
                    selectedMistId: @entangle('selectedMistId'),
                    selectedGifts: @entangle('selectedGifts'),
                    hasCard: @entangle('hasCard'),
                    cardMessage: @entangle('cardMessage'),
                    subtotal: @entangle('subtotal'),
                    curationOccasion: @entangle('curationOccasion'),
                    hoveredImage: null,
                    hoveredName: null,
                    activeSection: 'section-blooms',
                    showMobileLedger: false,
                    hoverTheme: null,
                    get localStems() {
                        return Object.assign({}, this.selectedStems);
                    },
                    get localGifts() {
                        return Object.assign({}, this.selectedGifts);
                    },

                    getOccasionLabel() {
                        var labels = {
                            birthday: 'Birthday Celebration',
                            anniversary: 'Anniversary & Love',
                            graduation: 'Graduation & Success',
                            romance: 'Romantic Gesture',
                            vase_bundle: 'Vase Bundle',
                            sympathy: 'Sympathy & Comfort'
                        };
                        return labels[this.curationOccasion] || 'Bespoke Curation';
                    },

                    isCurationEmpty() {
                        return this.getTotalStemCount() === 0 
                            && !this.selectedWrappingId 
                            && !this.selectedRibbonId 
                            && !this.selectedMistId 
                            && !this.hasGlitter 
                            && this.getTotalGiftCount() === 0 
                            && !this.hasCard;
                    },

                    init() {
                        var storedTheme = localStorage.getItem('nb_theme');
                        this.theme = (storedTheme === 'onyx' || storedTheme === 'champagne') ? storedTheme : 'onyx';
                        var self = this;

                        this.$watch('theme', function(val) {
                            localStorage.setItem('nb_theme', val);
                            document.documentElement.className = val;
                            document.documentElement.setAttribute('data-theme', val);
                            self.$dispatch('theme-changed', val);
                        });

                        // IntersectionObserver for Scroll Spy
                        if ('IntersectionObserver' in window) {
                            var options = {
                                root: null,
                                rootMargin: '-10% 0px -70% 0px',
                                threshold: 0
                            };
                            var observer = new IntersectionObserver(function(entries) {
                                entries.forEach(function(entry) {
                                    if (entry.isIntersecting) {
                                        self.activeSection = entry.target.id;
                                    }
                                });
                            }, options);
                            var sections = ['section-occasion', 'section-blooms', 'section-wrapping', 'section-scent', 'section-gifts', 'section-card'];
                            sections.forEach(function(id) {
                                var el = document.getElementById(id);
                                if (el) {
                                    observer.observe(el);
                                }
                            });
                        }
                    },

                    getStemImage() {
                        var stems = @json($availableStems);
                        var activeId = null;
                        for (var id in this.localStems) {
                            if (this.localStems[id] > 0) {
                                activeId = id;
                                break;
                            }
                        }
                        if (!activeId) {
                            activeId = {{ $availableStems->first()->id ?? 'null' }};
                        }
                        var active = stems.find(function(s) { return s.id == activeId; });
                        return active ? active.image_url : '/media/flowers/redrosestem.jpg';
                    },

                    getWrapImage() {
                        var wraps = @json($availableWrappings);
                        var self = this;
                        var active = wraps.find(function(w) { return w.id == self.selectedWrappingId; });
                        return active ? active.image_url : '/media/wraps/wrap.jpg';
                    },

                    getWrappingName() {
                        var wraps = @json($availableWrappings);
                        var self = this;
                        var active = wraps.find(function(w) { return w.id == self.selectedWrappingId; });
                        return active ? active.name : 'None';
                    },

                    getWrappingPrice() {
                        var wraps = @json($availableWrappings);
                        var self = this;
                        var active = wraps.find(function(w) { return w.id == self.selectedWrappingId; });
                        return active ? active.price : 0;
                    },

                    getRibbonName() {
                        var ribbons = @json($availableRibbons);
                        var self = this;
                        var active = ribbons.find(function(r) { return r.id == self.selectedRibbonId; });
                        return active ? active.name.replace(' Ribbon', '').replace(' Satin', '') : 'None';
                    },

                    getRibbonPrice() {
                        var ribbons = @json($availableRibbons);
                        var self = this;
                        var active = ribbons.find(function(r) { return r.id == self.selectedRibbonId; });
                        return active ? active.price : 0;
                    },

                    getMistPrice() {
                        var mists = @json($availableMists);
                        var self = this;
                        var active = mists.find(function(m) { return m.id == self.selectedMistId; });
                        return active ? active.price : 0;
                    },

                    getStemName(id) {
                        var stems = @json($availableStems);
                        var stem = stems.find(function(s) { return s.id == id; });
                        return stem ? stem.name.replace('Naivasha Volcanic ', '').replace('Naivasha Pure ', '').replace('Naivasha ', '').replace('Limuru Pure ', '').replace('Limuru ', '').replace(' (Grade A)', '') : '';
                    },

                    getStemPrice(id) {
                        var stems = @json($availableStems);
                        var stem = stems.find(function(s) { return s.id == id; });
                        return stem ? stem.price : 0;
                    },

                    getGiftName(id) {
                        var gifts = @json($availableWines).concat(@json($availableChocolates)).concat(@json($availableJewelry));
                        var gift = gifts.find(function(g) { return g.id == id; });
                        return gift ? gift.name.replace(' Premium', '').replace(' Gourmet', '').replace(' Luxury', '') : '';
                    },

                    getGiftPrice(id) {
                        var gifts = @json($availableWines).concat(@json($availableChocolates)).concat(@json($availableJewelry));
                        var gift = gifts.find(function(g) { return g.id == id; });
                        return gift ? gift.price : 0;
                    },

                    getSelectedStemsSummary() {
                        var stems = @json($availableStems);
                        var parts = [];
                        for (var id in this.localStems) {
                            var qty = this.localStems[id];
                            if (qty > 0) {
                                var stem = stems.find(function(s) { return s.id == id; });
                                if (stem) {
                                    var shortName = stem.name.replace('Naivasha Volcanic ', '').replace('Naivasha Pure ', '').replace('Naivasha ', '').replace('Limuru Pure ', '').replace('Limuru ', '').replace(' (Grade A)', '');
                                    parts.push(qty + 'x ' + shortName);
                                }
                            }
                        }
                        return parts.length > 0 ? parts.join(', ') : 'No Stems';
                    },

                    getTotalStemCount() {
                        var count = 0;
                        for (var id in this.localStems) {
                            count += parseInt(this.localStems[id] || 0);
                        }
                        return count;
                    },

                    scrollToSection(id) {
                        var el = document.getElementById(id);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    },

                    getMistName() {
                        var mists = @json($availableMists);
                        var self = this;
                        var active = mists.find(function(m) { return m.id == self.selectedMistId; });
                        return active ? active.name.replace('Atelier ', '').replace(' Mist', '') : 'None';
                    },

                    getTotalGiftCount() {
                        var count = 0;
                        for (var id in this.localGifts) {
                            count += parseInt(this.localGifts[id] || 0);
                        }
                        return count;
                    }
                };
            });
        });
    </script>
</div>
