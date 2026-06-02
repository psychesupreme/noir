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
    <canvas id="flower-ambient-canvas" class="fixed inset-0 pointer-events-none z-0"></canvas>

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
        <div class="max-w-7xl w-full mx-auto px-6 flex items-center justify-between gap-8">
            <a href="/" class="shrink-0 flex items-baseline space-x-2 select-none cursor-pointer">
                <span class="text-[11px] font-mono tracking-[0.4em] text-[#C5A880] uppercase">Atelier</span>
                <span :class="theme === 'champagne' ? 'text-black' : 'text-white'" class="text-base font-semibold uppercase tracking-[0.35em] transition-colors font-outfit">NOIR & BLOOM</span>
            </a>
            
            <div class="flex items-center space-x-6 text-[12px] font-mono uppercase tracking-widest text-neutral-400">
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

    <main class="max-w-7xl w-full mx-auto px-6 pt-32 flex-1 flex flex-col z-10 relative">
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
            <div class="grid grid-cols-1 gap-6">
                @foreach($services as $srv)
                    <div 
                        :class="theme === 'champagne' ? 'border-neutral-200 bg-white/70 shadow-sm text-neutral-900' : 'border-neutral-900/60 bg-[#0C0C0E]/50 text-white'"
                        class="flex flex-col md:flex-row rounded-3xl border overflow-hidden backdrop-blur-md transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 group"
                    >
                        <!-- Left image -->
                        <div class="w-full md:w-1/3 aspect-[4/3] md:aspect-auto relative overflow-hidden bg-neutral-900/10">
                            <img src="{{ $srv->image_url }}" alt="{{ $srv->name }}" class="absolute inset-0 w-full h-full object-cover transition-all duration-750 group-hover:scale-105">
                            <div class="absolute top-4 left-4">
                                <span class="bg-[#C5A880] text-black px-3 py-1 rounded-full text-[10px] font-mono font-bold tracking-wider uppercase">
                                    {{ $srv->grade ?? 'Specialization' }}
                                </span>
                            </div>
                        </div>
                        <!-- Right details -->
                        <div class="p-6 md:p-8 flex-1 flex flex-col justify-between space-y-6">
                            <div class="space-y-3">
                                <h4 class="text-xl font-outfit font-semibold uppercase tracking-wider">{{ $srv->name }}</h4>
                                <p class="text-neutral-500 font-light text-sm leading-relaxed">{{ $srv->description }}</p>
                                
                                <!-- Social Sharing Direct Links for SMM -->
                                <div class="flex items-center space-x-3 text-[10px] font-mono uppercase tracking-wider text-neutral-450 mt-2">
                                    <span class="text-neutral-500 text-[9px] uppercase tracking-widest font-bold">Share:</span>
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20this%20exclusive%20{{ urlencode($srv->name) }}%20service%20at%20Noir%20%26%20Bloom:%20{{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors font-bold" title="Share via WhatsApp">WA</a>
                                    <a href="https://twitter.com/intent/tweet?text=Bespoke%20{{ urlencode($srv->name) }}%20service%20from%20@NoirAndBloom:&url={{ urlencode(url('/services-gifts')) }}" 
                                       target="_blank" rel="noopener" class="hover:text-white transition-colors font-bold" title="Share on X">X</a>
                                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url('/services-gifts')) }}&media={{ urlencode($srv->image_url) }}&description=Bespoke%20Floral%20Service%20-%20{{ urlencode($srv->name) }}" 
                                       target="_blank" rel="noopener" class="hover:text-rose-500 transition-colors font-bold" title="Pin to Pinterest">PIN</a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-t border-neutral-500/10 pt-4">
                                <div>
                                    <span class="text-[10px] font-mono uppercase text-neutral-400 tracking-wider block">Service Base Investment</span>
                                    <span class="text-xl font-mono font-bold text-amber-500">{{ number_format($srv->price) }} KSH</span>
                                </div>
                                <a 
                                    href="/profile-portal"
                                    :class="theme === 'champagne' ? 'bg-black text-white hover:bg-[#B59A7A] hover:text-black' : 'bg-white text-black hover:bg-[#C5A880] hover:text-black'"
                                    class="px-6 py-3 rounded-full text-[11px] font-mono uppercase font-bold tracking-wider transition-all duration-300 shadow-md cursor-pointer font-sans"
                                >
                                    Request Consultation
                                </a>
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
        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-left">
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
        <div class="max-w-7xl w-full mx-auto border-t mt-12 pt-8 text-center text-[12px] font-mono uppercase tracking-wider">
            <p>&copy; {{ date('Y') }} Noir &amp; Bloom Ltd. Registered Tax Entity.</p>
        </div>
    </footer>

    <!-- Script for background canvas animation (Reynolds Flocking Boids) -->
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

                const particleCount = 60;
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
                    const speed = Math.random() * 0.8 + 0.3;
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: Math.cos(angle) * speed,
                        vy: Math.sin(angle) * speed,
                        r: Math.random() * 8 + 4,
                        d: Math.random() * particleCount,
                        angle: Math.random() * 360,
                        rotationSpeed: Math.random() * 0.8 - 0.4,
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
                        p.color = colors[idx % colors.length] + '55';

                        if (p.type === 'flower') {
                            drawFlower(p.x, p.y, p.r, 5, p.color, p.angle);
                        } else {
                            drawPetal(p.x, p.y, p.r, p.color, p.angle);
                        }

                        // Basic Reynolds Flocking
                        let flockVx = 0; let flockVy = 0;
                        let flockX = 0; let flockY = 0;
                        let avoidX = 0; let avoidY = 0;
                        let neighbors = 0; let closeNeighbors = 0;

                        const visualRange = 80;
                        const minDistance = 25;

                        particles.forEach(other => {
                            if (other === p) return;
                            let dx = other.x - p.x;
                            let dy = other.y - p.y;
                            let dist = Math.sqrt(dx*dx + dy*dy);
                            if (dist < visualRange) {
                                flockX += other.x; flockY += other.y;
                                flockVx += other.vx; flockVy += other.vy;
                                neighbors++;
                                if (dist < minDistance) {
                                    avoidX -= dx; avoidY -= dy;
                                    closeNeighbors++;
                                }
                            }
                        });

                        let ax = 0; let ay = 0;
                        if (neighbors > 0) {
                            ax += (flockX / neighbors - p.x) * 0.003;
                            ay += (flockY / neighbors - p.y) * 0.003;
                            ax += (flockVx / neighbors - p.vx) * 0.02;
                            ay += (flockVy / neighbors - p.vy) * 0.02;
                        }
                        if (closeNeighbors > 0) {
                            ax += avoidX * 0.04;
                            ay += avoidY * 0.04;
                        }

                        if (mouse.active) {
                            let mDx = p.x - mouse.x;
                            let mDy = p.y - mouse.y;
                            let mDist = Math.sqrt(mDx*mDx + mDy*mDy);
                            if (mDist < 180) {
                                let force = (180 - mDist) / 180;
                                let angle = Math.atan2(mDy, mDx);
                                ax += Math.cos(angle) * force * 1.2;
                                ay += Math.sin(angle) * force * 1.2;
                            }
                        }

                        p.vx += ax; p.vy += ay;
                        let speed = Math.sqrt(p.vx*p.vx + p.vy*p.vy);
                        const minS = 0.4; const maxS = 1.8;
                        if (speed > maxS) {
                            p.vx = (p.vx/speed)*maxS; p.vy = (p.vy/speed)*maxS;
                        } else if (speed < minS) {
                            if (speed === 0) {
                                p.vx = Math.random()*minS; p.vy = Math.random()*minS;
                            } else {
                                p.vx = (p.vx/speed)*minS; p.vy = (p.vy/speed)*minS;
                            }
                        }

                        p.x += p.vx; p.y += p.vy;
                        p.angle = Math.atan2(p.vy, p.vx) * (180 / Math.PI) + 90;

                        const margin = p.r * 2 + 10;
                        if (p.x < -margin) p.x = width + margin;
                        else if (p.x > width + margin) p.x = -margin;
                        if (p.y < -margin) p.y = height + margin;
                        else if (p.y > height + margin) p.y = -margin;
                    });

                    requestAnimationFrame(animate);
                }
                animate();
            }
        });
    </script>
</div>
