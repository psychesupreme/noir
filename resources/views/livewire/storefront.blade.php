@section('meta')
    <meta name="description" content="Explore Noir & Bloom's premium floral curations, Naivasha Rift Valley wholesale stems, and custom luxury gift hampers. Live eTIMS integration and instant M-Pesa checkout.">
    <meta name="keywords" content="premium bouquets, naivasha roses, flower shop Nairobi, luxury gift hampers Kenya, flower delivery Nairobi, eTIMS VAT invoices, safaricom mpesa checkout">
    <meta name="author" content="Noir & Bloom Atelier">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph (Facebook / Pinterest / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Noir &amp; Bloom | Premium Floral Curations &amp; Luxury Gifting Atelier">
    <meta property="og:description" content="Explore Noir & Bloom's premium floral curations, Naivasha volcanic roses, and custom luxury gift hampers. Sourcing fresh stems from Limuru and Naivasha with elite concierge delivery.">
    <meta property="og:image" content="{{ asset('media/og-storefront.jpg') }}">
    <meta property="og:site_name" content="Noir & Bloom">
    <meta property="og:locale" content="en_KE">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Noir &amp; Bloom | Premium Floral Curations &amp; Luxury Gifting Atelier">
    <meta name="twitter:description" content="Explore Noir & Bloom's premium floral curations, Naivasha volcanic roses, and custom luxury gift hampers. Sourcing fresh stems from Limuru and Naivasha with elite concierge delivery.">
    <meta name="twitter:image" content="{{ asset('media/og-storefront.jpg') }}">
    <meta name="twitter:site" content="@NoirAndBloom">

    <!-- Google Search Engine Structured Product Catalog Schema (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "ItemList",
      "name": "Noir & Bloom Showroom Catalog",
      "description": "Showcase of premium floral designs, export-quality volcanic stems, and custom luxury hampers.",
      "url": "{{ url()->current() }}",
      "numberOfItems": {{ count($products) }},
      "itemListElement": [
        @foreach($products as $index => $prod)
        {
          "@type": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "@type": "Product",
            "@id": "{{ url('/') }}/products/{{ $prod->id }}",
            "name": "{{ $prod->name }}",
            "image": "{{ $prod->backdrop_url ?? $prod->image_url }}",
            "description": "{{ Str::limit(strip_tags($prod->description), 150) }}",
            "sku": "{{ $prod->sku }}",
            "offers": {
              "@type": "Offer",
              "priceCurrency": "KES",
              "price": "{{ $prod->price }}",
              "itemCondition": "https://schema.org/NewCondition",
              "availability": "{{ $prod->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
              "url": "{{ url('/') }}/products/{{ $prod->id }}"
            }
          }
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
    </script>
@endsection

<div class="relative min-h-screen flex flex-col justify-between" 
    x-data="{ 
        drawerOpen: {{ $autoOpenDrawer ? 'true' : 'false' }}, 
        checkoutMode: {{ $autoOpenDrawer ? 'true' : 'false' }}, 
        theme: 'champagne', 
        accountPanelOpen: false, 
        chatOpen: false,
        quickViewOpen: false,
        quickViewProduct: null,
        quickViewSize: 'standard',
        numberFormat(val) { return new Intl.NumberFormat().format(val); },
        
        /* Preloader & transition states */
        loading: true,
        themeTransitioning: false,
        nextTheme: '',
        themeTransitionColor: '',
        
        /* Hover theme preview state */
        hoverTheme: null,

        changeTheme(targetTheme) {
            if (this.theme === targetTheme) return;
            this.theme = targetTheme;
        },

        /* Session timeout tracking */
        idleTimer: null,
        countdownTimer: null,
        isIdleWarning: false,
        timeLeft: 120 /* 2 minutes warning countdown */
    }" 
    x-init="
        /* Initialize preloader lift */
        setTimeout(() => { loading = false; }, 1600);

        /* Auto-open curation cart drawer if redirect query parameter is present */
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_cart') === 'true') {
            drawerOpen = true;
            checkoutMode = false;
        }


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

        /* Setup session timeout timer for authenticated users */
        @auth
            const resetIdleTimer = () => {
                isIdleWarning = false;
                timeLeft = 120;
                clearTimeout(idleTimer);
                clearInterval(countdownTimer);
                
                /* 28 minutes of inactivity -> trigger 2 minute countdown (28 * 60 = 1680 seconds) */
                idleTimer = setTimeout(() => {
                    isIdleWarning = true;
                    countdownTimer = setInterval(() => {
                        timeLeft--;
                        if (timeLeft <= 0) {
                            clearInterval(countdownTimer);
                            document.getElementById('logout-form-session').submit();
                        }
                    }, 1000);
                }, 1680000); 
            };

            ['mousemove', 'keypress', 'click', 'scroll'].forEach(evt => {
                window.addEventListener(evt, resetIdleTimer);
            });
            resetIdleTimer();
        @endauth
    "
    :class="{
        'bg-[#050507] text-[#F4F4F5]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne',
        'bg-[#15060A] text-[#FCE7EC]': theme === 'rose'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-x-clip"
>
    <!-- Luxury Preshader (Preloader Screen) -->
    <div x-show="loading"
         x-transition:leave="transition ease-in-out duration-700"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 pointer-events-none"
         class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-[#050507]"
    >
        <div class="absolute inset-0 bg-gradient-to-tr from-black via-[#050507] to-amber-950/10 pointer-events-none"></div>
        
        <!-- Subtle Linen Overlay inside preloader -->
        <div class="absolute inset-0 pointer-events-none fine-linen opacity-10" style="color: #C5A880"></div>

        <div class="relative flex flex-col items-center space-y-6 z-10">
            <!-- Breath-animated Monogram Outer Ring -->
            <div class="w-20 h-20 flex items-center justify-center border border-[#C5A880]/30 rounded-full bg-neutral-900/50 backdrop-blur-md shadow-[0_0_50px_rgba(197,168,128,0.15)] animate-pulse">
                <span class="text-xl font-mono tracking-[0.2em] text-[#C5A880] uppercase">NB</span>
            </div>
            
            <div class="text-center space-y-1.5">
                <span class="text-[10px] font-mono tracking-[0.4em] text-[#C5A880] uppercase block">Atelier</span>
                <span class="text-xs font-semibold text-white tracking-[0.3em] uppercase block font-outfit">NOIR & BLOOM</span>
            </div>

            <!-- Sleek gold progress line -->
            <div class="w-48 h-[1px] bg-neutral-955 rounded-full overflow-hidden relative">
                <div class="absolute left-0 top-0 h-full bg-[#C5A880] rounded-full animate-preloader-width" style="width: 0%;"></div>
            </div>
        </div>
    </div>



    <!-- Hidden logout form for inactivity auto-logout -->
    <form id="logout-form-session" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    <!-- Session Timeout Warning Banner Popup -->
    <div x-show="isIdleWarning" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4"
         style="display: none;"
    >
        <div class="bg-red-950/95 border border-red-900/60 text-red-200 px-5 py-4 rounded-2xl backdrop-blur-xl shadow-2xl flex flex-col gap-2">
            <div class="flex items-center space-x-3">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                <span class="text-[10px] font-mono uppercase tracking-wider font-bold">Atelier Security Timeout</span>
            </div>
            <p class="text-[12px] leading-relaxed font-light text-red-300">
                You have been inactive. For your security, you will be signed out in <span class="font-mono font-bold text-red-100" x-text="Math.floor(timeLeft / 60) + ':' + String(timeLeft % 60).padStart(2, '0')"></span>.
            </p>
            <button @click="isIdleWarning = false; resetIdleTimer();" class="mt-2 w-full bg-red-900 hover:bg-red-800 text-red-100 py-2 rounded-xl text-[10px] font-mono uppercase tracking-wider transition-all font-semibold cursor-pointer">
                Extend Active Session
            </button>
        </div>
    </div>

    <!-- Flash Messages (Stock limit errors, etc.) -->
    @if(session()->has('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-20 left-6 z-[100] max-w-sm"
        >
            <div class="bg-red-900/90 border border-red-800 text-red-100 px-4 py-3 rounded-xl backdrop-blur-md shadow-2xl flex items-center justify-between gap-4">
                <span class="text-xs font-light">{{ session('error') }}</span>
                <button @click="show = false" class="text-red-300 hover:text-white cursor-pointer select-none">
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Stylesheet for background animations -->
    <style>
        /* Preloader filling bar */
        @keyframes preloader-width {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .animate-preloader-width {
            animation: preloader-width 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        /* Fine Linen Organic Textile Texture */
        .fine-linen {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            opacity: 0.035; /* Whisper-quiet luxury subtlety */
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='currentColor' stroke-width='0.5' stroke-opacity='0.15'%3E%3Cpath d='M0 10h80M0 20h80M0 30h80M0 40h80M0 50h80M0 60h80M0 70h80M10 0v80M20 0v80M30 0v80M40 0v80M50 0v80M60 0v80M70 0v80'/%3E%3Cpath d='M0 5h80M0 15h80M0 25h80M0 35h80M0 45h80M0 55h80M0 65h80M0 75h80M5 0v80M15 0v80M25 0v80M35 0v80M45 0v80M55 0v80M65 0v80M75 0v80' stroke-width='0.25' stroke-opacity='0.08'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* Loyalty Points VIP Pulsating Glow & Text Shimmer */
        @keyframes loyalty-vip-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0px rgba(197, 168, 128, 0.5), 0 0 8px rgba(197, 168, 128, 0.2);
            }
            50% {
                box-shadow: 0 0 0 4px rgba(197, 168, 128, 0), 0 0 16px rgba(197, 168, 128, 0.5);
            }
        }
        @keyframes text-shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .loyalty-vip-glow {
            animation: loyalty-vip-pulse 2s infinite ease-in-out;
        }
        .loyalty-shimmer-text {
            background: linear-gradient(90deg, #F59E0B, #FDE68A, #F59E0B);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-shimmer 3s linear infinite;
        }

        @keyframes float-blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -60px) scale(1.1); }
            66% { transform: translate(-30px, 30px) scale(0.95); }
        }
        .animate-blob {
            animation: float-blob 22s infinite ease-in-out;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes storefront-grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-1%, -1%); }
            20% { transform: translate(1%, 1%); }
            30% { transform: translate(-0.5%, 1.5%); }
            40% { transform: translate(1.5%, -0.5%); }
            50% { transform: translate(-1.5%, 0.5%); }
            60% { transform: translate(0.5%, -1.5%); }
            70% { transform: translate(-1%, 1%); }
            80% { transform: translate(1%, -1%); }
            90% { transform: translate(-0.5%, 0.5%); }
        }
        .storefront-grain::after {
            content: '';
            position: absolute;
            inset: -50%;
            width: 200%;
            height: 200%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            animation: storefront-grain 8s steps(10) infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes card-fade-in {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-card-fade-in {
            animation: card-fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>


    <!-- Interactive SVG ambient floral background overlay -->

    <svg id="flower-ambient-svg" wire:ignore x-data="storefrontAmbient" class="fixed inset-0 w-full h-full pointer-events-none z-0 overflow-hidden" style="perspective: 800px; transform-style: preserve-3d;"></svg>

    <!-- Fine Linen Organic Grid Overlay -->
    <div class="absolute inset-0 pointer-events-none fine-linen z-0 opacity-80"></div>
    <header 
        class="fixed top-4 inset-x-4 h-16 bg-white/90 border border-neutral-200/80 rounded-full z-50 transition-all duration-500 flex items-center shadow-md hover:shadow-lg group"
    >
        {{-- FNP Green Bottom Glow Line --}}
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-emerald-600/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-6">
            <a href="/" class="shrink-0 flex items-center space-x-2 select-none cursor-pointer group-hover:scale-102 transition-transform duration-300">
                <div class="w-8.5 h-8.5 rounded-full bg-emerald-800 flex items-center justify-center text-white font-serif italic text-sm font-semibold shadow-md shrink-0">N</div>
                <div class="flex flex-col text-left leading-none">
                    <span class="text-[9px] font-mono tracking-[0.3em] text-emerald-700 uppercase font-bold">Atelier</span>
                    <span class="text-xs sm:text-sm md:text-base font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-outfit mt-0.5">Noir & Bloom</span>
                </div>
            </a>
            
            {{-- Prominent Header Search Bar (FNP-style) --}}
            <div class="flex-1 max-w-lg mx-auto hidden md:block">
                <div class="relative group" x-data="{ focused: false }">
                    <input 
                        type="text" 
                        wire:model.live.debounce.200ms="search"
                        @focus="focused = true"
                        @blur="focused = false"
                        placeholder="Search fresh flowers, luxury hampers, cakes, combinations..."
                        class="w-full bg-neutral-50 border border-neutral-200 text-neutral-800 placeholder-neutral-400 hover:border-emerald-600 focus:border-emerald-600 focus:bg-white focus:ring-2 focus:ring-emerald-600/10 rounded-full pl-10 pr-4 py-2 text-xs font-light font-sans focus:outline-none transition-all duration-300 shadow-sm"
                    >
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none text-neutral-400 group-focus-within:text-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4 text-[12px] font-mono uppercase tracking-widest text-neutral-600">
                <a href="{{ route('services-gifts') }}" class="hidden lg:inline-block hover:text-emerald-800 transition-colors duration-300 select-none cursor-pointer">Services</a>
                <a href="{{ route('curate') }}" 
                   class="hidden md:inline-block px-4 py-1.5 rounded-full border border-emerald-600/30 hover:border-emerald-600 hover:bg-emerald-600/5 text-emerald-800 font-medium transition-all duration-300 select-none cursor-pointer">
                   Curation Studio
                </a>

                {{-- Shopping Bag button --}}
                <button @click="drawerOpen = true; checkoutMode = false;" 
                        class="hover:text-emerald-700 transition-colors cursor-pointer select-none relative w-9 h-9 flex items-center justify-center border border-neutral-200 rounded-full bg-neutral-50 shadow-sm" 
                        title="View Curation Drawer"
                >
                    <svg class="w-4 h-4 stroke-current fill-none text-neutral-700" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-emerald-600 text-white text-[9px] font-bold font-sans shadow-md">
                            {{ $cartCount }}
                        </span>
                    @endif
                </button>

                <!-- Profile Portal Dropdown Card Popover -->
                <div x-data="{ profileMenuOpen: false }" class="relative inline-block text-left animate-nav-item" style="animation-delay: 500ms;">
                    @auth
                        <!-- Initials-based Monogram Avatar Button (Shown when logged in) -->
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                class="hover:border-emerald-600 border border-neutral-200 transition-all cursor-pointer select-none w-8 h-8 flex items-center justify-center rounded-full bg-neutral-100 shadow-sm"
                                title="Profile Portal Options"
                        >
                            <span class="text-[10px] font-mono font-bold tracking-wider text-emerald-800 uppercase">
                                {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                            </span>
                        </button>
                    @else
                        <!-- Log In / Sign In Button for Guests -->
                        <button @click="profileMenuOpen = !profileMenuOpen" 
                                class="transition-all duration-300 hover:scale-[1.03] cursor-pointer select-none px-4 h-8 flex items-center justify-center space-x-1.5 border border-neutral-200 rounded-full bg-neutral-50 text-[11px] font-sans font-light tracking-widest uppercase text-neutral-700 hover:text-emerald-800 hover:border-emerald-600"
                                title="Log In or Sign In"
                        >
                            <svg class="w-3.5 h-3.5 stroke-current fill-none transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3M15 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="hidden sm:inline">Sign In</span>
                        </button>
                    @endauth
 
                    <!-- Popover Dropdown Card with enhanced smooth luxury transition -->
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
                            <!-- Profile Header Segment -->
                            <div class="flex items-center space-x-3 pb-3 border-b border-neutral-500/10">
                                <!-- Large Initials Monogram static avatar -->
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

                            <!-- Mobile Phone & Address Info Segment -->
                            <div class="space-y-2.5 font-sans py-1">
                                <!-- Phone -->
                                <div class="flex items-center space-x-2 text-[11px] text-neutral-450">
                                    <svg class="w-3.5 h-3.5 text-[#C5A880]/80 stroke-current fill-none shrink-0" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 7.92z" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="font-mono">{{ auth()->user()->phone_number ?: 'Not Provided' }}</span>
                                </div>

                                <!-- Main Address -->
                                <div class="flex items-start space-x-2 text-[11px] text-neutral-450">
                                    <svg class="w-3.5 h-3.5 text-[#C5A880]/80 stroke-current fill-none shrink-0 mt-0.5" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="10" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="leading-relaxed truncate">
                                        <span class="font-bold text-[9px] uppercase tracking-wider text-[#C5A880]/80 block">Main Address</span>
                                        <span class="block truncate" title="{{ auth()->user()->default_delivery_address }}">{{ auth()->user()->default_delivery_address ?: 'No Address Set' }}</span>
                                        @if(auth()->user()->default_region)
                                            <span class="text-[9px] font-mono text-neutral-500 uppercase tracking-wider block mt-0.5">{{ auth()->user()->default_region }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Loyalty Points Segment -->
                            <div class="border-t border-neutral-500/10 pt-3 space-y-2 font-mono text-[11px]">
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-500">Tier:</span>
                                    <span class="text-[#C5A880] font-bold text-[10px] tracking-widest uppercase" x-text="'{{ auth()->user()->loyalty_tier }}'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-neutral-500">Loyalty Points:</span>
                                    
                                    @if(auth()->user()->loyalty_points > 1)
                                        <!-- Glowing Pulsing Badge for VIP loyalty count > 1 -->
                                        <span class="loyalty-vip-glow bg-amber-500/10 border border-amber-500/30 text-amber-500 font-bold px-2.5 py-1 rounded-full text-[10px] flex items-center space-x-1 select-none">
                                            <span class="relative flex h-1.5 w-1.5 shrink-0">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                            </span>
                                            <span class="loyalty-shimmer-text tracking-wide font-black">{{ number_format(auth()->user()->loyalty_points) }} PTS</span>
                                        </span>
                                    @else
                                        <span class="text-neutral-400 font-bold" x-text="'{{ number_format(auth()->user()->loyalty_points) }} PTS'"></span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
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
                                <p class="text-neutral-400 font-light text-[11px] leading-relaxed">Sign in to track orders, manage eTIMS profiles, and earn loyalty rewards.</p>
                                <div class="flex flex-col gap-2.5 pt-1">
                                    <!-- Sign In Button with theme-aware hover glow and scale -->
                                    <a href="/login" 
                                       :class="{
                                           'bg-[#C5A880] text-black shadow-[0_0_15px_rgba(197,168,128,0.2)] hover:bg-[#B59A7A] hover:shadow-[0_0_25px_rgba(197,168,128,0.4)]': theme === 'onyx',
                                           'bg-[#B59A7A] text-white shadow-[0_0_15px_rgba(181,154,122,0.2)] hover:bg-[#FAF7F0] hover:text-black hover:shadow-[0_0_25px_rgba(181,154,122,0.4)] border border-[#B59A7A]': theme === 'champagne',
                                           'bg-[#B76E79] text-white shadow-[0_0_15px_rgba(183,110,121,0.2)] hover:bg-[#15060A] hover:shadow-[0_0_25px_rgba(183,110,121,0.4)] border border-[#B76E79]': theme === 'rose'
                                       }"
                                       class="font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] transition-all duration-300 hover:scale-[1.02] text-center block shadow-md"
                                    >
                                        Sign In
                                    </a>
                                    <!-- Create Account Button with theme-aware border glow and scale -->
                                    <a href="/register" 
                                       :class="{
                                           'border-neutral-800 text-neutral-400 hover:text-[#C5A880] hover:border-[#C5A880] hover:shadow-[0_0_15px_rgba(197,168,128,0.2)]': theme === 'onyx',
                                           'border-neutral-200 text-neutral-600 hover:text-[#B59A7A] hover:border-[#B59A7A] hover:shadow-[0_0_15px_rgba(181,154,122,0.1)]': theme === 'champagne',
                                           'border-[#2D121F] text-pink-300/60 hover:text-[#B76E79] hover:border-[#B76E79] hover:shadow-[0_0_15px_rgba(183,110,121,0.2)]': theme === 'rose'
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
    <!-- Interactive Advertisements Carousel (5 Luxury Slides with auto-grading overlays, hover indicators, and Roman numerals) -->
    <div class="w-full px-0 pt-28 pb-4 shrink-0">
        <section x-data="{ 
                      activeSlide: 0, 
                      slidesCount: {{ count($slides) }}, 
                      timer: null,
                      init() {
                          this.startTimer();
                      },
                      startTimer() {
                          this.timer = setInterval(() => {
                              this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                          }, 5000); // Accelerated transition timing
                      },
                      resetTimer() {
                          clearInterval(this.timer);
                          this.startTimer();
                      }
                  }" 
                 class="w-full relative overflow-hidden rounded-none border-y border-neutral-500/10 h-[calc(100vh-240px)] min-h-[480px] flex items-center shadow-2xl group theme-section"
        >
            @foreach ($slides as $index => $slide)
            <!-- Slide {{ $index + 1 }}: {{ $slide['title'] }} -->
            <div x-show="activeSlide === {{ $index }}" 
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition duration-500 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 flex flex-col justify-center px-8 md:px-16 py-12 text-left"
                 @if($index !== 0) style="display: none;" @endif
            >
                <!-- Background Image with dynamic filtering -->
                <div class="absolute inset-0 bg-cover bg-center select-none"
                     :class="{
                          'filter brightness-75 contrast-125 saturate-[0.8]': theme === 'onyx',
                          'filter brightness-90 sepia-[0.15] saturate-[0.95]': theme === 'champagne',
                          'filter brightness-75 contrast-110 saturate-[0.85]': theme === 'rose'
                     }"
                     style="background-image: url('{{ $slide['bg_image'] }}');">
                </div>
                <!-- Linear Theme Blending Overlay -->
                <div class="absolute inset-0 pointer-events-none mix-blend-multiply"
                     :class="{
                          'bg-gradient-to-r from-[#050507]/95 via-[#050507]/50 to-transparent': theme === 'onyx',
                          'bg-gradient-to-r from-[#FAF7F0]/95 via-[#FAF7F0]/60 to-transparent': theme === 'champagne',
                          'bg-gradient-to-r from-[#15060A]/95 via-[#15060A]/60 to-transparent': theme === 'rose'
                     }">
                </div>
                <!-- Secondary Color Burn Blending -->
                <div class="absolute inset-0 pointer-events-none mix-blend-color-burn opacity-60"
                     :class="{
                          'bg-[#0C1E1A]/10': theme === 'onyx',
                          'bg-[#C5A880]/15': theme === 'champagne',
                          'bg-[#B76E79]/20': theme === 'rose'
                     }">
                </div>

                <div class="max-w-xl space-y-4 z-10 animate-hero-rise">
                    <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-[#C5A880] block">{{ $slide['badge'] }}</span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-outfit font-semibold uppercase tracking-wider leading-none"
                        :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'">
                        {{ $slide['title'] }}
                    </h2>
                    <div class="h-[1px] w-16 bg-[#C5A880]/40"></div>
                    <p class="text-sm sm:text-base font-light leading-relaxed"
                       :class="theme === 'champagne' ? 'text-neutral-700' : 'text-neutral-300'">
                        {{ $slide['description'] }}
                    </p>
                    <div class="pt-4">
                        <button @click="if ('{{ $slide['cta_link'] }}'.startsWith('/') || '{{ $slide['cta_link'] }}'.startsWith('http')) { window.location.href = '{{ $slide['cta_link'] }}'; } else { $wire.selectCategory('{{ $slide['cta_link'] }}'); document.getElementById('product-showroom').scrollIntoView({behavior: 'smooth'}); }"
                                class="bg-[#C5A880] text-black px-6 py-3 rounded-full text-[11px] font-mono uppercase tracking-[0.2em] font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#B59A7A] cursor-pointer">
                            {{ $slide['cta_text'] }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Edge Navigation Chevrons (Hover activated) -->
            <button 
                @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount; resetTimer();"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full flex items-center justify-center border transition-all duration-300 cursor-pointer shadow-lg hover:scale-105 backdrop-blur-md opacity-0 group-hover:opacity-100 group-hover:translate-x-0 -translate-x-4"
                :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 text-black hover:bg-neutral-100' : 'border-neutral-800/40 bg-black/60 text-white hover:bg-neutral-900'"
                title="Previous Slide"
            >
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <button 
                @click="activeSlide = (activeSlide + 1) % slidesCount; resetTimer();"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full flex items-center justify-center border transition-all duration-300 cursor-pointer shadow-lg hover:scale-105 backdrop-blur-md opacity-0 group-hover:opacity-100 group-hover:translate-x-0 translate-x-4"
                :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 text-black hover:bg-neutral-100' : 'border-neutral-800/40 bg-black/60 text-white hover:bg-neutral-900'"
                title="Next Slide"
            >
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- Roman Numeral slide indicators with moving progress line bar -->
            <div class="absolute bottom-8 right-8 md:right-16 flex flex-col items-end space-y-2.5 z-20 font-mono select-none">
                <div class="flex items-center space-x-6 text-sm tracking-widest">
                    <template x-for="(numeral, index) in ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'].slice(0, slidesCount)" :key="index">
                        <button @click="activeSlide = index; resetTimer();"
                                :class="activeSlide === index ? (theme === 'champagne' ? 'text-black font-bold' : 'text-white font-bold') : 'text-neutral-500 hover:text-neutral-300'"
                                class="transition-all duration-300 cursor-pointer text-xs font-semibold px-1 focus:outline-none"
                                x-text="numeral"
                        ></button>
                    </template>
                </div>
                <!-- Elegant slide progress line bar container -->
                <div class="w-40 h-[2px] rounded-full relative overflow-hidden bg-neutral-500/10">
                    <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500 ease-out bg-[#C5A880]"
                         :style="{ width: (100 / slidesCount) + '%', left: (activeSlide * (100 / slidesCount)) + '%' }"></div>
                </div>
            </div>
        </section>
    </div>

    @php
        $offerProducts = \App\Models\Product::where('category', '!=', 'specializtion')
            ->where('category', '!=', 'specialization')
            ->where('category', '!=', 'specializations')
            ->where('stock', '>', 0)
            ->limit(5)
            ->get()
            ->map(function($product) {
                $product->backdrop_url = $product->image_url ?: match($product->category) {
                    'stems'   => 'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&q=80&w=600',
                    'giftings' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=600',
                    default   => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600',
                };
                $product->stock_standard = $product->stock;
                $product->stock_deluxe = (int) floor($product->stock * 0.7);
                $product->stock_grand = (int) floor($product->stock * 0.4);
                return $product;
            });
    @endphp

    @if($offerProducts->count() > 0)
        <div class="w-full pb-2 pt-0 shrink-0 mt-0 z-10 relative">
            <div class="relative w-full overflow-hidden py-2 border-y backdrop-blur-sm transition-colors duration-500"
                 :class="theme === 'champagne' ? 'bg-white/30 border-neutral-200/50' : 'bg-neutral-950/20 border-neutral-900/60'">
                <div class="flex whitespace-nowrap space-x-4 w-max animate-marquee">
                    @for($i = 0; $i < 4; $i++)
                        @foreach($offerProducts as $product)
                            @php
                                $originalPrice = (int) round($product->price * 1.18);
                                $discountPercent = 15;
                            @endphp
                            <div :class="theme === 'champagne' ? 'bg-white/80 border-neutral-200/50 text-neutral-900' : 'bg-[#0F0F12]/80 border-neutral-900/60 text-white'"
                                 class="inline-flex items-center space-x-3 p-2 rounded-xl border w-[250px] transition-all duration-300 hover:border-[#C5A880] cursor-pointer theme-section"
                                 @click="quickViewProduct = { id: {{ $product->id }}, name: {{ \Illuminate\Support\Js::from($product->name) }}, price: {{ $product->price }}, description: {{ \Illuminate\Support\Js::from($product->description) }}, image: {{ \Illuminate\Support\Js::from($product->backdrop_url) }}, category: {{ \Illuminate\Support\Js::from($product->category) }}, stock_standard: {{ $product->stock_standard }}, stock_deluxe: {{ $product->stock_deluxe }}, stock_grand: {{ $product->stock_grand }} }; quickViewSize = 'standard'; quickViewOpen = true;"
                            >
                                <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-neutral-950/5 relative">
                                    <img src="{{ $product->backdrop_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    <div class="absolute top-0.5 left-0.5 bg-red-500 text-white text-[6px] font-bold px-1 py-0.5 rounded-sm">
                                        -{{ $discountPercent }}%
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 text-left">
                                    <span class="text-[8px] uppercase tracking-widest text-[#C5A880] font-outfit block font-bold">{{ str_replace('_', ' ', $product->category) }}</span>
                                    <h4 class="font-serif italic text-[11px] tracking-wide truncate mt-0.5">{{ $product->name }}</h4>
                                    <div class="flex items-center space-x-2 mt-0.5 font-mono text-[8px]">
                                        <span class="text-neutral-500 line-through">{{ number_format($originalPrice) }} KSH</span>
                                        <span class="text-amber-500 font-bold">{{ number_format($product->price) }} KSH</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
    @endif

        <h1 class="sr-only">Noir &amp; Bloom | Premium Floral Curations &amp; Luxury Gifting Atelier</h1>

        <!-- FNP-Style Circular Category Navigation Menu -->
        <div class="w-full py-8">
            <div class="flex items-center justify-center gap-6 md:gap-12 overflow-x-auto scrollbar-none py-2 px-4">
                <!-- Category: Flowers -->
                <button wire:click="selectCategory('bouquet')" class="flex flex-col items-center gap-2 group shrink-0 focus:outline-none cursor-pointer">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-neutral-250 group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-sm relative">
                        <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=200" alt="Flowers" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs md:text-sm font-medium text-neutral-700 group-hover:text-emerald-800 transition-colors">Flowers</span>
                </button>
                
                <!-- Category: Cakes & Sweets -->
                <button wire:click="selectCategory('giftings')" class="flex flex-col items-center gap-2 group shrink-0 focus:outline-none cursor-pointer">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-neutral-250 group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-sm relative">
                        <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=200" alt="Cakes & Sweets" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs md:text-sm font-medium text-neutral-700 group-hover:text-emerald-800 transition-colors">Cakes & Sweets</span>
                </button>
                
                <!-- Category: Plants & Vases -->
                <button wire:click="selectCategory('bundle')" class="flex flex-col items-center gap-2 group shrink-0 focus:outline-none cursor-pointer">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-neutral-250 group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-sm relative">
                        <img src="https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&q=80&w=200" alt="Plants & Vases" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs md:text-sm font-medium text-neutral-700 group-hover:text-emerald-800 transition-colors">Plants & Vases</span>
                </button>
                
                <!-- Category: Combos -->
                <button wire:click="selectCategory('giftings')" class="flex flex-col items-center gap-2 group shrink-0 focus:outline-none cursor-pointer">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-neutral-250 group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-sm relative">
                        <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=200" alt="Combos" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs md:text-sm font-medium text-neutral-700 group-hover:text-emerald-800 transition-colors">Combos</span>
                </button>
                
                <!-- Category: Hampers -->
                <button wire:click="selectCategory('giftings')" class="flex flex-col items-center gap-2 group shrink-0 focus:outline-none cursor-pointer">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-neutral-250 group-hover:border-emerald-600 group-hover:scale-105 transition-all duration-300 shadow-sm relative">
                        <img src="https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=200" alt="Hampers" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xs md:text-sm font-medium text-neutral-700 group-hover:text-emerald-800 transition-colors">Hampers</span>
                </button>
            </div>
        </div>

        <!-- FNP-Style Global Location & Date Checker Bar -->
        <div class="w-full bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 md:p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm text-left">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-800 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-neutral-800">Select Delivery Details</h3>
                    <p class="text-xs text-neutral-500 font-light">Confirm destination city and date to view available collections and slots.</p>
                </div>
            </div>
            
            <div class="w-full md:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Delivery City -->
                <div class="flex-1 min-w-[160px]">
                    <select wire:model.live="deliveryCity" class="w-full bg-white border border-neutral-200 rounded-lg px-3 py-2 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600/20 font-sans cursor-pointer">
                        <option value="">-- Select City --</option>
                        <option value="Nairobi">Nairobi</option>
                        <option value="Kiambu">Kiambu</option>
                        <option value="Mombasa">Mombasa</option>
                        <option value="Kisumu">Kisumu</option>
                        <option value="Nakuru">Nakuru</option>
                    </select>
                </div>
                
                <!-- Delivery Date -->
                <div class="flex-1 min-w-[160px]">
                    <input type="date" wire:model.live="deliveryDate" min="{{ date('Y-m-d') }}" class="w-full bg-white border border-neutral-200 rounded-lg px-3 py-2 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600/20 font-sans">
                </div>
                
                <!-- Delivery Slot -->
                <div class="flex-1 min-w-[160px]">
                    <select wire:model.live="deliverySlot" class="w-full bg-white border border-neutral-200 rounded-lg px-3 py-2 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600/20 font-sans cursor-pointer">
                        <option value="standard">Standard (Free)</option>
                        <option value="midnight">Midnight (1,500 KSH)</option>
                    </select>
                </div>
            </div>
            
            <!-- Delivery Status Message -->
            <div class="shrink-0 text-xs">
                @if($deliveryDetailsValid)
                    <span class="text-emerald-700 font-medium flex items-center gap-1.5 bg-emerald-100/50 px-3 py-1.5 rounded-lg border border-emerald-200">
                        ✓ Delivering to <span class="font-bold">{{ $deliveryCity }}</span> on <span class="font-bold">{{ \Carbon\Carbon::parse($deliveryDate)->format('d M Y') }}</span> ({{ ucfirst($deliverySlot) }})
                    </span>
                @else
                    <span class="text-amber-700 font-medium flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200 animate-pulse">
                        ⚠ Enter details to check availability
                    </span>
                @endif
            </div>
        </div>


        <!-- Double Column Layout: Left Sticky Sidebar, Right Catalog showroom -->
        <div id="product-showroom" class="flex flex-col lg:flex-row gap-8 w-full items-start">
            
            <!-- Sticky Sidebar Navigator (Desktop only) - Ultra Compact to fit without scrolling -->
            <aside :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="hidden lg:block w-60 shrink-0 sticky top-24 p-4.5 border rounded-[28px] backdrop-blur-md space-y-4 text-left transition-all duration-500 shadow-sm z-10 max-h-[calc(100vh-110px)] overflow-y-auto scrollbar-none theme-section">
                <!-- Sidebar Title -->
                <div class="border-b border-neutral-500/10 pb-2">
                    <h4 :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="font-serif text-base tracking-wider font-semibold">Curation Desk</h4>
                </div>

                <!-- Product Directory Tree sidebar navigator -->
                <div class="space-y-3.5 text-left" x-data="{ active: @entangle('selectedCategory') }">
                    <span class="text-[10px] font-sans uppercase tracking-[0.18em] block mb-1 transition-colors duration-500"
                          :class="{
                              'text-[#C5A880]': theme === 'onyx',
                              'text-[#B59A7A]': theme === 'champagne',
                              'text-[#B76E79]': theme === 'rose'
                          }">Catalog Directory</span>
                    
                    <!-- Department: Floral Collections -->
                    <div class="space-y-0.5">
                        <span class="text-[9px] font-serif text-neutral-500 uppercase tracking-widest block font-bold px-1.5 mb-1 italic">✦ Floral Catalog</span>
                        <button @click="active = 'all'; $wire.selectCategory('all')" 
                                :class="{
                                    'text-[#C5A880] bg-[#C5A880]/5 border-l-2 border-[#C5A880] font-medium': active === 'all' && theme === 'onyx',
                                    'text-[#B59A7A] bg-[#B59A7A]/5 border-l-2 border-[#B59A7A] font-medium': active === 'all' && theme === 'champagne',
                                    'text-[#B76E79] bg-[#B76E79]/5 border-l-2 border-[#B76E79] font-medium': active === 'all' && theme === 'rose',
                                    'text-neutral-450 hover:text-current hover:bg-neutral-500/5 hover:border-l-2 hover:border-neutral-500/20 border-l-2 border-transparent': active !== 'all'
                                }"
                                class="w-full flex items-center space-x-2 px-2 py-1 rounded-r-md text-[10px] uppercase tracking-[0.12em] transition-all cursor-pointer text-left">
                            <span>All Showroom</span>
                        </button>
                        <button @click="active = 'stems'; $wire.selectCategory('stems')" 
                                :class="{
                                    'text-[#C5A880] bg-[#C5A880]/5 border-l-2 border-[#C5A880] font-medium': active === 'stems' && theme === 'onyx',
                                    'text-[#B59A7A] bg-[#B59A7A]/5 border-l-2 border-[#B59A7A] font-medium': active === 'stems' && theme === 'champagne',
                                    'text-[#B76E79] bg-[#B76E79]/5 border-l-2 border-[#B76E79] font-medium': active === 'stems' && theme === 'rose',
                                    'text-neutral-450 hover:text-current hover:bg-neutral-500/5 hover:border-l-2 hover:border-neutral-500/20 border-l-2 border-transparent': active !== 'stems'
                                }"
                                class="w-full flex items-center space-x-2 px-2 py-1 rounded-r-md text-[10px] uppercase tracking-[0.12em] transition-all cursor-pointer text-left">
                            <span>Fresh Stems</span>
                        </button>
                        <button @click="active = 'bouquet'; $wire.selectCategory('bouquet')" 
                                :class="{
                                    'text-[#C5A880] bg-[#C5A880]/5 border-l-2 border-[#C5A880] font-medium': active === 'bouquet' && theme === 'onyx',
                                    'text-[#B59A7A] bg-[#B59A7A]/5 border-l-2 border-[#B59A7A] font-medium': active === 'bouquet' && theme === 'champagne',
                                    'text-[#B76E79] bg-[#B76E79]/5 border-l-2 border-[#B76E79] font-medium': active === 'bouquet' && theme === 'rose',
                                    'text-neutral-450 hover:text-current hover:bg-neutral-500/5 hover:border-l-2 hover:border-neutral-500/20 border-l-2 border-transparent': active !== 'bouquet'
                                }"
                                class="w-full flex items-center space-x-2 px-2 py-1 rounded-r-md text-[10px] uppercase tracking-[0.12em] transition-all cursor-pointer text-left">
                            <span>Bespoke Bouquets</span>
                        </button>
                    </div>

                    <!-- Department: Luxury Gifting -->
                    <div class="space-y-0.5 pt-1.5 border-t border-neutral-500/10">
                        <span class="text-[9px] font-serif text-neutral-500 uppercase tracking-widest block font-bold px-1.5 mb-1 italic">✦ Luxury Gifting</span>
                        <button @click="active = 'giftings'; $wire.selectCategory('giftings')" 
                                :class="{
                                    'text-[#C5A880] bg-[#C5A880]/5 border-l-2 border-[#C5A880] font-medium': active === 'giftings' && theme === 'onyx',
                                    'text-[#B59A7A] bg-[#B59A7A]/5 border-l-2 border-[#B59A7A] font-medium': active === 'giftings' && theme === 'champagne',
                                    'text-[#B76E79] bg-[#B76E79]/5 border-l-2 border-[#B76E79] font-medium': active === 'giftings' && theme === 'rose',
                                    'text-neutral-450 hover:text-current hover:bg-neutral-500/5 hover:border-l-2 hover:border-neutral-500/20 border-l-2 border-transparent': active !== 'giftings'
                                }"
                                class="w-full flex items-center space-x-2 px-2 py-1 rounded-r-md text-[10px] uppercase tracking-[0.12em] transition-all cursor-pointer text-left">
                            <span>Gifting Hampers</span>
                        </button>
                        <button @click="active = 'bundle'; $wire.selectCategory('bundle')" 
                                :class="{
                                    'text-[#C5A880] bg-[#C5A880]/5 border-l-2 border-[#C5A880] font-medium': active === 'bundle' && theme === 'onyx',
                                    'text-[#B59A7A] bg-[#B59A7A]/5 border-l-2 border-[#B59A7A] font-medium': active === 'bundle' && theme === 'champagne',
                                    'text-[#B76E79] bg-[#B76E79]/5 border-l-2 border-[#B76E79] font-medium': active === 'bundle' && theme === 'rose',
                                    'text-neutral-450 hover:text-current hover:bg-neutral-500/5 hover:border-l-2 hover:border-neutral-500/20 border-l-2 border-transparent': active !== 'bundle'
                                }"
                                class="w-full flex items-center space-x-2 px-2 py-1 rounded-r-md text-[10px] uppercase tracking-[0.12em] transition-all cursor-pointer text-left">
                            <span>Home & Vases</span>
                        </button>
                    </div>
                </div>

                <!-- Expanded Workspace Config mini preview listing actual items -->
                <div class="border-t border-neutral-500/10 pt-3">
                    <div :class="theme === 'champagne' ? 'bg-neutral-100/60 text-black border-neutral-200' : 'bg-neutral-900/40 text-neutral-300 border-neutral-800/80'" class="p-3 rounded-[18px] border space-y-3">
                        
                        <!-- List of Cart Items (Capped height to fit cleanly) -->
                        @if(count($cartItems) > 0)
                            <div class="space-y-1.5 max-h-24 overflow-y-auto scrollbar-none pr-1">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center justify-between gap-1.5 text-[10px] pb-1 border-b border-neutral-500/5">
                                        <div class="truncate flex-1">
                                            <span :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="font-sans font-normal block truncate uppercase text-[9px] tracking-wider">{{ $item['product']->name }}</span>
                                            <span class="text-[8px] text-neutral-500 font-mono block mt-0.5">{{ $item['quantity'] }}x &bull; {{ ucfirst($item['size']) }}</span>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-mono text-neutral-400 block text-[9px]">{{ number_format($item['subtotal']) }} KSH</span>
                                            <!-- Mini adjust controls -->
                                            <div class="flex items-center justify-end space-x-1.5 mt-0.5">
                                                <button wire:click="removeFromCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="hover:text-amber-500 font-mono text-[9px] cursor-pointer select-none font-bold">-</button>
                                                <button wire:click="addToCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="hover:text-amber-500 font-mono text-[9px] cursor-pointer select-none font-bold">+</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 flex flex-col items-center justify-center space-y-1.5 animate-pulse">
                                <svg class="w-5 h-5 text-neutral-500 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="text-[8px] text-neutral-500 font-mono tracking-widest uppercase">Workspace Empty</span>
                            </div>
                        @endif

                        <div class="pt-1.5 border-t border-neutral-500/10 space-y-1">
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-neutral-500">Curations:</span>
                                <span class="font-mono font-semibold text-neutral-400">{{ $cartCount }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-neutral-500">Subtotal:</span>
                                <span class="font-mono font-bold"
                                      :class="{
                                          'text-[#C5A880]': theme === 'onyx',
                                          'text-[#B59A7A]': theme === 'champagne',
                                          'text-[#B76E79]': theme === 'rose'
                                      }">{{ number_format($cartTotal) }} KSH</span>
                            </div>
                        </div>
                        <button @click="drawerOpen = true" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full mt-1.5 py-2 rounded-xl text-[9px] font-sans font-light uppercase tracking-widest font-semibold transition-all cursor-pointer text-center block shadow-sm">
                            Open Workspace
                        </button>
                    </div>
                </div>

                <!-- Concierge Info -->
                <div class="border-t border-neutral-500/10 pt-3 space-y-1">
                    <span class="text-[9px] font-sans uppercase tracking-[0.12em] text-neutral-500 block"> Concierge Dispatch</span>
                    <p class="text-[10px] text-neutral-500 leading-relaxed font-light font-sans">
                        Operating: Mon - Sat 07:00 - 20:00. Call <span class="font-mono text-neutral-400 font-semibold">+254 712 345 678</span> for custom events.
                    </p>
                </div>
            </aside>

            <!-- Right Column: Showroom Segment Selector & Product Grid -->
            <div class="flex-1 w-full space-y-8">
                
                <!-- Category sub-nav selector (mobile / tablet view) -->
                <div class="lg:hidden max-w-2xl mx-auto w-full overflow-x-auto scrollbar-none pb-4 border-b border-neutral-500/10" x-data="{ active: @entangle('selectedCategory') }">
                    <div class="flex space-x-2.5 min-w-max px-2 font-mono text-[11px] uppercase tracking-wider">
                        <button @click="active = 'all'; $wire.selectCategory('all')" :class="active === 'all' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-500/10'" class="px-4 py-2 rounded-full transition-all cursor-pointer">All</button>
                        <button @click="active = 'stems'; $wire.selectCategory('stems')" :class="active === 'stems' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-500/10'" class="px-4 py-2 rounded-full transition-all cursor-pointer">Stems</button>
                        <button @click="active = 'bouquet'; $wire.selectCategory('bouquet')" :class="active === 'bouquet' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-500/10'" class="px-4 py-2 rounded-full transition-all cursor-pointer">Bouquet</button>
                        <button @click="active = 'giftings'; $wire.selectCategory('giftings')" :class="active === 'giftings' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-500/10'" class="px-4 py-2 rounded-full transition-all cursor-pointer">Giftings</button>
                        <button @click="active = 'bundle'; $wire.selectCategory('bundle')" :class="active === 'bundle' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-500/10'" class="px-4 py-2 rounded-full transition-all cursor-pointer">Bundle</button>
                    </div>
                </div>
                <!-- Search bar & Filters container -->
                <div class="flex flex-col md:flex-row md:items-center justify-end gap-4 pb-2 border-b border-neutral-500/5">
                    <!-- Minimize size search bar -->
                    <div class="w-full max-w-xs text-left">
                        <div class="relative group" x-data="{ focused: false }">
                            <input 
                                type="text" 
                                wire:model.live.debounce.200ms="search"
                                @focus="focused = true"
                                @blur="focused = false"
                                placeholder="Search curation..."
                                :class="{
                                    'bg-neutral-900/40 border-neutral-850/60 text-white placeholder-neutral-550 hover:border-[#C5A880]/50 hover:shadow-[0_0_12px_rgba(197,168,128,0.08)]': theme === 'onyx',
                                    'bg-white border-neutral-250 text-neutral-900 placeholder-neutral-450 hover:border-[#B59A7A]/50 hover:shadow-[0_0_12px_rgba(181,154,122,0.08)]': theme === 'champagne',
                                    'bg-[#1C0A10]/40 border-[#2D121F] text-pink-100 placeholder-pink-300/40 hover:border-[#B76E79]/50 hover:shadow-[0_0_12px_rgba(183,110,121,0.08)]': theme === 'rose',
                                    
                                    'shadow-[0_0_15px_rgba(197,168,128,0.2)] !border-[#C5A880]': focused && theme === 'onyx',
                                    'shadow-[0_0_15px_rgba(181,154,122,0.2)] !border-[#B59A7A]': focused && theme === 'champagne',
                                    'shadow-[0_0_15px_rgba(183,110,121,0.2)] !border-[#B76E79]': focused && theme === 'rose'
                                }"
                                class="w-full border rounded-full pl-9 pr-4 py-1.5 text-xs font-light font-sans focus:outline-none transition-all duration-300 shadow-sm"
                                style="transition-property: all; font-size: 12px;"
                            >
                            <!-- Search Icon -->
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none transition-colors duration-300"
                                 :class="{
                                     'text-[#C5A880]': theme === 'onyx',
                                     'text-[#B59A7A]': theme === 'champagne',
                                     'text-[#B76E79]': theme === 'rose'
                                 }">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                                 </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Restored Curation Mood filter buttons -->
                    <div class="flex flex-wrap gap-2 text-[10px] font-mono uppercase tracking-wider items-center">
                        <button wire:click="filterByOccasion(null)" 
                                :class="theme === 'champagne' ? 
                                    ( '{{ is_null($selectedOccasion) }}' ? 'bg-[#B59A7A] text-white font-medium' : 'text-neutral-500 border border-neutral-250 hover:bg-neutral-50' ) : 
                                    ( '{{ is_null($selectedOccasion) }}' ? 'bg-[#C5A880] text-black font-bold' : 'text-neutral-500 border border-neutral-800/80 hover:text-neutral-300' )"
                                class="px-3 py-1 rounded-full cursor-pointer transition-all">All Curation Moods</button>
                        @foreach($occasions as $occasion)
                            <button wire:click="filterByOccasion('{{ $occasion->slug }}')" 
                                    :class="theme === 'champagne' ? 
                                        ( '{{ $selectedOccasion === $occasion->slug }}' ? 'bg-[#B59A7A] text-white font-medium' : 'text-neutral-500 border border-neutral-250 hover:bg-neutral-50' ) : 
                                        ( '{{ $selectedOccasion === $occasion->slug }}' ? 'font-bold text-white' : 'text-neutral-500 border border-neutral-800/80 hover:text-neutral-300' )"
                                    class="px-3 py-1 rounded-full cursor-pointer transition-all hover:scale-102"
                                    style="{{ $selectedOccasion === $occasion->slug ? 'background-color: '.$occasion->accent_color.'; border-color: '.$occasion->accent_color.';' : '' }}">
                                {{ $occasion->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Showroom Grid (Cathedral Arched product cards & Landscape custom rectangular cards) -->
                @php
                    $groupedProducts = $products->groupBy(function($item) {
                        return match($item->category) {
                            'bouquet', 'bouquets' => 'bouquets',
                            'giftings', 'gifting', 'hampers' => 'giftings',
                            'bundle', 'wraps' => 'bundles',
                            'stems' => 'stems',
                            default => 'other',
                        };
                    });

                    $isBrowsingAll = ($selectedCategory === 'all' && empty($search) && !$selectedOccasion);
                    
                    if ($isBrowsingAll) {
                        $sectionsToRender = [
                            [
                                'title' => 'Best Seller Flowers & Bouquets',
                                'subtitle' => 'Handcrafted luxury arrangements cut fresh and styled by our master florists.',
                                'category_key' => 'bouquet',
                                'items' => $groupedProducts->get('bouquets', collect())
                            ],
                            [
                                'title' => 'Luxury Hampers & Giftings',
                                'subtitle' => 'Premium cakes, Belgian truffles, fine wines, and custom jewelry sets.',
                                'category_key' => 'giftings',
                                'items' => $groupedProducts->get('giftings', collect())
                            ],
                            [
                                'title' => 'Fresh Plants & Design Accents',
                                'subtitle' => 'Living interior plants, designer vases, and fragrant ambient sprays.',
                                'category_key' => 'bundle',
                                'items' => $groupedProducts->get('bundles', collect())
                            ],
                            [
                                'title' => 'Fresh Cut Stems Catalog',
                                'subtitle' => 'Individual Grade-A stem varieties sourced directly from Rift Valley growers.',
                                'category_key' => 'stems',
                                'items' => $groupedProducts->get('stems', collect())
                            ]
                        ];
                    } else {
                        $sectionsToRender = [
                            [
                                'title' => 'Showroom Collections',
                                'subtitle' => 'Explore our curated collection of luxury arrangements and premium gifts.',
                                'category_key' => $selectedCategory,
                                'items' => $products
                            ]
                        ];
                    }
                @endphp

                <div class="w-full space-y-16 animate-hero-fade" wire:loading.class="opacity-40" wire:target="search, selectedCategory, filterByOccasion">
                    @foreach($sectionsToRender as $section)
                        @if($section['items']->count() > 0)
                            <div class="space-y-6">
                                <!-- Section Header Ribbon -->
                                <div class="border-b border-neutral-200/60 pb-3 flex flex-col md:flex-row md:items-baseline justify-between gap-2 text-left">
                                    <div>
                                        <h3 class="text-xl sm:text-2xl font-serif italic text-neutral-800 font-semibold">{{ $section['title'] }}</h3>
                                        <p class="text-xs text-neutral-500 font-light mt-0.5">{{ $section['subtitle'] }}</p>
                                    </div>
                                    @if($isBrowsingAll)
                                        <button wire:click="selectCategory('{{ $section['category_key'] }}')"
                                                class="text-[10px] font-bold text-emerald-800 hover:text-emerald-950 font-outfit uppercase tracking-widest shrink-0 cursor-pointer transition-colors">
                                            View All &rarr;
                                        </button>
                                    @endif
                                </div>

                                <!-- Section Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 grid-flow-dense gap-6">
                                    @foreach($section['items'] as $product)
                        @if($product->category === 'specializtion' || $product->category === 'specialization' || $product->category === 'specializations')
                            <!-- Rectangular Landscape Layout for specialized custom services (half height of standard card) -->
                            <div x-data="{ selectedSize: 'standard', basePrice: {{ $product->price }}, numberFormat(val) { return new Intl.NumberFormat().format(val); } }" 
                                 :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                                 class="col-span-1 flex flex-row p-3 rounded-[24px] border relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 group text-left backdrop-blur-md product-card theme-section self-start min-h-[170px] animate-card-fade-in"
                            >
                                <!-- Left side: Squared Image Frame -->
                                <div class="w-[105px] sm:w-[125px] aspect-square rounded-2xl relative overflow-hidden bg-neutral-950/5 p-1 border border-neutral-500/10 shrink-0 self-center">
                                    <img src="{{ $product->backdrop_url }}" alt="{{ $product->name }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-102 transition-all duration-700 z-0">
                                    
                                    <div class="absolute bottom-2 left-2 z-10">
                                        <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded-full text-[8px] font-outfit font-bold tracking-wider uppercase shadow-md">
                                            {{ $product->grade ?? 'Service' }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Right side: Details -->
                                <div class="flex-1 pl-3 flex flex-col justify-between overflow-hidden">
                                    <div class="space-y-1 overflow-hidden">
                                        <span class="text-[9px] uppercase tracking-[0.2em] text-[#C5A880] font-outfit block font-bold truncate">Specialization</span>
                                        <h3 :class="theme === 'champagne' ? 'text-neutral-900 font-medium' : 'text-white'" class="text-sm font-serif italic tracking-wide leading-tight truncate">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-neutral-550 font-light text-[11px] leading-snug line-clamp-2">
                                            {{ $product->description }}
                                        </p>
                                    </div>

                                    <div class="space-y-2 mt-1">
                                        <!-- Social Sharing Direct Links for SMM (Instagram, Facebook, X SVGs) -->
                                        <div :class="theme === 'champagne' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center space-x-2.5 text-[10px] font-outfit uppercase">
                                            <span class="text-neutral-500 text-[8px] uppercase tracking-widest font-bold">Share:</span>
                                            <!-- Instagram Icon -->
                                            <a href="https://instagram.com" target="_blank" rel="noopener" class="hover:text-pink-500 transition-colors" title="Instagram">
                                                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                                </svg>
                                            </a>
                                            <!-- Facebook Icon -->
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/')) }}" target="_blank" rel="noopener" class="hover:text-blue-500 transition-colors" title="Facebook">
                                                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                                </svg>
                                            </a>
                                            <!-- X Icon -->
                                            <a href="https://twitter.com/intent/tweet?text=Consulting+with+@NoirAndBloom+for+{{ urlencode($product->name) }}:&url={{ urlencode(url('/')) }}" target="_blank" rel="noopener" :class="theme === 'champagne' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors" title="Share on X">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                </svg>
                                            </a>
                                        </div>

                                        <div class="flex items-center justify-between border-t border-neutral-500/10 pt-2">
                                            <div>
                                                <span class="text-[8px] font-outfit uppercase tracking-[0.15em] text-neutral-400 block">Base Fee</span>
                                                <span class="font-outfit text-xs font-bold text-amber-500">
                                                    {{ number_format($product->price) }} KSH
                                                </span>
                                            </div>

                                            <button type="button"
                                               @click="quickViewProduct = { id: {{ $product->id }}, name: {{ \Illuminate\Support\Js::from($product->name) }}, price: {{ $product->price }}, description: {{ \Illuminate\Support\Js::from($product->description) }}, image: {{ \Illuminate\Support\Js::from($product->backdrop_url) }}, category: {{ \Illuminate\Support\Js::from($product->category) }}, stock_standard: {{ $product->stock_standard }}, stock_deluxe: {{ $product->stock_deluxe }}, stock_grand: {{ $product->stock_grand }} }; quickViewSize = 'standard'; quickViewOpen = true;"
                                               :class="theme === 'champagne' ? 'bg-black text-white hover:bg-[#B59A7A] hover:text-black' : 'bg-white text-black hover:bg-[#C5A880] hover:text-black'"
                                               class="px-3 py-1.5 rounded-full text-[9px] font-outfit uppercase font-bold tracking-wider transition-all duration-300 shadow-md cursor-pointer"
                                            >
                                                Request
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Standard Cathedral Arch Layout -->
                            <div x-data="{ selectedSize: 'standard', basePrice: {{ $product->price }}, numberFormat(val) { return new Intl.NumberFormat().format(val); } }" 
                                 :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/50'"
                                 class="flex flex-col p-3 rounded-t-[200px] rounded-b-[32px] border relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-1.5 group text-left backdrop-blur-md product-card theme-section animate-card-fade-in"
                            >
                                <!-- Product Image Frame -->
                                <div class="p-1 border border-neutral-500/10 rounded-t-[190px] rounded-b-[28px] overflow-hidden relative">
                                    <div class="aspect-[4/5] rounded-t-[180px] rounded-b-[24px] relative overflow-hidden bg-neutral-950/5">
                                        <img src="{{ $product->backdrop_url }}" alt="{{ $product->name }}" 
                                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-700 z-0">
                                        
                                        <div class="absolute bottom-3 inset-x-3 flex justify-between items-end z-10">
                                            <span class="bg-[#050507]/60 border border-white/5 text-neutral-200 px-2 py-0.5 rounded text-[10px] font-outfit uppercase tracking-widest backdrop-blur-md">
                                                {{ $product->category }}
                                            </span>
                                            @if($product->grade)
                                                <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded text-[10px] font-outfit font-bold tracking-wide uppercase shadow-sm">
                                                    {{ $product->grade }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Details Section -->
                                <div class="px-2 pt-4 pb-2 flex-1 flex flex-col justify-between">
                                    <div class="space-y-1.5">
                                        <span class="text-[12px] uppercase tracking-[0.3em] text-neutral-400 font-outfit block font-light">Noir & Bloom Atelier</span>
                                        <h3 :class="theme === 'champagne' ? 'text-neutral-900 font-medium' : 'text-white'" class="text-xl font-serif italic tracking-wider leading-snug">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-neutral-500 font-light text-sm leading-relaxed line-clamp-2">
                                            {{ $product->description ?? 'Premium luxury floral batch curation.' }}
                                        </p>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        <div class="flex justify-between items-baseline pt-2 border-t border-neutral-500/10">
                                            <span class="text-[12px] uppercase tracking-[0.2em] text-neutral-400 font-outfit">Curation Price</span>
                                            <span class="font-outfit text-base font-semibold tracking-wide text-amber-500">
                                                <span x-text="numberFormat(selectedSize === 'standard' ? basePrice : (selectedSize === 'deluxe' ? Math.round(basePrice * 1.5) : Math.round(basePrice * 2.2)))"></span> KSH
                                            </span>
                                        </div>

                                        <div class="max-h-0 opacity-0 group-hover:max-h-64 group-hover:opacity-100 transition-all duration-500 ease-in-out overflow-hidden space-y-3">
                                            @if($product->category !== 'stems')
                                                <div class="space-y-1">
                                                    <span class="text-[12px] uppercase tracking-wider text-neutral-500 font-outfit">Curated Size</span>
                                                    <div class="flex items-center space-x-1.5">
                                                        <button type="button" @click="selectedSize = 'standard'" 
                                                                :class="selectedSize === 'standard' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                                @if($product->stock_standard <= 0) disabled title="Standard size out of stock" class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                            Std
                                                         </button>
                                                        <button type="button" @click="selectedSize = 'deluxe'" 
                                                                :class="selectedSize === 'deluxe' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                                @if($product->stock_deluxe <= 0) disabled title="Deluxe size out of stock" class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                            Dlx
                                                        </button>
                                                        <button type="button" @click="selectedSize = 'grand'" 
                                                                :class="selectedSize === 'grand' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                                @if($product->stock_grand <= 0) disabled title="Grand size out of stock" class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-outfit uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                            Gnd
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            <button 
                                                type="button"
                                                @click="quickViewProduct = { id: {{ $product->id }}, name: {{ \Illuminate\Support\Js::from($product->name) }}, price: {{ $product->price }}, description: {{ \Illuminate\Support\Js::from($product->description) }}, image: {{ \Illuminate\Support\Js::from($product->backdrop_url) }}, category: {{ \Illuminate\Support\Js::from($product->category) }}, stock_standard: {{ $product->stock_standard }}, stock_deluxe: {{ $product->stock_deluxe }}, stock_grand: {{ $product->stock_grand }} }; quickViewSize = 'standard'; quickViewOpen = true;"
                                                class="w-full text-[12px] font-semibold tracking-[0.2em] uppercase py-2.5 rounded-full flex items-center justify-center font-outfit bg-emerald-800 text-white hover:bg-emerald-900 transition-colors shadow-sm cursor-pointer"
                                            >
                                                <span>Select Details</span>
                                            </button>

                                            <!-- Social Sharing Direct Links for SMM -->
                                            <div :class="theme === 'champagne' ? 'text-neutral-600' : 'text-neutral-400'" class="flex items-center justify-between border-t border-neutral-500/10 pt-2.5 text-[10px] font-outfit uppercase tracking-widest">
                                                <span>Share:</span>
                                                <div class="flex items-center space-x-3">
                                                    <!-- WhatsApp Icon -->
                                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20this%20stunning%20{{ urlencode($product->name) }}%20arrangement%20at%20Noir%20%26%20Bloom:%20{{ urlencode(url('/')) }}" 
                                                       target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors" title="Share via WhatsApp">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M17.472 14.382c-.022-.08-.117-.146-.217-.196-.1-.05-1.002-.494-1.157-.552-.155-.058-.268-.088-.38.08-.112.167-.432.552-.53.662-.097.11-.197.125-.297.075-.1-.05-.422-.156-.803-.496-.297-.265-.497-.592-.555-.693-.058-.1-.006-.153.044-.203.045-.045.1-.117.15-.175.05-.058.067-.1.1-.167.033-.066.017-.125-.008-.175-.025-.05-.268-.646-.367-.883-.097-.235-.195-.203-.268-.203-.07 0-.15 0-.23 0-.08 0-.21.03-.32.15-.11.12-.42.41-.42 1.01s.44 1.18.5 1.26c.06.08.86 1.31 2.08 1.84.29.12.51.2.69.26.3.09.57.08.78.05.24-.03.73-.3 1.01-.58.28-.28.28-.58.26-.66zm-5.46-8.7c-4.148 0-7.53 3.38-7.53 7.53 0 1.32.345 2.613 1.003 3.753L4.5 20.5l3.65-.957c1.097.6 2.33.914 3.593.914 4.148 0 7.53-3.38 7.53-7.53 0-4.15-3.382-7.53-7.53-7.53zm0 13.82c-1.13 0-2.242-.3-3.213-.88l-.23-.136-2.39.626.637-2.33-.15-.24c-.64-.993-.978-2.15-.978-3.33 0-3.418 2.78-6.2 6.2-6.2s6.2 2.782 6.2 6.2-2.78 6.2-6.2 6.2z"/>
                                                        </svg>
                                                    </a>
                                                    <!-- X Icon -->
                                                    <a href="https://twitter.com/intent/tweet?text=Loving%20the%20premium%20{{ urlencode($product->name) }}%20curation%20from%20@NoirAndBloom.%20Check%20it%20out:&url={{ urlencode(url('/')) }}" 
                                                       target="_blank" rel="noopener" :class="theme === 'champagne' ? 'hover:text-black' : 'hover:text-white'" class="transition-colors" title="Share on X">
                                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                        </svg>
                                                    </a>
                                                    <!-- Pinterest Icon -->
                                                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url('/')) }}&media={{ urlencode($product->backdrop_url) }}&description=Noir%20%26%20Bloom%20-%20{{ urlencode($product->name) }}" 
                                                       target="_blank" rel="noopener" class="hover:text-rose-500 transition-colors" title="Pin to Pinterest">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.947-.199-2.399.041-3.429.218-.927 1.408-5.965 1.408-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.204 0 1.03.397 2.133.893 2.734.1.121.114.227.085.345-.093.389-.3.122-.345-.067-.5-.208-1.579-.854-1.579-2.858 0-3.791 2.756-7.276 7.95-7.276 4.174 0 7.417 2.974 7.417 6.953 0 4.148-2.613 7.486-6.241 7.486-1.218 0-2.363-.633-2.756-1.379l-.752 2.863c-.272 1.04-.997 2.346-1.488 3.149 1.096.337 2.257.519 3.46.519 6.621 0 11.988-5.367 11.988-11.987C24.004 5.367 18.638 0 12.017 0z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($products->isEmpty())
                        <div class="col-span-full py-20 text-center border border-dashed border-neutral-250 rounded-2xl">
                            <p class="text-xs font-light text-neutral-500 font-mono">No computational logs found matching this showroom segment filter query.</p>
                        </div>
                    @endif
                </div>

                <!-- Manual Scroll Loader Button & Curation End Marker -->
                <div class="col-span-full py-16 flex flex-col items-center justify-center">
                    @if($hasMore)
                        <div 
                            x-data="{ loading: false }"
                            class="flex flex-col items-center justify-center space-y-3"
                        >
                            <button 
                                @click="
                                    loading = true;
                                    $wire.loadMore();
                                    setTimeout(() => { loading = false; }, 1000);
                                "
                                :disabled="loading"
                                :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'"
                                class="px-6 py-3 rounded-full text-[11px] font-mono uppercase tracking-[0.25em] font-bold shadow-lg transition-all duration-300 hover:scale-105 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer flex items-center space-x-2"
                            >
                                <svg x-show="loading" class="animate-spin h-3.5 w-3.5 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" class="opacity-25"></circle>
                                    <path d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor"></path>
                                </svg>
                                <span x-text="loading ? 'Unveiling...' : 'Unveil More Curations'"></span>
                            </button>
                        </div>
                    @else
                        <!-- Elegant Market Friendly End of Curation Marker -->
                        <div class="flex flex-col items-center space-y-2 py-4 animate-hero-fade">
                            <div class="h-[1px] w-24 bg-gradient-to-r from-transparent via-[#C5A880] to-transparent mb-1"></div>
                            <span class="text-[11px] font-mono uppercase tracking-[0.3em] text-amber-600 block">&bull; End of Atelier Curation &bull;</span>
                            <span class="text-[11px] font-light text-neutral-500 font-serif italic">Every flower hand-selected, every gift packaged with devotion.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <!-- Luxury Atelier Footer -->
    <footer 
        :class="{
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'onyx',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'champagne',
            'border-[#2D0D19]/40 bg-[#1D0C13] text-neutral-300': theme === 'rose'
        }"
        class="border-t mt-32 py-16 px-6 transition-colors duration-500 z-10 relative theme-section"
    >
        <div class="max-w-8xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-left">
            <!-- Col 1: Brand & Coordinates -->
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors">NOIR & BLOOM</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
                <div class="space-y-1 text-[12px] font-mono text-neutral-500">
                    <div class="flex items-center space-x-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                        <span>Nairobi: 1.2921° S, 36.8219° E</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                        <span>Kiambu: 1.1478° S, 36.8524° E</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Showroom & Catalog -->
            <div class="space-y-4">
                <h5 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><button wire:click="selectCategory('retail')" class="hover:underline cursor-pointer">Bespoke Retail Arrays</button></li>
                    <li><button wire:click="selectCategory('wholesale')" class="hover:underline cursor-pointer">Wholesale Graded Stems</button></li>
                    <li><button wire:click="selectCategory('gifting')" class="hover:underline cursor-pointer">Luxury Giftings</button></li>
                    <li><button @click="accountPanelOpen = true" class="hover:underline cursor-pointer">Atelier Loyalty Circle</button></li>
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

            <!-- Col 4: Newsletter & Dispatch Bulletin -->
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

        <div :class="theme === 'champagne' ? 'border-neutral-200/60 text-neutral-500' : 'border-neutral-900 text-neutral-600'" class="max-w-8xl w-full mx-auto border-t mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-[12px] font-mono uppercase tracking-wider gap-4">
            <p>&copy; {{ date('Y') }} Noir &amp; Bloom Ltd. Registered Tax Entity.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-neutral-400">Terms of Curation</a>
                <a href="#" class="hover:text-neutral-400">Logistics Policy</a>
                <a href="#" class="hover:text-neutral-400">eTIMS Verification</a>
            </div>
        </div>
    </footer>

    <!-- No Account Drawer here (Profile details managed inside dedicated /profile-portal) -->
    
    <!-- Backdrop for Curation Drawer -->
    <div x-show="drawerOpen" @click="drawerOpen = false" class="fixed inset-0 z-45 bg-black/40 backdrop-blur-xl" style="display: none;"></div>
    
    <!-- Floating Cart Overlay Panel (Center Modal) -->
    <div 
        x-show="drawerOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        :class="theme === 'champagne' ? 'bg-white border-neutral-200 text-neutral-900 shadow-2xl' : 'bg-[#0F0F12]/95 border border-neutral-900 text-white shadow-2xl'"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-48px)] sm:w-[520px] max-h-[85vh] z-50 flex flex-col justify-between text-left backdrop-blur-xl rounded-[32px] overflow-hidden"
        style="display: none;"
    >
        <div :class="theme === 'champagne' ? 'border-neutral-100' : 'border-neutral-900'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div>
                <h3 :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Selected Curations</h3>
                <span class="text-[9px] text-neutral-500 font-light">Bespoke Arrangement Hub</span>
            </div>
            <button @click="drawerOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Drawer">
                <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <!-- Cart items list (visible if not checking out) -->
        <div x-show="!checkoutMode && !@json($orderSubmitted)" class="flex-1 flex flex-col justify-between overflow-hidden">
            <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(85vh-220px)] scrollbar-none">
                @forelse($cartItems as $item)
                    <div :class="theme === 'champagne' ? 'border-neutral-100' : 'border-neutral-900/60'" class="flex items-center justify-between space-x-4 border-b pb-4 text-xs animate-hero-fade">
                        <div class="flex-1 space-y-0.5">
                            <h4 :class="theme === 'champagne' ? 'text-neutral-855' : 'text-white'" class="font-normal">{{ $item['product']->name }}</h4>
                            <p class="text-neutral-500 font-mono">{{ number_format($item['product']->price) }} KSH &bull; <span class="uppercase text-[10px]">Pack/{{ $item['product']->unit_type }}</span></p>
                        </div>
                        <div :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="flex items-center space-x-3 px-2.5 py-1.5 border rounded-full">
                            <button wire:click="removeFromCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="text-neutral-400 font-bold font-mono cursor-pointer select-none">-</button>
                            <span class="text-xs font-mono min-w-[15px] text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="text-neutral-400 font-bold font-mono cursor-pointer select-none">+</button>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center space-y-3 pt-28 text-neutral-500 animate-pulse">
                        <svg class="w-10 h-10 text-neutral-500 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-[10px] font-mono uppercase tracking-[0.2em]">Empty</span>
                        <p class="text-[11px] font-light max-w-[220px]">Browse the showroom to compile premium items into your workspace configuration.</p>
                    </div>
                @endforelse
            </div>

            @if(count($cartItems) > 0)
                <div :class="theme === 'champagne' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0">
                    <div class="flex justify-between items-baseline text-xs font-light">
                        <span class="text-neutral-500 tracking-wider">Estimated Subtotal</span>
                        <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-base font-mono font-semibold">{{ number_format($cartTotal) }} KSH</span>
                    </div>
                    @auth
                        <button @click="checkoutMode = true" class="w-full text-xs font-semibold tracking-[0.2em] uppercase py-4 cursor-pointer rounded-full btn-curate">
                            Proceed to Logistics Spec
                        </button>
                    @else
                        <!-- Guest authentication request block -->
                        <div class="border border-dashed border-[#C5A880]/30 rounded-2xl p-4.5 bg-[#C5A880]/5 text-center space-y-3.5">
                            <span class="text-[10px] font-sans uppercase tracking-[0.2em] text-[#C5A880] block font-semibold">✦ Authentication Required ✦</span>
                            <p class="text-neutral-400 font-light text-[11.5px] leading-relaxed font-sans">Please sign in or create an account to configure logistics details and checkout.</p>
                            <div class="grid grid-cols-2 gap-3 pt-1.5">
                                <button type="button" wire:click="prepareGuestCheckoutRedirect('/login')" class="bg-[#C5A880] text-black font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] hover:bg-[#B59A7A] transition-all cursor-pointer shadow-md">
                                    Sign In
                                </button>
                                <button type="button" wire:click="prepareGuestCheckoutRedirect('/register')" class="border border-neutral-800 text-neutral-450 hover:text-white hover:border-neutral-600 font-mono font-bold uppercase tracking-wider py-2.5 rounded-xl text-[10px] transition-all cursor-pointer">
                                    Register
                                </button>
                            </div>
                        </div>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Checkout forms area -->
        <div x-show="checkoutMode && !@json($orderSubmitted)" class="flex-1 flex flex-col justify-between overflow-hidden" style="display: none;">
            <form wire:submit.prevent="submitCurationRequest" class="flex-1 flex flex-col justify-between overflow-hidden">
                
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(85vh-210px)] scrollbar-none text-xs">
                    <div :class="theme === 'champagne' ? 'border-neutral-100' : 'border-neutral-900'" class="flex items-center justify-between pb-2 border-b">
                        <span class="text-xs uppercase tracking-wider text-neutral-400">Atelier Delivery Profile</span>
                        <button type="button" @click="checkoutMode = false" class="text-neutral-500 hover:text-neutral-400 text-xs font-mono cursor-pointer flex items-center space-x-1">
                            <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Back</span>
                        </button>
                    </div>

                    <div :class="theme === 'champagne' ? 'bg-neutral-100 border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="p-1 border rounded-full grid grid-cols-2 text-center text-[11px] font-mono uppercase tracking-wider shadow-inner">
                        <button type="button" @click="$wire.set('checkoutType', 'standard')" :class="$wire.checkoutType === 'standard' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">Personal Delivery</button>
                        <button type="button" @click="$wire.set('checkoutType', 'corporate')" :class="$wire.checkoutType === 'corporate' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">Corporate eTIMS</button>
                    </div>

                    @if($checkoutType === 'corporate')
                        <div class="space-y-1.5 bg-black/10 border border-neutral-900 p-3 rounded-2xl">
                            <label class="text-[10px] uppercase tracking-wider text-neutral-500 font-mono block">Remittance Protocol</label>
                            <div :class="theme === 'champagne' ? 'bg-neutral-100 border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="p-1 border rounded-full grid grid-cols-2 text-center text-[11px] font-mono uppercase tracking-wider shadow-inner">
                                <button type="button" @click="$wire.set('paymentMethod', 'mpesa')" :class="$wire.paymentMethod === 'mpesa' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">M-Pesa Push</button>
                                <button type="button" @click="$wire.set('paymentMethod', 'net_30')" :class="$wire.paymentMethod === 'net_30' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">Credit Invoice (Net 30)</button>
                            </div>
                            @error('paymentMethod')
                                <span class="text-[10px] text-rose-500 font-mono block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="p-4 border rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-xs font-normal block">Send this order as a luxury gift delivery?</span>
                                <span class="text-xs text-neutral-500 font-light block mt-0.5">Recipient delivery parameters will cleanly isolate away from receipt parameters.</span>
                            </div>
                            <input type="checkbox" wire:model.live="is_gift" class="w-3.5 h-3.5 rounded text-black border-neutral-800 focus:ring-0 cursor-pointer">
                        </div>
                    </div>

                    <div x-show="$wire.is_gift" class="space-y-4 border border-dashed border-neutral-800 p-4 rounded-xl bg-black/5" style="display: none;" x-transition>
                        <span class="text-[11px] font-mono uppercase text-amber-500 tracking-wider block font-bold">&bull; Recipient Delivery Profile</span>
                        <div class="space-y-1">
                            <label class="text-xs uppercase tracking-wider text-neutral-500">Recipient Full Name *</label>
                            <input type="text" placeholder="Enter full recipient name" wire:model="recipient_name" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs uppercase tracking-wider text-neutral-500">Recipient Contact Line *</label>
                            <input type="text" placeholder="e.g. 0712345678 (Required for courier logistics)" wire:model="recipient_phone" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                        </div>
                    </div>

                    <div x-show="!$wire.is_gift" x-transition>
                        <div :class="theme === 'champagne' ? 'bg-neutral-100 text-black border-neutral-200' : 'bg-neutral-900/60 text-neutral-400 border-neutral-900'" class="p-3.5 border rounded-xl text-xs font-light space-y-1.5">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-500 block pb-1 border-b border-neutral-500/10">Pre-authenticated Customer Ledger Record</span>
                            <div><span class="text-neutral-500">Contact Payer:</span> <span class="font-semibold text-neutral-300 dark:text-white">{{ $full_name }}</span></div>
                            <div><span class="text-neutral-500">Secure Comm:</span> <span class="font-mono">{{ $phone }} &bull; {{ $email }}</span></div>
                            @if($checkoutType === 'corporate')
                                <div><span class="text-neutral-500 font-mono">eTIMS KRA PIN:</span> <span class="font-mono text-amber-500 font-semibold uppercase">{{ $kra_pin }}</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wider text-neutral-500 block">Presentation Customization Packages (Pure delivery is free of added cost)</label>
                        <div class="grid grid-cols-1 gap-2">
                            <button type="button" wire:click="$set('delivery_type', 'standard')" :class="$wire.delivery_type === 'standard' ? 'border-black dark:border-white bg-neutral-500/5 font-medium shadow-sm' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-xl text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">Standard Courier Dispatch</span><span class="text-[11px] text-neutral-500 block mt-0.5">Premium transport routing directly to your destination building coordinates.</span></div>
                                <span class="font-mono text-[11px]">+ 0 KSH</span>
                            </button>
                            <button type="button" wire:click="$set('delivery_type', 'secret')" :class="$wire.delivery_type === 'secret' ? 'border-amber-600 bg-amber-950/10 text-amber-500 font-medium' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-xl text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">The Secret Admirer Protocol</span><span class="text-[11px] text-neutral-500 block mt-0.5">We will fully conceal your sender profile parameters behind wax-sealed card enclosures.</span></div>
                                <span class="font-mono text-[11px] text-amber-500 font-semibold">+ 500 KSH</span>
                            </button>
                            <button type="button" wire:click="$set('delivery_type', 'concierge')" :class="$wire.delivery_type === 'concierge' ? 'border-emerald-600 bg-emerald-950/10 text-emerald-500 font-medium' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-xl text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">Uniformed Concierge Presentation</span><span class="text-[11px] text-neutral-500 block mt-0.5">Hand-delivered via sharp, uniformed corporate couriers. Elite tier presentation statement.</span></div>
                                <span class="font-mono text-[11px] text-emerald-500 font-semibold">+ 1,500 KSH</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs uppercase tracking-wider text-neutral-500">Distribution Node *</label>
                            <select wire:model.live="region" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-2.5 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                                <option value="Nairobi">Nairobi Metropolitan</option>
                                <option value="Kiambu">Kiambu Ridge Hub</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs uppercase tracking-wider text-neutral-500">Landmarks Address *</label>
                            <input type="text" list="premium-address-nodes" placeholder="Type complex, street, or estate..." wire:model="delivery_address" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                            <datalist id="premium-address-nodes">
                                @foreach($this->getAddressSuggestions() as $node) <option value="{{ $node }}"></option> @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>

                <div :class="theme === 'champagne' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0 text-xs">
                    <div class="space-y-3">
                        <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-500 block pb-1 border-b border-neutral-500/10">Itemized Pre-Payment Statement</span>
                        
                        <!-- Product Item list -->
                        <div class="space-y-1.5 border-b border-neutral-500/5 pb-2">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between text-neutral-400">
                                    <span>{{ $item['product']->name }} ({{ strtoupper(substr($item['size'], 0, 3)) }} × {{ $item['quantity'] }})</span>
                                    <span class="font-mono text-neutral-300">{{ number_format($item['subtotal']) }} KSH</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Financial/Tax Breakdown -->
                        <div class="space-y-1 text-neutral-500 font-mono text-[11px]">
                            <div class="flex justify-between">
                                <span>Taxable Net Base (Excl. VAT):</span>
                                <span>{{ number_format((int)round(($cartTotal + $service_fee) / 1.16)) }} KSH</span>
                            </div>
                            <div class="flex justify-between">
                                <span>VAT Compliance (16%):</span>
                                <span>{{ number_format(($cartTotal + $service_fee) - (int)round(($cartTotal + $service_fee) / 1.16)) }} KSH</span>
                            </div>
                            @if($service_fee > 0)
                                <div class="flex justify-between text-amber-500">
                                    <span>Order Fulfillment (Upsell):</span>
                                    <span>+ {{ number_format($service_fee) }} KSH</span>
                                </div>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-baseline text-sm font-normal pt-2 border-t border-neutral-500/10">
                            <span class="text-neutral-400">Grand Dispatch Total:</span>
                            <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-md font-mono font-bold tracking-tight">{{ number_format($cartTotal + $service_fee) }} KSH</span>
                        </div>
                    </div>
                    
                    <button 
                        type="submit" wire:loading.attr="disabled" wire:target="submitCurationRequest"
                        class="w-full text-xs font-semibold tracking-[0.2em] uppercase py-4 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center space-x-2 rounded-full btn-curate"
                    >
                        <span wire:loading wire:target="submitCurationRequest" class="animate-spin rounded-full h-2.5 w-2.5 border border-neutral-400 border-t-transparent inline-block"></span>
                        <span wire:loading.remove wire:target="submitCurationRequest">Request Atelier Dispatch</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mpesa Payment Initiation Page (Visible once orderSubmitted is true) -->
        @if($orderSubmitted)
            <div class="flex-1 flex flex-col justify-between overflow-hidden shrink-0 h-full">
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(85vh-200px)] scrollbar-none w-full text-xs">
                    <div class="space-y-6 max-w-sm mx-auto w-full pt-4">
                        
                        @if($paymentStatus === 'idle')
                            {{-- State: IDLE - Prompt phone number --}}
                            <div class="w-10 h-10 rounded-full border border-neutral-800 flex items-center justify-center bg-neutral-900/50 mx-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10B981]"></span>
                            </div>
                            <div class="space-y-1 text-center">
                                <h4 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-sm uppercase tracking-[0.2em] font-medium">Dispatch Mapped</h4>
                                <p class="text-xs text-neutral-500 font-light leading-relaxed">Your curation specs are locked. Dispatch Safaricom API prompts down below.</p>
                                <div :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A]/80 border-neutral-900 text-neutral-300'" class="p-4 border rounded-xl text-xs font-light space-y-2.5">
                                 <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-400 block pb-1 border-b border-neutral-500/10">Remittance Statement Summary</span>
                                 
                                 <!-- Itemized pricing lines -->
                                 <div class="space-y-1.5 border-b border-neutral-500/5 pb-2">
                                     @foreach($cartItems as $item)
                                         <div class="flex justify-between text-neutral-400">
                                             <span>{{ $item['product']->name }} ({{ strtoupper(substr($item['size'], 0, 3)) }} × {{ $item['quantity'] }})</span>
                                             <span class="font-mono text-neutral-300">{{ number_format($item['product']->price * $item['quantity']) }} KSH</span>
                                         </div>
                                     @endforeach
                                 </div>

                                 <!-- Client Billing Context -->
                                 <div class="space-y-1 text-neutral-400 border-b border-neutral-500/5 pb-2">
                                     <div><span class="text-neutral-500">Payer Account:</span> <span class="font-medium text-neutral-300">{{ $full_name }}</span></div>
                                     @if($is_gift)
                                         <div><span class="text-amber-500 font-medium">Gift Delivery For:</span> <span class="text-amber-500 font-medium">{{ $recipient_name }} ({{ $recipient_phone }})</span></div>
                                     @endif
                                     <div><span class="text-neutral-500">Destination:</span> <span>{{ $delivery_address }}, Node/{{ $region }}</span></div>
                                 </div>

                                 <!-- Financial and Tax Splits -->
                                 <div class="space-y-1 text-neutral-400">
                                     <div class="flex justify-between">
                                         <span>Taxable Net Base:</span>
                                         <span class="font-mono text-[11px]">{{ number_format((int)round(($cartTotal + $service_fee) / 1.16)) }} KSH</span>
                                     </div>
                                     <div class="flex justify-between">
                                         <span>VAT (16% Rate Compliance):</span>
                                         <span class="font-mono text-[11px]">{{ number_format(($cartTotal + $service_fee) - (int)round(($cartTotal + $service_fee) / 1.16)) }} KSH</span>
                                     </div>
                                     @if($service_fee > 0)
                                         <div class="flex justify-between">
                                             <span>Order Fulfillment (Concierge):</span>
                                             <span class="font-mono text-[11px]">+ {{ number_format($service_fee) }} KSH</span>
                                         </div>
                                     @endif
                                 </div>

                                 <div class="flex justify-between pt-2 border-t border-neutral-500/10 text-emerald-400 font-semibold">
                                     <span>Grand Total:</span>
                                     <span class="font-mono text-sm text-emerald-400 font-bold">{{ number_format($cartTotal + $service_fee) }} KSH</span>
                                 </div>
                             </div>
                            </div>

                            <div :class="theme === 'champagne' ? 'bg-white border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="space-y-3 p-4 border rounded-2xl shadow-2xl">
                                <div class="space-y-1">
                                    <label class="text-[10px] uppercase tracking-wider text-neutral-500 font-mono">Safaricom Authorization Line</label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-3 text-xs font-mono text-neutral-600">+254</span>
                                        <input type="tel" wire:model="phone" placeholder="712345678" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F0F] border-neutral-800 text-white'" class="w-full border rounded-xl pl-14 pr-3 py-1.5 text-xs font-mono focus:outline-none focus:border-neutral-400">
                                    </div>
                                </div>

                                @if($mpesaErrorMessage)
                                    <div class="p-2 border border-dashed border-rose-900 bg-rose-950/20 text-[10px] font-mono text-rose-400 rounded-sm">{{ $mpesaErrorMessage }}</div>
                                @endif

                                <button type="button" wire:click="initiateMpesaPayment" wire:loading.attr="disabled" class="w-full text-xs font-semibold tracking-[0.2em] uppercase py-3 cursor-pointer rounded-full flex items-center justify-center space-x-2 btn-curate">
                                    <span wire:loading wire:target="initiateMpesaPayment" class="animate-spin rounded-full h-3 w-3 border border-current border-t-transparent inline-block"></span>
                                    <span wire:loading.remove wire:target="initiateMpesaPayment">Authorize STK Push</span>
                                </button>
                            </div>
                        @elseif($paymentStatus === 'pending')
                            {{-- State: PENDING - Polling state checking payment callbacks --}}
                            <div wire:poll.3s="checkPaymentStatus" class="space-y-6 text-center py-8">
                                <div x-data="{ timer: 60 }" x-init="const interval = setInterval(() => { if(timer > 0) { timer--; } else { clearInterval(interval); } }, 1000)" class="space-y-6">
                                    <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
                                        <span class="absolute inline-flex h-12 w-12 rounded-full bg-amber-500/20 animate-ping"></span>
                                        <div class="w-12 h-12 rounded-full border border-neutral-800 flex items-center justify-center bg-neutral-900/60 z-10">
                                            <svg class="w-5 h-5 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 :class="theme === 'champagne' ? 'text-neutral-900 font-semibold' : 'text-white'" class="text-sm uppercase tracking-[0.2em] font-medium font-mono text-amber-500">Awaiting PIN Auth</h4>
                                        <p class="text-xs text-neutral-500 font-light leading-relaxed">
                                            An M-Pesa prompt has been dispatched to <span class="font-mono text-neutral-300 font-semibold">+254{{ $phone }}</span>.<br>Enter your Safaricom PIN to complete checkout.
                                        </p>
                                    </div>
                                    <div class="text-[10px] font-mono text-neutral-600 uppercase tracking-widest bg-neutral-950/20 py-2 border border-neutral-900/50 max-w-[200px] mx-auto rounded-full">
                                        Expiry in <span class="font-bold text-white" x-text="timer"></span>s
                                    </div>
                                </div>
                            </div>
                        @elseif($paymentStatus === 'completed')
                            {{-- State: COMPLETED - Success state + Invoice retrieval --}}
                            <div class="space-y-8 text-center py-6">
                                <div class="w-16 h-16 rounded-full border border-emerald-800 bg-emerald-950/20 flex items-center justify-center mx-auto shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-sm uppercase tracking-[0.2em] font-semibold text-emerald-400 font-mono">Remittance Confirmed</h4>
                                    <p class="text-xs text-neutral-500 font-light leading-relaxed">
                                        Your payment has been cleared by Safaricom Daraja. Your order is now queued for design and atelier fulfillment.
                                    </p>
                                    <div :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A]/60 border-neutral-900 text-neutral-300'" class="p-4 border rounded-xl text-[11px] font-mono text-left space-y-2 max-w-sm mx-auto">
                                     <span class="text-[9px] uppercase tracking-wider text-neutral-500 block border-b border-neutral-500/10 pb-1.5 font-bold">Transaction Compliance Audit</span>
                                     <div class="flex justify-between"><span>Atelier Order ID:</span><span class="text-white font-semibold">#NB-ORD-{{ str_pad($trackedOrderId, 4, '0', STR_PAD_LEFT) }}</span></div>
                                     @if($mpesaReceiptNumber)
                                         <div class="flex justify-between"><span>M-Pesa Receipt:</span><span class="text-amber-500 font-bold uppercase">{{ $mpesaReceiptNumber }}</span></div>
                                     @else
                                         <div class="flex justify-between"><span>Billing Terms:</span><span class="text-amber-500 font-bold uppercase">Net 30 Credit Invoice</span></div>
                                     @endif
                                     <div class="flex justify-between"><span>eTIMS Invoiced:</span><span class="text-emerald-400">16% VAT COMPLIANT</span></div>
                                 </div>
                                </div>

                                <div class="space-y-3 max-w-sm mx-auto pt-4">
                                    <a href="{{ URL::signedRoute('receipt.download', ['order' => $trackedOrderId]) }}" target="_blank" class="w-full bg-[#C5A880] hover:bg-[#B59A7A] text-black text-xs font-mono font-bold uppercase tracking-[0.2em] py-3 rounded-full flex items-center justify-center space-x-2 transition-all shadow-md transform hover:scale-[1.02]">
                                        <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Download PDF Receipt</span>
                                    </a>
                                </div>
                            </div>
                        @elseif($paymentStatus === 'failed')
                            {{-- State: FAILED - Failure notification + retry option --}}
                            <div class="space-y-8 text-center py-6">
                                <div class="w-16 h-16 rounded-full border border-rose-800 bg-rose-950/20 flex items-center justify-center mx-auto shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                    <svg class="w-8 h-8 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-sm uppercase tracking-[0.2em] font-semibold text-rose-500 font-mono">Remittance Failed</h4>
                                    <p class="text-xs text-rose-400 font-mono bg-rose-950/25 border border-rose-900/30 p-3 rounded-xl max-w-sm mx-auto leading-relaxed">
                                        {{ $mpesaErrorMessage }}
                                    </p>
                                </div>
                                
                                <div class="pt-4 max-w-sm mx-auto">
                                    <button type="button" wire:click="$set('paymentStatus', 'idle')" class="w-full text-xs font-semibold tracking-[0.2em] uppercase py-3 cursor-pointer rounded-full btn-curate">
                                        Retry M-Pesa Authorization
                                    </button>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="text-center p-4 border-t border-neutral-500/5 shrink-0 bg-black/10 flex justify-center">
                    <button @click="drawerOpen = false; checkoutMode = false;" wire:click="returnToCollections" class="text-neutral-500 hover:text-neutral-450 text-xs font-mono tracking-widest uppercase cursor-pointer flex items-center space-x-1">
                        <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Clear & Return to Showroom</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Redundant Floating Round Cart Button removed in favor of header navigation cart -->

    <!-- Floating Chat Widget -->
    <div x-show="!drawerOpen" x-transition class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans">
        <!-- Jumping Animated Circular Icon Button -->
        <button 
            @click="chatOpen = !chatOpen" 
            :class="theme === 'champagne' ? 'bg-[#FAF7F0] text-black border-neutral-200 shadow-sm' : 'bg-[#0A0A0A] text-white border-neutral-800 shadow-2xl'"
            class="w-14 h-14 rounded-full flex items-center justify-center border cursor-pointer hover:scale-105 transition-all duration-300 animate-aura-bounce relative theme-section"
            title="Aura Curation Companion"
        >
            <span x-show="!chatOpen" class="absolute top-1 right-1 flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
            </span>
            
            <span x-show="!chatOpen" class="flex items-center justify-center">
                <!-- Chat SVG icon -->
                <svg class="w-6 h-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span x-show="chatOpen" style="display: none;" class="flex items-center justify-center">
                <!-- Close SVG icon -->
                <svg class="w-6 h-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </button>
 
        <div x-show="chatOpen" style="display: none;" x-transition class="mt-3 w-80 md:w-96 h-[420px] bg-[#0F0F0F] border border-neutral-800 rounded-3xl shadow-2xl flex flex-col justify-between overflow-hidden">
            <div class="p-4 border-b border-neutral-800 bg-[#0A0A0A] flex items-center justify-between text-left">
                <div>
                    <span class="text-xs uppercase font-mono text-neutral-300 tracking-wider font-semibold">Aura Concierge AI</span>
                    <span class="block text-[11px] text-emerald-400 font-mono mt-0.5">&bull; Active Curation Companion</span>
                </div>
                <button @click="chatOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Chat">
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 p-4 overflow-y-auto space-y-4 text-xs font-light scrollbar-none flex flex-col text-left">
                @foreach($chatHistory as $msg)
                    <div class="max-w-[85%] rounded px-3 py-2.5 leading-relaxed {{ $msg['sender'] === 'bot' ? 'bg-neutral-900 text-neutral-300 self-start border border-neutral-800/40' : 'bg-white text-black font-normal self-end shadow-md' }}">{{ $msg['text'] }}</div>
                @endforeach
            </div>
            <form wire:submit.prevent="sendChatMessage" class="p-3 border-t border-neutral-800 bg-[#0A0A0A] flex items-center gap-2">
                <input type="text" wire:model="chatMessage" placeholder="Ask Aura about arrangements, branches, points..." class="flex-1 bg-[#141414] border border-neutral-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-neutral-700 font-light font-sans">
                <button type="submit" class="bg-white text-black font-mono text-[11px] uppercase font-bold px-3.5 py-2 rounded-full cursor-pointer hover:bg-neutral-200 transition-colors">Ask</button>
            </form>
        </div>
    </div>


    <!-- Product Detail Modal (FNP-style) -->
    <div x-show="quickViewOpen" @click="quickViewOpen = false" class="fixed inset-0 z-45 bg-black/60 backdrop-blur-md" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div
        x-show="quickViewOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100vw-32px)] sm:w-[640px] max-h-[90vh] z-50 bg-white border border-neutral-200 text-neutral-900 shadow-2xl flex flex-col justify-between text-left rounded-[28px] overflow-hidden font-sans"
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

        <div class="p-6 overflow-y-auto flex-1 flex flex-col md:flex-row gap-6">
            <!-- Image column -->
            <div class="w-full md:w-1/2 shrink-0">
                <div class="w-full h-48 md:h-64 rounded-2xl overflow-hidden bg-neutral-100 relative">
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
                            <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(quickViewProduct.price) + ' KSH' : ''"></span>
                            <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_standard <= 0">Out of Stock</span>
                        </button>
                        <!-- Deluxe -->
                        <button type="button" @click="quickViewSize = 'deluxe'"
                            :class="quickViewSize === 'deluxe' ? 'border-emerald-800 bg-emerald-50 text-emerald-800 font-semibold' : 'border-neutral-200 text-neutral-600 hover:text-neutral-800'"
                            class="flex-1 min-w-[80px] px-3 py-2 border text-[11px] font-outfit uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center"
                            :disabled="quickViewProduct && quickViewProduct.stock_deluxe <= 0"
                        >
                            <span class="block">Deluxe</span>
                            <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(Math.round(quickViewProduct.price * 1.5)) + ' KSH' : ''"></span>
                            <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_deluxe <= 0">Out of Stock</span>
                        </button>
                        <!-- Grand -->
                        <button type="button" @click="quickViewSize = 'grand'"
                            :class="quickViewSize === 'grand' ? 'border-emerald-800 bg-emerald-50 text-emerald-800 font-semibold' : 'border-neutral-200 text-neutral-600 hover:text-neutral-800'"
                            class="flex-1 min-w-[80px] px-3 py-2 border text-[11px] font-outfit uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center"
                            :disabled="quickViewProduct && quickViewProduct.stock_grand <= 0"
                        >
                            <span class="block">Grand</span>
                            <span class="block text-[9px] opacity-75 font-light" x-text="quickViewProduct ? numberFormat(Math.round(quickViewProduct.price * 2.2)) + ' KSH' : ''"></span>
                            <span class="block text-[8px] mt-0.5 text-red-500 font-semibold" x-show="quickViewProduct && quickViewProduct.stock_grand <= 0">Out of Stock</span>
                        </button>
                    </div>
                </div>

                <!-- Location & Date Picker -->
                <div class="bg-neutral-50 rounded-xl p-3 border border-neutral-100 flex flex-col gap-2.5">
                    <span class="text-xs font-bold text-neutral-700 block">Fulfillment Details</span>
                    
                    <!-- Delivery City -->
                    <div>
                        <label class="text-[10px] text-neutral-500 uppercase font-semibold block mb-1">City</label>
                        <select wire:model.live="deliveryCity" class="w-full bg-white border border-neutral-200 rounded-lg px-2.5 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 font-sans cursor-pointer">
                            <option value="">-- Select City --</option>
                            <option value="Nairobi">Nairobi</option>
                            <option value="Kiambu">Kiambu</option>
                            <option value="Mombasa">Mombasa</option>
                            <option value="Kisumu">Kisumu</option>
                            <option value="Nakuru">Nakuru</option>
                        </select>
                    </div>
                    
                    <!-- Delivery Date -->
                    <div>
                        <label class="text-[10px] text-neutral-500 uppercase font-semibold block mb-1">Delivery Date</label>
                        <input type="date" wire:model.live="deliveryDate" min="{{ date('Y-m-d') }}" class="w-full bg-white border border-neutral-200 rounded-lg px-2.5 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 font-sans">
                    </div>
                    
                    <!-- Delivery Slot -->
                    <div>
                        <label class="text-[10px] text-neutral-500 uppercase font-semibold block mb-1">Delivery Time Slot</label>
                        <select wire:model.live="deliverySlot" class="w-full bg-white border border-neutral-200 rounded-lg px-2.5 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-emerald-600 font-sans cursor-pointer">
                            <option value="standard">Standard (Free)</option>
                            <option value="midnight">Midnight (1,500 KSH)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Validation Warning -->
                <div x-show="!$wire.deliveryCity || !$wire.deliveryDate" class="text-xs text-amber-700 font-medium bg-amber-50 border border-amber-200/60 rounded-lg px-3 py-2 flex items-start gap-1.5 animate-pulse">
                    <span>⚠</span>
                    <span>Please enter delivery city and date above to enable adding this item to your curation.</span>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-neutral-100 bg-neutral-50 shrink-0 flex items-center justify-between">
            <div>
                <span class="text-[10px] text-neutral-500 uppercase tracking-wider block">Price</span>
                <span class="text-base font-bold text-neutral-850 font-mono">
                    <span x-text="quickViewProduct ? numberFormat(quickViewSize === 'standard' ? quickViewProduct.price : (quickViewSize === 'deluxe' ? Math.round(quickViewProduct.price * 1.5) : Math.round(quickViewProduct.price * 2.2))) : ''"></span> KSH
                </span>
            </div>
            
            <button
                type="button"
                :disabled="!$wire.deliveryCity || !$wire.deliveryDate || (quickViewProduct && (quickViewSize === 'standard' && quickViewProduct.stock_standard <= 0) || (quickViewProduct && (quickViewSize === 'deluxe' && quickViewProduct.stock_deluxe <= 0) || (quickViewProduct && (quickViewSize === 'grand' && quickViewProduct.stock_grand <= 0))))"
                @click="$wire.addToCuration(quickViewProduct.id, quickViewSize); quickViewOpen = false; drawerOpen = true; checkoutMode = false;"
                class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-950 text-white rounded-xl text-xs uppercase tracking-wider font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
            >
                Add to Curation
            </button>
        </div>
    </div>

</div>