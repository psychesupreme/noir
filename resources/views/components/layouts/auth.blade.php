<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Noir & Bloom | Authentication' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            const storedTheme = localStorage.getItem('nb_theme');
            const theme = (storedTheme === 'onyx' || storedTheme === 'dark') ? 'dark' : 'light';
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <style>
        @keyframes grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-2%, -2%); }
            20% { transform: translate(2%, 2%); }
            30% { transform: translate(-1%, 3%); }
            40% { transform: translate(3%, -1%); }
            50% { transform: translate(-3%, 1%); }
            60% { transform: translate(1%, -3%); }
            70% { transform: translate(-2%, 2%); }
            80% { transform: translate(2%, -2%); }
            90% { transform: translate(-1%, 1%); }
        }
        .grain::after {
            content: '';
            position: absolute;
            inset: -50%;
            width: 200%;
            height: 200%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            animation: grain 8s steps(10) infinite;
            pointer-events: none;
            z-index: 1;
        }
        .auth-gradient-onyx {
            background: radial-gradient(ellipse at 20% 80%, rgba(197, 168, 128, 0.05) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(139, 92, 246, 0.03) 0%, transparent 50%),
                        linear-gradient(180deg, #050507 0%, #0A0A0E 50%, #050507 100%);
        }
        .auth-gradient-champagne {
            background: radial-gradient(ellipse at 20% 80%, rgba(181, 154, 122, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(212, 175, 55, 0.04) 0%, transparent 50%),
                        linear-gradient(180deg, #FAF7F0 0%, #F5F0E8 50%, #FAF7F0 100%);
        }
        .auth-gradient-rose {
            background: radial-gradient(ellipse at 20% 80%, rgba(244, 114, 182, 0.06) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(183, 110, 121, 0.04) 0%, transparent 50%),
                        linear-gradient(180deg, #15060A 0%, #2D0D19 50%, #15060A 100%);
        }
        .ambient-glow-onyx {
            background: radial-gradient(circle, rgba(197, 168, 128, 0.05) 0%, rgba(139, 92, 246, 0.01) 50%, transparent 100%);
        }
        .ambient-glow-champagne {
            background: radial-gradient(circle, rgba(181, 154, 122, 0.1) 0%, rgba(212, 175, 55, 0.01) 60%, transparent 100%);
        }
        .ambient-glow-rose {
            background: radial-gradient(circle, rgba(244, 114, 182, 0.06) 0%, rgba(183, 110, 121, 0.01) 50%, transparent 100%);
        }
    </style>
</head>
<body x-data="{ theme: (localStorage.getItem('nb_theme') === 'dark' || localStorage.getItem('nb_theme') === 'light') ? localStorage.getItem('nb_theme') : 'light' }" class="bg-bg-base text-text-primary antialiased font-sans transition-colors duration-500 selection:bg-[#C5A880]/30 selection:text-neutral-200 min-h-screen">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Left Brand Panel — desktop only --}}
        <div 
            :class="{
                'auth-gradient-onyx border-neutral-900/40': theme === 'dark',
                'auth-gradient-champagne border-[#E8E2D5]/50': theme === 'light'
            }"
            class="hidden lg:flex lg:w-[45%] xl:w-[42%] relative overflow-hidden grain transition-colors duration-500"
        >

            {{-- Background Image with dynamic filtering --}}
            <div class="absolute inset-0 bg-cover bg-center select-none z-0"
                 :class="{
                     'filter brightness-[0.45] contrast-[1.15] saturate-[0.8]': theme === 'dark',
                     'filter brightness-[0.95] sepia-[0.1] saturate-[0.8]': theme === 'light'
                 }"
                 style="background-image: url('{{ asset('media/auth_flower_bg.png') }}');">
            </div>

            <!-- Linear Theme Blending Overlay -->
            <div class="absolute inset-0 pointer-events-none mix-blend-multiply z-0"
                 :class="{
                     'bg-gradient-to-b from-[#0B0B0D]/90 via-[#0B0B0D]/40 to-[#0B0B0D]/90': theme === 'dark',
                     'bg-gradient-to-b from-[#FAF7F0]/90 via-[#FAF7F0]/60 to-[#FAF7F0]/90': theme === 'light'
                 }">
            </div>

            <!-- Secondary Color Burn Blending -->
            <div class="absolute inset-0 pointer-events-none mix-blend-color-burn opacity-60 z-0"
                 :class="{
                     'bg-[#0C1E1A]/10': theme === 'dark',
                     'bg-[#C5A880]/15': theme === 'light'
                 }">
            </div>

            {{-- Decorative border line --}}
            <div 
                :class="theme === 'light' ? 'via-[#E8E2D5]/40' : 'via-neutral-800/50'"
                class="absolute top-0 right-0 w-px h-full bg-gradient-to-b from-transparent to-transparent z-10"
            ></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between w-full h-full min-h-screen p-12 xl:p-16">

                {{-- Top area --}}
                <div>
                    <div :class="theme === 'light' ? 'bg-[#E8E2D5]' : 'bg-neutral-800'" class="w-8 h-px mb-6 transition-colors"></div>
                    <span 
                        :class="theme === 'light' ? 'text-[#78716C]' : 'text-neutral-600'"
                        class="text-[9px] font-mono tracking-[0.4em] uppercase block transition-colors"
                    >Est. Nairobi</span>
                </div>

                {{-- Center brand mark --}}
                <div class="space-y-8 my-auto py-12">
                    <div class="space-y-4">
                        <span class="text-[11px] font-mono tracking-[0.5em] text-[#D4AF37] uppercase block">Atelier</span>
                        <h1 
                            :class="theme === 'light' ? 'text-[#1C1917]' : 'text-white'"
                            class="text-4xl xl:text-5xl font-serif italic tracking-[0.2em] leading-tight transition-colors"
                        >
                            Noir &amp; Bloom
                        </h1>
                    </div>

                    {{-- Decorative divider --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-px bg-gradient-to-r from-[#D4AF37]/45 to-transparent"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]/40"></div>
                        <div class="w-8 h-px bg-gradient-to-r from-[#D4AF37]/25 to-transparent"></div>
                    </div>

                    <div class="space-y-4">
                        <p 
                            :class="theme === 'light' ? 'text-[#78716C]' : 'text-neutral-450'"
                            class="text-xs font-light tracking-[0.2em] uppercase leading-relaxed max-w-[320px] transition-colors font-mono"
                        >
                            Premium Floral &amp; Gifting Concierge
                        </p>
                        <p 
                            :class="theme === 'light' ? 'text-[#1C1917]/70' : 'text-neutral-500'"
                            class="text-xs font-light leading-relaxed max-w-[300px] transition-colors font-sans"
                        >
                            Bespoke arrangements, curated luxury giftings, and concierge dispatch across Kenya.
                        </p>
                    </div>
                </div>

                {{-- Bottom footer --}}
                <div class="space-y-4">
                    <div :class="theme === 'light' ? 'bg-[#E8E2D5]' : 'bg-neutral-850'" class="w-16 h-px transition-colors"></div>
                    <div class="flex items-center gap-6">
                        <span 
                            :class="theme === 'light' ? 'text-[#78716C]' : 'text-neutral-700'"
                            class="text-[8px] font-mono tracking-[0.3em] uppercase transition-colors"
                        >Nairobi</span>
                        <span :class="theme === 'light' ? 'text-[#E8E2D5]' : 'text-neutral-800'" class="text-[8px] transition-colors">&bull;</span>
                        <span 
                            :class="theme === 'light' ? 'text-[#78716C]' : 'text-neutral-700'"
                            class="text-[8px] font-mono tracking-[0.3em] uppercase transition-colors"
                        >Kiambu</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Right Form Panel --}}
        <div 
            :class="{
                'bg-[#0B0B0D]': theme === 'dark',
                'bg-[#FAF7F0]': theme === 'light'
            }"
            class="flex-1 flex flex-col min-h-screen relative z-10 transition-colors duration-500 overflow-hidden"
        >

            {{-- Ambient Glow blob inside the form side --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
                <div 
                    :class="{
                        'ambient-glow-onyx': theme === 'dark',
                        'ambient-glow-champagne': theme === 'light'
                    }"
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full blur-3xl opacity-90 transition-all duration-500"
                ></div>
            </div>

            {{-- Mobile brand header — visible only on small screens --}}
            <div class="lg:hidden px-6 pt-10 pb-6 text-center space-y-2 z-10">
                <span class="text-[8px] font-mono tracking-[0.4em] text-[#D4AF37]/50 uppercase block">Atelier</span>
                <h1 
                    :class="theme === 'light' ? 'text-[#1C1917]' : 'text-white/90'"
                    class="text-sm font-semibold uppercase tracking-[0.3em] transition-colors"
                >Noir &amp; Bloom</h1>
                <div :class="theme === 'light' ? 'bg-[#E8E2D5]' : 'bg-neutral-800'" class="w-8 h-px mx-auto mt-3 transition-colors"></div>
            </div>

            {{-- Centered form slot --}}
            <div class="flex-1 flex items-center justify-center px-6 py-8 lg:py-12 z-10">
                <div class="w-full max-w-[420px]">
                    {{ $slot }}
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="px-6 pb-6 text-center">
                <p 
                    :class="theme === 'light' ? 'text-[#78716C]' : 'text-neutral-700'"
                    class="text-[9px] font-mono tracking-[0.2em] uppercase transition-colors"
                >
                    &copy; {{ date('Y') }} Noir &amp; Bloom &mdash; All rights reserved
                </p>
            </div>

        </div>

    </div>

    @livewireScripts
</body>
</html>
