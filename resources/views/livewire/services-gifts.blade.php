@section('meta')
    <meta name="description" content="Explore Atelier Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
    <meta name="keywords" content="wedding flowers Kenya, corporate flower subscription Nairobi, flower subscriptions Kiambu, custom gift hampers Nairobi, luxury event florist">
    <meta name="author" content="Atelier Noir & Bloom">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph (Facebook / Pinterest / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Bespoke Services &amp; Luxury Gifting Suites | Atelier Noir & Bloom">
    <meta property="og:description" content="Explore Atelier Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
    <meta property="og:image" content="{{ asset('media/og-services.jpg') }}">
    <meta property="og:site_name" content="Atelier Noir & Bloom">
    <meta property="og:locale" content="en_KE">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Bespoke Services &amp; Luxury Gifting Suites | Atelier Noir & Bloom">
    <meta name="twitter:description" content="Explore Atelier Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
    <meta name="twitter:image" content="{{ asset('media/og-services.jpg') }}">
    <meta name="twitter:site" content="@NoirAndBloom">

    <!-- Google Search Engine Structured LocalBusiness Schema (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@graph": [
        {
          "@type": "LocalBusiness",
          "@id": "{{ url('/') }}/#nairobi-branch",
          "name": "Atelier Noir & Bloom - Nairobi Branch",
          "image": "{{ asset('media/nairobi-atelier.jpg') }}",
          "telephone": "+254-712-345-678",
          "url": "{{ url('/') }}",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Riverside Drive, Office Park Complexes",
            "addressLocality": "Nairobi",
            "addressCountry": "KE"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -1.2921,
            "longitude": 36.8219
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday"
            ],
            "opens": "07:00",
            "closes": "20:00"
          }
        },
        {
          "@type": "LocalBusiness",
          "@id": "{{ url('/') }}/#kiambu-branch",
          "name": "Atelier Noir & Bloom - Kiambu Branch",
          "image": "{{ asset('media/kiambu-atelier.jpg') }}",
          "telephone": "+254-712-345-678",
          "url": "{{ url('/') }}",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Limuru Road, Tea Estate Ridge",
            "addressLocality": "Kiambu",
            "addressCountry": "KE"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -1.1478,
            "longitude": 36.8524
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday"
            ],
            "opens": "07:00",
            "closes": "20:00"
          }
        }
      ]
    }
    </script>
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
        'bg-[#050507] text-[#F4F4F5]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne',
        'bg-[#15060A] text-[#FCE7EC]': theme === 'rose'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-hidden"
