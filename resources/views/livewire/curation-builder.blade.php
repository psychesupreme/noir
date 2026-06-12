<div 
    x-data="{
        theme: '{{ $theme }}',
        activeCategory: 'stems',
        selectedVaseId: @entangle('selectedVaseId'),
        selectedStems: @entangle('selectedStems'),
        size: @entangle('size'),
        subtotal: @entangle('subtotal'),
        selectedWrappingId: @entangle('selectedWrappingId'),
        selectedWineId: @entangle('selectedWineId'),
        selectedGiftId: @entangle('selectedGiftId'),
        selectedFragranceId: @entangle('selectedFragranceId'),
        orbitActive: true,
        vasesList: {{ json_encode($availableVases) }},
        toggleOrbit() {
            this.orbitActive = !this.orbitActive;
            this.$dispatch('toggle-orbit', this.orbitActive);
        }
    }"
    x-init="
        theme = localStorage.getItem('nb_theme') || 'onyx';
        $watch('theme', val => {
            localStorage.setItem('nb_theme', val);
            document.documentElement.className = val;
            document.documentElement.setAttribute('data-theme', val);
            $dispatch('theme-changed', val);
        });
        $watch('selectedWrappingId', val => { $dispatch('wrapping-changed', val); });
        $watch('selectedWineId', val => { $dispatch('wine-changed', val); });
        $watch('selectedGiftId', val => { $dispatch('gift-changed', val); });
        $watch('selectedFragranceId', val => { $dispatch('fragrance-changed', val); });
    "
    :class="{
        'bg-[#050507] text-[#F4F4F5]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne',
        'bg-[#15060A] text-[#FCE7EC]': theme === 'rose'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-hidden"
