<div 
    x-data="{ 
        drawerOpen: false, 
        checkoutMode: false, 
        theme: localStorage.getItem('nb_theme') || 'midnight', 
        accountPanelOpen: false, 
        chatOpen: false 
    }" 
    x-init="$watch('theme', val => { 
        localStorage.setItem('nb_theme', val); 
        document.documentElement.className = val; 
        document.documentElement.setAttribute('data-theme', val);
        const bgColors = {
            'midnight': '#09090B',
            'alabaster': '#F4F4F6',
            'floral': '#121A16',
            'love': '#1C0D12',
            'cute': '#24181B'
        };
        const textColors = {
            'midnight': '#FAFAFA',
            'alabaster': '#1C1C1E',
            'floral': '#EDF2EF',
            'love': '#FDF4F7',
            'cute': '#FFF5F7'
        };
        document.documentElement.style.backgroundColor = bgColors[val];
        document.documentElement.style.color = textColors[val];
    })"
    :class="{
        'bg-[#09090B] text-[#FAFAFA]': theme === 'midnight',
        'bg-[#F4F4F6] text-[#1C1C1E]': theme === 'alabaster',
        'bg-[#121A16] text-[#EDF2EF]': theme === 'floral',
        'bg-[#1C0D12] text-[#FDF4F7]': theme === 'love',
        'bg-[#24181B] text-[#FFF5F7]': theme === 'cute'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 pb-12 overflow-hidden"
