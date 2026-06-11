@section('meta')
    <meta name="description" content="Explore Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
    <meta name="keywords" content="wedding flowers Kenya, corporate flower subscription Nairobi, flower subscriptions Kiambu, custom gift hampers Nairobi, luxury event florist">
    <meta name="author" content="Noir & Bloom Atelier">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph (Facebook / Pinterest / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Bespoke Services &amp; Luxury Gifting Suites | Noir &amp; Bloom">
    <meta property="og:description" content="Explore Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
    <meta property="og:image" content="{{ asset('media/og-services.jpg') }}">
    <meta property="og:site_name" content="Noir & Bloom">
    <meta property="og:locale" content="en_KE">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Bespoke Services &amp; Luxury Gifting Suites | Noir &amp; Bloom">
    <meta name="twitter:description" content="Explore Noir & Bloom's specialized corporate floral services, wedding designs, workspace subscriptions, and premium gifting hampers. Order directly online.">
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
          "name": "Noir &amp; Bloom - Nairobi Atelier",
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
          "name": "Noir &amp; Bloom - Kiambu Atelier",
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
        theme: localStorage.getItem('nb_theme') || 'onyx', 
        accountPanelOpen: false
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
    <div class="absolute inset-0 pointer-events-none storefront-grain z-0 opacity-80"></div>
    
    <!-- Cohesive Header -->
    <header 
        :class="{
            'bg-[#050507]/75 border-neutral-950/20 shadow-2xl': theme === 'onyx',
            'bg-white/75 border-neutral-200/50 shadow-sm': theme === 'champagne',
            'bg-[#15060A]/75 border-[#2D0D19]/30 shadow-2xl': theme === 'rose'
        }"
        class="fixed top-4 inset-x-4 h-16 backdrop-blur-md border rounded-full z-50 transition-all duration-500 flex items-center shadow-lg hover:shadow-xl group"
    >
        <!-- Gold Accent Bottom Glow Line -->
        <div class="absolute bottom-0 inset-x-8 h-[1px] bg-gradient-to-r from-transparent via-[#C5A880]/30 to-transparent"></div>
        <div class="max-w-8xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 select-none cursor-pointer">
                <span class="text-[11px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
                <span :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
            </a>
            
            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
                <!-- Navigation links -->
                <a href="{{ route('curate') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 select-none cursor-pointer">3D Curation</a>
                <a href="{{ route('services-gifts') }}" class="hidden md:inline-block hover:text-[#C5A880] transition-colors duration-300 select-none cursor-pointer text-[#C5A880] font-semibold">Services</a>

                <!-- Theme Switcher Pill -->
                <div class="hidden lg:flex items-center space-x-1 border border-neutral-500/10 rounded-full bg-neutral-500/5 p-1 select-none">
                    <button @click="theme = 'onyx'" :class="theme === 'onyx' ? 'bg-[#C5A880] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Onyx</span>
                    </button>
                    <button @click="theme = 'champagne'" :class="theme === 'champagne' ? 'bg-[#B59A7A] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Champagne</span>
                    </button>
                    <button @click="theme = 'rose'" :class="theme === 'rose' ? 'bg-[#B76E79] text-black shadow-sm font-semibold' : 'text-neutral-400 hover:text-neutral-200'" class="px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-wider transition-all duration-300 flex items-center space-x-1 cursor-pointer">
                        <span>Rose</span>
                    </button>
                </div>

                <!-- Back to Shop -->
                <a href="/" class="hover:text-neutral-300 transition-colors flex items-center space-x-1.5 text-xs font-mono font-medium tracking-wider">
                    <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Back to Shop</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-8xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col z-10 relative">
        <div class="space-y-4 mb-12">
            <span class="text-[12px] font-mono uppercase tracking-[0.4em] text-[#C5A880] block">Noir & Bloom Atelier</span>
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
                                <div class="flex items-center space-x-2.5 text-[10px] font-mono uppercase text-neutral-400">
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
                                    <a href="https://twitter.com/intent/tweet?text=Bespoke+{{ urlencode($srv->name) }}+service+from+@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" target="_blank" rel="noopener" class="hover:text-white transition-colors" title="Share on X">
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
                                <div class="flex items-center space-x-2 text-[9px] font-mono uppercase tracking-wider text-neutral-450 pt-1">
                                    <span class="text-neutral-500 font-bold">Share:</span>
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20the%20exclusive%20{{ urlencode($gift->name) }}%20gift%20accent%20at%20Noir%20%26%20Bloom:%20{{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors font-bold" title="Share via WhatsApp">WA</a>
                                    <a href="https://twitter.com/intent/tweet?text=Premium%20{{ urlencode($gift->name) }}%20gift%20at%20@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-white transition-colors font-bold" title="Share on X">X</a>
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

    <!-- Luxury Atelier Footer -->
    <footer 
        :class="{
            'border-neutral-900 bg-[#070709] text-neutral-400': theme === 'onyx',
            'border-neutral-200 bg-[#EBEBEF] text-neutral-600': theme === 'champagne',
            'border-[#2D0D19]/40 bg-[#1D0C13] text-neutral-300': theme === 'rose'
        }"
        class="border-t py-16 px-6 transition-colors duration-500 z-10 relative"
    >
        <div class="max-w-8xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-left">
            <div class="space-y-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-[10px] font-mono tracking-[0.4em] text-neutral-500 uppercase">Atelier</span>
                    <h4 :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-sm font-semibold uppercase tracking-[0.35em]">NOIR & BLOOM</h4>
                </div>
                <p class="text-xs font-light leading-relaxed max-w-xs">
                    Premium floral curation, bespoke gifting suites, and high-end events concierge. Sourcing directly from Rift Valley growers.
                </p>
            </div>
            <div class="space-y-4">
                <h5 class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Directory</h5>
                <ul class="space-y-2 text-xs font-light">
                    <li><a href="/" class="hover:underline">Bespoke Catalog</a></li>
                    <li><a href="/services-gifts" class="hover:underline">Concierge Services</a></li>
                    <li><a href="/profile-portal" class="hover:underline">Profile Portal</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h5 class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">Concierge Dispatch</h5>
                <p class="text-xs font-light">
                    Operating Hours: Mon &mdash; Sat: 07:00 &mdash; 20:00<br>
                    Hotline: +254 (0) 712 345 678
                </p>
            </div>
            <div class="space-y-4">
                <h5 class="text-[12px] font-mono uppercase tracking-[0.2em] font-semibold">The Atelier Bulletin</h5>
                <p class="text-xs font-light">Join the circle for seasonal launches and custom service upgrades.</p>
            </div>
        </div>
        <div class="max-w-8xl w-full mx-auto border-t mt-12 pt-8 text-center text-[12px] font-mono uppercase tracking-wider">
            <p>&copy; {{ date('Y') }} Noir &amp; Bloom Ltd. Registered Tax Entity.</p>
        </div>
    </footer>


</div>