>
    <!-- 3D Flower Ambient Animation Canvas -->

    <canvas id="flower-ambient-canvas" wire:ignore x-data="canvasAmbient" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Fine Grain Noise Overlay -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-[0.03]"></div>
    
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
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 animate-nav-item select-none cursor-pointer {{ request()->routeIs('services-gifts') ? 'text-[#C5A880] font-semibold' : '' }}" style="animation-delay: 200ms;">Services</a>
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
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#050507]" title="Background"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#C5A880]" title="Accent"></span>
                                <span class="w-4 h-4 rounded-full border border-neutral-800 bg-[#F4F4F5]" title="Text"></span>
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

    <main class="max-w-8xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col z-10 relative">
        <div class="space-y-4 mb-12">
            <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-[#C5A880] block">Atelier Noir & Bloom</span>
            <h1 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-4xl sm:text-5xl font-outfit font-semibold uppercase tracking-wider leading-tight">
                Services &amp; Gifting Accents
            </h1>
            <p class="text-sm font-light text-neutral-500 max-w-2xl">
                Explore our premium custom consults, luxury event designs, workspace subscriptions, and additional curated chocolates, cards, and accessories to elevate your collections.
            </p>
        </div>

        <!-- 1. Rectangular Cards for Custom Services/Specializations -->
        <section class="space-y-6 mb-16">
            <h3 class="text-[14px] font-mono uppercase tracking-[0.25em] text-neutral-400 font-bold border-b border-neutral-500/10 pb-2">
                &bull; Bespoke Specialized Services
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $srv)
                    <div 
                        :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                        class="col-span-1 flex flex-row p-3 rounded-[24px] border relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 group text-left backdrop-blur-md theme-section self-start min-h-[170px]"
                    >
                        <!-- Left side: Squared Image Frame -->
                        <div class="w-[105px] sm:w-[125px] aspect-square rounded-2xl relative overflow-hidden bg-neutral-950/5 p-1 border border-neutral-500/10 shrink-0 self-center">
                            <img src="{{ $srv->image_url }}" alt="{{ $srv->name }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-750 group-hover:scale-105 z-0">
                            <div class="absolute bottom-2 left-2 z-10">
                                <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded-full text-[8px] font-mono font-bold tracking-wider uppercase shadow-md">
                                    {{ $srv->grade ?? 'Service' }}
                                </span>
                            </div>
                        </div>
                        <!-- Right details -->
                        <div class="flex-1 pl-3 flex flex-col justify-between overflow-hidden">
                            <div class="space-y-1 overflow-hidden">
                                <span class="text-[9px] uppercase tracking-[0.2em] text-[#C5A880] font-mono block font-bold truncate">Specialization</span>
                                <h4 class="text-sm font-serif italic tracking-wide leading-tight truncate text-current">{{ $srv->name }}</h4>
                                <p class="text-neutral-500 font-light text-[11px] leading-snug line-clamp-2">{{ $srv->description }}</p>
                            </div>

                            <div class="space-y-2 mt-1">
                                <!-- Social Sharing Direct Links for SMM (Instagram, Facebook, X SVGs) -->
                                <div :class="theme === 'champagne' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center space-x-2.5 text-[10px] font-mono uppercase">
                                    <span class="text-neutral-500 text-[8px] uppercase tracking-widest font-bold font-sans">Share:</span>
                                    <!-- Instagram Icon -->
                                    <a href="https://instagram.com" target="_blank" rel="noopener" class="hover:text-pink-500 transition-colors" title="Instagram">
                                        <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                        </svg>
                                    </a>
                                    <!-- Facebook Icon -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/services-gifts')) }}" target="_blank" rel="noopener" class="hover:text-blue-500 transition-colors" title="Facebook">
                                        <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                        </svg>
                                    </a>
                                    <!-- X Icon -->
                                    <a href="https://twitter.com/intent/tweet?text=Bespoke+{{ urlencode($srv->name) }}+service+from+@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" target="_blank" rel="noopener" :class="theme === 'champagne' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors" title="Share on X">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                </div>

                                <div class="flex items-center justify-between border-t border-neutral-500/10 pt-2">
                                    <div>
                                        <span class="text-[8px] font-mono uppercase tracking-[0.15em] text-neutral-400 block">Base Fee</span>
                                        <span class="font-mono text-xs font-bold text-amber-500">{{ number_format($srv->price) }} KSH</span>
                                    </div>
                                    <a 
                                        href="/profile-portal"
                                        :class="theme === 'champagne' ? 'bg-black text-white hover:bg-[#B59A7A] hover:text-black' : 'bg-white text-black hover:bg-[#C5A880] hover:text-black'"
                                        class="px-3 py-1.5 rounded-full text-[9px] font-mono uppercase font-bold tracking-wider transition-all duration-300 shadow-md"
                                    >
                                        Request
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 2. Curated Gifts/Hampers and Accessories -->
        <section class="space-y-6 mb-20">
            <h3 class="text-[14px] font-mono uppercase tracking-[0.25em] text-neutral-400 font-bold border-b border-neutral-500/10 pb-2">
                &bull; Luxury Hampers & Gift Accents
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($gifts as $gift)
                    <div 
                        :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                        class="flex flex-col p-3 rounded-[32px] border relative transition-all duration-500 hover:-translate-y-1 group backdrop-blur-md"
                    >
                        <div class="aspect-[1/1] rounded-[24px] relative overflow-hidden bg-neutral-950/5">
                            <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-750 group-hover:scale-105">
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-black/50 text-neutral-300 border border-white/10 px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider">
                                    {{ $gift->unit_type }}
                                </span>
                            </div>
                        </div>
                        <div class="px-2 pt-4 pb-2 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h4 class="text-base font-serif italic tracking-wide leading-snug">{{ $gift->name }}</h4>
                                <p class="text-neutral-500 font-light text-xs line-clamp-2">{{ $gift->description }}</p>
                                
                                <!-- Social Sharing Direct Links for SMM -->
                                <div :class="theme === 'champagne' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center space-x-2 text-[9px] font-mono uppercase tracking-wider pt-1">
                                    <span class="text-neutral-500 font-bold">Share:</span>
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20the%20exclusive%20{{ urlencode($gift->name) }}%20gift%20accent%20at%20Noir%20%26%20Bloom:%20{{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors font-bold" title="Share via WhatsApp">WA</a>
                                    <a href="https://twitter.com/intent/tweet?text=Premium%20{{ urlencode($gift->name) }}%20gift%20at%20@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" :class="theme === 'champagne' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors font-bold" title="Share on X">X</a>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-neutral-500/10 pt-3">
                                <span class="text-sm font-mono font-bold text-amber-500">{{ number_format($gift->price) }} KSH</span>
                                <a 
                                    href="/"
                                    :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'"
                                    class="px-3.5 py-1.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider transition-all"
                                >
                                    Select
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
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
            
            {{-- Social Media Icons --}}
            <div class="flex items-center space-x-3.5">
                {{-- Instagram --}}
                <a href="https://instagram.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.3)]': theme === 'onyx',
                       'border-neutral-200 text-neutral-400 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.25)]': theme === 'champagne',
                       'border-[#2D121F] text-pink-300/40 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.3)]': theme === 'rose'
                   }" title="Instagram">
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
                   }" title="Facebook">
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
                   }" title="X (Twitter)">
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
                   }" title="Pinterest">
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
                   }" title="WhatsApp">
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


</div>