>
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

    <!-- Ambient Background Blobs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Blob 1 -->
        <div 
            :class="{
                'bg-[#D4AF37]/4': theme === 'midnight' || theme === 'alabaster',
                'bg-emerald-500/4': theme === 'floral',
                'bg-rose-500/4': theme === 'love' || theme === 'cute'
            }"
            class="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full blur-[120px] animate-blob transition-colors duration-1000"
        ></div>
        <!-- Blob 2 -->
        <div 
            :class="{
                'bg-purple-500/4': theme === 'midnight',
                'bg-blue-400/4': theme === 'alabaster',
                'bg-teal-500/4': theme === 'floral',
                'bg-pink-500/4': theme === 'love' || theme === 'cute'
            }"
            class="absolute top-1/2 right-1/4 w-[600px] h-[600px] rounded-full blur-[140px] animate-blob animation-delay-2000 transition-colors duration-1000"
        ></div>
        <!-- Blob 3 -->
        <div 
            :class="{
                'bg-rose-500/3': theme === 'midnight' || theme === 'love' || theme === 'cute',
                'bg-[#D4AF37]/3': theme === 'alabaster' || theme === 'floral'
            }"
            class="absolute bottom-1/4 left-1/3 w-[450px] h-[450px] rounded-full blur-[100px] animate-blob animation-delay-4000 transition-colors duration-1000"
        ></div>
    </div>

    <!-- Fine Grain Noise Overlay -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-80"></div>
    <header 
        :class="{
            'border-neutral-900 bg-[#0F0F12]/80 shadow-2xl': theme === 'midnight',
            'border-neutral-200 bg-white/80 shadow-sm': theme === 'alabaster',
            'border-[#1B2921]/60 bg-[#16201B]/80 shadow-2xl': theme === 'floral',
            'border-[#3D1A27]/60 bg-[#28131B]/80 shadow-2xl': theme === 'love',
            'border-[#4A323A]/60 bg-[#322126]/80 shadow-2xl': theme === 'cute'
        }"
        class="fixed top-0 inset-x-0 h-20 backdrop-blur-xl border-b z-50 transition-all duration-500 flex items-center"
    >
        <div class="max-w-7xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <div class="shrink-0 flex items-baseline space-x-2">
                <span class="text-[9px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                <h1 :class="theme === 'alabaster' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.3em] transition-colors">NOIR & BLOOM</h1>
            </div>
            
            <div class="flex-1 max-w-sm hidden md:block">
                <div class="relative flex items-center">
                    <input 
                        type="text" 
                        wire:model.live.debounce.200ms="search"
                        placeholder="Search bouquets, stems, custom gifts..."
                        :class="theme === 'alabaster' ? 'bg-neutral-100 text-black placeholder-neutral-400 focus:bg-neutral-200/60' : 'bg-neutral-900/60 text-white placeholder-neutral-700 focus:bg-black'"
                        class="w-full border-none rounded-full px-5 py-2 text-xs font-light focus:outline-none transition-all shadow-inner"
                    >
                </div>
            </div>

            <div class="flex items-center space-x-6 text-[10px] font-mono uppercase tracking-widest text-neutral-400">
                <div class="flex items-center space-x-2 border-r border-neutral-500/10 pr-6 hidden lg:flex">
                    <button @click="theme = 'midnight'" title="Obsidian Luxury" :class="theme === 'midnight' ? 'ring-2 ring-white ring-offset-2 ring-offset-black' : ''" class="w-3.5 h-3.5 rounded-full bg-[#09090B] border border-neutral-800 cursor-pointer transition-all"></button>
                    <button @click="theme = 'alabaster'" title="Editorial Minimalist" :class="theme === 'alabaster' ? 'ring-2 ring-neutral-900 ring-offset-2 ring-offset-white' : ''" class="w-3.5 h-3.5 rounded-full bg-[#F4F4F6] border border-neutral-300 cursor-pointer transition-all"></button>
                    <button @click="theme = 'floral'" title="Naivasha Atelier" :class="theme === 'floral' ? 'ring-2 ring-emerald-400 ring-offset-2 ring-offset-black' : ''" class="w-3.5 h-3.5 rounded-full bg-[#121A16] border border-emerald-950 cursor-pointer transition-all"></button>
                    <button @click="theme = 'love'" title="Blush Romance" :class="theme === 'love' ? 'ring-2 ring-rose-500 ring-offset-2 ring-offset-black' : ''" class="w-3.5 h-3.5 rounded-full bg-[#28131B] border border-[#3D1A27] cursor-pointer transition-all"></button>
                    <button @click="theme = 'cute'" title="Petite Blossom" :class="theme === 'cute' ? 'ring-2 ring-pink-300 ring-offset-2 ring-offset-black' : ''" class="w-3.5 h-3.5 rounded-full bg-[#322126] border border-[#4A323A] cursor-pointer transition-all"></button>
                </div>

                <button @click="drawerOpen = true" class="hover:text-neutral-500 flex items-center space-x-2 cursor-pointer select-none">
                    <span>Cart</span>
                    <span :class="theme === 'alabaster' ? 'bg-neutral-200 text-black border-neutral-300' : 'bg-neutral-900 text-white border-neutral-800'" class="px-2 py-0.5 rounded-md text-[9px] font-sans border font-bold">{{ $cartCount }}</span>
                </button>

                <button @click="accountPanelOpen = true" class="hover:text-neutral-300 flex items-center space-x-1.5 cursor-pointer text-neutral-400 uppercase select-none">
                    <span>[ Workspace ]</span>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl w-full mx-auto px-6 pt-32 flex-1">
        <!-- Apple-Inspired Sub-Navigation Category Bar -->
        <div class="max-w-2xl mx-auto w-full mb-12" x-data="{ active: @entangle('selectedCategory') }">
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
                    <svg viewBox="0 0 24 24" class="w-6 h-6 stroke-current fill-none" stroke-width="1.5">
                        <path d="M12 12c2.5-3.5 1-7-1-8-2 1-3.5 4.5-1 8z" />
                        <path d="M7.5 10.5c1.5-3-1-6-2.5-6.5-1.5.5-2 3.5 0 6.5z" />
                        <path d="M16.5 10.5c-1.5-3 1-6 2.5-6.5 1.5.5 2 3.5 0 6.5z" />
                        <path d="M8 12c-2.5-1.5-4.5.5-5 2 .5 1.5 3 2 5-2z" />
                        <path d="M16 12c2.5-1.5 4.5.5 5 2-.5 1.5-3 2-5-2z" />
                        <path d="M12 12c-1.5 4.5.5 8 1 9" />
                        <path d="M12 12c1.5 4.5-.5 8-1 9" />
                        <path d="M9 13.5c1.5 2.5.5 5.5 0 7.5" />
                        <path d="M15 13.5c-1.5 2.5-.5 5.5 0 7.5" />
                        <path d="M10 16h4" />
                    </svg>
                    <span class="text-[10px] font-mono uppercase tracking-[0.2em] font-medium">Showroom</span>
                </button>
                <button @click="active = 'retail'; $wire.selectCategory('retail')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'retail' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 stroke-current fill-none" stroke-width="1.5">
                        <path d="M12 9V2M12 2l-2 2M12 2l2 2" />
                        <path d="M12 5.5c1.5-.5 3-1.5 3-3-1.5 0-2.5 1.5-3 3z" />
                        <path d="M12 5.5c-1.5-.5-3-1.5-3-3 1.5 0 2.5 1.5 3 3z" />
                        <path d="M9.5 9h5l1 2v4a3.5 3.5 0 0 1-7 0v-4l1-2z" />
                        <path d="M10 19h4v2h-4z" />
                    </svg>
                    <span class="text-[10px] font-mono uppercase tracking-[0.2em] font-medium">Bespoke</span>
                </button>
                <button @click="active = 'wholesale'; $wire.selectCategory('wholesale')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'wholesale' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 stroke-current fill-none" stroke-width="1.5">
                        <path d="M6 3l3 8M9 2l2 9M15 2l-2 9M18 3l-3 8" />
                        <path d="M8 11h8l-2 10H10L8 11z" />
                        <path d="M8 11l-2 4 4 6" />
                        <path d="M16 11l2 4-4 6" />
                        <path d="M7.5 15h9" />
                    </svg>
                    <span class="text-[10px] font-mono uppercase tracking-[0.2em] font-medium">Wholesale</span>
                </button>
                <button @click="active = 'gifting'; $wire.selectCategory('gifting')" class="flex flex-col items-center justify-center space-y-2 cursor-pointer transition-all duration-300" :class="active === 'gifting' ? 'text-[#D4AF37] scale-105' : 'text-neutral-500 hover:text-neutral-300'">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 stroke-current fill-none" stroke-width="1.5">
                        <rect x="4" y="8" width="16" height="12" rx="1" />
                        <rect x="3" y="5" width="18" height="3" rx="0.5" />
                        <path d="M12 5V20" />
                        <path d="M3 12h18" />
                        <path d="M12 5c-1.5-2.5-4.5-2.5-4.5 0s3 2.5 4.5 0z" />
                        <path d="M12 5c1.5-2.5 4.5-2.5 4.5 0s-3 2.5-4.5 0z" />
                    </svg>
                    <span class="text-[10px] font-mono uppercase tracking-[0.2em] font-medium">Gifting</span>
                </button>
            </div>
        </div>

        <div class="mb-16 flex flex-wrap gap-2 text-[9px] font-mono uppercase tracking-wider">
            <button wire:click="filterByOccasion(null)" class="px-4 py-1.5 rounded-full border border-neutral-500/10 cursor-pointer {{ is_null($selectedOccasion) ? 'bg-neutral-500/10 text-white' : 'text-neutral-500' }}">All Curation Moods</button>
            @foreach($occasions as $occasion)
                <button wire:click="filterByOccasion('{{ $occasion->slug }}')" class="px-4 py-1.5 rounded-full border border-neutral-500/10 cursor-pointer transition-all" style="{{ $selectedOccasion === $occasion->slug ? 'background-color: '.$occasion->accent_color.'; color: #fff; border-color: '.$occasion->accent_color.'; font-weight: bold;' : 'color: text-neutral-500;' }}">
                    {{ $occasion->name }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-12" wire:loading.class="opacity-40" wire:target="search, selectedCategory, filterByOccasion">
            @forelse($products as $product)
                <div class="flex flex-col space-y-3 group text-left">
                    <div :class="theme === 'alabaster' ? 'border-neutral-200 bg-white' : 'border-neutral-900 bg-neutral-900/10'" 
                         class="aspect-[4/5] border rounded-2xl relative overflow-hidden transition-all duration-500 shadow-sm hover:shadow-xl">
                        
                        <!-- Clean image by default, scales slightly on hover -->
                        <img src="{{ $product->backdrop_url }}" alt="{{ $product->name }}" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-700 z-0">
                        
                        <!-- Dark glassmorphic overlay on hover -->
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 flex flex-col justify-between p-5 text-white">
                            <!-- Top row on hover overlay -->
                            <div class="flex justify-between items-start">
                                <span class="bg-white/15 border border-white/10 text-neutral-200 px-2 py-0.5 rounded text-[8px] font-mono uppercase tracking-widest backdrop-blur-md">
                                    {{ $product->category }}
                                </span>
                                @if($product->grade)
                                    <span class="bg-[#D4AF37] text-black px-2 py-0.5 rounded text-[8px] font-mono font-bold tracking-wide uppercase">
                                        {{ $product->grade }}
                                    </span>
                                @endif
                            </div>

                            <!-- Bottom section on hover overlay -->
                            <div class="space-y-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-[9px] uppercase tracking-[0.2em] text-neutral-300 font-mono">Price</span>
                                    <span class="font-mono text-base font-semibold tracking-tight">{{ number_format($product->price) }} KSH</span>
                                </div>
                                <button 
                                    wire:click="addToCuration({{ $product->id }})"
                                    @click="drawerOpen = true; checkoutMode = false;"
                                    class="w-full bg-transparent border border-white hover:bg-white hover:text-black text-white text-[9px] font-semibold tracking-[0.2em] uppercase py-3 cursor-pointer transition-all duration-300 rounded-xl"
                                >
                                    Curate Selection
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Info below card -->
                    <div class="px-1 space-y-1.5">
                        <span class="text-[9px] uppercase tracking-[0.3em] text-neutral-400 font-mono block font-light">Noir & Bloom Atelier</span>
                        <h3 :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'" class="text-base font-serif italic tracking-wide leading-snug">
                            {{ $product->name }}
                        </h3>
                        <p class="text-neutral-500 font-light text-xs leading-relaxed line-clamp-2">
                            {{ $product->description ?? 'Premium luxury floral batch curation.' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center border border-dashed border-neutral-800 rounded-2xl">
                    <p class="text-xs font-light text-neutral-500 font-mono">No computational logs found matching this showroom segment filter query.</p>
                </div>
            @endforelse
        </div>

        <!-- Infinite Scroll Trigger -->
        @if(count($products) >= $perPage)
            <div x-intersect="$wire.loadMore()" class="col-span-full flex flex-col items-center justify-center py-16 space-y-4">
                <svg class="animate-spin h-8 w-8 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="2.5" class="fill-current" />
                    <!-- Petals -->
                    <path d="M12 9.5c0-1.5 1-3 2.5-3s2.5 1.5 2.5 3-1 3-2.5 3-2.5-1.5-2.5-3z" />
                    <path d="M12 14.5c0 1.5-1 3-2.5 3s-2.5-1.5-2.5-3 1-3 2.5-3 2.5 1.5 2.5 3z" />
                    <path d="M14.5 12c1.5 0 3 1 3 2.5s-1.5 2.5-3 2.5-3-1-3-2.5 1.5-3 3-3z" />
                    <path d="M9.5 12c-1.5 0-3-1-3-2.5s1.5-2.5 3-2.5 3 1 3 2.5-1.5 3-3 3z" />
                </svg>
                <span class="text-[10px] font-mono uppercase tracking-[0.25em] text-neutral-500">Unveiling More Curation...</span>
            </div>
        @endif
    </main>

    <!-- Luxury Atelier Footer -->
    <footer 
        :class="{
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'midnight',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'alabaster',
            'border-[#1B2921]/40 bg-[#0E1512] text-neutral-300': theme === 'floral',
            'border-[#3D1A27]/40 bg-[#160B0E] text-[#E5D5DA]': theme === 'love',
            'border-[#4A323A]/40 bg-[#1C1215] text-[#EADEE1]': theme === 'cute'
        }"
        class="border-t mt-32 py-16 px-6 transition-colors duration-500 z-10 relative"
    >
        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-left">
            <!-- Col 1: Brand & Coordinates -->
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[8px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'alabaster' ? 'text-black' : 'text-white'" class="text-xs font-semibold uppercase tracking-[0.3em] transition-colors">NOIR & BLOOM</h4>
                </div>
                <p class="text-[11px] font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
                <div class="space-y-1 text-[10px] font-mono text-neutral-500">
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
                <h5 :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[10px] font-mono uppercase tracking-[0.2em] font-semibold">The Showroom</h5>
                <ul class="space-y-2 text-[11px] font-light">
                    <li><button wire:click="selectCategory('retail')" class="hover:underline cursor-pointer">Bespoke Retail Arrays</button></li>
                    <li><button wire:click="selectCategory('wholesale')" class="hover:underline cursor-pointer">Wholesale Graded Stems</button></li>
                    <li><button wire:click="selectCategory('gifting')" class="hover:underline cursor-pointer">Luxury Giftings</button></li>
                    <li><button @click="accountPanelOpen = true" class="hover:underline cursor-pointer">Atelier Loyalty Circle</button></li>
                </ul>
            </div>

            <!-- Col 3: Hours & Support -->
            <div class="space-y-4">
                <h5 :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[10px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <ul class="space-y-2 text-[11px] font-light">
                    <li><span class="block text-neutral-500">Operating Hours</span> Mon &mdash; Sat: 07:00 &mdash; 20:00</li>
                    <li>Sunday: 09:00 &mdash; 17:00</li>
                    <li class="pt-2"><span class="block text-neutral-500 font-mono text-[9px] uppercase tracking-wider">Hotline Direct</span> +254 (0) 712 345 678</li>
                    <li>concierge@noirbloom.co.ke</li>
                </ul>
            </div>

            <!-- Col 4: Newsletter & Dispatch Bulletin -->
            <div class="space-y-4">
                <h5 :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-neutral-300'" class="text-[10px] font-mono uppercase tracking-[0.2em] font-semibold">The Atelier Bulletin</h5>
                <p class="text-[11px] font-light leading-relaxed">
                    Subscribe for seasonal curation updates, wholesale catalog changes, and exclusive releases.
                </p>
                <div class="flex items-center space-x-2 pt-1">
                    <input 
                        type="email" 
                        placeholder="you@company.co.ke" 
                        :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-black placeholder-neutral-400 focus:border-neutral-500' : 'bg-neutral-900/60 border-neutral-800 text-white placeholder-neutral-700 focus:border-neutral-700'"
                        class="flex-1 text-[11px] px-3.5 py-2.5 border rounded-sm focus:outline-none transition-all"
                    >
                    <button 
                        :class="theme === 'alabaster' ? 'bg-neutral-950 text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                        class="px-4 py-2.5 text-[9px] font-mono uppercase tracking-wider font-semibold rounded-sm transition-all"
                    >
                        Join
                    </button>
                </div>
            </div>
        </div>

        <div :class="theme === 'alabaster' ? 'border-neutral-200/60 text-neutral-500' : 'border-neutral-900 text-neutral-600'" class="max-w-7xl w-full mx-auto border-t mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-[10px] font-mono uppercase tracking-wider gap-4">
            <p>&copy; {{ date('Y') }} Noir &amp; Bloom Ltd. Registered Tax Entity.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-neutral-400">Terms of Curation</a>
                <a href="#" class="hover:text-neutral-400">Logistics Policy</a>
                <a href="#" class="hover:text-neutral-400">eTIMS Verification</a>
            </div>
        </div>
    </footer>

    <div x-show="accountPanelOpen" @click="accountPanelOpen = false" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50" style="display: none;"></div>
    <div 
        x-show="accountPanelOpen"
        x-transition:enter="transition transform ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-full sm:w-[600px] bg-[#0C0C0E] border-l border-neutral-900 shadow-2xl z-50 flex flex-col justify-between text-left p-6 text-neutral-200 h-full"
        style="display: none;"
    >
        @auth
            <div class="border-b border-neutral-900 pb-4 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-white">Atelier Member Workspace</h2>
                    <span class="text-[10px] text-neutral-500 font-mono">Loyalty Tier: {{ auth()->user()->loyalty_tier }}</span>
                </div>
                <button @click="accountPanelOpen = false" class="text-neutral-500 hover:text-white font-mono text-[10px] uppercase tracking-widest cursor-pointer">[ Close Workspace ]</button>
            </div>

            <div class="flex-1 overflow-y-auto my-6 space-y-6 scrollbar-none max-h-[calc(100vh-160px)] pr-1">
                {{-- Profile Info + Loyalty Stats --}}
                <div class="bg-neutral-900/30 border border-neutral-900 p-5 rounded-sm space-y-4">
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

                {{-- Real Order Logs --}}
                <div class="space-y-3">
                    <h3 class="text-[10px] uppercase font-mono tracking-wider text-neutral-400 font-bold">&bull; Historical Dispatch Logs Matrix</h3>
                    
                    @forelse ($userOrders as $order)
                        <div class="bg-neutral-900/40 border border-neutral-900 rounded p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3 text-xs">
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

                {{-- Sign Out Button --}}
                <div class="pt-6 border-t border-neutral-900">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-850 border border-neutral-850 hover:border-neutral-700 text-neutral-300 py-2.5 text-[10px] font-mono uppercase tracking-[0.2em] rounded-sm transition-all cursor-pointer">
                            [ Sign Out of Workspace ]
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="border-b border-neutral-900 pb-4 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-white">Atelier Member Workspace</h2>
                    <span class="text-[10px] text-neutral-500 font-mono">Authentication Required</span>
                </div>
                <button @click="accountPanelOpen = false" class="text-neutral-500 hover:text-white font-mono text-[10px] uppercase tracking-widest cursor-pointer">[ Close Workspace ]</button>
            </div>

            <div class="flex-1 flex flex-col justify-center space-y-6 py-12 text-center">
                <div class="space-y-2">
                    <span class="text-amber-500 font-serif text-3xl italic block">Atelier Loyalty Circle</span>
                    <p class="text-neutral-400 font-light text-xs max-w-sm mx-auto">
                        Authenticate your membership to earn points on each floral arrangement purchase (1 point per 100 KSH spent), unlock premium Noir pricing tiers, and manage corporate tax details.
                    </p>
                </div>

                <div class="flex flex-col space-y-3 max-w-xs mx-auto w-full pt-4">
                    <a href="{{ route('login') }}" class="bg-white text-black hover:bg-neutral-200 py-3 text-[10px] font-mono uppercase tracking-[0.25em] rounded-sm transition-all text-center">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="border border-neutral-800 hover:border-neutral-700 hover:bg-neutral-900/30 text-neutral-300 py-3 text-[10px] font-mono uppercase tracking-[0.25em] rounded-sm transition-all text-center">
                        Create Account
                    </a>
                </div>
            </div>
        @endauth

    </div>

    <div x-show="drawerOpen" @click="drawerOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50" style="display: none;"></div>
    <div 
        x-show="drawerOpen"
        x-transition:enter="transition transform ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        :class="theme === 'alabaster' ? 'bg-white border-neutral-200 text-neutral-900' : 'bg-[#0F0F12] border-neutral-900 text-white'"
        class="fixed inset-y-0 right-0 w-full sm:w-[480px] border-l shadow-2xl z-50 flex flex-col justify-between text-left transition-colors duration-500 h-full"
        style="display: none;"
    >
        <div :class="theme === 'alabaster' ? 'border-neutral-100' : 'border-neutral-900'" class="p-5 border-b flex items-center justify-between shrink-0">
            <div><h3 :class="theme === 'alabaster' ? 'text-neutral-800' : 'text-white'" class="text-xs uppercase tracking-[0.2em]">Selected Curations</h3><span class="text-[9px] text-neutral-500 font-light">Bespoke Arrangement Hub</span></div>
            <button @click="drawerOpen = false" class="text-neutral-500 hover:text-neutral-400 text-[10px] font-mono tracking-widest uppercase cursor-pointer">[ Close ]</button>
        </div>

        <div x-show="!checkoutMode && !@json($orderSubmitted)" class="flex-1 flex flex-col justify-between overflow-hidden">
            <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-220px)] scrollbar-none">
                @forelse($cartItems as $item)
                    <div :class="theme === 'alabaster' ? 'border-neutral-100' : 'border-neutral-900/60'" class="flex items-center justify-between space-x-4 border-b pb-4 text-xs">
                        <div class="flex-1 space-y-0.5">
                            <h4 :class="theme === 'alabaster' ? 'text-neutral-800' : 'text-white'" class="font-normal">{{ $item['product']->name }}</h4>
                            <p class="text-neutral-500 font-mono">{{ number_format($item['product']->price) }} KSH &bull; <span class="uppercase text-[10px]">Pack/{{ $item['product']->unit_type }}</span></p>
                        </div>
                        <div :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="flex items-center space-x-3 px-2.5 py-1.5 border rounded-sm">
                            <button wire:click="removeFromCuration({{ $item['product']->id }})" class="text-neutral-400 font-bold font-mono cursor-pointer select-none">-</button>
                            <span class="text-xs font-mono min-w-[15px] text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCuration({{ $item['product']->id }})" class="text-neutral-400 font-bold font-mono cursor-pointer select-none">+</button>
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
                <div :class="theme === 'alabaster' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0">
                    <div class="flex justify-between items-baseline text-xs font-light">
                        <span class="text-neutral-500 tracking-wider">Estimated Subtotal</span>
                        <span :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'" class="text-base font-mono font-semibold">{{ number_format($cartTotal) }} KSH</span>
                    </div>
                    <button @click="checkoutMode = true" class="w-full bg-black text-white dark:bg-white dark:text-black text-[10px] font-semibold tracking-[0.2em] uppercase py-4 hover:opacity-90 transition-all cursor-pointer rounded-sm">
                        Proceed to Logistics Spec
                    </button>
                </div>
            @endif
        </div>

        <div x-show="checkoutMode && !@json($orderSubmitted)" class="flex-1 flex flex-col justify-between overflow-hidden" style="display: none;">
            <form wire:submit.prevent="submitCurationRequest" class="flex-1 flex flex-col justify-between overflow-hidden">
                
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-210px)] scrollbar-none text-xs">
                    <div :class="theme === 'alabaster' ? 'border-neutral-100' : 'border-neutral-900'" class="flex items-center justify-between pb-2 border-b">
                        <span class="text-[11px] uppercase tracking-wider text-neutral-400">Atelier Delivery Profile</span>
                        <button type="button" @click="checkoutMode = false" class="text-neutral-500 hover:text-neutral-400 text-[10px] font-mono cursor-pointer">[ Back to Items ]</button>
                    </div>

                    <div :class="theme === 'alabaster' ? 'bg-neutral-100 border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="p-1 border rounded-full grid grid-cols-2 text-center text-[9px] font-mono uppercase tracking-wider shadow-inner">
                        <button type="button" @click="$wire.set('checkoutType', 'standard')" :class="$wire.checkoutType === 'standard' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">Personal Delivery</button>
                        <button type="button" @click="$wire.set('checkoutType', 'corporate')" :class="$wire.checkoutType === 'corporate' ? 'bg-black text-white dark:bg-white dark:text-black font-bold' : 'text-neutral-500'" class="py-1.5 rounded-full cursor-pointer transition-all">Corporate eTIMS</button>
                    </div>

                    <div :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="p-4 border rounded-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <span :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'" class="text-xs font-normal block">Send this order as a luxury gift delivery?</span>
                                <span class="text-[10px] text-neutral-500 font-light block mt-0.5">Recipient delivery parameters will cleanly isolate away from receipt parameters.</span>
                            </div>
                            <input type="checkbox" wire:model.live="is_gift" class="w-3.5 h-3.5 rounded text-black border-neutral-800 focus:ring-0 cursor-pointer">
                        </div>
                    </div>

                    <div x-show="$wire.is_gift" class="space-y-4 border border-dashed border-neutral-800 p-4 rounded-sm bg-black/5" style="display: none;" x-transition>
                        <span class="text-[9px] font-mono uppercase text-amber-500 tracking-wider block font-bold">&bull; Recipient Delivery Profile</span>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-neutral-500">Recipient Full Name *</label>
                            <input type="text" placeholder="Enter full recipient name" wire:model="recipient_name" :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-neutral-500">Recipient Contact Line *</label>
                            <input type="text" placeholder="e.g. 0712345678 (Required for courier logistics)" wire:model="recipient_phone" :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                        </div>
                    </div>

                    <div x-show="!$wire.is_gift" x-transition>
                        <div :class="theme === 'alabaster' ? 'bg-neutral-100 text-black border-neutral-200' : 'bg-neutral-900/60 text-neutral-400 border-neutral-900'" class="p-3.5 border rounded text-[11px] font-light space-y-1.5">
                            <span class="text-[8px] font-mono uppercase tracking-wider text-neutral-500 block pb-1 border-b border-neutral-500/10">Pre-authenticated Customer Ledger Record</span>
                            <div><span class="text-neutral-500">Contact Payer:</span> <span class="font-semibold text-neutral-300 dark:text-white">{{ $full_name }}</span></div>
                            <div><span class="text-neutral-500">Secure Comm:</span> <span class="font-mono">{{ $phone }} &bull; {{ $email }}</span></div>
                            @if($checkoutType === 'corporate')
                                <div><span class="text-neutral-500 font-mono">eTIMS KRA PIN:</span> <span class="font-mono text-amber-500 font-semibold uppercase">{{ $kra_pin }}</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-wider text-neutral-500 block">Presentation Customization Packages (Pure delivery is free of added cost)</label>
                        <div class="grid grid-cols-1 gap-2">
                            <button type="button" wire:click="$set('delivery_type', 'standard')" :class="delivery_type === 'standard' ? 'border-black dark:border-white bg-neutral-500/5 font-medium shadow-sm' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-sm text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">Standard Courier Dispatch</span><span class="text-[10px] text-neutral-500 block mt-0.5">Premium transport routing directly to your destination building coordinates.</span></div>
                                <span class="font-mono text-[11px]">+ 0 KSH</span>
                            </button>
                            <button type="button" wire:click="$set('delivery_type', 'secret')" :class="delivery_type === 'secret' ? 'border-amber-600 bg-amber-950/10 text-amber-500 font-medium' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-sm text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">The Secret Admirer Protocol</span><span class="text-[10px] text-neutral-500 block mt-0.5">We will fully conceal your sender profile parameters behind wax-sealed card enclosures.</span></div>
                                <span class="font-mono text-[11px] text-amber-500 font-semibold">+ 500 KSH</span>
                            </button>
                            <button type="button" wire:click="$set('delivery_type', 'concierge')" :class="delivery_type === 'concierge' ? 'border-emerald-600 bg-emerald-950/10 text-emerald-500 font-medium' : 'border-neutral-800 text-neutral-400'" class="p-3 border rounded-sm text-left flex justify-between items-center transition-all cursor-pointer">
                                <div><span class="block">Uniformed Concierge Presentation</span><span class="text-[10px] text-neutral-500 block mt-0.5">Hand-delivered via sharp, uniformed corporate couriers. Elite tier presentation statement.</span></div>
                                <span class="font-mono text-[11px] text-emerald-500 font-semibold">+ 1,500 KSH</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-neutral-500">Distribution Node *</label>
                            <select wire:model="region" :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded px-2.5 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                                <option value="Nairobi">Nairobi Metropolitan</option>
                                <option value="Kiambu">Kiambu Ridge Hub</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-neutral-500">Landmarks Address *</label>
                            <input type="text" list="premium-address-nodes" placeholder="Type complex, street, or estate..." wire:model="delivery_address" :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A] border-neutral-900 text-white'" class="w-full border rounded px-3 py-1.5 focus:outline-none focus:border-neutral-400 font-light">
                            <datalist id="premium-address-nodes">
                                @foreach($addressSuggestions as $node) <option value="{{ $node }}"></option> @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>

                <div :class="theme === 'alabaster' ? 'border-neutral-200 bg-neutral-50/60' : 'border-neutral-900 bg-black/40'" class="p-5 border-t space-y-4 shrink-0 text-xs">
                    <div class="space-y-1.5 text-neutral-500">
                        <div class="flex justify-between"><span>Showroom Subtotal:</span><span class="font-mono text-neutral-400">{{ number_format($cartTotal) }} KSH</span></div>
                        @if($service_fee > 0)
                            <div class="flex justify-between text-amber-500 font-mono"><span>Presentation Upsell Pack Fee:</span><span>+ {{ number_format($service_fee) }} KSH</span></div>
                        @endif
                        <div class="flex justify-between items-baseline text-sm font-normal pt-2 border-t border-neutral-500/10">
                            <span class="text-neutral-400">Grand Dispatch Total:</span>
                            <span :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'" class="text-md font-mono font-bold tracking-tight">{{ number_format($cartTotal + $service_fee) }} KSH</span>
                        </div>
                    </div>
                    
                    <button 
                        type="submit" wire:loading.attr="disabled" wire:target="submitCurationRequest"
                        class="w-full bg-black text-white dark:bg-white dark:text-black text-[10px] font-semibold tracking-[0.2em] uppercase py-4 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer flex items-center justify-center space-x-2 rounded-sm"
                    >
                        <span wire:loading wire:target="submitCurationRequest" class="animate-spin rounded-full h-2.5 w-2.5 border border-neutral-400 border-t-transparent inline-block"></span>
                        <span wire:loading.remove wire:target="submitCurationRequest">Request Atelier Dispatch</span>
                    </button>
                </div>
            </form>
        </div>

        @if($orderSubmitted)
            <div class="flex-1 flex flex-col justify-between overflow-hidden shrink-0 h-full">
                <div class="flex-1 overflow-y-auto p-5 space-y-5 max-h-[calc(100vh-200px)] scrollbar-none w-full text-xs">
                    <div class="space-y-6 max-w-sm mx-auto w-full pt-4">
                        <div class="w-10 h-10 rounded-full border border-neutral-800 flex items-center justify-center bg-neutral-900/50 mx-auto"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_#10B981]" :class="mpesaProcessing ? 'animate-ping' : ''"></span></div>
                        <div class="space-y-1 text-center">
                            <h4 :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'" class="text-xs uppercase tracking-[0.2em] font-medium">Dispatch Mapped</h4>
                            <p class="text-[11px] text-neutral-500 font-light leading-relaxed">Your curation specs are locked. Dispatch Safaricom API prompts down below.</p>
                        </div>

                        <div :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0A0A0A]/80 border-neutral-900 text-neutral-300'" class="p-4 border rounded text-[11px] font-light space-y-1.5">
                            <span class="text-[8px] font-mono uppercase tracking-wider text-neutral-400 block pb-1 border-b border-neutral-500/10">Authorized Billing Context Receipt</span>
                            <div><span class="text-neutral-500">Payer Name:</span> <span class="font-medium">{{ $full_name }}</span></div>
                            @if($is_gift)
                                <div><span class="text-amber-500 font-medium">Gift Delivery For:</span> <span class="text-amber-500 font-medium">{{ $recipient_name }} ({{ $recipient_phone }})</span></div>
                            @endif
                            <div><span class="text-neutral-500">Destination Anchor:</span> <span>{{ $delivery_address }}, Node/{{ $region }}</span></div>
                            <div><span class="text-neutral-500 font-mono">Grand Remittance Total:</span> <span class="font-mono text-emerald-500 font-semibold">{{ number_format($cartTotal + $service_fee) }} KSH</span></div>
                        </div>

                        <div :class="theme === 'alabaster' ? 'bg-white border-neutral-200' : 'bg-[#0A0A0A] border-neutral-900'" class="space-y-3 p-4 border rounded-sm shadow-2xl" x-data="{ mpesaProcessing: false }">
                            <div class="space-y-1" x-show="!mpesaProcessing">
                                <label class="text-[8px] uppercase tracking-wider text-neutral-500 font-mono">Safaricom Authorization Line</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-xs font-mono text-neutral-600">+254</span>
                                    <input type="tel" wire:model="phone" placeholder="712345678" :class="theme === 'alabaster' ? 'bg-neutral-50 border-neutral-200 text-black' : 'bg-[#0F0F0F] border-neutral-800 text-white'" class="w-full border rounded pl-14 pr-3 py-1.5 text-xs font-mono focus:outline-none focus:border-neutral-400">
                                </div>
                            </div>

                            @if($mpesaErrorMessage)
                                <div class="p-2 border border-dashed border-rose-900 bg-rose-950/20 text-[10px] font-mono text-rose-400 rounded-sm">{{ $mpesaErrorMessage }}</div>
                            @endif

                            <button type="button" wire:click="initiateMpesaPayment" wire:loading.attr="disabled" @click="if(@js(!$errors->has('phone'))) mpesaProcessing = true" class="w-full bg-[#10B981] hover:bg-emerald-600 text-white text-[9px] font-bold tracking-[0.2em] uppercase py-3 transition-colors cursor-pointer rounded-sm flex items-center justify-center space-x-2">
                                <span wire:loading wire:target="initiateMpesaPayment" class="animate-spin rounded-full h-3 w-3 border border-white border-t-transparent inline-block"></span>
                                <span wire:loading.remove wire:target="initiateMpesaPayment">Authorize STK Push</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center p-4 border-t border-neutral-500/5 shrink-0 bg-black/10">
                    <button @click="drawerOpen = false; checkoutMode = false;" wire:click="returnToCollections" class="text-neutral-500 hover:text-neutral-400 text-[10px] font-mono tracking-widest uppercase cursor-pointer">[ Clear & Return to Showroom ]</button>
                </div>
            </div>
        @endif
    </div>

    <div x-show="!drawerOpen" x-transition class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans">
        <button @click="chatOpen = !chatOpen" class="px-5 h-11 rounded-full bg-black text-white border border-neutral-800 shadow-2xl flex items-center space-x-3 text-[10px] uppercase font-mono tracking-[0.2em] cursor-pointer hover:bg-neutral-900 transition-all duration-300">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
            <span x-show="!chatOpen">Aura Curation Companion</span>
            <span x-show="chatOpen" style="display: none;">[ Close Dialogue ]</span>
        </button>
 
        <div x-show="chatOpen" style="display: none;" x-transition class="mt-3 w-80 md:w-96 h-[420px] bg-[#0F0F0F] border border-neutral-800 rounded shadow-2xl flex flex-col justify-between overflow-hidden">
            <div class="p-4 border-b border-neutral-800 bg-[#0A0A0A] flex items-center justify-between text-left">
                <div><span class="text-[10px] uppercase font-mono text-neutral-300 tracking-wider font-semibold">Aura Concierge AI</span><span class="block text-[9px] text-emerald-400 font-mono mt-0.5">&bull; Active Curation Companion</span></div>
            </div>
            <div class="flex-1 p-4 overflow-y-auto space-y-4 text-[11px] font-light scrollbar-none flex flex-col text-left">
                @foreach($chatHistory as $msg)
                    <div class="max-w-[85%] rounded px-3 py-2.5 leading-relaxed {{ $msg['sender'] === 'bot' ? 'bg-neutral-900 text-neutral-300 self-start border border-neutral-800/40' : 'bg-white text-black font-normal self-end shadow-md' }}">{{ $msg['text'] }}</div>
                @endforeach
            </div>
            <form wire:submit.prevent="sendChatMessage" class="p-3 border-t border-neutral-800 bg-[#0A0A0A] flex items-center gap-2">
                <input type="text" wire:model="chatMessage" placeholder="Ask Aura about arrangements, branches, points..." class="flex-1 bg-[#141414] border border-neutral-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-neutral-700 font-light">
                <button type="submit" class="bg-white text-black font-mono text-[9px] uppercase font-bold px-3 py-2 rounded-sm cursor-pointer hover:bg-neutral-200 transition-colors">Ask</button>
            </form>
        </div>
    </div>
</div>