>
    <!-- Script definitions defined BEFORE elements to prevent Alpine ReferenceErrors -->


    <!-- Background grid/ambient overlays -->
    <canvas id="flower-ambient-canvas" wire:ignore x-data="canvasAmbient" class="fixed inset-0 pointer-events-none z-0 opacity-40"></canvas>
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-40"></div>

    <!-- Luxury Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne',
            'bg-[#15060A]/75 border-[#2D0D19]/30 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center justify-between px-6 shadow-lg group"
    >
        <!-- Brand Link -->
        <a href="{{ route('storefront') }}" class="flex items-center space-x-3 group/brand">
            <span class="font-serif italic text-xl tracking-wide text-[#C5A880] group-hover/brand:text-white transition-colors duration-300">
                Noir & Bloom
            </span>
        </a>

        <!-- Title -->
        <span class="hidden md:inline font-sans uppercase tracking-[0.3em] text-[10px] text-neutral-400">
            ✦ 3D Custom Curation Desk ✦
        </span>

        <!-- Right navigation items -->
        <div class="flex items-center space-x-6">
            <!-- Theme toggle -->
            <div class="flex items-center space-x-1.5 bg-[#0F0F12]/80 border border-neutral-800/60 p-1 rounded-full text-[10px] font-mono">
                <button @click="theme = 'onyx'" :class="theme === 'onyx' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Onyx</button>
                <button @click="theme = 'champagne'" :class="theme === 'champagne' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Champagne</button>
                <button @click="theme = 'rose'" :class="theme === 'rose' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Rose</button>
            </div>

            <!-- Back to Showroom -->
            <a href="{{ route('storefront') }}" class="text-xs uppercase tracking-widest text-[#C5A880] hover:text-white transition-colors duration-300 font-medium">
                Showroom
            </a>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 pt-24 pb-6 px-4 md:px-6 z-10 flex flex-col lg:flex-row gap-6 max-w-8xl mx-auto w-full h-[calc(100vh-120px)] overflow-hidden">
        
        <!-- Left Panel: Product Catalog & Addons -->
        <div 
            :class="{
                'bg-[#09090D]/90 border-neutral-900': theme === 'onyx',
                'bg-white/95 border-neutral-200 shadow-xl': theme === 'champagne',
                'bg-[#1A080E]/90 border-[#3D1222]': theme === 'rose'
            }"
            class="w-full lg:w-96 rounded-3xl border p-5 flex flex-col justify-between backdrop-blur-md overflow-hidden shrink-0"
                  <!-- Category Tabs (Expanded to Stems, Vases, Wraps, Addons, Studio) -->
            <div class="grid grid-cols-5 border-b border-neutral-800/40 pb-3 mb-4 shrink-0 text-[9px] font-bold uppercase tracking-wider gap-0.5">
                <button 
                    @click="activeCategory = 'stems'" 
                    :class="activeCategory === 'stems' ? 'border-[#C5A880] text-[#C5A880]' : 'border-transparent text-neutral-400 hover:text-neutral-200'"
                    class="text-center py-2 border-b-2 transition-all cursor-pointer"
                >
                    Stems
                </button>
                <button 
                    @click="activeCategory = 'vases'" 
                    :class="activeCategory === 'vases' ? 'border-[#C5A880] text-[#C5A880]' : 'border-transparent text-neutral-400 hover:text-neutral-200'"
                    class="text-center py-2 border-b-2 transition-all cursor-pointer"
                >
                    Vases
                </button>
                <button 
                    @click="activeCategory = 'wraps'" 
                    :class="activeCategory === 'wraps' ? 'border-[#C5A880] text-[#C5A880]' : 'border-transparent text-neutral-400 hover:text-neutral-200'"
                    class="text-center py-2 border-b-2 transition-all cursor-pointer"
                >
                    Wraps
                </button>
                <button 
                    @click="activeCategory = 'addons'" 
                    :class="activeCategory === 'addons' ? 'border-[#C5A880] text-[#C5A880]' : 'border-transparent text-neutral-400 hover:text-neutral-200'"
                    class="text-center py-2 border-b-2 transition-all cursor-pointer"
                >
                    Addons
                </button>
                <button 
                    @click="activeCategory = 'studio'" 
                    :class="activeCategory === 'studio' ? 'border-[#C5A880] text-[#C5A880]' : 'border-transparent text-neutral-400 hover:text-neutral-200'"
                    class="text-center py-2 border-b-2 transition-all cursor-pointer"
                >
                    Studio
                </button>
            </div>

            <!-- Scrollable Catalog list -->
            <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                
                <!-- Stems Grid -->
                <div x-show="activeCategory === 'stems'" class="grid grid-cols-2 gap-3">
                    @foreach($availableStems as $stem)
                        <div 
                            :class="(selectedStems[{{ $stem->id }}] ?? 0) > 0 
                                ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.15)] bg-[#C5A880]/5' 
                                : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                            class="flex flex-col rounded-2xl border p-2.5 transition-all group justify-between relative"
                        >
                            <div class="relative w-full aspect-square overflow-hidden rounded-xl mb-2 shrink-0">
                                <img src="{{ $stem->image_url }}" alt="{{ $stem->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($stem->stock < 10)
                                    <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-red-950/80 text-red-400 border border-red-900/30 rounded text-[7px] font-mono uppercase tracking-wider">Low Stock</span>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $stem->name }}">{{ $stem->name }}</h4>
                                    <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($stem->price) }} KSH</p>
                                </div>

                                <!-- Quantity Selector -->
                                <div class="flex items-center justify-between mt-3 bg-black/30 rounded-lg p-1">
                                    <button 
                                        wire:click="removeStem({{ $stem->id }})" 
                                        class="w-5 h-5 rounded-md flex items-center justify-center border border-neutral-800 text-neutral-400 hover:text-white hover:bg-neutral-900 font-mono text-[10px] cursor-pointer select-none"
                                    >
                                        -
                                    </button>
                                    <span class="text-[10px] font-mono w-4 text-center text-neutral-200">{{ $selectedStems[$stem->id] ?? 0 }}</span>
                                    <button 
                                        wire:click="addStem({{ $stem->id }})" 
                                        class="w-5 h-5 rounded-md flex items-center justify-center border border-neutral-800 text-neutral-400 hover:text-white hover:bg-neutral-900 font-mono text-[10px] cursor-pointer select-none"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vases Grid -->
                <div x-show="activeCategory === 'vases'" class="grid grid-cols-2 gap-3">
                    @foreach($availableVases as $vase)
                        <div 
                            @click="$wire.selectVase({{ $vase->id }})"
                            :class="selectedVaseId == {{ $vase->id }} 
                                ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                            class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                        >
                            <div class="relative w-full aspect-square overflow-hidden rounded-xl mb-2 shrink-0">
                                <img src="{{ $vase->image_url }}" alt="{{ $vase->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $vase->name }}">{{ $vase->name }}</h4>
                                    <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($vase->price) }} KSH</p>
                                </div>

                                <div class="flex justify-between items-center mt-2.5">
                                    <span class="text-[8px] text-neutral-500 font-mono uppercase">Vase Base</span>
                                    <div :class="selectedVaseId == {{ $vase->id }} ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                        <template x-if="selectedVaseId == {{ $vase->id }}">
                                            <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Wrappings Grid -->
                <div x-show="activeCategory === 'wraps'" class="grid grid-cols-2 gap-3">
                    <!-- Option for No Wrap -->
                    <div 
                        @click="$wire.selectWrapping(null)"
                        :class="selectedWrappingId === null 
                            ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                            : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                        class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                    >
                        <div class="w-full aspect-square bg-neutral-500/5 rounded-xl flex items-center justify-center text-[10px] font-mono text-neutral-500 mb-2 shrink-0">None</div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors">No Wrap</h4>
                                <p class="text-[9px] text-neutral-500 mt-0.5">Bare presentation</p>
                            </div>
                            <div class="flex justify-between items-center mt-2.5">
                                <span class="text-[8px] text-neutral-500 font-mono uppercase">Free</span>
                                <div :class="selectedWrappingId === null ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                    <template x-if="selectedWrappingId === null">
                                        <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($availableWrappings as $wrap)
                        <div 
                            @click="$wire.selectWrapping({{ $wrap->id }})"
                            :class="selectedWrappingId == {{ $wrap->id }} 
                                ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                            class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                        >
                            <div class="relative w-full aspect-square overflow-hidden rounded-xl mb-2 shrink-0">
                                <img src="{{ $wrap->image_url }}" alt="{{ $wrap->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $wrap->name }}">{{ $wrap->name }}</h4>
                                    <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($wrap->price) }} KSH</p>
                                </div>

                                <div class="flex justify-between items-center mt-2.5">
                                    <span class="text-[8px] text-neutral-500 font-mono uppercase">Wrapping</span>
                                    <div :class="selectedWrappingId == {{ $wrap->id }} ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                        <template x-if="selectedWrappingId == {{ $wrap->id }}">
                                            <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Addons Grid (Wine, Gifts, Fragrances) -->
                <div x-show="activeCategory === 'addons'" class="space-y-6">
                    
                    <!-- Wine Sub-Section -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] uppercase tracking-widest font-mono text-[#C5A880] font-bold">1. Wines & Champagne</h4>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- None Option -->
                            <div 
                                @click="$wire.selectWine(null)"
                                :class="selectedWineId === null 
                                    ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                    : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                            >
                                <div class="w-full aspect-video bg-neutral-500/5 rounded-xl flex items-center justify-center text-[10px] font-mono text-neutral-500 mb-2 shrink-0">None</div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors">No Wine</h4>
                                        <p class="text-[9px] text-neutral-500 mt-0.5">Bare bouquet</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2.5">
                                        <span class="text-[8px] text-neutral-500 font-mono uppercase">Free</span>
                                        <div :class="selectedWineId === null ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                            <template x-if="selectedWineId === null">
                                                <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($availableWines as $wine)
                                <div 
                                    @click="$wire.selectWine({{ $wine->id }})"
                                    :class="selectedWineId == {{ $wine->id }} 
                                        ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                        : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                    class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                                >
                                    <div class="w-full aspect-video overflow-hidden rounded-xl mb-2 shrink-0">
                                        <img src="{{ $wine->image_url }}" alt="{{ $wine->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $wine->name }}">{{ $wine->name }}</h4>
                                            <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($wine->price) }} KSH</p>
                                        </div>

                                        <div class="flex justify-between items-center mt-2.5">
                                            <span class="text-[8px] text-neutral-500 font-mono uppercase">Wine</span>
                                            <div :class="selectedWineId == {{ $wine->id }} ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                                <template x-if="selectedWineId == {{ $wine->id }}">
                                                    <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Gifts Sub-Section -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] uppercase tracking-widest font-mono text-[#C5A880] font-bold">2. Luxury Gifts</h4>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- None Option -->
                            <div 
                                @click="$wire.selectGift(null)"
                                :class="selectedGiftId === null 
                                    ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                    : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                            >
                                <div class="w-full aspect-video bg-neutral-500/5 rounded-xl flex items-center justify-center text-[10px] font-mono text-neutral-500 mb-2 shrink-0">None</div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors">No Gift</h4>
                                        <p class="text-[9px] text-neutral-500 mt-0.5">Bare bouquet</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2.5">
                                        <span class="text-[8px] text-neutral-500 font-mono uppercase">Free</span>
                                        <div :class="selectedGiftId === null ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                            <template x-if="selectedGiftId === null">
                                                <svg class="w-2.5 h-2.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($availableGifts as $gift)
                                <div 
                                    @click="$wire.selectGift({{ $gift->id }})"
                                    :class="selectedGiftId == {{ $gift->id }} 
                                        ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                        : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                    class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                                >
                                    <div class="w-full aspect-video overflow-hidden rounded-xl mb-2 shrink-0">
                                        <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $gift->name }}">{{ $gift->name }}</h4>
                                            <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($gift->price) }} KSH</p>
                                        </div>

                                        <div class="flex justify-between items-center mt-2.5">
                                            <span class="text-[8px] text-neutral-500 font-mono uppercase">Gift</span>
                                            <div :class="selectedGiftId == {{ $gift->id }} ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                                <template x-if="selectedGiftId == {{ $gift->id }}">
                                                    <svg class="w-2.5 h-2.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fragrances Sub-Section -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] uppercase tracking-widest font-mono text-[#C5A880] font-bold">3. Atelier Floral Mists</h4>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- None Option -->
                            <div 
                                @click="$wire.selectFragrance(null)"
                                :class="selectedFragranceId === null 
                                    ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                    : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                            >
                                <div class="w-full aspect-video bg-neutral-500/5 rounded-xl flex items-center justify-center text-[10px] font-mono text-neutral-500 mb-2 shrink-0">None</div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors">No Mist</h4>
                                        <p class="text-[9px] text-neutral-550 mt-0.5">Bare bouquet</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2.5">
                                        <span class="text-[8px] text-neutral-500 font-mono uppercase">Free</span>
                                        <div :class="selectedFragranceId === null ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                            <template x-if="selectedFragranceId === null">
                                                <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($availableFragrances as $frag)
                                <div 
                                    @click="$wire.selectFragrance({{ $frag->id }})"
                                    :class="selectedFragranceId == {{ $frag->id }} 
                                        ? 'border-[#C5A880] shadow-[0_0_12px_rgba(197,168,128,0.2)] bg-[#C5A880]/5' 
                                        : (theme === 'champagne' ? 'bg-neutral-50/50 hover:bg-neutral-100/60 border-neutral-200' : 'bg-black/20 hover:bg-black/40 border-neutral-800/30')"
                                    class="flex flex-col rounded-2xl border p-2.5 transition-all cursor-pointer group justify-between"
                                >
                                    <div class="w-full aspect-video overflow-hidden rounded-xl mb-2 shrink-0">
                                        <img src="{{ $frag->image_url }}" alt="{{ $frag->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-[11px] font-semibold truncate group-hover:text-[#C5A880] transition-colors" title="{{ $frag->name }}">{{ $frag->name }}</h4>
                                            <p class="text-[9px] text-neutral-550 font-mono mt-0.5">{{ number_format($frag->price) }} KSH</p>
                                        </div>

                                        <div class="flex justify-between items-center mt-2.5">
                                            <span class="text-[8px] text-neutral-500 font-mono uppercase">Mist</span>
                                            <div :class="selectedFragranceId == {{ $frag->id }} ? 'bg-[#C5A880]' : 'border border-neutral-700'" class="w-3.5 h-3.5 rounded-full flex items-center justify-center">
                                                <template x-if="selectedFragranceId == {{ $frag->id }}">
                                                    <svg class="w-2 h-2 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Studio/Atmosphere Tab content -->
                <div x-show="activeCategory === 'studio'" class="space-y-5">
                    <div class="space-y-2.5">
                        <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-400 block font-bold">Studio Lighting Themes</label>
                        <div class="grid grid-cols-1 gap-2">
                            <button 
                                @click="theme = 'onyx'"
                                :class="theme === 'onyx' ? 'border-[#C5A880] bg-[#C5A880]/10 text-white font-semibold' : 'border-neutral-800/30 text-neutral-400 hover:text-neutral-200 bg-black/10'" 
                                class="flex items-center justify-between p-3.5 rounded-2xl border text-xs cursor-pointer transition-all"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.5)]"></span>
                                    Onyx Dark Room
                                </span>
                                <span class="text-[9px] font-mono uppercase text-neutral-500">Amber Glow</span>
                            </button>
                            <button 
                                @click="theme = 'champagne'"
                                :class="theme === 'champagne' ? 'border-[#C5A880] bg-[#C5A880]/10 text-neutral-900 font-semibold' : 'border-neutral-800/30 text-neutral-400 hover:text-neutral-200 bg-black/10'" 
                                class="flex items-center justify-between p-3.5 rounded-2xl border text-xs cursor-pointer transition-all"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-100 shadow-[0_0_8px_rgba(254,249,195,0.5)]"></span>
                                    Champagne Atelier
                                </span>
                                <span class="text-[9px] font-mono uppercase text-neutral-500">Bright Luxury</span>
                            </button>
                            <button 
                                @click="theme = 'rose'"
                                :class="theme === 'rose' ? 'border-[#C5A880] bg-[#C5A880]/10 text-white font-semibold' : 'border-neutral-800/30 text-neutral-400 hover:text-neutral-200 bg-black/10'" 
                                class="flex items-center justify-between p-3.5 rounded-2xl border text-xs cursor-pointer transition-all"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-pink-400 shadow-[0_0_8px_rgba(244,114,182,0.5)]"></span>
                                    Blushing Rose Salon
                                </span>
                                <span class="text-[9px] font-mono uppercase text-neutral-500">Rose Glow</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2.5 pt-4 border-t border-neutral-800/40">
                        <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-400 block font-bold">Cinematic Viewpoints</label>
                        <div class="grid grid-cols-2 gap-2 text-[10px] font-mono">
                            <button @click="$dispatch('camera-preset', 'front')" class="p-3 rounded-2xl border border-neutral-800/30 bg-black/15 text-neutral-350 hover:text-white hover:border-[#C5A880]/70 hover:bg-neutral-800/30 transition text-left cursor-pointer select-none">
                                🎬 Front View
                            </button>
                            <button @click="$dispatch('camera-preset', 'birds_eye')" class="p-3 rounded-2xl border border-neutral-800/30 bg-black/15 text-neutral-350 hover:text-white hover:border-[#C5A880]/70 hover:bg-neutral-800/30 transition text-left cursor-pointer select-none">
                                👁️ Bird's Eye
                            </button>
                            <button @click="$dispatch('camera-preset', 'macro')" class="p-3 rounded-2xl border border-neutral-800/30 bg-black/15 text-neutral-350 hover:text-white hover:border-[#C5A880]/70 hover:bg-neutral-800/30 transition text-left cursor-pointer select-none">
                                🔍 Macro Zoom
                            </button>
                            <button @click="$dispatch('camera-preset', 'reset')" class="p-3 rounded-2xl border border-neutral-800/30 bg-black/15 text-neutral-350 hover:text-white hover:border-[#C5A880]/70 hover:bg-neutral-800/30 transition text-left cursor-pointer select-none">
                                🔄 Reset Cam
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Feedback / Alerts -->
            <div class="mt-4 shrink-0">
                @if (session()->has('error'))
                    <div class="text-[11px] bg-red-950/20 border border-red-900/40 text-red-400 px-3.5 py-2.5 rounded-xl animate-hero-fade">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="text-[11px] bg-emerald-950/20 border border-emerald-900/40 text-emerald-400 px-3.5 py-2.5 rounded-xl animate-hero-fade">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Middle: 3D Viewport -->
        <div class="flex-1 rounded-3xl border border-neutral-800/30 overflow-hidden relative flex flex-col justify-end min-h-[400px] lg:min-h-0 bg-[#07070A]/30">
            
            <!-- Three.js Scene Loader -->
            <script>
                window.threeCurationScene = function() {
                    return {
                        // Three.js Core Components
                        scene: null,
                        camera: null,
                        renderer: null,
                        controls: null,
                        vaseMesh: null,
                        flowersGroup: null,
                        tableMesh: null,
                        ambientLight: null,
                        dirLight: null,
                        pointLight: null,
                        
                        // Addons Meshes
                        wrappingMesh: null,
                        wineMesh: null,
                        giftMesh: null,
                        fragranceMesh: null,

                        // Pedestal Under-glow components
                        glowRing: null,
                        glowMat: null,

                        // Animation loop handle
                        animationFrameId: null,

                        // Camera Presets Smooth Transition states
                        cameraTargetPos: new THREE.Vector3(0, 7, 13),
                        cameraTargetLookAt: new THREE.Vector3(0, 3, 0),
                        isTransitioningCamera: false,

                        vaseStyles: {
                            1: { color: '#8C6239', roughness: 0.85, metalness: 0.1, opacity: 1.0, transmission: 0.0 }, // Clay
                            2: { color: '#E2F0D9', roughness: 0.1, metalness: 0.1, opacity: 0.65, transmission: 0.9, transparent: true }, // Glass
                            default: { color: '#111115', roughness: 0.15, metalness: 0.9, opacity: 1.0, transmission: 0.0 }
                        },

                        /**
                         * Initialize Three.js viewport, table pedestal, lights, controls, and render loops.
                         * Attaches event listeners and registers proper garbage collection handlers on destruction.
                         */
                        initScene() {
                            if (typeof THREE === 'undefined') {
                                setTimeout(() => this.initScene(), 150);
                                return;
                            }

                            const canvas = document.getElementById('curation-3d-canvas');
                            const container = canvas.parentElement;

                            // 1. Scene setup
                            this.scene = new THREE.Scene();
                            
                            // 2. Camera setup with starting coordinates
                            this.camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 100);
                            this.camera.position.copy(this.cameraTargetPos);

                            // 3. WebGL Renderer configuration with high-fidelity soft shadows
                            this.renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
                            this.renderer.setSize(container.clientWidth, container.clientHeight);
                            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                            this.renderer.shadowMap.enabled = true;
                            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

                            // 4. OrbitControls with elastic dampening & custom angle limits
                            this.controls = new OrbitControls(this.camera, this.renderer.domElement);
                            this.controls.enableDamping = true;
                            this.controls.dampingFactor = 0.05;
                            this.controls.maxPolarAngle = Math.PI / 2 - 0.05;
                            this.controls.minDistance = 4;
                            this.controls.maxDistance = 25;
                            this.controls.target.copy(this.cameraTargetLookAt);

                            // Auto-rotate by default for premium rotation preview
                            this.controls.autoRotate = this.$parent.orbitActive;
                            this.controls.autoRotateSpeed = 1.2;

                            // 5. Lighting: Ambient, Directional (with shadows), and Point lighting
                            this.ambientLight = new THREE.AmbientLight(0xffffff, 1.0);
                            this.scene.add(this.ambientLight);

                            this.dirLight = new THREE.DirectionalLight(0xffffff, 2.0);
                            this.dirLight.position.set(5, 12, 8);
                            this.dirLight.castShadow = true;
                            this.dirLight.shadow.mapSize.width = 1024;
                            this.dirLight.shadow.mapSize.height = 1024;
                            this.scene.add(this.dirLight);

                            this.pointLight = new THREE.PointLight(0xffffff, 1.5, 20);
                            this.pointLight.position.set(0, 5, 2);
                            this.scene.add(this.pointLight);

                            // 6. Pedestal Base (Lower deck)
                            const baseGeo = new THREE.CylinderGeometry(5.2, 5.2, 0.4, 64);
                            const baseMat = new THREE.MeshStandardMaterial({
                                color: 0x070709,
                                roughness: 0.3,
                                metalness: 0.9,
                            });
                            const baseMesh = new THREE.Mesh(baseGeo, baseMat);
                            baseMesh.position.y = -0.3;
                            baseMesh.receiveShadow = true;
                            this.scene.add(baseMesh);

                            // Table Pedestal Top Shelf (Upper deck)
                            const topGeo = new THREE.CylinderGeometry(4.8, 4.8, 0.3, 64);
                            const topMat = new THREE.MeshStandardMaterial({
                                color: 0x18181c,
                                roughness: 0.08,
                                metalness: 0.85,
                            });
                            this.tableMesh = new THREE.Mesh(topGeo, topMat);
                            this.tableMesh.position.y = 0.05;
                            this.tableMesh.receiveShadow = true;
                            this.scene.add(this.tableMesh);

                            // Emissive Neon Under-glow Ring
                            const glowGeo = new THREE.TorusGeometry(4.9, 0.06, 8, 64);
                            this.glowMat = new THREE.MeshBasicMaterial({
                                color: 0xEAB308, // Default Onyx glow color
                            });
                            this.glowRing = new THREE.Mesh(glowGeo, this.glowMat);
                            this.glowRing.position.y = -0.1;
                            this.glowRing.rotation.x = Math.PI / 2;
                            this.scene.add(this.glowRing);

                            this.flowersGroup = new THREE.Group();
                            this.scene.add(this.flowersGroup);

                            // Helper function to extract ID values from entangles safely
                            const getDetailId = (detail) => {
                                if (Array.isArray(detail)) return detail[0];
                                if (detail && typeof detail === 'object' && 'id' in detail) return detail.id;
                                return detail;
                            };

                            // Initialize viewport geometry and materials
                            this.buildVase();
                            this.updateArrangement();
                            this.updateWrapping();
                            this.updateWine();
                            this.updateGift();
                            this.updateFragrance();
                            this.applyTheme(this.$parent.theme);

                            // --- WINDOW EVENT HANDLERS ---
                            const handleResize = () => {
                                if (!container || !this.camera || !this.renderer) return;
                                this.camera.aspect = container.clientWidth / container.clientHeight;
                                this.camera.updateProjectionMatrix();
                                this.renderer.setSize(container.clientWidth, container.clientHeight);
                            };
                            window.addEventListener('resize', handleResize);

                            const handleVaseChanged = (e) => {
                                const id = getDetailId(e.detail);
                                if (id !== undefined) this.$parent.selectedVaseId = id;
                                this.buildVase();
                            };
                            window.addEventListener('vase-changed', handleVaseChanged);

                            const triggerUpdate = () => {
                                setTimeout(() => this.updateArrangement(), 50);
                            };
                            const handleStemAdded = triggerUpdate;
                            const handleStemRemoved = triggerUpdate;
                            const handleSizeChanged = triggerUpdate;
                            
                            window.addEventListener('stem-added', handleStemAdded);
                            window.addEventListener('stem-removed', handleStemRemoved);
                            window.addEventListener('size-changed', handleSizeChanged);
                            
                            const handleThemeChanged = (e) => {
                                const themeVal = getDetailId(e.detail);
                                if (themeVal !== undefined) this.$parent.theme = themeVal;
                                this.applyTheme(themeVal);
                            };
                            window.addEventListener('theme-changed', handleThemeChanged);

                            const handleWrappingChanged = (e) => {
                                const id = getDetailId(e.detail);
                                if (id !== undefined) this.$parent.selectedWrappingId = id;
                                this.updateWrapping();
                            };
                            window.addEventListener('wrapping-changed', handleWrappingChanged);

                            const handleWineChanged = (e) => {
                                const id = getDetailId(e.detail);
                                if (id !== undefined) this.$parent.selectedWineId = id;
                                this.updateWine();
                            };
                            window.addEventListener('wine-changed', handleWineChanged);

                            const handleGiftChanged = (e) => {
                                const id = getDetailId(e.detail);
                                if (id !== undefined) this.$parent.selectedGiftId = id;
                                this.updateGift();
                            };
                            window.addEventListener('gift-changed', handleGiftChanged);

                            const handleFragranceChanged = (e) => {
                                const id = getDetailId(e.detail);
                                if (id !== undefined) this.$parent.selectedFragranceId = id;
                                this.updateFragrance();
                            };
                            window.addEventListener('fragrance-changed', handleFragranceChanged);

                            const handleToggleOrbit = (e) => {
                                this.controls.autoRotate = !!getDetailId(e.detail);
                            };
                            window.addEventListener('toggle-orbit', handleToggleOrbit);

                            // Cinematic Camera Presets events listener
                            const handleCameraPreset = (e) => {
                                const preset = getDetailId(e.detail);
                                this.isTransitioningCamera = true;
                                if (preset === 'front') {
                                    this.cameraTargetPos.set(0, 5, 13);
                                    this.cameraTargetLookAt.set(0, 3, 0);
                                } else if (preset === 'birds_eye') {
                                    this.cameraTargetPos.set(0, 12, 0.1);
                                    this.cameraTargetLookAt.set(0, 4.5, 0);
                                } else if (preset === 'macro') {
                                    this.cameraTargetPos.set(0, 4.2, 3.8);
                                    this.cameraTargetLookAt.set(0, 4.2, 0);
                                } else if (preset === 'reset') {
                                    this.cameraTargetPos.set(0, 7, 13);
                                    this.cameraTargetLookAt.set(0, 3, 0);
                                }
                            };
                            window.addEventListener('camera-preset', handleCameraPreset);

                            // --- ANIMATION LOOP ---
                            let frameCount = 0;
                            const animate = () => {
                                frameCount++;
                                
                                // Interpolate camera coordinates smoothly for high-end cinematic sweeps
                                if (this.isTransitioningCamera) {
                                    this.camera.position.lerp(this.cameraTargetPos, 0.06);
                                    this.controls.target.lerp(this.cameraTargetLookAt, 0.06);
                                    if (this.camera.position.distanceTo(this.cameraTargetPos) < 0.01) {
                                        this.isTransitioningCamera = false;
                                    }
                                }
                                
                                // Organic growing Bezier-tubes and rose spin-blooms
                                if (this.flowersGroup) {
                                    this.flowersGroup.children.forEach((stemGroup, index) => {
                                        if (stemGroup.userData.growth < 1.0) {
                                            stemGroup.userData.growth = Math.min(1.0, stemGroup.userData.growth + 0.035);
                                            const g = stemGroup.userData.growth;
                                            
                                            // Extract full curve points
                                            const p0 = stemGroup.userData.p0;
                                            const p1 = stemGroup.userData.p1;
                                            const p2 = stemGroup.userData.p2;

                                            // Interpolate control and end points to animate curve length
                                            const p1_g = p0.clone().lerp(p1, g);
                                            const p2_g = p0.clone().lerp(p2, g);

                                            const curve = new THREE.QuadraticBezierCurve3(p0, p1_g, p2_g);
                                            const newGeo = new THREE.TubeGeometry(curve, 16, 0.05, 8, false);

                                            // Swap geometry safely
                                            const stemMesh = stemGroup.children[0];
                                            if (stemMesh) {
                                                stemMesh.geometry.dispose();
                                                stemMesh.geometry = newGeo;
                                            }

                                            // Update bloom position at tip
                                            const bloomGroup = stemGroup.children[1];
                                            if (bloomGroup) {
                                                bloomGroup.position.copy(p2_g);
                                                
                                                // Orientation aligns with curve tangent
                                                const tangent = curve.getTangentAt(1.0);
                                                const upVec = new THREE.Vector3(0, 1, 0);
                                                const quat = new THREE.Quaternion().setFromUnitVectors(upVec, tangent);
                                                bloomGroup.quaternion.copy(quat);

                                                // Special Rose Spin Bloom Motion Graphics
                                                if (stemGroup.userData.type === 'rose') {
                                                    bloomGroup.scale.set(g, g, g);
                                                    bloomGroup.rotation.y = g * Math.PI * 4; // Extra spinning bloom effect
                                                } else {
                                                    bloomGroup.scale.set(g, g, g);
                                                }
                                            }
                                        }

                                        // Subtle organic sway wind drift
                                        const swayAmount = 0.015 * Math.sin(frameCount * 0.015 + index * 0.4);
                                        const bloomGroup = stemGroup.children[1];
                                        if (bloomGroup && stemGroup.userData.growth >= 1.0) {
                                            bloomGroup.rotation.y += swayAmount * 0.1;
                                        }
                                    });
                                }

                                this.controls.update();
                                this.renderer.render(this.scene, this.camera);
                                this.animationFrameId = requestAnimationFrame(animate);
                            };
                            animate();

                            // --- ALPINE CLEANUP HANDLER (PREVENTS WebGL CONTEXT LEAKS) ---
                            this.$cleanup(() => {
                                if (this.animationFrameId) {
                                    cancelAnimationFrame(this.animationFrameId);
                                }

                                // Remove window event listeners
                                window.removeEventListener('resize', handleResize);
                                window.removeEventListener('vase-changed', handleVaseChanged);
                                window.removeEventListener('stem-added', handleStemAdded);
                                window.removeEventListener('stem-removed', handleStemRemoved);
                                window.removeEventListener('size-changed', handleSizeChanged);
                                window.removeEventListener('theme-changed', handleThemeChanged);
                                window.removeEventListener('wrapping-changed', handleWrappingChanged);
                                window.removeEventListener('wine-changed', handleWineChanged);
                                window.removeEventListener('gift-changed', handleGiftChanged);
                                window.removeEventListener('fragrance-changed', handleFragranceChanged);
                                window.removeEventListener('toggle-orbit', handleToggleOrbit);
                                window.removeEventListener('camera-preset', handleCameraPreset);

                                // Dispose controls and renderer
                                if (this.controls) this.controls.dispose();
                                if (this.renderer) this.renderer.dispose();

                                // Traverse scene and dispose of geometries, materials, and textures
                                if (this.scene) {
                                    this.scene.traverse((obj) => {
                                        if (obj.geometry) obj.geometry.dispose();
                                        if (obj.material) {
                                            if (Array.isArray(obj.material)) {
                                                obj.material.forEach((mat) => mat.dispose());
                                            } else {
                                                obj.material.dispose();
                                            }
                                        }
                                    });
                                }
                                console.log('Three.js scene WebGL resources and Alpine scope events successfully garbage-collected.');
                            });
                        },

                        buildVase() {
                            // Properly traverse and dispose of existing WebGL entities to prevent leaks
                            if (this.vaseMesh) {
                                this.vaseMesh.traverse((child) => {
                                    if (child.geometry) child.geometry.dispose();
                                    if (child.material) {
                                        if (Array.isArray(child.material)) child.material.forEach(m => m.dispose());
                                        else child.material.dispose();
                                    }
                                });
                                this.scene.remove(this.vaseMesh);
                                this.vaseMesh = null;
                            }

                            // Dynamic 3D Lathe Geometry & Material configuration based on database SKU mapping
                            const vasesList = @json($availableVases);
                            const selectedVase = vasesList.find(v => v.id == this.$parent.selectedVaseId);
                            const sku = selectedVase ? selectedVase.sku : '';

                            const vaseGroup = new THREE.Group();
                            const points = [];
                            let vaseMat = null;

                            if (sku === 'NB-DEC-CGW-02') { // Crystal Glass Watamu Vase (Hourglass profile + Clear Glass material)
                                points.push(new THREE.Vector2(0, 0));
                                points.push(new THREE.Vector2(1.0, 0));
                                points.push(new THREE.Vector2(1.4, 0.6));
                                points.push(new THREE.Vector2(0.7, 1.6));
                                points.push(new THREE.Vector2(0.6, 2.2));
                                points.push(new THREE.Vector2(0.9, 2.8));
                                points.push(new THREE.Vector2(1.1, 3.2));

                                vaseMat = new THREE.MeshPhysicalMaterial({
                                    color: new THREE.Color('#D4EBF2'),
                                    roughness: 0.05,
                                    metalness: 0.1,
                                    transmission: 0.9,
                                    opacity: 1.0,
                                    transparent: true,
                                    ior: 1.5,
                                    thickness: 0.15,
                                    side: THREE.DoubleSide,
                                    clearcoat: 1.0,
                                    clearcoatRoughness: 0.05
                                });
                            } else if (sku === 'NB-DEC-OOA-03') { // Obsidian Onyx Alabaster Vase (Stout profile + polished black gold material + physical gold top rim)
                                points.push(new THREE.Vector2(0, 0));
                                points.push(new THREE.Vector2(1.3, 0));
                                points.push(new THREE.Vector2(1.3, 2.8));
                                points.push(new THREE.Vector2(1.1, 2.9));
                                points.push(new THREE.Vector2(1.1, 3.0));

                                vaseMat = new THREE.MeshPhysicalMaterial({
                                    color: new THREE.Color('#111115'),
                                    roughness: 0.15,
                                    metalness: 0.8,
                                    side: THREE.DoubleSide,
                                    clearcoat: 1.0,
                                    clearcoatRoughness: 0.1
                                });

                                // Add Golden Top Rim for Obsidian Onyx to make it feel extremely luxury
                                const rimGeo = new THREE.TorusGeometry(1.1, 0.07, 16, 64);
                                const rimMat = new THREE.MeshStandardMaterial({
                                    color: 0xD4AF37,
                                    roughness: 0.18,
                                    metalness: 0.95
                                });
                                const rimMesh = new THREE.Mesh(rimGeo, rimMat);
                                rimMesh.position.y = 3.0;
                                rimMesh.rotation.x = Math.PI / 2;
                                rimMesh.castShadow = true;
                                rimMesh.receiveShadow = true;
                                vaseGroup.add(rimMesh);
                            } else if (sku === 'NB-DEC-FGL-04') { // Frosted Glacier Lily Vase (Fluted profile + Frosted Glass material)
                                points.push(new THREE.Vector2(0, 0));
                                points.push(new THREE.Vector2(0.8, 0));
                                points.push(new THREE.Vector2(0.9, 0.5));
                                points.push(new THREE.Vector2(0.8, 1.2));
                                points.push(new THREE.Vector2(1.2, 2.2));
                                points.push(new THREE.Vector2(1.7, 3.0));
                                points.push(new THREE.Vector2(1.8, 3.1));

                                vaseMat = new THREE.MeshPhysicalMaterial({
                                    color: new THREE.Color('#E0F2FE'),
                                    roughness: 0.35,
                                    metalness: 0.05,
                                    transmission: 0.8,
                                    opacity: 0.9,
                                    transparent: true,
                                    ior: 1.2,
                                    thickness: 0.2,
                                    side: THREE.DoubleSide,
                                    clearcoat: 0.2,
                                    clearcoatRoughness: 0.4
                                });
                            } else { // NB-DEC-MRV-01 Matte Clay Rift Valley Vase ( Artisan classic clay silhouette + Matte Volcanic soils color)
                                points.push(new THREE.Vector2(0, 0));
                                points.push(new THREE.Vector2(1.2, 0));
                                points.push(new THREE.Vector2(1.3, 0.4));
                                points.push(new THREE.Vector2(1.1, 1.2));
                                points.push(new THREE.Vector2(0.7, 2.0));
                                points.push(new THREE.Vector2(0.8, 2.8));
                                points.push(new THREE.Vector2(1.0, 3.0));

                                vaseMat = new THREE.MeshPhysicalMaterial({
                                    color: new THREE.Color('#8C6239'),
                                    roughness: 0.85,
                                    metalness: 0.1,
                                    side: THREE.DoubleSide,
                                    clearcoat: 0.0,
                                    clearcoatRoughness: 0.1
                                });
                            }

                            const vaseGeo = new THREE.LatheGeometry(points, 64);
                            const baseBody = new THREE.Mesh(vaseGeo, vaseMat);
                            baseBody.castShadow = true;
                            baseBody.receiveShadow = true;
                            vaseGroup.add(baseBody);

                            this.vaseMesh = vaseGroup;
                            this.scene.add(this.vaseMesh);
                        },

                        updateArrangement() {
                            // Dispose old geometries inside flowersGroup to avoid leaks
                            while (this.flowersGroup.children.length > 0) {
                                const stemGroup = this.flowersGroup.children[0];
                                const stemMesh = stemGroup.children[0];
                                if (stemMesh && stemMesh.geometry) {
                                    stemMesh.geometry.dispose();
                                }
                                this.flowersGroup.remove(stemGroup);
                            }

                            const stemsList = [];
                            const stemProducts = @json($availableStems);
                            
                            stemProducts.forEach(prod => {
                                const qty = this.$parent.selectedStems[prod.id] || 0;
                                if (qty > 0) {
                                    stemsList.push({
                                        id: prod.id,
                                        name: prod.name,
                                        qty: qty,
                                        color: this.getFlowerColor(prod.name),
                                        type: this.getFlowerType(prod.name)
                                    });
                                }
                            });

                            if (stemsList.length === 0) return;

                            let densityMultiplier = 1.0;
                            if (this.$parent.size === 'deluxe') densityMultiplier = 1.5;
                            if (this.$parent.size === 'grand') densityMultiplier = 2.2;

                            const arrangementItems = [];
                            stemsList.forEach(item => {
                                const totalStemsForType = Math.ceil(item.qty * densityMultiplier);
                                for (let k = 0; k < totalStemsForType; k++) {
                                    arrangementItems.push(item);
                                }
                            });

                            arrangementItems.sort(() => Math.random() - 0.5);

                            const vaseOpeningHeight = 3.0;

                            arrangementItems.forEach((item, i) => {
                                const stemGroup = new THREE.Group();

                                const goldenAngle = 2.39996;
                                const theta = i * goldenAngle;
                                
                                const spacing = 0.28 + (0.15 * Math.sqrt(i));
                                const targetX = spacing * Math.cos(theta);
                                const targetZ = spacing * Math.sin(theta);
                                
                                const centerHeight = 6.2 + (Math.random() * 0.4);
                                const targetY = centerHeight - (0.45 * spacing);

                                const p0 = new THREE.Vector3(0, 0.4, 0);
                                const p1 = new THREE.Vector3(targetX * 0.4, vaseOpeningHeight, targetZ * 0.4);
                                const p2 = new THREE.Vector3(targetX, targetY, targetZ);

                                stemGroup.userData = {
                                    growth: 0,
                                    type: item.type,
                                    color: item.color,
                                    p0: p0,
                                    p1: p1,
                                    p2: p2
                                };

                                // Initial growth curve at t=0.01
                                const p1_g = p0.clone().lerp(p1, 0.01);
                                const p2_g = p0.clone().lerp(p2, 0.01);
                                const curve = new THREE.QuadraticBezierCurve3(p0, p1_g, p2_g);

                                const stemGeo = new THREE.TubeGeometry(curve, 16, 0.05, 8, false);
                                const stemMat = new THREE.MeshStandardMaterial({
                                    color: 0x3D5A3A,
                                    roughness: 0.7,
                                    metalness: 0.1
                                });
                                const stemMesh = new THREE.Mesh(stemGeo, stemMat);
                                stemMesh.castShadow = true;
                                stemGroup.add(stemMesh);

                                const bloomGroup = new THREE.Group();
                                bloomGroup.position.copy(p2_g);

                                const bloomDirection = curve.getTangentAt(1.0);
                                const upVec = new THREE.Vector3(0, 1, 0);
                                const quat = new THREE.Quaternion().setFromUnitVectors(upVec, bloomDirection);
                                bloomGroup.quaternion.copy(quat);

                                this.buildBloom(bloomGroup, item.type, item.color);
                                bloomGroup.scale.set(0.001, 0.001, 0.001);
                                stemGroup.add(bloomGroup);

                                this.flowersGroup.add(stemGroup);
                            });
                        },

                        updateWrapping() {
                            if (this.wrappingMesh) {
                                this.scene.remove(this.wrappingMesh);
                                this.wrappingMesh = null;
                            }

                            if (!this.$parent.selectedWrappingId) return;

                            const wrappingsList = @json($availableWrappings);
                            const selectedWrapping = wrappingsList.find(w => w.id == this.$parent.selectedWrappingId);
                            const sku = selectedWrapping ? selectedWrapping.sku : '';
                            
                            if (sku === 'NB-DEC-LKW-01') { // Kraft paper wrap
                                const wrapGeo = new THREE.CylinderGeometry(1.6, 1.2, 3.4, 32, 1, true);
                                const wrapMat = new THREE.MeshStandardMaterial({
                                    color: 0xC6A678,
                                    roughness: 0.95,
                                    metalness: 0.0,
                                    side: THREE.DoubleSide
                                });
                                this.wrappingMesh = new THREE.Mesh(wrapGeo, wrapMat);
                                this.wrappingMesh.position.y = 1.7;
                                this.wrappingMesh.castShadow = true;
                                this.wrappingMesh.receiveShadow = true;
                                this.scene.add(this.wrappingMesh);
                            } else if (sku === 'NB-DEC-LLW-02') { // Linen wrap
                                const wrapGeo = new THREE.CylinderGeometry(1.65, 1.2, 3.4, 32, 1, true);
                                const wrapMat = new THREE.MeshStandardMaterial({
                                    color: 0xEAE5D9,
                                    roughness: 0.98,
                                    metalness: 0.0,
                                    side: THREE.DoubleSide
                                });
                                this.wrappingMesh = new THREE.Mesh(wrapGeo, wrapMat);
                                this.wrappingMesh.position.y = 1.7;
                                this.wrappingMesh.castShadow = true;
                                this.wrappingMesh.receiveShadow = true;
                                this.scene.add(this.wrappingMesh);
                            } else if (sku === 'NB-DEC-NOG-03') { // Obsidian Gift Box
                                const boxGeo = new THREE.BoxGeometry(3.2, 2.2, 3.2);
                                const boxMat = new THREE.MeshPhysicalMaterial({
                                    color: 0x111115,
                                    roughness: 0.18,
                                    metalness: 0.85,
                                    clearcoat: 1.0,
                                    clearcoatRoughness: 0.1
                                });
                                this.wrappingMesh = new THREE.Mesh(boxGeo, boxMat);
                                this.wrappingMesh.position.y = 1.1;
                                this.wrappingMesh.castShadow = true;
                                this.wrappingMesh.receiveShadow = true;
                                this.scene.add(this.wrappingMesh);
                            }
                        },

                        updateWine() {
                            if (this.wineMesh) {
                                this.scene.remove(this.wineMesh);
                                this.wineMesh = null;
                            }

                            if (!this.$parent.selectedWineId) return;

                            const wineGroup = new THREE.Group();

                            // Wine bottle body
                            const bodyGeo = new THREE.CylinderGeometry(0.35, 0.35, 1.8, 20);
                            const glassMat = new THREE.MeshPhysicalMaterial({
                                color: 0x2A3E1E,
                                roughness: 0.05,
                                metalness: 0.1,
                                transmission: 0.45,
                                opacity: 1.0,
                                transparent: true,
                                thickness: 0.15
                            });
                            const bodyMesh = new THREE.Mesh(bodyGeo, glassMat);
                            bodyMesh.position.y = 0.9;
                            bodyMesh.castShadow = true;
                            wineGroup.add(bodyMesh);

                            // Wine bottle neck
                            const neckGeo = new THREE.CylinderGeometry(0.12, 0.16, 0.8, 20);
                            const neckMesh = new THREE.Mesh(neckGeo, glassMat);
                            neckMesh.position.y = 2.2;
                            neckMesh.castShadow = true;
                            wineGroup.add(neckMesh);

                            // Bottle Label
                            const labelGeo = new THREE.CylinderGeometry(0.355, 0.355, 0.8, 20, 1, true);
                            const labelMat = new THREE.MeshStandardMaterial({
                                color: 0xFAF6EB,
                                roughness: 0.8,
                            });
                            const labelMesh = new THREE.Mesh(labelGeo, labelMat);
                            labelMesh.position.y = 0.8;
                            wineGroup.add(labelMesh);

                            // Position next to the vase on table
                            wineGroup.position.set(-2.8, 0, 1.5);
                            this.wineMesh = wineGroup;
                            this.scene.add(this.wineMesh);
                        },

                        updateGift() {
                            if (this.giftMesh) {
                                this.scene.remove(this.giftMesh);
                                this.giftMesh = null;
                            }

                            if (!this.$parent.selectedGiftId) return;

                            const giftGroup = new THREE.Group();

                            // Chocolates box
                            const boxGeo = new THREE.BoxGeometry(1.3, 0.6, 1.3);
                            const boxMat = new THREE.MeshStandardMaterial({
                                color: 0x4F1122, // Luxury Burgundy
                                roughness: 0.35,
                                metalness: 0.1
                            });
                            const boxMesh = new THREE.Mesh(boxGeo, boxMat);
                            boxMesh.position.y = 0.3;
                            boxMesh.castShadow = true;
                            giftGroup.add(boxMesh);

                            // Gold ribbon bands
                            const ribbonMat = new THREE.MeshStandardMaterial({
                                color: 0xD4AF37, // Gold
                                roughness: 0.25,
                                metalness: 0.8
                            });
                            const band1Geo = new THREE.BoxGeometry(1.32, 0.62, 0.12);
                            const band1 = new THREE.Mesh(band1Geo, ribbonMat);
                            band1.position.y = 0.3;
                            giftGroup.add(band1);

                            const band2Geo = new THREE.BoxGeometry(0.12, 0.62, 1.32);
                            const band2 = new THREE.Mesh(band2Geo, ribbonMat);
                            band2.position.y = 0.3;
                            giftGroup.add(band2);

                            // Ribbon Bow on top
                            const bowGeo = new THREE.TorusGeometry(0.15, 0.04, 8, 24);
                            const bowLeft = new THREE.Mesh(bowGeo, ribbonMat);
                            bowLeft.position.set(-0.15, 0.63, 0);
                            bowLeft.rotation.y = Math.PI / 4;
                            giftGroup.add(bowLeft);

                            const bowRight = new THREE.Mesh(bowGeo, ribbonMat);
                            bowRight.position.set(0.15, 0.63, 0);
                            bowRight.rotation.y = -Math.PI / 4;
                            giftGroup.add(bowRight);

                            // Position on table
                            giftGroup.position.set(2.8, 0, 1.2);
                            this.giftMesh = giftGroup;
                            this.scene.add(this.giftMesh);
                        },

                        updateFragrance() {
                            if (this.fragranceMesh) {
                                this.scene.remove(this.fragranceMesh);
                                this.fragranceMesh = null;
                            }

                            if (!this.$parent.selectedFragranceId) return;

                            const fragGroup = new THREE.Group();

                            // Cylindrical spray bottle
                            const bottleGeo = new THREE.CylinderGeometry(0.2, 0.2, 1.1, 16);
                            const glassMat = new THREE.MeshPhysicalMaterial({
                                color: 0xffffff,
                                roughness: 0.05,
                                metalness: 0.1,
                                transmission: 0.9,
                                opacity: 0.8,
                                transparent: true,
                                thickness: 0.08
                            });
                            const bottleMesh = new THREE.Mesh(bottleGeo, glassMat);
                            bottleMesh.position.y = 0.55;
                            bottleMesh.castShadow = true;
                            fragGroup.add(bottleMesh);

                            // Gold Atomizer spray cap
                            const capGeo = new THREE.CylinderGeometry(0.15, 0.15, 0.28, 16);
                            const capMat = new THREE.MeshStandardMaterial({
                                color: 0xD4AF37, // Gold
                                roughness: 0.2,
                                metalness: 0.8
                            });
                            const capMesh = new THREE.Mesh(capGeo, capMat);
                            capMesh.position.y = 1.24;
                            capMesh.castShadow = true;
                            fragGroup.add(capMesh);

                            // Position next to the vase
                            fragGroup.position.set(-1.8, 0, -2.0);
                            this.fragranceMesh = fragGroup;
                            this.scene.add(this.fragranceMesh);
                        },

                        buildBloom(group, type, colorHex) {
                            const color = new THREE.Color(colorHex);

                            if (type === 'rose') {
                                const bloomMat = new THREE.MeshStandardMaterial({
                                    color: color,
                                    roughness: 0.6,
                                    metalness: 0.1,
                                });

                                const centerGeo = new THREE.ConeGeometry(0.35, 0.8, 16);
                                const centerBud = new THREE.Mesh(centerGeo, bloomMat);
                                centerBud.rotation.x = Math.PI;
                                centerBud.position.y = 0.2;
                                group.add(centerBud);

                                const petalGeo = new THREE.SphereGeometry(0.35, 8, 8, 0, Math.PI * 2, 0, Math.PI / 2);
                                for (let j = 0; j < 12; j++) {
                                    const petal = new THREE.Mesh(petalGeo, bloomMat);
                                    const scale = 0.85 + (Math.random() * 0.3);
                                    petal.scale.set(scale, scale * 0.8, scale * 1.3);
                                    
                                    const radius = 0.25;
                                    const angle = (j / 12) * Math.PI * 2;
                                    petal.position.set(radius * Math.cos(angle), 0.3 - (j * 0.01), radius * Math.sin(angle));
                                    
                                    petal.rotation.y = -angle + Math.PI / 2;
                                    petal.rotation.x = 0.45;
                                    group.add(petal);
                                }
                            } else if (type === 'lily') {
                                const petalMat = new THREE.MeshStandardMaterial({
                                    color: color,
                                    roughness: 0.4,
                                    metalness: 0.05,
                                    side: THREE.DoubleSide
                                });

                                const stamenMat = new THREE.MeshStandardMaterial({ color: 0xF59E0B, roughness: 0.5 });
                                const pistilGeo = new THREE.CylinderGeometry(0.02, 0.02, 0.7, 8);
                                const pistil = new THREE.Mesh(pistilGeo, stamenMat);
                                pistil.position.y = 0.35;
                                group.add(pistil);

                                for (let j = 0; j < 6; j++) {
                                    const stamen = new THREE.Mesh(pistilGeo, stamenMat);
                                    stamen.scale.set(0.6, 0.8, 0.6);
                                    const stAngle = (j / 6) * Math.PI * 2;
                                    stamen.position.set(0.12 * Math.cos(stAngle), 0.3, 0.12 * Math.sin(stAngle));
                                    stamen.rotation.z = Math.sin(stAngle) * 0.25;
                                    stamen.rotation.x = Math.cos(stAngle) * 0.25;
                                    group.add(stamen);
                                }

                                const lilyPetalGeo = new THREE.ConeGeometry(0.18, 0.9, 4);
                                for (let j = 0; j < 6; j++) {
                                    const petal = new THREE.Mesh(lilyPetalGeo, petalMat);
                                    petal.scale.set(1.4, 1.4, 0.45);
                                    
                                    const angle = (j / 6) * Math.PI * 2;
                                    petal.position.set(0.3 * Math.cos(angle), 0.25, 0.3 * Math.sin(angle));
                                    
                                    petal.rotation.y = -angle;
                                    petal.rotation.z = Math.cos(angle) * 0.8;
                                    petal.rotation.x = Math.sin(angle) * 0.8;
                                    group.add(petal);
                                }
                            } else if (type === 'hibiscus') {
                                const petalMat = new THREE.MeshStandardMaterial({
                                    color: color,
                                    roughness: 0.5,
                                    side: THREE.DoubleSide
                                });

                                const petGeo = new THREE.SphereGeometry(0.42, 8, 8, 0, Math.PI * 2, 0, Math.PI / 2);
                                for (let j = 0; j < 5; j++) {
                                    const petal = new THREE.Mesh(petGeo, petalMat);
                                    petal.scale.set(1.4, 0.15, 1.1);
                                    
                                    const angle = (j / 5) * Math.PI * 2;
                                    petal.position.set(0.25 * Math.cos(angle), 0.1, 0.25 * Math.sin(angle));
                                    
                                    petal.rotation.y = -angle;
                                    petal.rotation.z = 0.55;
                                    group.add(petal);
                                }

                                const stamenMat = new THREE.MeshStandardMaterial({ color: 0xDC2626 });
                                const styleGeo = new THREE.CylinderGeometry(0.03, 0.03, 1.0, 8);
                                const style = new THREE.Mesh(styleGeo, stamenMat);
                                style.position.y = 0.45;
                                style.rotation.z = 0.15;
                                group.add(style);

                                const antherGeo = new THREE.SphereGeometry(0.04, 6, 6);
                                const antherMat = new THREE.MeshStandardMaterial({ color: 0xF59E0B });
                                for (let k = 0; k < 6; k++) {
                                    const anther = new THREE.Mesh(antherGeo, antherMat);
                                    anther.position.set(Math.random() * 0.1 - 0.05, 0.95, Math.random() * 0.1 - 0.05);
                                    group.add(anther);
                                }
                            } else {
                                const leafMat = new THREE.MeshStandardMaterial({
                                    color: 0x2A4B27,
                                    roughness: 0.8,
                                    side: THREE.DoubleSide
                                });
                                const leafGeo = new THREE.BoxGeometry(0.18, 0.02, 0.45);
                                for (let j = 0; j < 8; j++) {
                                    const leaf = new THREE.Mesh(leafGeo, leafMat);
                                    const scale = 0.7 + (Math.random() * 0.6);
                                    leaf.scale.set(scale, 1.0, scale);
                                    
                                    const radius = 0.18 + (Math.random() * 0.15);
                                    const angle = (j / 8) * Math.PI * 2;
                                    leaf.position.set(radius * Math.cos(angle), 0.15 + (Math.random() * 0.15), radius * Math.sin(angle));
                                    
                                    leaf.rotation.y = -angle;
                                    leaf.rotation.z = 0.45 + (Math.random() * 0.4);
                                    leaf.rotation.x = Math.random() * 0.3;
                                    group.add(leaf);
                                }
                            }
                        },

                        applyTheme(themeName) {
                            if (!this.scene) return;

                            // Dynamic shifting of the neon under-glow color ring matching active theme settings
                            if (this.glowMat) {
                                if (themeName === 'rose') {
                                    this.glowMat.color.setHex(0xF472B6); // Blushing pink
                                } else if (themeName === 'champagne') {
                                    this.glowMat.color.setHex(0xD4AF37); // Champagne gold
                                } else {
                                    this.glowMat.color.setHex(0xEAB308); // Onyx amber
                                }
                            }

                            if (themeName === 'rose') {
                                this.renderer.setClearColor(0x15060A, 1.0);
                                this.ambientLight.color.setHex(0x3E1E2C);
                                this.ambientLight.intensity = 1.2;
                                
                                this.dirLight.color.setHex(0xFDA4AF);
                                this.dirLight.intensity = 2.4;
                                
                                this.pointLight.color.setHex(0xF472B6);
                                this.pointLight.intensity = 2.0;

                                if (this.tableMesh) {
                                    this.tableMesh.material.color.setHex(0x3D1826);
                                    this.tableMesh.material.metalness = 0.85;
                                    this.tableMesh.material.roughness = 0.15;
                                }
                            } else if (themeName === 'champagne') {
                                this.renderer.setClearColor(0xFAF7F0, 1.0);
                                this.ambientLight.color.setHex(0xD5C5B0);
                                this.ambientLight.intensity = 1.4;
                                
                                this.dirLight.color.setHex(0xFFFCF5);
                                this.dirLight.intensity = 2.2;
                                
                                this.pointLight.color.setHex(0xDECEB5);
                                this.pointLight.intensity = 1.2;

                                if (this.tableMesh) {
                                    this.tableMesh.material.color.setHex(0xFAF6EB);
                                    this.tableMesh.material.metalness = 0.2;
                                    this.tableMesh.material.roughness = 0.08;
                                }
                            } else {
                                this.renderer.setClearColor(0x050507, 1.0);
                                this.ambientLight.color.setHex(0x111625);
                                this.ambientLight.intensity = 0.95;
                                
                                this.dirLight.color.setHex(0xFFF3C7);
                                this.dirLight.intensity = 2.5;
                                
                                this.pointLight.color.setHex(0xEAB308);
                                this.pointLight.intensity = 1.8;

                                if (this.tableMesh) {
                                    this.tableMesh.material.color.setHex(0x0E0E10);
                                    this.tableMesh.material.metalness = 0.9;
                                    this.tableMesh.material.roughness = 0.12;
                                }
                            }
                        },

                        getFlowerColor(name) {
                            const nameLower = name.toLowerCase();
                            if (nameLower.includes('red') || nameLower.includes('rose') || nameLower.includes('obsidian')) {
                                return '#DC2626';
                            } else if (nameLower.includes('white') || nameLower.includes('lily') || nameLower.includes('alabaster')) {
                                return '#FFFFFF';
                            } else if (nameLower.includes('coral') || nameLower.includes('hibiscus') || nameLower.includes('sunset')) {
                                return '#F97316';
                            } else if (nameLower.includes('lavender')) {
                                return '#A78BFA';
                            }
                            return '#10B981';
                        },

                        getFlowerType(name) {
                            const nameLower = name.toLowerCase();
                            if (nameLower.includes('rose') || nameLower.includes('obsidian')) {
                                return 'rose';
                            } else if (nameLower.includes('lily') || nameLower.includes('orchid')) {
                                return 'lily';
                            } else if (nameLower.includes('hibiscus') || nameLower.includes('sunset')) {
                                return 'hibiscus';
                            }
                            return 'foliage';
                        }
                    }
                }
            </script>

            <!-- 3D Canvas element -->
            <div wire:ignore class="absolute inset-0 z-0 w-full h-full" x-data="threeCurationScene()" x-init="initScene()">
                <canvas id="curation-3d-canvas" class="w-full h-full block cursor-grab active:cursor-grabbing"></canvas>

                <!-- Floating Overlays (Inside wire:ignore so Livewire never breaks them) -->
                <!-- Top-Left Curation Stats Sheet -->
                <div class="absolute top-4 left-4 z-20 w-56 bg-black/60 backdrop-blur-md border border-neutral-800/40 p-3.5 rounded-2xl pointer-events-auto space-y-2 text-neutral-300">
                    <div class="flex items-center justify-between border-b border-neutral-800/40 pb-1.5">
                        <span class="text-[9px] font-mono tracking-widest uppercase text-[#C5A880] font-semibold">Atelier Status</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-mono">
                            eTIMS Compliant
                        </span>
                    </div>
                    <div class="space-y-1.5 text-[10px] font-mono">
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Base Vase:</span>
                            <span class="text-neutral-200 truncate max-w-[120px]" x-text="((vasesList.find(v => v.id == selectedVaseId) || {name: 'None'}).name).replace(' Vase', '')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Base Price:</span>
                            <span class="text-neutral-200" x-text="Number((vasesList.find(v => v.id == selectedVaseId) || {price: 0}).price).toLocaleString() + ' KSH'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Floral Stems:</span>
                            <span class="text-[#C5A880] font-semibold" x-text="Math.ceil(Object.values(selectedStems).reduce((a, b) => a + (parseInt(b) || 0), 0) * (size === 'deluxe' ? 1.5 : (size === 'grand' ? 2.2 : 1.0))) + ' Stems'"></span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-neutral-800/20">
                            <span class="text-neutral-500">Desk Total:</span>
                            <span class="text-white font-semibold" x-text="Number(subtotal).toLocaleString() + ' KSH'"></span>
                        </div>
                    </div>
                </div>

                <!-- Top-Right Camera Presets Toolbar -->
                <div class="absolute top-4 right-4 z-20 flex space-x-1 bg-black/60 backdrop-blur-md p-1 border border-neutral-800/40 rounded-xl pointer-events-auto">
                    <button @click="$dispatch('camera-preset', 'front')" class="px-2.5 py-1.5 rounded-lg text-[9px] font-mono tracking-wider uppercase text-neutral-300 hover:text-white hover:bg-neutral-800 transition cursor-pointer select-none">Front</button>
                    <button @click="$dispatch('camera-preset', 'birds_eye')" class="px-2.5 py-1.5 rounded-lg text-[9px] font-mono tracking-wider uppercase text-neutral-300 hover:text-white hover:bg-neutral-800 transition cursor-pointer select-none">Bird's Eye</button>
                    <button @click="$dispatch('camera-preset', 'macro')" class="px-2.5 py-1.5 rounded-lg text-[9px] font-mono tracking-wider uppercase text-neutral-300 hover:text-white hover:bg-neutral-800 transition cursor-pointer select-none">Macro</button>
                    <button @click="$dispatch('camera-preset', 'reset')" class="px-2.5 py-1.5 rounded-lg text-[9px] font-mono tracking-wider uppercase text-neutral-300 hover:text-white hover:bg-neutral-800 transition cursor-pointer select-none">Reset</button>
                </div>
            </div>

            <!-- Scene Overlays & HUD -->
            <div class="absolute inset-x-0 bottom-4 px-5 z-20 flex justify-between items-center pointer-events-none">
                <!-- Theme/Lighting & Auto-Orbit Controls -->
                <div class="flex items-center space-x-2 pointer-events-auto">
                    <div class="bg-black/55 backdrop-blur-md px-3.5 py-2 border border-neutral-800/50 rounded-2xl text-[10px] font-mono tracking-widest text-[#C5A880] uppercase">
                        Vase Lighting: <span x-text="theme"></span>
                    </div>
                    <button @click="toggleOrbit()" class="bg-black/55 hover:bg-black/85 backdrop-blur-md px-3.5 py-2 border border-neutral-800/50 rounded-2xl text-[10px] font-mono tracking-widest text-[#C5A880] uppercase cursor-pointer select-none">
                        Auto-Orbit: <span x-text="orbitActive ? 'ON' : 'OFF'"></span>
                    </button>
                </div>

                <!-- Interaction Hint -->
                <div class="hidden sm:block bg-black/55 backdrop-blur-md px-3.5 py-2 border border-neutral-800/50 rounded-2xl text-[10px] font-mono text-neutral-400">
                    ✦ Drag to Rotate &bull; Pinch to Zoom ✦
                </div>
            </div>
        </div>

        <!-- Right Panel: Summary & Checkout -->
        <div 
            :class="{
                'bg-[#09090D]/90 border-neutral-900': theme === 'onyx',
                'bg-white/95 border-neutral-200 shadow-xl': theme === 'champagne',
                'bg-[#1A080E]/90 border-[#3D1222]': theme === 'rose'
            }"
            class="w-full lg:w-80 rounded-3xl border p-5 flex flex-col justify-between backdrop-blur-md overflow-hidden shrink-0"
        >
            <div class="flex flex-col h-full justify-between">
                <!-- Arrangement specifications -->
                <div class="space-y-6">
                    <h3 class="font-serif italic text-lg text-[#C5A880]">Arrangement Specs</h3>

                    <!-- Size Tier selector -->
                    <div class="space-y-2.5">
                        <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-400 block">Size Level</label>
                        <div class="grid grid-cols-3 gap-1 bg-[#0F0F12]/80 border border-neutral-800/60 p-1 rounded-xl text-[10px] font-mono">
                            <button @click="$wire.setSize('standard')" :class="size === 'standard' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="py-2 rounded-lg transition-all cursor-pointer">Standard</button>
                            <button @click="$wire.setSize('deluxe')" :class="size === 'deluxe' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="py-2 rounded-lg transition-all cursor-pointer">Deluxe</button>
                            <button @click="$wire.setSize('grand')" :class="size === 'grand' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="py-2 rounded-lg transition-all cursor-pointer">Grand</button>
                        </div>
                        <p class="text-[9px] text-neutral-500 italic font-sans leading-relaxed">
                            Deluxe adds 50% more stems (1.5x price multiplier). Grand adds 120% more stems (2.2x price multiplier).
                        </p>
                    </div>

                    <!-- Items Included -->
                    <div class="space-y-3">
                        <label class="text-[10px] uppercase tracking-widest font-mono text-neutral-400 block">Stems & Base</label>
                        
                        <div class="max-h-[160px] overflow-y-auto space-y-2 pr-1 scrollbar-thin text-xs">
                            <!-- Vase item -->
                            @if($selectedVaseId)
                                @php $vase = $availableVases->firstWhere('id', $selectedVaseId); @endphp
                                @if($vase)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35">
                                        <span class="text-neutral-350 truncate">{{ $vase->name }}</span>
                                        <span class="font-mono text-neutral-400 shrink-0">1x</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Stem items -->
                            @foreach($availableStems as $stem)
                                @if(($selectedStems[$stem->id] ?? 0) > 0)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35">
                                        <span class="text-neutral-350 truncate">{{ $stem->name }}</span>
                                        <span class="font-mono text-[#C5A880] shrink-0 font-semibold">{{ $selectedStems[$stem->id] }}x</span>
                                    </div>
                                @endif
                            @endforeach

                            <!-- Wrapping -->
                            @if($selectedWrappingId)
                                @php $wrapObj = $availableWrappings->firstWhere('id', $selectedWrappingId); @endphp
                                @if($wrapObj)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35 text-[#C5A880]">
                                        <span class="truncate">{{ $wrapObj->name }}</span>
                                        <span class="font-mono shrink-0">1x</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Wine -->
                            @if($selectedWineId)
                                @php $wineObj = $availableWines->firstWhere('id', $selectedWineId); @endphp
                                @if($wineObj)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35 text-[#C5A880]">
                                        <span class="truncate">{{ $wineObj->name }}</span>
                                        <span class="font-mono shrink-0">1x</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Gift -->
                            @if($selectedGiftId)
                                @php $giftObj = $availableGifts->firstWhere('id', $selectedGiftId); @endphp
                                @if($giftObj)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35 text-[#C5A880]">
                                        <span class="truncate">{{ $giftObj->name }}</span>
                                        <span class="font-mono shrink-0">1x</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Fragrance -->
                            @if($selectedFragranceId)
                                @php $fragObj = $availableFragrances->firstWhere('id', $selectedFragranceId); @endphp
                                @if($fragObj)
                                    <div class="flex justify-between items-center py-1 border-b border-neutral-800/35 text-[#C5A880]">
                                        <span class="truncate">{{ $fragObj->name }}</span>
                                        <span class="font-mono shrink-0">1x</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subtotal and Cart action -->
                <div class="pt-6 border-t border-neutral-800/40 space-y-4 mt-6">
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs text-neutral-400 tracking-wider">Desk Subtotal</span>
                        <span class="text-xl font-mono font-semibold text-white tracking-tight">
                            {{ number_format($subtotal) }} KSH
                        </span>
                    </div>

                    <button 
                        wire:click="addToCart" 
                        class="w-full text-xs font-semibold tracking-[0.2em] uppercase py-4 cursor-pointer rounded-full bg-white text-black hover:bg-neutral-200 transition-all duration-300 shadow-lg"
                    >
                        Send to Curation Cart
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
