<div 
    x-data="{ 
        drawerOpen: false, 
        checkoutMode: false, 
        theme: localStorage.getItem('nb_theme') || 'onyx', 
        accountPanelOpen: false, 
        chatOpen: false,
        
        /* Session timeout tracking */
        idleTimer: null,
        countdownTimer: null,
        isIdleWarning: false,
        timeLeft: 120 /* 2 minutes warning countdown */
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
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-hidden"
>
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
    </style>


    <!-- 3D Flower Ambient Animation Canvas -->
    <canvas id="flower-ambient-canvas" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Fine Grain Noise Overlay -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-80"></div>
    
    <header 
        :class="{
            'border-neutral-900 bg-[#050507]/80 shadow-2xl': theme === 'onyx',
            'border-neutral-200 bg-white/80 shadow-sm': theme === 'champagne',
            'border-[#2D0D19]/60 bg-[#15060A]/80 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-0 inset-x-0 h-20 backdrop-blur-xl border-b z-50 transition-all duration-500 flex items-center"
    >
        <div class="max-w-7xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 animate-nav-item select-none cursor-pointer" style="animation-delay: 100ms;">
                <span class="text-[11px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                <h1 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors">NOIR & BLOOM</h1>
            </a>
            
            <div class="flex-1 max-w-sm hidden md:block animate-nav-item" style="animation-delay: 200ms;">
                <div class="relative flex items-center">
                    <input 
                        type="text" 
                        wire:model.live.debounce.200ms="search"
                        placeholder="Search bouquets, stems, custom gifts..."
                        :class="theme === 'champagne' ? 'bg-neutral-100 text-black placeholder-neutral-400 focus:bg-neutral-200/60' : 'bg-neutral-900/60 text-white placeholder-neutral-700 focus:bg-black'"
                        class="w-full border-none rounded-full px-5 py-2 text-sm font-light focus:outline-none transition-all shadow-inner"
                    >
                </div>
            </div>

            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Theme Switcher Pill (3 options, desktop only) -->
                <div class="hidden lg:flex items-center space-x-1 border border-neutral-500/10 rounded-full bg-neutral-500/5 p-1 animate-nav-item select-none" style="animation-delay: 300ms;">
                    <button @click="theme = 'onyx'" :class="theme === 'onyx' ? 'bg-[#C5A880] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Onyx</span>
                    </button>
                    <button @click="theme = 'champagne'" :class="theme === 'champagne' ? 'bg-[#B59A7A] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Champagne</span>
                    </button>
                    <button @click="theme = 'rose'" :class="theme === 'rose' ? 'bg-[#B76E79] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span class="w-1 h-1 rounded-full bg-current"></span>
                        <span>Rose</span>
                    </button>
                </div>
                <!-- Collapsed mobile theme switcher -->
                <button @click="theme = theme === 'onyx' ? 'champagne' : (theme === 'champagne' ? 'rose' : 'onyx')" 
                        class="lg:hidden hover:text-neutral-200 transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item"
                        style="animation-delay: 300ms;"
                        title="Cycle Theme"
                >
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="5" />
                        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.77l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </button>

                <!-- Modern SVG shopping bag cart button with absolute badge -->
                <button @click="drawerOpen = true; checkoutMode = false;" 
                        class="hover:text-neutral-300 transition-colors cursor-pointer select-none relative w-8 h-8 flex items-center justify-center border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item" 
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
                </button>

                <!-- Profile Portal Sign In / User button -->
                @auth
                    <!-- Profile Portal - Logged In (Show user silhouette SVG) -->
                    <button @click="accountPanelOpen = true" 
                            class="hover:text-neutral-300 transition-colors cursor-pointer select-none w-8 h-8 flex items-center justify-center border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item"
                            style="animation-delay: 500ms;"
                            title="Profile Portal"
                    >
                        <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                @else
                    <!-- Profile Portal - Logged Out (Show sign-in text and arrow entry SVG) -->
                    <button @click="accountPanelOpen = true" 
                            class="hover:text-neutral-300 transition-colors cursor-pointer select-none px-3.5 h-8 flex items-center justify-center space-x-1.5 border border-neutral-500/10 rounded-full bg-neutral-500/5 animate-nav-item text-xs font-mono font-medium tracking-wider"
                            style="animation-delay: 500ms;"
                            title="Log In or Sign In"
                    >
                        <svg class="w-3.5 h-3.5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3M15 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="hidden sm:inline">Log In / Sign In</span>
                    </button>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col">
        <!-- Interactive Advertisements Carousel (3 Luxury Slides with images, custom textures, colors, and controls) -->
        <section x-data="{ 
                     activeSlide: 0, 
                     slidesCount: 3, 
                     timer: null,
                     init() {
                         this.timer = setInterval(() => {
                             this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                         }, 8000);
                     }
                 }" 
                 class="w-full relative overflow-hidden mb-12 rounded-sm border border-neutral-500/10 min-h-[550px] flex items-center shadow-2xl"
        >
            <!-- Slide 1: Naivasha Rift Valley Stems (Jade/Emerald Theme with fresh rose bundles image) -->
            <div x-show="activeSlide === 0" 
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition duration-500 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 flex flex-col justify-center px-8 md:px-16 py-12 text-left bg-cover bg-center"
                 style="background-image: linear-gradient(to right, rgba(6, 19, 15, 0.95) 40%, rgba(11, 46, 36, 0.7) 70%, rgba(4, 13, 11, 0.4) 100%), url('/media/slide1.png');"
            >
                <div class="absolute inset-0 pointer-events-none opacity-5 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,_transparent_1px),_linear-gradient(90deg,_rgba(255,255,255,0.05)_1px,_transparent_1px)] bg-[size:40px_40px]"></div>
                
                <div class="max-w-xl space-y-4 z-10 animate-hero-rise">
                    <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-emerald-400 block">Wholesale Premium Export</span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif italic text-white leading-tight tracking-wider">
                        Rift Valley Stems
                    </h2>
                    <div class="h-[1px] w-16 bg-emerald-500/30"></div>
                    <p class="text-sm sm:text-base font-light leading-relaxed text-emerald-100/80">
                        Sourced from high-altitude Naivasha volcanic farms. Grade A export-quality stems cut daily and dispatched via strict cold-chain logistics.
                    </p>
                    <div class="pt-4">
                        <button @click="$wire.selectCategory('wholesale'); document.getElementById('product-showroom').scrollIntoView({behavior: 'smooth'})" class="bg-emerald-500 text-neutral-950 px-6 py-3 rounded-full text-[11px] font-mono uppercase tracking-[0.2em] font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:bg-emerald-400 cursor-pointer">
                            Order Stems Catalog
                        </button>
                    </div>
                </div>
            </div>

            <!-- Slide 2: The Secret Admirer Protocol (Onyx/Crimson Theme with velvet dark moody rose image) -->
            <div x-show="activeSlide === 1" 
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition duration-500 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 flex flex-col justify-center px-8 md:px-16 py-12 text-left bg-cover bg-center"
                 style="display: none; background-image: linear-gradient(to right, rgba(9, 5, 7, 0.95) 40%, rgba(42, 8, 21, 0.75) 70%, rgba(5, 1, 3, 0.4) 100%), url('/media/slide2.png');"
             >
                <div class="absolute inset-0 pointer-events-none opacity-30 bg-repeat bg-[radial-gradient(circle_at_1px,_rgba(255,255,255,0.03)_1px,_transparent_1px)] bg-[size:24px_24px]"></div>
                
                <div class="max-w-xl space-y-4 z-10">
                    <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-rose-400 block">Bespoke Delivery Protocol</span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif italic text-white leading-tight tracking-wider">
                        The Secret Admirer
                    </h2>
                    <div class="h-[1px] w-16 bg-rose-500/30"></div>
                    <p class="text-sm sm:text-base font-light leading-relaxed text-rose-100/80">
                        Send premium arrangements anonymously. Delivered inside luxury, heavy linen wraps with card details protected by traditional wax seals.
                    </p>
                    <div class="pt-4">
                        <button @click="document.getElementById('product-showroom').scrollIntoView({behavior: 'smooth'})" class="bg-rose-800 hover:bg-rose-700 text-white px-6 py-3 rounded-full text-[11px] font-mono uppercase tracking-[0.2em] font-bold shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
                            Acquire Protocol
                        </button>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Bespoke Concierge Suites (Champagne/Gold Theme with luxury table setting image) -->
            <div x-show="activeSlide === 2" 
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition duration-500 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 flex flex-col justify-center px-8 md:px-16 py-12 text-left bg-cover bg-center"
                 style="display: none; background-image: linear-gradient(to right, rgba(250, 247, 240, 0.95) 40%, rgba(232, 226, 213, 0.75) 70%, rgba(213, 205, 191, 0.4) 100%), url('/media/slide3.png');"
            >
                <div class="absolute inset-0 pointer-events-none opacity-5 bg-[linear-gradient(45deg,_rgba(0,0,0,0.05)_25%,_transparent_25%,_transparent_50%,_rgba(0,0,0,0.05)_50%,_rgba(0,0,0,0.05)_75%,_transparent_75%,_transparent)] bg-[size:10px_10px]"></div>

                <div class="max-w-xl space-y-4 z-10">
                    <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-amber-800 block">Elite Concierge Logistics</span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif italic text-neutral-900 leading-tight tracking-wider">
                        Uniformed Presentation
                    </h2>
                    <div class="h-[1px] w-16 bg-amber-800/30"></div>
                    <p class="text-sm sm:text-base font-light leading-relaxed text-neutral-700">
                        Elevate corporate events, boardrooms, and private lounges. Hand-delivered via sharp, uniformed corporate couriers. eTIMS invoicing pre-enabled.
                    </p>
                    <div class="pt-4">
                        <button @click="$wire.selectCategory('gifting'); document.getElementById('product-showroom').scrollIntoView({behavior: 'smooth'})" class="bg-amber-800 text-white px-6 py-3 rounded-full text-[11px] font-mono uppercase tracking-[0.2em] font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:bg-amber-900 cursor-pointer">
                            Reserve Event Concierge
                        </button>
                    </div>
                </div>
            </div>

            <!-- Slide Controls (Dots) -->
            <div class="absolute bottom-6 right-8 md:right-16 flex items-center space-x-2 z-20">
                <template x-for="(val, index) in Array.from({ length: slidesCount })" :key="index">
                    <button @click="activeSlide = index" 
                            :class="activeSlide === index ? 'bg-amber-500 w-6' : 'bg-neutral-500/40 w-2'" 
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                    ></button>
                </template>
            </div>
        </section>

        <!-- Double Column Layout: Left Sticky Sidebar, Right Catalog showroom -->
        <div id="product-showroom" class="flex flex-col lg:flex-row gap-8 w-full items-start">
            
            <!-- Sticky Sidebar Navigator (Desktop only) -->
            <aside :class="theme === 'champagne' ? 'border-neutral-200 bg-white/50' : 'border-neutral-900/60 bg-[#0C0C0E]/40'" class="hidden lg:block w-64 shrink-0 sticky top-28 p-6 border rounded-[32px] backdrop-blur-md space-y-6 text-left transition-all duration-500 shadow-sm z-10 max-h-[calc(100vh-130px)] overflow-y-auto scrollbar-none">
                <!-- Sidebar Title -->
                <div class="border-b border-neutral-500/10 pb-4">
                    <span class="text-[12px] font-mono uppercase tracking-[0.2em] text-neutral-500 block">Workspace Navigator</span>
                    <h4 :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="text-sm font-semibold uppercase tracking-wider mt-1">Curation Desk</h4>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-1.5" x-data="{ active: @entangle('selectedCategory') }">
                    <span class="text-[11px] font-mono uppercase tracking-widest text-neutral-500 block mb-2">Showroom Segments</span>
                    <button @click="active = 'all'; $wire.selectCategory('all')" 
                            :class="active === 'all' ? 'bg-neutral-500/10 text-amber-500 font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                            class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                            <path d="M12 12c2.5-3.5 1-7-1-8-2 1-3.5 4.5-1 8z" />
                        </svg>
                        <span>All Showroom</span>
                    </button>
                    <button @click="active = 'retail'; $wire.selectCategory('retail')" 
                            :class="active === 'retail' ? 'bg-neutral-500/10 text-amber-500 font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                            class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                            <path d="M9.5 9h5l1 2v4a3.5 3.5 0 0 1-7 0v-4l1-2z" />
                        </svg>
                        <span>Bespoke Retail</span>
                    </button>
                    <button @click="active = 'wholesale'; $wire.selectCategory('wholesale')" 
                            :class="active === 'wholesale' ? 'bg-neutral-500/10 text-amber-500 font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                            class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                            <path d="M8 11h8l-2 10H10L8 11z" />
                        </svg>
                        <span>Wholesale Stems</span>
                    </button>
                    <button @click="active = 'gifting'; $wire.selectCategory('gifting')" 
                            :class="active === 'gifting' ? 'bg-neutral-500/10 text-amber-500 font-semibold' : 'text-neutral-400 hover:text-neutral-200'"
                            class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12px] font-mono uppercase tracking-wider transition-all sidebar-nav-item cursor-pointer text-left">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none" stroke-width="1.5">
                            <rect x="4" y="8" width="16" height="12" rx="1" />
                        </svg>
                        <span>Luxury Gifting</span>
                    </button>
                </div>

                <!-- Expanded Workspace Config mini preview listing actual items -->
                <div class="border-t border-neutral-500/10 pt-4 space-y-3">
                    <span class="text-[11px] font-mono uppercase tracking-widest text-neutral-500 block">Workspace Config</span>
                    <div :class="theme === 'champagne' ? 'bg-neutral-100/60 text-black' : 'bg-neutral-900/40 text-neutral-300'" class="p-4 rounded-2xl border border-neutral-500/10 space-y-3">
                        
                        <!-- List of Cart Items -->
                        @if(count($cartItems) > 0)
                            <div class="space-y-2 max-h-48 overflow-y-auto scrollbar-none pr-1">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center justify-between gap-2 text-[12px] pb-2 border-b border-neutral-500/5">
                                        <div class="truncate flex-1">
                                            <span :class="theme === 'champagne' ? 'text-neutral-800' : 'text-white'" class="font-medium block truncate">{{ $item['product']->name }}</span>
                                            <span class="text-[11px] text-neutral-500 font-mono block">{{ $item['quantity'] }}x &bull; {{ $item['size'] }}</span>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-mono text-neutral-400 block">{{ number_format($item['subtotal']) }} KSH</span>
                                            <!-- Mini adjust controls -->
                                            <div class="flex items-center justify-end space-x-2 mt-1">
                                                <button wire:click="removeFromCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="hover:text-amber-500 font-mono text-[11px] cursor-pointer select-none font-bold">-</button>
                                                <button wire:click="addToCuration({{ $item['original_id'] }}, '{{ $item['size'] }}')" class="hover:text-amber-500 font-mono text-[11px] cursor-pointer select-none font-bold">+</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <span class="text-[12px] text-neutral-500 font-mono block">&bull; Workspace Empty &bull;</span>
                            </div>
                        @endif

                        <div class="pt-2 border-t border-neutral-500/10 space-y-1.5">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral-500">Curations:</span>
                                <span class="font-mono font-semibold">{{ $cartCount }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-neutral-500">Subtotal:</span>
                                <span class="font-mono font-bold text-amber-500">{{ number_format($cartTotal) }} KSH</span>
                            </div>
                        </div>
                        <button @click="drawerOpen = true" :class="theme === 'champagne' ? 'bg-black text-white hover:bg-neutral-800' : 'bg-white text-black hover:bg-neutral-200'" class="w-full mt-2 py-2 rounded-xl text-[11px] font-mono uppercase tracking-wider font-semibold transition-all cursor-pointer text-center block shadow-sm">
                            Open Drawer
                        </button>
                    </div>
                </div>

                <!-- Concierge Info -->
                <div class="border-t border-neutral-500/10 pt-4 space-y-2">
                    <span class="text-[11px] font-mono uppercase tracking-widest text-neutral-500 block"> Concierge Dispatch</span>
                    <p class="text-[12px] text-neutral-500 leading-relaxed font-light">
                        Operating: Mon - Sat 07:00 - 20:00. Call <span class="font-mono text-neutral-400 font-semibold">+254 712 345 678</span> for custom events.
                    </p>
                </div>
            </aside>

            <!-- Right Column: Showroom Segment Selector & Product Grid -->
            <div class="flex-1 w-full space-y-8">
                
                <!-- Category sub-nav selector (mobile / tablet view) -->
                <div class="lg:hidden max-w-2xl mx-auto w-full" x-data="{ active: @entangle('selectedCategory') }">
                    <div class="grid grid-cols-4 relative border-b border-neutral-500/10 pb-4">
                        <!-- Underline active slider line -->
                        <div class="absolute bottom-0 h-[2px] bg-[#D4AF37] transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                             :class="{
                                 'left-0 w-1/4': active === 'all',
                                 'left-1/4 w-1/4': active === 'retail',
                                 'left-2/4 w-1/4': active === 'wholesale',
                                 'left-3/4 w-1/4': active === 'gifting'
                             }"></div>
                        
                        <button @click="active = 'all'; $wire.selectCategory('all')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'all' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 stroke-current fill-none" stroke-width="1.5">
                                <path d="M12 12c2.5-3.5 1-7-1-8-2 1-3.5 4.5-1 8z" />
                            </svg>
                            <span class="text-[11px] font-mono uppercase tracking-[0.1em]">Showroom</span>
                        </button>
                        <button @click="active = 'retail'; $wire.selectCategory('retail')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'retail' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 stroke-current fill-none" stroke-width="1.5">
                                <path d="M9.5 9h5l1 2v4a3.5 3.5 0 0 1-7 0v-4l1-2z" />
                            </svg>
                            <span class="text-[11px] font-mono uppercase tracking-[0.1em]">Bespoke</span>
                        </button>
                        <button @click="active = 'wholesale'; $wire.selectCategory('wholesale')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'wholesale' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 stroke-current fill-none" stroke-width="1.5">
                                <path d="M8 11h8l-2 10H10L8 11z" />
                            </svg>
                            <span class="text-[11px] font-mono uppercase tracking-[0.1em]">Wholesale</span>
                        </button>
                        <button @click="active = 'gifting'; $wire.selectCategory('gifting')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'gifting' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 stroke-current fill-none" stroke-width="1.5">
                                <rect x="4" y="8" width="16" height="12" rx="1" />
                            </svg>
                            <span class="text-[11px] font-mono uppercase tracking-[0.1em]">Gifting</span>
                        </button>
                    </div>
                </div>

                <!-- Curation Mood filter buttons -->
                <div class="flex flex-wrap gap-2 text-[12px] font-mono uppercase tracking-wider">
                    <button wire:click="filterByOccasion(null)" class="px-4 py-2 rounded-full border border-neutral-500/10 cursor-pointer {{ is_null($selectedOccasion) ? 'bg-neutral-500/10 text-white' : 'text-neutral-500 hover:text-neutral-300' }} transition-all font-mono">All Curation Moods</button>
                    @foreach($occasions as $occasion)
                        <button wire:click="filterByOccasion('{{ $occasion->slug }}')" class="px-4 py-2 rounded-full border border-neutral-500/10 cursor-pointer transition-all hover:scale-105 font-mono" style="{{ $selectedOccasion === $occasion->slug ? 'background-color: '.$occasion->accent_color.'; color: #fff; border-color: '.$occasion->accent_color.'; font-weight: bold;' : 'color: text-neutral-500;' }}">
                            {{ $occasion->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Showroom Grid (Cathedral Arched product cards, 3 columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-40" wire:target="search, selectedCategory, filterByOccasion">
                    @forelse($products as $product)
                        <div x-data="{ selectedSize: 'standard', basePrice: {{ $product->price }}, numberFormat(val) { return new Intl.NumberFormat().format(val); } }" 
                             :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm' : 'border-neutral-900/60 bg-[#0C0C0E]/50'"
                             class="flex flex-col p-3 rounded-t-[200px] rounded-b-[32px] border relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-1.5 group text-left backdrop-blur-md"
                        >
                            <!-- Product Image Frame: Cathedral Arch floral museum aesthetic -->
                            <div class="p-1 border border-neutral-500/10 rounded-t-[190px] rounded-b-[28px] overflow-hidden relative">
                                <div class="aspect-[4/5] rounded-t-[180px] rounded-b-[24px] relative overflow-hidden bg-neutral-950/5">
                                    <!-- Product Image -->
                                    <img src="{{ $product->backdrop_url }}" alt="{{ $product->name }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-700 z-0">
                                    
                                    <!-- Subtle Category and Grade tags at the bottom of the image frame (safe from arch clipping) -->
                                    <div class="absolute bottom-3 inset-x-3 flex justify-between items-end z-10">
                                        <span class="bg-[#050507]/60 border border-white/5 text-neutral-200 px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-widest backdrop-blur-md">
                                            {{ $product->category }}
                                        </span>
                                        @if($product->grade)
                                            <span class="bg-[#C5A880] text-black px-2 py-0.5 rounded text-[10px] font-mono font-bold tracking-wide uppercase shadow-sm">
                                                {{ $product->grade }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Details Section: Animated below the image (without blur) -->
                            <div class="px-2 pt-4 pb-2 flex-1 flex flex-col justify-between">
                                <div class="space-y-1.5">
                                    <span class="text-[12px] uppercase tracking-[0.3em] text-neutral-400 font-mono block font-light">Noir & Bloom Atelier</span>
                                    <h3 :class="theme === 'champagne' ? 'text-neutral-900 font-medium' : 'text-white'" class="text-xl font-serif italic tracking-wider leading-snug">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-neutral-500 font-light text-sm leading-relaxed line-clamp-2">
                                        {{ $product->description ?? 'Premium luxury floral batch curation.' }}
                                    </p>
                                </div>

                                <!-- Pricing & Size Details - Slide down smoothly on card hover -->
                                <div class="mt-4 space-y-3">
                                    <!-- Always visible price based on active size -->
                                    <div class="flex justify-between items-baseline pt-2 border-t border-neutral-500/10">
                                        <span class="text-[12px] uppercase tracking-[0.2em] text-neutral-400 font-mono">Curation Price</span>
                                        <span class="font-mono text-base font-semibold tracking-wide text-amber-500">
                                            <span x-text="numberFormat(selectedSize === 'standard' ? basePrice : (selectedSize === 'deluxe' ? Math.round(basePrice * 1.5) : Math.round(basePrice * 2.2)))"></span> KSH
                                        </span>
                                    </div>

                                    <!-- Size selector & add to cart button (reveal on hover) -->
                                    <div class="max-h-0 opacity-0 group-hover:max-h-32 group-hover:opacity-100 transition-all duration-500 ease-in-out overflow-hidden space-y-3">
                                        @if($product->category !== 'wholesale')
                                            <!-- Size Picker with Stock Check -->
                                            <div class="space-y-1">
                                                <span class="text-[12px] uppercase tracking-wider text-neutral-500 font-mono">Curated Size</span>
                                                <div class="flex items-center space-x-1.5">
                                                    <!-- Standard -->
                                                    <button type="button" @click="selectedSize = 'standard'" 
                                                            :class="selectedSize === 'standard' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                            @if($product->stock_standard <= 0) disabled title="Standard size out of stock" class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                        Std
                                                     </button>
                                                    <!-- Deluxe -->
                                                    <button type="button" @click="selectedSize = 'deluxe'" 
                                                            :class="selectedSize === 'deluxe' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                            @if($product->stock_deluxe <= 0) disabled title="Deluxe size out of stock" class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                        Dlx
                                                    </button>
                                                    <!-- Grand -->
                                                    <button type="button" @click="selectedSize = 'grand'" 
                                                            :class="selectedSize === 'grand' ? 'border-neutral-800 bg-neutral-900 text-white dark:border-neutral-200 dark:bg-white dark:text-black font-semibold' : 'border-neutral-250 text-neutral-500 hover:text-neutral-700 dark:border-neutral-800/80'" 
                                                            @if($product->stock_grand <= 0) disabled title="Grand size out of stock" class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full opacity-30 cursor-not-allowed transition-all" @else class="px-2.5 py-0.5 border text-[11px] font-mono uppercase tracking-wider rounded-full cursor-pointer transition-all" @endif>
                                                        Gnd
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Add to Curation Button with active click suggestion colour change -->
                                        <button 
                                            type="button"
                                            :disabled="(selectedSize === 'standard' && {{ $product->stock_standard }} <= 0) || (selectedSize === 'deluxe' && {{ $product->stock_deluxe }} <= 0) || (selectedSize === 'grand' && {{ $product->stock_grand }} <= 0)"
                                            @click="$wire.addToCuration({{ $product->id }}, selectedSize); drawerOpen = true; checkoutMode = false;"
                                            :class="theme === 'champagne' ? 'bg-black text-white hover:bg-[#B59A7A] hover:text-black hover:shadow-[0_0_15px_rgba(181,154,122,0.5)]' : 'bg-white text-black hover:bg-[#C5A880] hover:text-black hover:shadow-[0_0_15px_rgba(197,168,128,0.5)]'"
                                            class="w-full text-[12px] font-semibold tracking-[0.2em] uppercase py-2.5 transition-all duration-300 rounded-full flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed transform hover:scale-[1.02]"
                                        >
                                            <span x-text="(selectedSize === 'standard' && {{ $product->stock_standard }} <= 0) || (selectedSize === 'deluxe' && {{ $product->stock_deluxe }} <= 0) || (selectedSize === 'grand' && {{ $product->stock_grand }} <= 0) ? 'Out of Stock' : 'Curate Selection'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center border border-dashed border-neutral-800 rounded-2xl">
                            <p class="text-xs font-light text-neutral-500 font-mono">No computational logs found matching this showroom segment filter query.</p>
                        </div>
                    @endforelse
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
        class="border-t mt-32 py-16 px-6 transition-colors duration-500 z-10 relative"
    >
        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-left">
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

        <div :class="theme === 'champagne' ? 'border-neutral-200/60 text-neutral-500' : 'border-neutral-900 text-neutral-600'" class="max-w-7xl w-full mx-auto border-t mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-[12px] font-mono uppercase tracking-wider gap-4">
            <p>&copy; {{ date('Y') }} Noir &amp; Bloom Ltd. Registered Tax Entity.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-neutral-400">Terms of Curation</a>
                <a href="#" class="hover:text-neutral-400">Logistics Policy</a>
                <a href="#" class="hover:text-neutral-400">eTIMS Verification</a>
            </div>
        </div>
    </footer>

    <!-- Overlay backdrop for Account Panel -->
    <div x-show="accountPanelOpen" @click="accountPanelOpen = false" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50" style="display: none;"></div>
    
    <!-- Account Portal Side Drawer -->
    <div 
        x-show="accountPanelOpen"
        x-transition:enter="transition transform ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full sm:w-[600px] bg-[#0A0A0C] border-l border-neutral-900 shadow-2xl z-50 flex flex-col justify-between text-left p-8 text-neutral-200 h-full rounded-l-3xl"
        style="display: none;"
    >
        @auth
            <div class="border-b border-neutral-900 pb-4 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-white">Profile Portal</h2>
                    <span class="text-[10px] text-neutral-500 font-mono">Loyalty Tier: {{ auth()->user()->loyalty_tier }}</span>
                </div>
                <button @click="accountPanelOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Portal">
                    <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto my-6 space-y-6 scrollbar-none max-h-[calc(100vh-160px)] pr-1">
                <!-- Profile Info + Loyalty Stats -->
                <div class="bg-neutral-900/30 border border-neutral-900 p-5 rounded-xl space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-white font-medium block text-sm">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-neutral-400 font-mono block">{{ auth()->user()->email }} &bull; {{ auth()->user()->phone_number }}</span>
                            <span class="text-[10px] text-neutral-500 font-mono block">{{ auth()->user()->default_delivery_address }}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono text-[9px] uppercase tracking-widest px-2 py-1 rounded block">
                                {{ auth()->user()->display_tier ?? 'Retail Member' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-neutral-900/60 font-mono">
                        <div>
                            <span class="text-[9px] uppercase text-neutral-500 block">Atelier Points</span>
                            <span class="text-sm text-amber-400 font-bold mt-1 block">{{ number_format(auth()->user()->loyalty_points) }} PTS</span>
                        </div>
                        <div>
                            <span class="text-[9px] uppercase text-neutral-500 block">Referral Code</span>
                            <span class="text-sm text-neutral-200 mt-1 block tracking-wider uppercase">{{ auth()->user()->referral_code ?? 'None' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Historical Orders -->
                <div class="space-y-3">
                    <h3 class="text-[10px] uppercase font-mono tracking-wider text-neutral-400 font-bold">&bull; Historical Dispatch Logs Matrix</h3>
                    
                    @forelse ($userOrders as $order)
                        <div class="bg-neutral-900/40 border border-neutral-900 rounded-xl p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3 text-xs">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-white font-mono font-semibold">#NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-950/40 text-amber-400 border-amber-900/30',
                                            'approved' => 'bg-blue-950/40 text-blue-400 border-blue-900/30',
                                            'processing' => 'bg-purple-950/40 text-purple-400 border-purple-900/30',
                                            'delivered' => 'bg-emerald-950/40 text-emerald-400 border-emerald-900/30',
                                            'cancelled' => 'bg-neutral-900/60 text-neutral-500 border-neutral-800',
                                        ];
                                        $colorClass = $statusColors[$order->status] ?? $statusColors['pending'];
                                    @endphp
                                    <span class="text-[9px] px-1.5 py-0.5 rounded font-mono border {{ $colorClass }} uppercase tracking-wider">{{ $order->status }}</span>
                                </div>
                                <p class="text-neutral-400 text-[11px]">
                                    @foreach ($order->products as $product)
                                        {{ $product->pivot->quantity }}x {{ $product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                                @if ($order->etimsInvoice)
                                    <span class="text-[10px] text-neutral-500 font-mono block">eTIMS Receipt: {{ $order->etimsInvoice->invoice_number }}</span>
                                @endif
                            </div>
                            <div class="sm:text-right shrink-0">
                                <span class="text-white font-mono font-bold block">{{ number_format($order->total_amount) }} KSH</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-neutral-900/20 border border-neutral-900 rounded-sm">
                            <span class="text-[9px] font-mono uppercase tracking-[0.2em] text-neutral-500">&bull; No Orders Recorded &bull;</span>
                            <p class="text-[11px] text-neutral-400 font-light mt-1">Place an order in the showroom to record your first transaction.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Sign Out -->
                <div class="pt-6 border-t border-neutral-900">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-850 border border-neutral-850 hover:border-neutral-700 text-neutral-300 py-2.5 text-[10px] font-mono uppercase tracking-[0.2em] rounded-full transition-all cursor-pointer">
                            [ Sign Out of Portal ]
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="border-b border-neutral-900 pb-4 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-white">Profile Portal</h2>
                    <span class="text-[10px] text-neutral-500 font-mono">Authentication Required</span>
                </div>
                <button @click="accountPanelOpen = false" class="text-neutral-500 hover:text-white cursor-pointer select-none transition-colors" title="Close Portal">
                    <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 flex flex-col justify-center space-y-6 py-12 text-center">
                <div class="space-y-2">
                    <span class="text-amber-500 font-serif text-3xl italic block">Atelier Loyalty Circle</span>
                    <p class="text-neutral-400 font-light text-xs max-w-sm mx-auto">
                        Authenticate your membership to earn points on each purchase, unlock premium pricing tiers, and manage corporate tax details.
                    </p>
                </div>

                <div class="flex flex-col space-y-3 max-w-xs mx-auto w-full pt-4">
                    <a href="{{ route('login') }}" class="bg-white text-black hover:bg-neutral-250 py-3 text-[10px] font-mono uppercase tracking-[0.25em] rounded-full transition-all text-center">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="border border-neutral-850 hover:border-neutral-700 hover:bg-neutral-900/30 text-neutral-300 py-3 text-[10px] font-mono uppercase tracking-[0.25em] rounded-full transition-all text-center">
                        Create Account
                    </a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Overlay backdrop for Curation Drawer -->
    <div x-show="drawerOpen" @click="drawerOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50" style="display: none;"></div>
    
    <!-- Curation Selection Sidebar Drawer -->
    <div 
        x-show="drawerOpen"
        x-transition:enter="transition transform ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        :class="theme === 'champagne' ? 'bg-white border-neutral-200 text-neutral-900' : 'bg-[#0F0F12] border-l border-neutral-900 text-white'"
        class="fixed inset-y-0 right-0 w-full sm:w-[480px] shadow-2xl z-50 flex flex-col justify-between text-left transition-colors duration-500 h-full rounded-l-3xl"
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
            <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-220px)] scrollbar-none">
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
                    <div class="h-full flex flex-col items-center justify-center text-center space-y-2 pt-24 text-neutral-500">
                        <span class="text-[9px] font-mono uppercase tracking-[0.2em]">&bull; Selection Empty &bull;</span>
                        <p class="text-[11px] font-light max-w-[200px]">Browse the showroom to compile premium items into your workspace configuration.</p>
                    </div>
                @endforelse
            </div>

            @if(count($cartItems) > 0)
                <div :class="theme === 'champagne' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0">
                    <div class="flex justify-between items-baseline text-xs font-light">
                        <span class="text-neutral-500 tracking-wider">Estimated Subtotal</span>
                        <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-base font-mono font-semibold">{{ number_format($cartTotal) }} KSH</span>
                    </div>
                    <button @click="checkoutMode = true" class="w-full bg-black text-white dark:bg-white dark:text-black text-xs font-semibold tracking-[0.2em] uppercase py-4 hover:opacity-90 transition-all cursor-pointer rounded-full">
                        Proceed to Logistics Spec
                    </button>
                </div>
            @endif
        </div>

        <!-- Checkout forms area -->
        <div x-show="checkoutMode && !@json($orderSubmitted)" class="flex-1 flex flex-col justify-between overflow-hidden" style="display: none;">
            <form wire:submit.prevent="submitCurationRequest" class="flex-1 flex flex-col justify-between overflow-hidden">
                
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-210px)] scrollbar-none text-xs">
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
                            <select wire:model="region" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-2.5 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                                <option value="Nairobi">Nairobi Metropolitan</option>
                                <option value="Kiambu">Kiambu Ridge Hub</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs uppercase tracking-wider text-neutral-500">Landmarks Address *</label>
                            <input type="text" list="premium-address-nodes" placeholder="Type complex, street, or estate..." wire:model="delivery_address" :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded-xl px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                            <datalist id="premium-address-nodes">
                                @foreach($addressSuggestions as $node) <option value="{{ $node }}"></option> @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>

                <div :class="theme === 'champagne' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0 text-xs">
                    <div class="space-y-1.5 text-neutral-500">
                        <div class="flex justify-between"><span>Showroom Subtotal:</span><span class="font-mono text-neutral-400">{{ number_format($cartTotal) }} KSH</span></div>
                        @if($service_fee > 0)
                            <div class="flex justify-between text-amber-500 font-mono"><span>Presentation Upsell Pack Fee:</span><span>+ {{ number_format($service_fee) }} KSH</span></div>
                        @endif
                        <div class="flex justify-between items-baseline text-sm font-normal pt-2 border-t border-neutral-500/10">
                            <span class="text-neutral-400">Grand Dispatch Total:</span>
                            <span :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-md font-mono font-bold tracking-tight">{{ number_format($cartTotal + $service_fee) }} KSH</span>
                        </div>
                    </div>
                    
                    <button 
                        type="submit" wire:loading.attr="disabled" wire:target="submitCurationRequest"
                        class="w-full bg-black text-white dark:bg-white dark:text-black text-xs font-semibold tracking-[0.2em] uppercase py-4 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer flex items-center justify-center space-x-2 rounded-full"
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
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-200px)] scrollbar-none w-full text-xs">
                    <div class="space-y-6 max-w-sm mx-auto w-full pt-4">
                        <div class="w-10 h-10 rounded-full border border-neutral-800 flex items-center justify-center bg-neutral-900/50 mx-auto">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10B981]"></span>
                        </div>
                        <div class="space-y-1 text-center">
                            <h4 :class="theme === 'champagne' ? 'text-neutral-900' : 'text-white'" class="text-sm uppercase tracking-[0.2em] font-medium">Dispatch Mapped</h4>
                            <p class="text-xs text-neutral-500 font-light leading-relaxed">Your curation specs are locked. Dispatch Safaricom API prompts down below.</p>
                        </div>

                        <div :class="theme === 'champagne' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A]/80 border-neutral-900 text-neutral-300'" class="p-4 border rounded-xl text-xs font-light space-y-1.5">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-400 block pb-1 border-b border-neutral-500/10">Authorized Billing Context Receipt</span>
                            <div><span class="text-neutral-500">Payer Name:</span> <span class="font-medium">{{ $full_name }}</span></div>
                            @if($is_gift)
                                <div><span class="text-amber-500 font-medium">Gift Delivery For:</span> <span class="text-amber-500 font-medium">{{ $recipient_name }} ({{ $recipient_phone }})</span></div>
                            @endif
                            <div><span class="text-neutral-500">Destination Anchor:</span> <span>{{ $delivery_address }}, Node/{{ $region }}</span></div>
                            <div><span class="text-neutral-500 font-mono">Grand Remittance Total:</span> <span class="font-mono text-emerald-500 font-semibold">{{ number_format($cartTotal + $service_fee) }} KSH</span></div>
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

                            <button type="button" wire:click="initiateMpesaPayment" wire:loading.attr="disabled" class="w-full bg-[#10B981] hover:bg-emerald-600 text-white text-xs font-bold tracking-[0.2em] uppercase py-3 transition-colors cursor-pointer rounded-full flex items-center justify-center space-x-2">
                                <span wire:loading wire:target="initiateMpesaPayment" class="animate-spin rounded-full h-3 w-3 border border-white border-t-transparent inline-block"></span>
                                <span wire:loading.remove wire:target="initiateMpesaPayment">Authorize STK Push</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center p-4 border-t border-neutral-500/5 shrink-0 bg-black/10 flex justify-center">
                    <button @click="drawerOpen = false; checkoutMode = false;" wire:click="returnToCollections" class="text-neutral-500 hover:text-neutral-400 text-xs font-mono tracking-widest uppercase cursor-pointer flex items-center space-x-1">
                        <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Clear & Return to Showroom</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Floating Chat Widget -->
    <div x-show="!drawerOpen" x-transition class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans">
        <!-- Jumping Animated Circular Icon Button -->
        <button 
            @click="chatOpen = !chatOpen" 
            :class="theme === 'champagne' ? 'bg-[#FAF7F0] text-black border-neutral-200 shadow-sm' : 'bg-[#0A0A0A] text-white border-neutral-800 shadow-2xl'"
            class="w-14 h-14 rounded-full flex items-center justify-center border cursor-pointer hover:scale-105 transition-all duration-300 animate-aura-bounce relative"
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
</div>

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

            // Physics and mouse interactive configuration
            const particleCount = 96;
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
                const speed = Math.random() * 1.2 + 0.4;
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    r: Math.random() * 12 + 6, // Larger sizes for premium visual impact (6 to 18px radius)
                    d: Math.random() * particleCount,
                    baseSpeed: Math.random() * 0.5 + 0.3,
                    sway: Math.random() * 0.6 - 0.3,
                    angle: Math.random() * 360,
                    rotationSpeed: Math.random() * 1.2 - 0.6,
                    color: '',
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
                    // Increase visibility opacity from 15% (26) to 46% (77)
                    p.color = colors[idx % colors.length] + '77';

                    if (p.type === 'flower') {
                        drawFlower(p.x, p.y, p.r, 5, p.color, p.angle);
                    } else {
                        drawPetal(p.x, p.y, p.r, p.color, p.angle);
                    }

                    // Reynolds Flocking Algorithm implementation (Separation, Cohesion, Alignment)
                    let flockVx = 0;
                    let flockVy = 0;
                    let flockX = 0;
                    let flockY = 0;
                    let avoidX = 0;
                    let avoidY = 0;
                    let neighbors = 0;
                    let closeNeighbors = 0;

                    const visualRange = 100;
                    const minDistance = 35;

                    particles.forEach(other => {
                        if (other === p) return;
                        
                        let dx = other.x - p.x;
                        let dy = other.y - p.y;
                        let dist = Math.sqrt(dx*dx + dy*dy);
                        
                        if (dist < visualRange) {
                            flockX += other.x;
                            flockY += other.y;
                            flockVx += other.vx;
                            flockVy += other.vy;
                            neighbors++;

                            if (dist < minDistance) {
                                avoidX -= dx;
                                avoidY -= dy;
                                closeNeighbors++;
                            }
                        }
                    });

                    let ax = 0;
                    let ay = 0;

                    if (neighbors > 0) {
                        // Cohesion
                        let avgX = flockX / neighbors;
                        let avgY = flockY / neighbors;
                        ax += (avgX - p.x) * 0.005;
                        ay += (avgY - p.y) * 0.005;

                        // Alignment
                        let avgVx = flockVx / neighbors;
                        let avgVy = flockVy / neighbors;
                        ax += (avgVx - p.vx) * 0.03;
                        ay += (avgVy - p.vy) * 0.03;
                    }

                    if (closeNeighbors > 0) {
                        // Separation
                        ax += avoidX * 0.05;
                        ay += avoidY * 0.05;
                    }

                    // Mouse interaction repulsion/avoidance
                    if (mouse.active) {
                        let mDx = p.x - mouse.x;
                        let mDy = p.y - mouse.y;
                        let mDist = Math.sqrt(mDx*mDx + mDy*mDy);
                        if (mDist < 220) {
                            let force = (220 - mDist) / 220;
                            let angle = Math.atan2(mDy, mDx);
                            ax += Math.cos(angle) * force * 1.5;
                            ay += Math.sin(angle) * force * 1.5;
                        }
                    }

                    // Default subtle wind/drift flow to keep motion alive
                    const ambientWindX = 0.04;
                    const ambientWindY = -0.04;
                    ax += ambientWindX;
                    ay += ambientWindY;

                    // Update velocities
                    p.vx += ax;
                    p.vy += ay;

                    // Bound boid velocity speed
                    let speed = Math.sqrt(p.vx*p.vx + p.vy*p.vy);
                    const minSpeed = 0.6;
                    const maxSpeed = 2.4;

                    if (speed > maxSpeed) {
                        p.vx = (p.vx / speed) * maxSpeed;
                        p.vy = (p.vy / speed) * maxSpeed;
                    } else if (speed < minSpeed) {
                        if (speed === 0) {
                            let theta = Math.random() * Math.PI * 2;
                            p.vx = Math.cos(theta) * minSpeed;
                            p.vy = Math.sin(theta) * minSpeed;
                        } else {
                            p.vx = (p.vx / speed) * minSpeed;
                            p.vy = (p.vy / speed) * minSpeed;
                        }
                    }

                    // Update positions
                    p.x += p.vx;
                    p.y += p.vy;

                    // Dynamic orientation - rotate to point in direction of velocity vector
                    p.angle = Math.atan2(p.vy, p.vx) * (180 / Math.PI) + 90;

                    // Boundaries wrapping checks with margins
                    const margin = p.r * 2 + 10;
                    if (p.x < -margin) {
                        p.x = width + margin;
                    } else if (p.x > width + margin) {
                        p.x = -margin;
                    }

                    if (p.y < -margin) {
                        p.y = height + margin;
                    } else if (p.y > height + margin) {
                        p.y = -margin;
                    }
                });

                requestAnimationFrame(animate);
            }

            animate();
        }
    });
</script>
</div>