@php
if (!function_exists('toJsObject')) {
    function toJsObject($product) {
        return \Illuminate\Support\Js::from([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'price_standard' => $product->price_standard,
            'price_deluxe' => $product->price_deluxe,
            'price_grand' => $product->price_grand,
            'description' => $product->description,
            'image' => $product->backdrop_url ?? $product->image_url ?? '/media/default.jpg',
            'category' => $product->category,
            'stock_standard' => $product->stock_standard,
            'stock_deluxe' => $product->stock_deluxe,
            'stock_grand' => $product->stock_grand,
            'average_rating' => $product->average_rating,
            'average_quality_rating' => $product->average_quality_rating,
            'average_freshness_rating' => $product->average_freshness_rating,
            'average_value_rating' => $product->average_value_rating,
        ]);
    }
}
@endphp
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
          "telephone": "+254 (0) 712354697",
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
          "telephone": "+254 (0) 712354697",
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
    @wishlist-updated.window="wishlistIds = $event.detail.wishlistIds || []"
    x-data="{ 
        theme: (() => {
            try {
                @auth
                    const pref = '{{ auth()->user()->settings["preferred_theme"] ?? "" }}';
                    if (pref) return (pref === 'onyx' || pref === 'dark') ? 'dark' : 'light';
                @endauth
                const stored = localStorage.getItem('nb_theme');
                return (stored === 'onyx' || stored === 'dark') ? 'dark' : 'light';
            } catch (e) {
                return 'light';
            }
        })(),
        hoverTheme: null,
        changeTheme(targetTheme) {
            if (this.theme === targetTheme) return;
            this.theme = targetTheme;
        },
        quickViewOpen: false,
        quickViewProduct: null,
        quickViewSize: 'standard',
        numberFormat(val) { return new Intl.NumberFormat().format(val); },
        profileOpen: false,
        notificationsOpen: false,
        mobileMenuOpen: false,
        wishlistIds: @js(auth()->check() ? (auth()->user()->settings['wishlist'] ?? []) : [])
    }" 
    x-init="
        $watch('quickViewOpen', val => {
            if (val && quickViewProduct) {
                $wire.loadProductReviews(quickViewProduct.id);
            }
        });

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
    "
    :class="{
        'bg-[#050507] text-[#E4E4E7]': theme === 'dark',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'light',
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
            'bg-[#050507]/80 border-b border-neutral-850 shadow-2xl text-white': theme === 'dark',
            'bg-[#FAF7F0]/80 border-b border-neutral-200 shadow-md text-neutral-900': theme === 'light',
        }"
        class="fixed top-0 inset-x-0 w-full h-24 backdrop-blur-md z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group theme-section"
    >
        <!-- Bottom Accent Glow Line -->
        <div class="absolute bottom-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent to-transparent"
             :class="{
                 'via-[#C5A880]/30': theme === 'dark',
                 'via-emerald-600/30': theme === 'light',
             }"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            {{-- Mobile Menu Hamburger --}}
            <button @click="mobileMenuOpen = true" 
                    class="md:hidden transition-colors cursor-pointer select-none relative w-9 h-9 flex items-center justify-center rounded-full shadow-sm shrink-0" 
                    :class="{
                        'border border-neutral-700 bg-neutral-900/40 text-neutral-350 hover:text-[#C5A880]': theme === 'dark',
                        'border border-neutral-200 bg-neutral-50 text-neutral-700 hover:text-emerald-700': theme === 'light',
                    }"
                    title="Menu"
            >
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

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
                <a href="{{ route('services-gifts') }}" 
                   class="hidden md:flex items-center space-x-1.5 px-4 py-2 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer font-semibold text-xs tracking-wider border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880]"
                   style="animation-delay: 200ms;">
                   <span class="w-1.5 h-1.5 rounded-full animate-pulse bg-[#C5A880]"></span>
                   <span>Services & Gifts</span>
                </a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-5 py-2.5 rounded-full border transition-all duration-300 animate-nav-item select-none cursor-pointer {{ request()->routeIs('curate') ? 'border-[#C5A880] bg-[#C5A880]/10 text-[#C5A880] font-semibold' : 'border-[#C5A880]/30 hover:border-[#C5A880] hover:bg-[#C5A880]/5 text-[#C5A880]' }}"
                   style="animation-delay: 250ms;">
                   Curate Your Arrangement
                </a>

                {{-- Theme Switcher Dropdown (Header) --}}
                <div x-data="{ themeMenuOpen: false }" class="hidden md:inline-block relative text-left select-none animate-nav-item">
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
                                class="transition-all cursor-pointer select-none w-10 h-10 flex items-center justify-center rounded-full shadow-sm"
                                :class="{
                                    'hover:border-[#C5A880] border border-neutral-700 bg-neutral-900/40': theme === 'dark',
                                    'hover:border-emerald-600 border border-neutral-200 bg-neutral-100': theme === 'light',
                                }"
                                title="Profile Portal Options"
                        >
                            <span class="text-xs font-mono font-bold tracking-wider uppercase"
                                  :class="{
                                      'text-[#C5A880]': theme === 'dark',
                                      'text-emerald-800': theme === 'light',
                                  }">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                            </span>
                        </button>
                    @else
                        <!-- Log In / Sign In Button for Guests -->
                        <button @click="profileOpen = true" 
                                class="transition-all duration-300 hover:scale-[1.03] cursor-pointer select-none px-5 h-11 flex items-center justify-center space-x-2 rounded-full text-xs font-sans font-semibold tracking-widest uppercase"
                                :class="{
                                    'border border-neutral-700 bg-neutral-900/40 text-neutral-350 hover:text-[#C5A880] hover:border-[#C5A880]': theme === 'dark',
                                    'border border-neutral-200 bg-neutral-50 text-neutral-700 hover:text-emerald-800 hover:border-emerald-600': theme === 'light',
                                }"
                                title="Log In or Sign In"
                        >
                            <svg class="w-4 h-4 stroke-current fill-none transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3M15 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="hidden sm:inline">Sign In</span>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-8xl w-full mx-auto px-6 pt-36 flex-1 flex flex-col z-10 relative">
        <div class="space-y-4 mb-12">
            <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-[#C5A880] block">Atelier Noir & Bloom</span>
            <h1 :class="theme === 'light' ? 'text-black' : 'text-white'" class="text-4xl sm:text-5xl font-outfit font-semibold uppercase tracking-wider leading-tight">
                Services &amp; Gifting Accents
            </h1>
            <p class="text-sm font-light text-neutral-500 max-w-2xl">
                Explore our premium custom consults, luxury event designs, workspace subscriptions, and additional curated chocolates, cards, and accessories to elevate your collections.
            </p>
        </div>

        <!-- Bespoke Services Marketing Hero Slider (4 Ads) -->
        <section class="mb-12 relative rounded-[32px] overflow-hidden shadow-2xl border transition-colors duration-500"
                 :class="theme === 'light' ? 'border-neutral-200' : 'border-neutral-850'"
                 x-data="{ 
                     activeSlide: 0,
                     slidesCount: 4,
                     autoPlayInterval: null,
                     startAutoPlay() {
                         this.autoPlayInterval = setInterval(() => {
                             this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                         }, 6000);
                     },
                     stopAutoPlay() {
                         if (this.autoPlayInterval) clearInterval(this.autoPlayInterval);
                     }
                 }"
                 x-init="startAutoPlay()"
                 @mouseenter="stopAutoPlay()"
                 @mouseleave="startAutoPlay()"
        >
            <div class="relative w-full h-[360px] md:h-[420px] overflow-hidden bg-black">
                <!-- Slide 1 -->
                <div x-show="activeSlide === 0" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1200" alt="Bespoke Weddings & Galas" class="absolute inset-0 w-full h-full object-cover brightness-[0.6] hover:brightness-[0.75] transition-all duration-500 scale-105" :class="activeSlide === 0 ? 'scale-100' : 'scale-105'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/40 flex flex-col justify-end p-8 md:p-12 text-left space-y-3.5">
                        <span class="text-[10px] font-mono uppercase tracking-[0.3em] text-[#C5A880] font-bold">✦ Signature Event Design</span>
                        <h2 class="text-2xl md:text-4xl font-serif italic text-white tracking-wide leading-tight max-w-2xl">Bespoke Weddings &amp; Immersive Galas</h2>
                        <p class="text-xs md:text-sm font-light text-neutral-350 max-w-xl leading-relaxed">Uncompromising floral architecture and bespoke botanical landscaping designed to tell your custom love story.</p>
                        <div class="pt-2">
                            <a href="/profile-portal" class="inline-block bg-[#C5A880] hover:bg-[#B59A7A] text-black font-mono text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-full transition-all shadow-lg">Schedule Consult</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div x-show="activeSlide === 1" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200" alt="Corporate Subscriptions" class="absolute inset-0 w-full h-full object-cover brightness-[0.6] hover:brightness-[0.75] transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/40 flex flex-col justify-end p-8 md:p-12 text-left space-y-3.5">
                        <span class="text-[10px] font-mono uppercase tracking-[0.3em] text-[#C5A880] font-bold">✦ B2B Workspace Rotations</span>
                        <h2 class="text-2xl md:text-4xl font-serif italic text-white tracking-wide leading-tight max-w-2xl">Corporate Office &amp; Showroom Subscriptions</h2>
                        <p class="text-xs md:text-sm font-light text-neutral-350 max-w-xl leading-relaxed">Weekly curated design statements that elevate corporate workspace psychology and leave lasting brand impressions.</p>
                        <div class="pt-2">
                            <a href="/profile-portal" class="inline-block bg-[#C5A880] hover:bg-[#B59A7A] text-black font-mono text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-full transition-all shadow-lg">Request Subscription</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div x-show="activeSlide === 2" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1200" alt="Private Home Rotations" class="absolute inset-0 w-full h-full object-cover brightness-[0.6] hover:brightness-[0.75] transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/40 flex flex-col justify-end p-8 md:p-12 text-left space-y-3.5">
                        <span class="text-[10px] font-mono uppercase tracking-[0.3em] text-[#C5A880] font-bold">✦ Residential Curation</span>
                        <h2 class="text-2xl md:text-4xl font-serif italic text-white tracking-wide leading-tight max-w-2xl">Bespoke Home Rotations &amp; Decor</h2>
                        <p class="text-xs md:text-sm font-light text-neutral-350 max-w-xl leading-relaxed">Fresh, custom-curated designer arrays delivered directly to your residence on a flexible repeating calendar.</p>
                        <div class="pt-2">
                            <a href="/profile-portal" class="inline-block bg-[#C5A880] hover:bg-[#B59A7A] text-black font-mono text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-full transition-all shadow-lg">Inquire Now</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div x-show="activeSlide === 3" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full" style="display: none;">
                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1200" alt="Luxury Hampers" class="absolute inset-0 w-full h-full object-cover brightness-[0.6] hover:brightness-[0.75] transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/40 flex flex-col justify-end p-8 md:p-12 text-left space-y-3.5">
                        <span class="text-[10px] font-mono uppercase tracking-[0.3em] text-[#C5A880] font-bold">✦ Premium Gifting Suites</span>
                        <h2 class="text-2xl md:text-4xl font-serif italic text-white tracking-wide leading-tight max-w-2xl">Curated Gift Hampers &amp; Treats</h2>
                        <p class="text-xs md:text-sm font-light text-neutral-350 max-w-xl leading-relaxed">Tailored luxury gift bundles pairing fine wine, select chocolates, custom scent mists, and fresh stems.</p>
                        <div class="pt-2">
                            <a href="/profile-portal" class="inline-block bg-[#C5A880] hover:bg-[#B59A7A] text-black font-mono text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-full transition-all shadow-lg">Order Custom Hamper</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide Indicators (Dots) -->
            <div class="absolute bottom-5 right-8 z-30 flex items-center space-x-2">
                <template x-for="(s, idx) in Array.from({length: slidesCount})" :key="idx">
                    <button @click="activeSlide = idx" class="w-2.5 h-2.5 rounded-full transition-all duration-300 cursor-pointer" :class="activeSlide === idx ? 'bg-[#C5A880] w-6' : 'bg-white/40 hover:bg-white/80'"></button>
                </template>
            </div>

            <!-- Navigation Arrows -->
            <button @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 border border-white/10 text-white flex items-center justify-center transition-all cursor-pointer">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button @click="activeSlide = (activeSlide + 1) % slidesCount" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 border border-white/10 text-white flex items-center justify-center transition-all cursor-pointer">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </section>

        @if(session('success_wishlist'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="mb-6 p-3 border border-dashed border-emerald-800 bg-emerald-950/20 text-emerald-400 text-xs font-mono rounded-xl"
            >
                {{ session('success_wishlist') }}
            </div>
        @endif
        @if(session('error_wishlist'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="mb-6 p-3 border border-dashed border-rose-800 bg-rose-950/20 text-rose-400 text-xs font-mono rounded-xl"
            >
                {{ session('error_wishlist') }}
            </div>
        @endif

        <!-- 1. Rectangular Cards for Custom Services/Specializations -->
        <section class="space-y-6 mb-16">
            <h3 class="text-[14px] font-mono uppercase tracking-[0.25em] text-neutral-400 font-bold border-b border-neutral-500/10 pb-2">
                &bull; Bespoke Specialized Services
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $srv)
                    <div 
                        :class="theme === 'light' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                        class="col-span-1 flex flex-row p-3 rounded-[24px] border relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 group text-left backdrop-blur-md theme-section self-start min-h-[170px]"
                    >
                        <!-- Left side: Squared Image Frame -->
                        <div class="w-[105px] sm:w-[125px] aspect-square rounded-2xl relative overflow-hidden bg-neutral-950/5 p-1 border border-neutral-500/10 shrink-0 self-center">
                            <img src="{{ $srv->image_url }}" alt="{{ $srv->name }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-750 group-hover:scale-105 z-0 cursor-pointer" @click="quickViewProduct = {{ toJsObject($srv) }}; quickViewSize = 'standard'; quickViewOpen = true;" loading="lazy">
                            
                            @auth
                                @php
                                    $inWishlist = in_array($srv->id, auth()->user()->settings['wishlist'] ?? []);
                                @endphp
                            @else
                                @php
                                    $inWishlist = false;
                                @endphp
                            @endauth
                            <!-- Wishlist Button -->
                            <button 
                                type="button" 
                                @click.stop=""
                                wire:click="toggleWishlist({{ $srv->id }})" 
                                class="absolute top-2 right-2 z-20 w-9 h-9 rounded-full flex items-center justify-center bg-[#0B0B0D]/65 border border-white/10 text-[#C5A880] hover:scale-110 hover:bg-neutral-900 transition-all cursor-pointer shadow-md"
                                title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                            >
                                <svg class="w-4.5 h-4.5 fill-current {{ $inWishlist ? 'text-rose-500' : 'text-neutral-400 fill-none stroke-current' }}" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>

                            <div class="absolute bottom-2 inset-x-2 flex justify-between items-center z-10">
                                <span class="bg-[#0B0B0D]/65 border border-white/10 text-neutral-350 px-1.5 py-0.5 rounded text-[8px] font-mono uppercase tracking-wider backdrop-blur-md">
                                    {{ $srv->category }}
                                </span>
                                @if($srv->grade)
                                    <span class="bg-[#C5A880] text-black px-1.5 py-0.5 rounded-full text-[8px] font-mono font-bold tracking-wider uppercase shadow-md">
                                        {{ $srv->grade }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Right details -->
                        <div class="flex-1 pl-3 flex flex-col justify-between overflow-hidden">
                            <div class="space-y-1 overflow-hidden">
                                <span class="text-[9px] uppercase tracking-[0.2em] text-[#C5A880] font-mono block font-bold truncate">Specialization</span>
                                <h4 class="text-sm font-serif italic tracking-wide leading-tight truncate text-current cursor-pointer hover:underline" @click="quickViewProduct = {{ toJsObject($srv) }}; quickViewSize = 'standard'; quickViewOpen = true;">{{ $srv->name }}</h4>
                                <p class="text-neutral-500 font-light text-[11px] leading-snug line-clamp-2">{{ $srv->description }}</p>
                            </div>

                            <div class="space-y-2 mt-1">
                                <!-- Social Sharing Direct Links for SMM (Instagram, Facebook, X SVGs) -->
                                <div :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center space-x-2.5 text-[10px] font-mono uppercase">
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
                                    <a href="https://twitter.com/intent/tweet?text=Bespoke+{{ urlencode($srv->name) }}+service+from+@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" target="_blank" rel="noopener" :class="theme === 'light' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors" title="Share on X">
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
                                        :class="theme === 'light' ? 'bg-black text-white hover:bg-[#B59A7A] hover:text-black' : 'bg-white text-black hover:bg-[#C5A880] hover:text-black'"
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
                        :class="theme === 'light' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                        class="flex flex-col p-3 rounded-[32px] border relative transition-all duration-500 hover:-translate-y-1 group backdrop-blur-md"
                    >
                        <div class="aspect-[1/1] rounded-[24px] relative overflow-hidden bg-neutral-950/5">
                            <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-750 group-hover:scale-105 cursor-pointer" @click="quickViewProduct = {{ toJsObject($gift) }}; quickViewSize = 'standard'; quickViewOpen = true;" loading="lazy">
                            
                            @auth
                                @php
                                    $inWishlist = in_array($gift->id, auth()->user()->settings['wishlist'] ?? []);
                                @endphp
                            @else
                                @php
                                    $inWishlist = false;
                                @endphp
                            @endauth
                            <!-- Wishlist Button -->
                            <button 
                                type="button" 
                                @click.stop=""
                                wire:click="toggleWishlist({{ $gift->id }})" 
                                class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full flex items-center justify-center bg-[#0B0B0D]/65 border border-white/10 text-[#C5A880] hover:scale-110 hover:bg-neutral-900 transition-all cursor-pointer shadow-md"
                                title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                            >
                                <svg class="w-4.5 h-4.5 fill-current {{ $inWishlist ? 'text-rose-500' : 'text-neutral-400 fill-none stroke-current' }}" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>

                            <div class="absolute bottom-3 inset-x-3 flex justify-between items-center z-10">
                                <span class="bg-[#0B0B0D]/65 border border-white/10 text-neutral-300 px-2 py-0.5 rounded text-[9px] font-mono uppercase tracking-wider backdrop-blur-md">
                                    {{ $gift->category }}
                                </span>
                                @if($gift->grade)
                                    <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded-full text-[9px] font-mono font-bold tracking-wider uppercase shadow-md">
                                        {{ $gift->grade }}
                                    </span>
                                @else
                                    <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded-full text-[9px] font-mono font-bold tracking-wider uppercase shadow-md">
                                        {{ $gift->unit_type }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="px-2 pt-4 pb-2 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h4 class="text-base font-serif italic tracking-wide leading-snug cursor-pointer hover:underline" @click="quickViewProduct = {{ toJsObject($gift) }}; quickViewSize = 'standard'; quickViewOpen = true;">{{ $gift->name }}</h4>
                                <p class="text-neutral-500 font-light text-xs line-clamp-2">{{ $gift->description }}</p>
                                
                                <!-- Social Sharing Direct Links for SMM -->
                                <div :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center space-x-2 text-[9px] font-mono uppercase tracking-wider pt-1">
                                    <span class="text-neutral-500 font-bold">Share:</span>
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20the%20exclusive%20{{ urlencode($gift->name) }}%20gift%20accent%20at%20Noir%20%26%20Bloom:%20{{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors font-bold" title="Share via WhatsApp">WA</a>
                                    <a href="https://twitter.com/intent/tweet?text=Premium%20{{ urlencode($gift->name) }}%20gift%20at%20@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" :class="theme === 'light' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors font-bold" title="Share on X">X</a>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-neutral-500/10 pt-3">
                                <span class="text-sm font-mono font-bold text-amber-500">{{ number_format($gift->price) }} KSH</span>
                                <a 
                                    href="/"
                                    :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'"
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
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'dark',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'light',
        }"
        class="border-t mt-12 py-6 px-6 transition-colors duration-500 z-10 relative theme-section"
    >
        <div class="max-w-5xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
            <!-- Col 1: Brand & Info -->
            <div class="space-y-2.5">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'light' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors">Noir & Bloom</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
            </div>

            <!-- Col 2: Showroom Catalog Links -->
            <div class="space-y-2.5">
                <h5 :class="theme === 'light' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><a href="/?tier=bouquet" class="hover:underline">Bespoke Retail Arrays</a></li>
                    <li><a href="/?tier=stems" class="hover:underline">Wholesale Graded Stems</a></li>
                    <li><a href="/?tier=giftings" class="hover:underline">Luxury Giftings</a></li>
                    <li><a href="/profile-portal" class="hover:underline">Atelier Loyalty Circle</a></li>
                </ul>
            </div>

            <!-- Col 3: Hours & Support -->
            <div class="space-y-2.5">
                <h5 :class="theme === 'light' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><span class="block text-neutral-500">Operating Hours</span> Mon &mdash; Sat: 07:00 &mdash; 20:00</li>
                    <li>Sunday: 09:00 &mdash; 17:00</li>
                    <li class="pt-2"><span class="block text-neutral-500 font-mono text-[11px] uppercase tracking-wider">Hotline Direct</span> +254 (0) 712354697</li>
                    <li>concierge@noirbloom.co.ke</li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div class="space-y-2.5">
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
            
            {{-- Social Media Icons --}}
            <div class="flex items-center space-x-3.5">
                {{-- Instagram --}}
                <a href="https://instagram.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.3)]': theme === 'dark',
                       'border-neutral-200 text-neutral-400 hover:text-[#E1306C] hover:border-[#E1306C] hover:shadow-[0_0_15px_rgba(225,48,108,0.25)]': theme === 'light',
                   }" title="Instagram">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                    </svg>
                </a>
                {{-- Facebook --}}
                <a href="https://facebook.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#1877F2] hover:border-[#1877F2] hover:shadow-[0_0_15px_rgba(24,119,242,0.3)]': theme === 'dark',
                       'border-neutral-200 text-neutral-400 hover:text-[#1877F2] hover:border-[#1877F2] hover:shadow-[0_0_15px_rgba(24,119,242,0.25)]': theme === 'light',
                   }" title="Facebook">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
                    </svg>
                </a>
                {{-- X (Twitter) --}}
                <a href="https://twitter.com/NoirAndBloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-white hover:border-white hover:shadow-[0_0_15px_rgba(255,255,255,0.2)]': theme === 'dark',
                       'border-neutral-200 text-neutral-400 hover:text-black hover:border-black hover:shadow-[0_0_15px_rgba(0,0,0,0.15)]': theme === 'light',
                   }" title="X (Twitter)">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                {{-- Pinterest --}}
                <a href="https://pinterest.com/noirandbloom" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#E60023] hover:border-[#E60023] hover:shadow-[0_0_15px_rgba(230,0,35,0.3)]': theme === 'dark',
                       'border-neutral-200 text-neutral-400 hover:text-[#E60023] hover:border-[#E60023] hover:shadow-[0_0_15px_rgba(230,0,35,0.25)]': theme === 'light',
                   }" title="Pinterest">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.395-5.92 1.395-5.92s-.36-.715-.36-1.777c0-1.664.962-2.907 2.162-2.907 1.02 0 1.513.766 1.513 1.682 0 1.026-.65 2.558-.99 3.978-.282 1.187.592 2.155 1.764 2.155 2.113 0 3.738-2.23 3.738-5.447 0-2.848-2.049-4.839-4.969-4.839-3.385 0-5.372 2.54-5.372 5.163 0 1.023.392 2.122.882 2.719.098.118.113.22.083.342-.09.378-.292 1.189-.331 1.348-.052.21-.173.253-.399.148-1.492-.695-2.423-2.88-2.423-4.636 0-3.774 2.744-7.24 7.907-7.24 4.15 0 7.375 2.957 7.375 6.9 0 4.124-2.597 7.443-6.204 7.443-1.213 0-2.355-.63-2.744-1.373l-.747 2.847c-.269 1.027-.997 2.316-1.488 3.118 4.417 1.282 9.21.365 12.825-2.525C22.617 19.387 24 15.86 24 11.987 24 5.367 18.63 0 12.017 0z"/>
                    </svg>
                </a>
                {{-- WhatsApp --}}
                <a href="https://wa.me/254712354697" target="_blank" rel="noopener"
                   class="w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 hover:scale-110 hover:-translate-y-0.5"
                   :class="{
                       'border-neutral-800 text-neutral-500 hover:text-[#25D366] hover:border-[#25D366] hover:shadow-[0_0_15px_rgba(37,211,102,0.3)]': theme === 'dark',
                       'border-neutral-200 text-neutral-400 hover:text-[#25D366] hover:border-[#25D366] hover:shadow-[0_0_15px_rgba(37,211,102,0.25)]': theme === 'light',
                   }" title="WhatsApp">
                    <svg class="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                        <path d="M19.005 3.175C17.252 1.42 14.927.453 12.443.453 7.429.453 3.353 4.53 3.353 9.544c0 1.602.418 3.167 1.213 4.544L2.247 22.25l8.36-2.193c1.332.726 2.828 1.11 4.363 1.112h.006c5.011 0 9.088-4.076 9.088-9.09 0-2.43-.946-4.714-2.703-6.471l-.356-.356zm-6.562 16.92c-1.442-.002-2.857-.388-4.095-1.116l-.294-.174-5.043 1.323 1.347-4.923-.19-.304c-.8-1.272-1.222-2.742-1.22-4.26.002-4.42 3.6-8.016 8.026-8.016 2.14 0 4.153.834 5.666 2.348 1.513 1.513 2.345 3.526 2.343 5.67-.004 4.42-3.601 8.018-8.026 8.018l-.534-.016zm4.414-6.027c-.242-.12-1.432-.707-1.654-.788-.222-.08-.383-.12-.544.12-.16.242-.624.788-.765.947-.14.16-.282.18-.523.06-.24-.12-1.018-.374-1.94-1.196-.718-.64-1.202-1.43-1.343-1.67-.14-.242-.015-.373.106-.493.11-.108.242-.282.363-.423.12-.14.16-.242.242-.403.08-.16.04-.302-.02-.423-.06-.12-.544-1.31-.746-1.794-.197-.473-.396-.408-.544-.416-.14-.007-.302-.007-.463-.007s-.423.06-.644.302c-.22.242-.845.826-.845 2.015 0 1.19.865 2.338.986 2.5.12.16 1.704 2.602 4.13 3.65.577.248.995.397 1.353.51.58.185 1.107.159 1.523.097.464-.068 1.432-.585 1.633-1.15.202-.564.202-1.047.14-1.15-.06-.102-.222-.162-.463-.282z"/>
                    </svg>
                </a>
            </div>
            
            <div class="flex space-x-6">
                <a href="#" :class="theme === 'light' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Terms of Curation</a>
                <a href="#" :class="theme === 'light' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Logistics Policy</a>
                <a href="#" :class="theme === 'light' ? 'hover:text-neutral-800' : 'hover:text-neutral-400'" class="transition-colors">Invoice Request</a>
            </div>
        </div>
    </footer>


    <!-- Product Detail Modal (FNP-style) -->
    <div x-show="quickViewOpen" @click="quickViewOpen = false" class="fixed inset-0 z-45 bg-black/60 backdrop-blur-md" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div
        x-show="quickViewOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-32px)] sm:w-[640px] max-h-[90vh] z-50 bg-white/90 border border-neutral-200/80 text-neutral-900 shadow-2xl flex flex-col justify-between text-left rounded-[28px] overflow-hidden font-sans backdrop-blur-xl"
        style="display: none;"
    >
        <div class="p-5 border-b border-neutral-100 flex items-center justify-between shrink-0">
            <div>
                <span class="text-[9px] uppercase tracking-widest text-emerald-800 font-bold block" x-text="quickViewProduct ? quickViewProduct.category.replace('_', ' ') : ''"></span>
                <h3 class="text-lg font-serif italic text-neutral-800" x-text="quickViewProduct ? quickViewProduct.name : ''"></h3>
            </div>
            <button @click="quickViewOpen = false" class="text-neutral-450 hover:text-neutral-800 cursor-pointer select-none transition-colors" title="Close Details">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Image column -->
                <div class="w-full md:w-1/2 shrink-0">
                    <div class="w-full h-48 md:h-64 rounded-2xl overflow-hidden bg-neutral-150 relative">
                        <img :src="quickViewProduct ? quickViewProduct.image : ''" :alt="quickViewProduct ? quickViewProduct.name : ''" class="w-full h-full object-cover">
                    </div>
                    <p class="text-xs text-neutral-500 font-light mt-3 leading-relaxed" x-text="quickViewProduct ? quickViewProduct.description : ''"></p>
                </div>
                <!-- Config column -->
                <div class="flex-1 flex flex-col gap-4">
                    <!-- Size Selector -->
                    <div>
                        <span class="text-xs font-bold text-neutral-700 block mb-2">Select Sizing & Volume</span>
                        <div class="flex flex-wrap gap-2">
                            <!-- Standard -->
                            <button type="button" @click="quickViewSize = 'standard'"
                                :class="quickViewSize === 'standard' ? 'border-emerald-800 bg-emerald-50 text-emerald-800 font-semibold' : 'border-neutral-200 text-neutral-600 hover:text-neutral-800'"
                                class="flex-1 min-w-[80px] px-3 py-2 border text-[11px] font-outfit uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center"
                                :disabled="quickViewProduct && quickViewProduct.stock_standard <= 0"
                            >
                                <span class="block">Standard</span>
                                <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(quickViewProduct.price_standard) + ' KSH' : ''"></span>
                                <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_standard <= 0">Out of Stock</span>
                            </button>
                            <!-- Deluxe -->
                            <button type="button" @click="quickViewSize = 'deluxe'"
                                :class="quickViewSize === 'deluxe' ? 'border-emerald-800 bg-emerald-50 text-emerald-800 font-semibold' : 'border-neutral-200 text-neutral-600 hover:text-neutral-800'"
                                class="flex-1 min-w-[80px] px-3 py-2 border text-[11px] font-outfit uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center"
                                :disabled="quickViewProduct && quickViewProduct.stock_deluxe <= 0"
                            >
                                <span class="block">Deluxe</span>
                                <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(quickViewProduct.price_deluxe) + ' KSH' : ''"></span>
                                <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_deluxe <= 0">Out of Stock</span>
                            </button>
                            <!-- Grand -->
                            <button type="button" @click="quickViewSize = 'grand'"
                                :class="quickViewSize === 'grand' ? 'border-emerald-800 bg-emerald-50 text-emerald-800 font-semibold' : 'border-neutral-200 text-neutral-600 hover:text-neutral-800'"
                                class="flex-1 min-w-[80px] px-3 py-2 border text-[11px] font-outfit uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center"
                                :disabled="quickViewProduct && quickViewProduct.stock_grand <= 0"
                            >
                                <span class="block">Grand</span>
                                <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(quickViewProduct.price_grand) + ' KSH' : ''"></span>
                                <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_grand <= 0">Out of Stock</span>
                            </button>
                        </div>
                        
                        <!-- Reactive Stock Level Indicator in Quick View -->
                        <template x-if="quickViewProduct">
                            <div class="mt-2.5 text-left">
                                <span x-show="(quickViewSize === 'standard' && quickViewProduct.stock_standard <= 0) || (quickViewSize === 'deluxe' && quickViewProduct.stock_deluxe <= 0) || (quickViewSize === 'grand' && quickViewProduct.stock_grand <= 0)" 
                                      class="inline-block text-rose-600 font-semibold bg-rose-500/10 px-2.5 py-1 rounded uppercase tracking-wider text-[9px] font-outfit">
                                    Out of Stock
                                </span>
                                <span x-show="(quickViewSize === 'standard' && quickViewProduct.stock_standard > 0 && quickViewProduct.stock_standard <= 5) || (quickViewSize === 'deluxe' && quickViewProduct.stock_deluxe > 0 && quickViewProduct.stock_deluxe <= 5) || (quickViewSize === 'grand' && quickViewProduct.stock_grand > 0 && quickViewProduct.stock_grand <= 5)" 
                                      class="inline-block text-amber-600 font-semibold bg-amber-500/10 px-2.5 py-1 rounded uppercase tracking-wider text-[9px] font-outfit animate-pulse">
                                    Limited Items
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab/Section -->
            <div class="border-t border-neutral-100 pt-6 space-y-4">
                <h4 class="text-sm font-serif italic text-neutral-800">Reviews &amp; Client Feedback</h4>
                
                @if(session('success_review'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="p-2.5 bg-emerald-50 border border-dashed border-emerald-300 text-emerald-800 text-[11px] font-mono rounded-xl"
                    >
                        {{ session('success_review') }}
                    </div>
                @endif
                @if(session('error_review'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="p-2.5 bg-rose-50 border border-dashed border-rose-300 text-rose-800 text-[11px] font-mono rounded-xl"
                    >
                        {{ session('error_review') }}
                    </div>
                @endif

                <!-- Review list -->
                @php
                    $reviews = $quickViewProductId ? \App\Models\Review::where('product_id', $quickViewProductId)->with('user')->latest()->get() : collect();
                @endphp

                <div class="space-y-3 max-h-48 overflow-y-auto pr-1">
                    @forelse($reviews as $rev)
                        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-100 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-neutral-700">{{ $rev->user ? $rev->user->name : 'Anonymous Client' }}</span>
                                <div class="flex items-center space-x-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-[10px] {{ $i <= $rev->rating ? 'text-amber-500' : 'text-neutral-200' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-[11px] text-neutral-600 font-light leading-relaxed">{{ $rev->comment }}</p>
                            <span class="text-[8px] text-neutral-400 block font-mono">{{ $rev->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="text-[11px] text-neutral-400 italic">No reviews logged for this arrangement yet. Be the first to share your experience!</p>
                    @endforelse
                </div>

                <!-- Submit Review Form -->
                @auth
                    <form wire:submit.prevent="submitProductReview" class="p-3 border border-neutral-100 rounded-2xl bg-neutral-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-neutral-700 uppercase tracking-wider">Leave a Review</span>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('newReview.rating', {{ $i }})" class="text-xs focus:outline-none transition-transform hover:scale-125 {{ ($newReview['rating'] ?? 5) >= $i ? 'text-amber-500' : 'text-neutral-350' }}">★</button>
                                @endfor
                            </div>
                        </div>
                        <div class="space-y-1">
                            <textarea wire:model="newReview.comment" placeholder="Describe your experience with this arrangement..." class="w-full text-xs p-2 border border-neutral-200 rounded-xl bg-white focus:outline-none focus:border-neutral-400 font-light" rows="2" required></textarea>
                            @error('newReview.comment') <span class="text-rose-600 text-[9px] block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-emerald-800 hover:bg-emerald-950 text-white font-mono text-[9px] uppercase font-bold px-5 py-2.5 rounded-full cursor-pointer transition-colors">Submit Review</button>
                        </div>
                    </form>
                @else
                    <div class="p-3 bg-neutral-50 rounded-2xl text-center border border-neutral-100">
                        <p class="text-[10px] text-neutral-500 font-light">Please <a href="/login" class="text-emerald-800 font-bold hover:underline">sign in</a> to submit reviews.</p>
                    </div>
                @endauth
            </div>
        </div>

        <div class="p-5 border-t border-neutral-100 bg-neutral-50/80 backdrop-blur-md shrink-0 flex items-center justify-between">
            <div>
                <span class="text-[10px] text-neutral-500 uppercase tracking-wider block">Price</span>
                <span :class="theme === 'light' ? 'text-neutral-850' : 'text-[#C5A880]'" class="text-base font-bold font-mono">
                    <span x-text="quickViewProduct ? numberFormat(quickViewSize === 'standard' ? quickViewProduct.price_standard : (quickViewSize === 'deluxe' ? quickViewProduct.price_deluxe : quickViewProduct.price_grand)) : ''"></span> KSH
                </span>
            </div>
            
            <div class="flex items-center space-x-2">
                <!-- Wishlist Toggle inside Details Modal -->
                <button
                    type="button"
                    @click="$wire.toggleWishlist(quickViewProduct.id)"
                    :class="theme === 'light' ? 'border-neutral-300 text-neutral-600 hover:text-rose-500 bg-white' : 'border-neutral-800 text-[#C5A880]/70 hover:text-rose-500 bg-neutral-900/40'"
                    class="w-10 h-10 rounded-xl border flex items-center justify-center transition-all cursor-pointer shadow-sm shrink-0"
                    :title="wishlistIds.includes(quickViewProduct.id) ? 'Remove from Wishlist' : 'Add to Wishlist'"
                    x-show="quickViewProduct"
                >
                    <svg class="w-4.5 h-4.5 transition-transform duration-300 hover:scale-110" :class="wishlistIds.includes(quickViewProduct.id) ? 'fill-current text-rose-500' : 'fill-none stroke-current'" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>

                <button
                    type="button"
                    :disabled="(quickViewSize === 'standard' && quickViewProduct && quickViewProduct.stock_standard <= 0) || (quickViewSize === 'deluxe' && quickViewProduct && quickViewProduct.stock_deluxe <= 0) || (quickViewSize === 'grand' && quickViewProduct && quickViewProduct.stock_grand <= 0)"
                    @click="$wire.addToCuration(quickViewProduct.id, quickViewSize); quickViewOpen = false;"
                    :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-850' : 'bg-[#C5A880] text-black hover:bg-[#B59A7A]'" class="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer shadow-md"
                >
                    Add to Curation
                </button>
            </div>
        </div>
    </div>

                <!-- Backdrop for Profile Modal -->
    <div x-show="profileOpen" @click="profileOpen = false" class="fixed inset-0 z-45 bg-black/60 backdrop-blur-md" style="display: none;"></div>

    <!-- Profile Overlay Panel (Center Modal) -->
    <div 
        x-show="profileOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        :class="theme === 'light' ? 'bg-[#FAF7F0]/95 border-neutral-200 text-neutral-900 shadow-xl' : 'bg-[#121110]/95 border border-[#C5A880]/20 text-white shadow-2xl'"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[500px] max-h-[85vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl rounded-[32px] overflow-hidden transition-all duration-300"
        style="display: none;"
    >
        <div :class="theme === 'light' ? 'border-neutral-200 bg-neutral-100/30' : 'border-[#C5A880]/15 bg-neutral-950/20'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div>
                <h3 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Profile Portal</h3>
                <span class="text-[9px] text-neutral-500 font-light">Atelier Customer Account</span>
            </div>
            <button @click="profileOpen = false" :class="theme === 'light' ? 'text-neutral-400 hover:text-neutral-800' : 'text-neutral-500 hover:text-[#C5A880]'" class="cursor-pointer select-none transition-colors" title="Close Modal">
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
                             'border-[#B59A7A]/40': theme === 'light',
                         }">
                        <span class="text-base font-mono font-bold tracking-wider"
                              :class="{
                                  'text-[#C5A880]': theme === 'dark',
                                  'text-[#B59A7A]': theme === 'light',
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
                                      'border-[#B59A7A]/30 bg-[#B59A7A]/5 text-[#B59A7A]': theme === 'light',
                                  }">
                                {{ auth()->user()->loyalty_tier }}
                            </span>
                        </div>
                        <span class="text-[10px] text-neutral-500 block font-mono tracking-tight">{{ auth()->user()->email }}</span>
                    </div>
                </div>

                <!-- Account Stats Grid -->
                <div class="grid grid-cols-3 gap-2.5 text-center py-2">
                    <div :class="theme === 'light' ? 'bg-neutral-100/60 border-neutral-200 text-neutral-800' : 'bg-neutral-950/40 border-[#C5A880]/10 text-neutral-350'" class="p-3 border rounded-2xl">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-555 block">Client Since</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <div :class="theme === 'light' ? 'bg-neutral-100/60 border-neutral-200 text-neutral-800' : 'bg-neutral-950/40 border-[#C5A880]/10 text-neutral-350'" class="p-3 border rounded-2xl">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-555 block">Total Curations</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ $totalOrdersCount }}</span>
                    </div>
                    <div :class="theme === 'light' ? 'bg-neutral-100/60 border-neutral-200 text-neutral-800' : 'bg-neutral-950/40 border-[#C5A880]/10 text-neutral-350'" class="p-3 border rounded-2xl">
                        <span class="text-[8px] font-mono uppercase tracking-widest text-neutral-555 block">Active dispatches</span>
                        <span class="text-xs font-mono font-bold block mt-1" :class="theme === 'light' ? 'text-neutral-800' : 'text-white'">{{ $activeOrdersCount }}</span>
                    </div>
                </div>

                <!-- Info Segment -->
                <div class="space-y-3 font-sans py-1 text-xs">
                    <div class="flex items-center space-x-2">
                        <span :class="theme === 'light' ? 'text-[#B59A7A]' : 'text-[#C5A880]'" class="font-bold text-[9px] uppercase tracking-wider">Phone:</span>
                        <span class="font-mono" :class="theme === 'light' ? 'text-neutral-900' : 'text-white'">{{ auth()->user()->phone_number ?: 'Not Provided' }}</span>
                    </div>

                    <div class="leading-relaxed">
                        <span :class="theme === 'light' ? 'text-[#B59A7A]' : 'text-[#C5A880]'" class="font-bold text-[9px] uppercase tracking-wider block">Main Address</span>
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
                            <span :class="theme === 'light' ? 'text-neutral-850' : 'text-white'" class="font-bold">{{ number_format(auth()->user()->loyalty_points) }} PTS</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2.5 pt-4">
                    <a href="/profile-portal" 
                       :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-[#C5A880] text-black hover:bg-[#B59A7A]'"
                       class="w-full text-center font-mono font-bold uppercase tracking-wider py-3 rounded-full text-[10px] flex items-center justify-center space-x-2 transition-all transform hover:scale-[1.01] shadow-md cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 18c-2.204 0-4.224-.788-5.79-2.104m0 0L3.75 18M12 13.5a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
                        </svg>
                        <span>View Profile Dashboard</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                :class="theme === 'light' ? 'border-neutral-250 text-neutral-550 hover:text-rose-600 hover:border-rose-300' : 'border-neutral-850 text-neutral-400 hover:text-rose-450 hover:border-rose-900/50'"
                                class="w-full border font-mono font-bold uppercase tracking-wider py-2.5 rounded-full text-[9px] flex items-center justify-center space-x-2 transition-all cursor-pointer bg-transparent"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            <span>Sign Out of Atelier</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-2.5 space-y-4">
                    <span class="font-serif text-xl italic block" :class="theme === 'light' ? 'text-[#B59A7A]' : 'text-[#C5A880]'">Atelier Loyalty Circle</span>
                    <p class="text-neutral-500 font-light text-[11px] leading-relaxed">Sign in to track orders, manage billing profiles, and earn loyalty rewards.</p>
                    <div class="flex flex-col gap-2.5 pt-1">
                        <a href="/login" 
                           :class="theme === 'light' ? 'bg-[#B59A7A] text-white hover:bg-neutral-800' : 'bg-[#C5A880] text-black hover:bg-[#B59A7A]'"
                           class="font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] text-center block shadow-md"
                        >
                            Sign In
                        </a>
                        <a href="/register" 
                           :class="theme === 'light' ? 'border-neutral-250 text-neutral-600 hover:text-[#B59A7A]' : 'border-neutral-850 text-neutral-400 hover:text-[#C5A880]'"
                           class="border font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] text-center block"
                        >
                            Create Account
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>


        <!-- Backdrop for Notifications Modal -->
    <div x-show="notificationsOpen" @click="notificationsOpen = false" class="fixed inset-0 z-45 bg-black/60 backdrop-blur-md" style="display: none;"></div>

    <!-- Notifications Overlay Panel (Center Modal) -->
    <div 
        x-show="notificationsOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        :class="theme === 'light' ? 'bg-[#FAF7F0]/95 border-neutral-200 text-neutral-900 shadow-xl' : 'bg-[#121110]/95 border border-[#C5A880]/20 text-white shadow-2xl'"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[500px] max-h-[80vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl rounded-[32px] overflow-hidden transition-all duration-300"
        style="display: none;"
    >
        <div :class="theme === 'light' ? 'border-neutral-200 bg-neutral-100/30' : 'border-[#C5A880]/15 bg-neutral-950/20'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div>
                <h3 :class="theme === 'light' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Notification Log</h3>
                <span class="text-[9px] text-neutral-500 font-light">Inbox logs & system alerts</span>
            </div>
            <button @click="notificationsOpen = false" :class="theme === 'light' ? 'text-neutral-400 hover:text-neutral-800' : 'text-neutral-500 hover:text-[#C5A880]'" class="cursor-pointer select-none transition-colors" title="Close Modal">
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
                        <div 
                            x-data="{ expanded: false }" 
                            @mouseenter="if (!{{ $notif['is_read'] ? 'true' : 'false' }}) { $wire.markNotificationAsRead({{ $notif['id'] }}) }"
                            class="border p-4 rounded-2xl transition-all {{ $notifCls }} flex flex-col gap-2 relative group text-xs"
                        >
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
                                <h4 :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" class="font-serif italic text-xs tracking-wide font-semibold">{{ $notif['title'] }}</h4>
                                <p @click="expanded = !expanded" :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-400'" class="text-[11px] leading-relaxed font-light mt-1 cursor-pointer" :class="{ 'line-clamp-2': !expanded }">
                                    {{ $notif['message'] }}
                                </p>
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
                    <div :class="theme === 'light' ? 'border-neutral-200 bg-neutral-100/30' : 'border-[#C5A880]/15 bg-neutral-950/20'" class="p-4 flex items-center justify-between gap-4 shrink-0 border-t">
                        <button 
                            wire:click="markAllAsSeen" 
                            :class="theme === 'light' 
                                ? 'border-neutral-350 text-neutral-700 hover:bg-neutral-150 hover:text-black' 
                                : 'border-[#C5A880]/30 text-[#C5A880] hover:bg-[#C5A880]/10'" 
                            class="border font-mono font-bold uppercase tracking-wider py-2 px-4 rounded-full text-[9px] transition-all cursor-pointer bg-transparent"
                        >
                            Mark All as Read
                        </button>
                        <button 
                            @click="notificationsOpen = false" 
                            :class="theme === 'light' ? 'bg-black text-white hover:bg-neutral-850' : 'bg-[#C5A880] text-black hover:bg-[#B59A7A]'" 
                            class="font-mono font-bold uppercase tracking-wider px-5 py-2 rounded-full text-[9px] transition-all cursor-pointer shadow-md"
                        >
                            Dismiss
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    </div>


    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-md md:hidden" style="display: none;" x-transition></div>
    
    <!-- Floating Mobile Menu Panel (Left Drawer) -->
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        :class="theme === 'light' ? 'bg-[#FAF7F0] border-r border-neutral-200 text-neutral-900 shadow-2xl' : 'bg-[#0F0F12] border-r border-neutral-900 text-white shadow-2xl'"
        class="fixed top-0 bottom-0 left-0 w-72 z-[101] flex flex-col justify-between text-left backdrop-blur-xl transition-all duration-300 md:hidden"
        style="display: none;"
    >
        <div class="p-6 space-y-8 flex flex-col h-full justify-between">
            <div class="space-y-6">
                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <div class="flex flex-col text-left leading-none">
                        <span class="text-[9px] font-mono tracking-[0.3em] uppercase font-bold text-[#C5A880]">Atelier</span>
                        <span class="text-sm font-extrabold uppercase tracking-[0.15em] font-outfit mt-0.5">Noir & Bloom</span>
                    </div>
                    <button @click="mobileMenuOpen = false" :class="theme === 'light' ? 'text-neutral-400 hover:text-neutral-800' : 'text-neutral-500 hover:text-[#C5A880]'" class="cursor-pointer select-none transition-colors">
                        <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <div :class="theme === 'light' ? 'bg-neutral-200' : 'bg-neutral-850'" class="w-full h-px"></div>

                {{-- Mobile Search Input (Standard Redirect) --}}
                <form action="/" method="GET" class="relative w-full">
                    <input 
                        type="text" 
                        name="find"
                        placeholder="Search items..."
                        :class="theme === 'light' ? 'bg-neutral-100 border-neutral-300 text-neutral-900 placeholder-neutral-500 focus:border-emerald-600 focus:ring-emerald-600/10' : 'bg-neutral-900/40 border-neutral-800 text-white placeholder-neutral-500 focus:border-[#C5A880] focus:ring-[#C5A880]/10'"
                        class="w-full pl-9 pr-4 py-2 border rounded-full text-xs font-sans focus:outline-none focus:ring-2 transition-all duration-300"
                    >
                    <button type="submit" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                {{-- Navigation Links --}}
                <nav class="space-y-4 text-xs font-mono uppercase tracking-widest font-semibold">
                    <a href="/" class="block py-2 hover:text-[#C5A880] transition-colors">Home Showroom</a>
                    <a href="{{ route('services-gifts') }}" class="block py-2 hover:text-[#C5A880] transition-colors text-[#C5A880]">Services &amp; Gifts</a>
                    <a href="{{ route('curate') }}" class="block py-2 hover:text-[#C5A880] transition-colors">Curation Studio</a>
                    <button @click="mobileMenuOpen = false; notificationsOpen = true;" class="w-full text-left block py-2 hover:text-[#C5A880] transition-colors font-mono uppercase tracking-widest font-semibold cursor-pointer">
                        Notifications
                    </button>
                    <button @click="changeTheme(theme === 'light' ? 'dark' : 'light')" class="w-full text-left flex items-center justify-between py-2 hover:text-[#C5A880] transition-colors font-mono uppercase tracking-widest font-semibold cursor-pointer">
                        <span>Theme: <span x-text="theme"></span></span>
                        <span class="w-3 h-3 rounded-full" :class="theme === 'light' ? 'bg-emerald-600' : 'bg-[#C5A880]'"></span>
                    </button>
                </nav>
            </div>

            {{-- Bottom Profile section --}}
            <div class="space-y-4">
                <div :class="theme === 'light' ? 'bg-neutral-200' : 'bg-neutral-850'" class="w-full h-px"></div>
                
                @auth
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#C5A880]/15 flex items-center justify-center text-[#C5A880] font-bold font-mono">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <span class="block text-xs font-semibold truncate">{{ auth()->user()->name }}</span>
                            <span class="block text-[9px] text-[#C5A880] uppercase tracking-wider font-mono">{{ auth()->user()->account_tier }} member</span>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false; profileOpen = true;" class="w-full text-center py-2.5 bg-[#C5A880]/10 hover:bg-[#C5A880]/20 text-[#C5A880] text-[10px] font-mono uppercase tracking-widest rounded-xl transition-all cursor-pointer font-bold">
                        Member Portal
                    </button>
                @else
                    <div class="grid grid-cols-2 gap-3 w-full">
                        <a href="/login" class="bg-[#C5A880] text-black font-mono font-bold text-center uppercase tracking-wider py-2.5 rounded-xl text-[10px] hover:bg-[#B59A7A] transition-all cursor-pointer shadow-md block">
                            Sign In
                        </a>
                        <a href="/register" class="border border-neutral-800 text-neutral-400 text-center hover:text-white hover:border-neutral-600 font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] transition-all cursor-pointer block">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>