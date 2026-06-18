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
<div 
    x-data="curationStudio"
    :class="{
        'bg-[#050507] text-[#F4F4F5]': theme === 'onyx',
        'bg-[#FAF7F0] text-[#1C1C20]': theme === 'champagne',
        'bg-[#15060A] text-[#FCE7EC]': theme === 'rose'
    }"
    class="min-h-screen font-sans antialiased relative text-left flex flex-col justify-between transition-colors duration-500 overflow-x-hidden"
>
    <!-- Background overlay grains -->
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-30"></div>

    <!-- Luxury Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne',
            'bg-[#15060A]/75 border-[#2D0D19]/30 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center justify-between px-6 shadow-lg"
    >
        <!-- Brand logo -->
        <a href="{{ route('storefront') }}" class="flex items-center space-x-3 group/brand">
            <span class="text-[10px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
            <span :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
        </a>

        <!-- Middle Page Title -->
        <span class="hidden md:inline font-sans uppercase tracking-[0.3em] text-[10px] text-neutral-400">
            ✦ Bespoke Curation Studio ✦
        </span>

        <!-- Right header details -->
        <div class="flex items-center space-x-6">
            <!-- Theme selectors -->
            <div class="flex items-center space-x-1 bg-[#0F0F12]/80 border border-neutral-800/60 p-1 rounded-full text-[10px] font-mono">
                <button @click="theme = 'onyx'" :class="theme === 'onyx' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Onyx</button>
                <button @click="theme = 'champagne'" :class="theme === 'champagne' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Champagne</button>
                <button @click="theme = 'rose'" :class="theme === 'rose' ? 'bg-[#C5A880] text-black font-semibold' : 'text-neutral-400 hover:text-white'" class="px-2.5 py-1 rounded-full transition-all cursor-pointer">Rose</button>
            </div>

            <!-- Back link -->
            <a href="{{ route('storefront') }}" class="text-xs uppercase tracking-widest text-[#C5A880] hover:text-white transition-colors duration-300 font-medium">
                Showroom
            </a>
        </div>
    </header>

    <!-- Main split workspace -->
    <main class="flex-1 pt-24 pb-32 px-4 md:px-6 z-10 flex flex-col lg:flex-row gap-6 max-w-7xl mx-auto w-full">
        
        <!-- Left Panel: Fixed Visual Anchor & Invoice Summary (Locked on Desktop) -->
        <div class="w-full lg:w-5/12 lg:sticky lg:top-24 lg:h-[calc(100vh-8rem)] lg:max-h-[760px] lg:min-h-[580px] lg:overflow-hidden rounded-3xl border border-neutral-800/20 relative flex flex-col justify-between p-6 bg-black/10 backdrop-blur-md shadow-2xl">
            
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
                    <!-- Blurred Background Layer for Rich depth -->
                    <div class="absolute inset-0 scale-110 blur-xl opacity-30 pointer-events-none transition-all duration-500">
                        <img :src="hoveredImage || (viewMode === 'arrangement' ? getStemImage() : getWrapImage())" alt="" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Centered Contained Image -->
                    <img :src="hoveredImage || (viewMode === 'arrangement' ? getStemImage() : getWrapImage())" alt="Preview" class="relative z-10 w-full h-full object-contain p-4 transition-all duration-500">
                    
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
                        <!-- Stems items -->
                        <template x-for="(qty, id) in selectedStems" :key="id">
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
                        <template x-for="(qty, id) in selectedGifts" :key="'gift-'+id">
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
                            @mouseenter="if ({{ $stem->stock }} > 0) { hoveredImage = '{{ $stem->image_url }}'; hoveredName = '{{ addslashes($stem->name) }}'; viewMode = 'arrangement'; }"
                            @mouseleave="hoveredImage = null; hoveredName = null;"
                            :class="{
                                'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (selectedStems[{{ $stem->id }}] || 0) > 0 && {{ $stem->stock }} > 0,
                                'border-neutral-800/20': !((selectedStems[{{ $stem->id }}] || 0) > 0) || {{ $stem->stock }} <= 0,
                                'bg-white/80': theme === 'champagne',
                                'bg-[#09090D]/40': theme !== 'champagne',
                                'opacity-60 saturate-50': {{ $stem->stock }} <= 0,
                                'animate-shake border-red-500/50': shake,
                                'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $stem->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $stem->stock }} > 0,
                                'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $stem->stock }} > 0
                            }"
                            class="flex flex-col justify-between p-5 rounded-2xl border transition-all relative group"
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
                                    <p class="text-[9px] text-neutral-450 mt-1 line-clamp-2 leading-relaxed">{{ $stem->description }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest font-mono text-neutral-400 block">Per Stem Price</span>
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($stem->price) }} KSH</span>
                                </div>

                                <!-- Quantity adjuster pill -->
                                <div class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                    <button 
                                        type="button"
                                        @click="if ({{ $stem->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($stem->name) }} is currently out of stock.'); } else { $wire.adjustStemQuantity({{ $stem->id }}, -1); viewMode = 'arrangement'; }"
                                        class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                    >-</button>
                                    <span class="font-mono font-medium min-w-[16px] text-center" x-text="selectedStems[{{ $stem->id }}] || 0"></span>
                                    <button 
                                        type="button"
                                        @click="if ({{ $stem->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($stem->name) }} is currently out of stock.'); } else { $wire.adjustStemQuantity({{ $stem->id }}, 1); viewMode = 'arrangement'; }"
                                        class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
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
                                @mouseenter="if ({{ $wine->stock }} > 0) { hoveredImage = '{{ $wine->image_url }}'; hoveredName = '{{ addslashes($wine->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (selectedGifts[{{ $wine->id }}] || 0) > 0 && {{ $wine->stock }} > 0,
                                    'border-neutral-800/20': !(selectedGifts[{{ $wine->id }}] || 0) > 0 || {{ $wine->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $wine->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $wine->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $wine->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $wine->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group"
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
                                    
                                    <div class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click="if ({{ $wine->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wine->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $wine->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="selectedGifts[{{ $wine->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click="if ({{ $wine->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($wine->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $wine->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
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
                                @mouseenter="if ({{ $choc->stock }} > 0) { hoveredImage = '{{ $choc->image_url }}'; hoveredName = '{{ addslashes($choc->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (selectedGifts[{{ $choc->id }}] || 0) > 0 && {{ $choc->stock }} > 0,
                                    'border-neutral-800/20': !(selectedGifts[{{ $choc->id }}] || 0) > 0 || {{ $choc->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $choc->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $choc->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $choc->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $choc->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group"
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
                                        <p class="text-[9px] text-neutral-400 mt-0.5 line-clamp-2 leading-tight">{{ $choc->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center border-t border-neutral-800/10 pt-3">
                                    <span class="text-xs font-semibold font-mono text-[#C5A880]">{{ number_format($choc->price) }} KSH</span>
                                    
                                    <div class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click="if ({{ $choc->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($choc->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $choc->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="selectedGifts[{{ $choc->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click="if ({{ $choc->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($choc->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $choc->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
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
                                @mouseenter="if ({{ $jewel->stock }} > 0) { hoveredImage = '{{ $jewel->image_url }}'; hoveredName = '{{ addslashes($jewel->name) }}'; }"
                                @mouseleave="hoveredImage = null; hoveredName = null;"
                                :class="{
                                    'border-[#C5A880] bg-[#C5A880]/5 shadow-lg': (selectedGifts[{{ $jewel->id }}] || 0) > 0 && {{ $jewel->stock }} > 0,
                                    'border-neutral-800/20': !(selectedGifts[{{ $jewel->id }}] || 0) > 0 || {{ $jewel->stock }} <= 0,
                                    'bg-white/80': theme === 'champagne',
                                    'bg-[#09090D]/40': theme !== 'champagne',
                                    'opacity-60 saturate-50': {{ $jewel->stock }} <= 0,
                                    'animate-shake border-red-500/50': shake,
                                    'hover:shadow-[0_0_20px_rgba(197,168,128,0.15)] hover:border-[#C5A880]/40': theme === 'onyx' && {{ $jewel->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(181,154,122,0.25)] hover:border-[#B59A7A]/40': theme === 'champagne' && {{ $jewel->stock }} > 0,
                                    'hover:shadow-[0_0_20px_rgba(183,110,121,0.25)] hover:border-[#B76E79]/40': theme === 'rose' && {{ $jewel->stock }} > 0
                                }"
                                class="flex flex-col justify-between p-4 rounded-2xl border transition-all relative group"
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
                                    
                                    <div class="flex items-center space-x-3 bg-black/20 rounded-full p-1 border border-neutral-800/40 text-xs">
                                        <button 
                                            type="button"
                                            @click="if ({{ $jewel->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($jewel->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $jewel->id }}, -1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
                                        >-</button>
                                        <span class="font-mono font-medium min-w-[16px] text-center" x-text="selectedGifts[{{ $jewel->id }}] || 0"></span>
                                        <button 
                                            type="button"
                                            @click="if ({{ $jewel->stock }} <= 0) { shake = true; setTimeout(() => shake = false, 500); $dispatch('curation-error', '{{ addslashes($jewel->name) }} is currently out of stock.'); } else { $wire.adjustGiftQuantity({{ $jewel->id }}, 1); }"
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-neutral-400 hover:text-white hover:bg-neutral-850 transition cursor-pointer select-none"
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

    <!-- Mobile Sticky Footer Bar (visible only on mobile/tablet) -->
    <div 
        :class="theme === 'champagne' ? 'bg-white/95 border-neutral-200 shadow-lg' : 'bg-[#050507]/95 border-neutral-800/20 shadow-2xl'"
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

                <!-- Stems items -->
                <template x-for="(qty, id) in selectedStems" :key="id">
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
                <template x-for="(qty, id) in selectedGifts" :key="'gift-'+id">
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
                    hoveredImage: null,
                    hoveredName: null,
                    activeSection: 'section-blooms',
                    showMobileLedger: false,

                    init() {
                        this.theme = localStorage.getItem('nb_theme') || 'onyx';
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
                            var sections = ['section-blooms', 'section-wrapping', 'section-scent', 'section-gifts', 'section-card'];
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
                        for (var id in this.selectedStems) {
                            if (this.selectedStems[id] > 0) {
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
                        for (var id in this.selectedStems) {
                            var qty = this.selectedStems[id];
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
                        for (var id in this.selectedStems) {
                            count += parseInt(this.selectedStems[id] || 0);
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
                        for (var id in this.selectedGifts) {
                            count += parseInt(this.selectedGifts[id] || 0);
                        }
                        return count;
                    }
                };
            });
        });
    </script>
</div>
