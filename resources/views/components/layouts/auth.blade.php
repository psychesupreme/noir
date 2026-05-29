<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
            const theme = localStorage.getItem('nb_theme') || 'midnight';
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
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
            document.documentElement.style.backgroundColor = bgColors[theme];
            document.documentElement.style.color = textColors[theme];
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
        .auth-gradient-midnight {
            background: radial-gradient(ellipse at 20% 80%, rgba(212, 175, 55, 0.04) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(16, 185, 129, 0.03) 0%, transparent 50%),
                        linear-gradient(180deg, #0A0A0A 0%, #0F0F12 50%, #09090B 100%);
        }
        .auth-gradient-alabaster {
            background: radial-gradient(ellipse at 20% 80%, rgba(212, 175, 55, 0.06) 0%, transparent 50%),
                        linear-gradient(180deg, #F4F4F6 0%, #E4E4E7 50%, #D4D4D8 100%);
        }
        .auth-gradient-floral {
            background: radial-gradient(ellipse at 20% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                        linear-gradient(180deg, #121A16 0%, #1A2620 50%, #0F1412 100%);
        }
        .auth-gradient-love {
            background: radial-gradient(ellipse at 20% 80%, rgba(244, 63, 94, 0.08) 0%, transparent 50%),
                        linear-gradient(180deg, #1C0D12 0%, #2A141B 50%, #15090C 100%);
        }
        .auth-gradient-cute {
            background: radial-gradient(ellipse at 20% 80%, rgba(236, 72, 153, 0.08) 0%, transparent 50%),
                        linear-gradient(180deg, #24181B 0%, #352328 50%, #1B1214 100%);
        }
    </style>
</head>
<body 
    x-data="{ theme: localStorage.getItem('nb_theme') || 'midnight' }"
    :class="{
        'bg-[#09090B] text-[#FAFAFA]': theme === 'midnight',
        'bg-[#F4F4F6] text-[#1C1C1E]': theme === 'alabaster',
        'bg-[#121A16] text-[#EDF2EF]': theme === 'floral',
        'bg-[#1C0D12] text-[#FDF4F7]': theme === 'love',
        'bg-[#24181B] text-[#FFF5F7]': theme === 'cute'
    }"
    class="antialiased selection:bg-rose-950 selection:text-rose-200 min-h-screen font-[Plus_Jakarta_Sans] transition-colors duration-500"
>

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Left Brand Panel — desktop only --}}
        <div 
            :class="{
                'auth-gradient-midnight border-neutral-900/40': theme === 'midnight',
                'auth-gradient-alabaster border-neutral-200/50': theme === 'alabaster',
                'auth-gradient-floral border-emerald-950/30': theme === 'floral',
                'auth-gradient-love border-[#3D1A27]/30': theme === 'love',
                'auth-gradient-cute border-[#4A323A]/30': theme === 'cute'
            }"
            class="hidden lg:flex lg:w-[45%] xl:w-[42%] relative overflow-hidden grain transition-colors duration-500"
        >

            {{-- Decorative border line --}}
            <div 
                :class="theme === 'alabaster' ? 'via-neutral-300/40' : 'via-neutral-800/50'"
                class="absolute top-0 right-0 w-px h-full bg-gradient-to-b from-transparent to-transparent"
            ></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between w-full h-full min-h-screen p-12 xl:p-16">

                {{-- Top area --}}
                <div>
                    <div :class="theme === 'alabaster' ? 'bg-neutral-300' : 'bg-neutral-800'" class="w-8 h-px mb-6 transition-colors"></div>
                    <span 
                        :class="theme === 'alabaster' ? 'text-neutral-500' : 'text-neutral-600'"
                        class="text-[9px] font-mono tracking-[0.4em] uppercase block transition-colors"
                    >Est. Nairobi</span>
                </div>

                {{-- Center brand mark --}}
                <div class="space-y-8">
                    <div class="space-y-3">
                        <span class="text-[9px] font-mono tracking-[0.4em] text-[#D4AF37]/60 uppercase block">Atelier</span>
                        <h1 
                            :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white/90'"
                            class="text-[22px] xl:text-[26px] font-semibold uppercase tracking-[0.3em] leading-tight transition-colors"
                        >
                            Noir &amp; Bloom
                        </h1>
                    </div>

                    {{-- Decorative divider --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-px bg-gradient-to-r from-[#D4AF37]/40 to-transparent"></div>
                        <div class="w-1 h-1 rounded-full bg-[#D4AF37]/30"></div>
                        <div class="w-8 h-px bg-gradient-to-r from-[#D4AF37]/20 to-transparent"></div>
                    </div>

                    <div class="space-y-4">
                        <p 
                            :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                            class="text-[11px] font-light tracking-[0.15em] uppercase leading-relaxed max-w-[280px] transition-colors"
                        >
                            Premium Floral &amp; Gifting Concierge
                        </p>
                        <p 
                            :class="theme === 'alabaster' ? 'text-neutral-700' : 'text-neutral-650'"
                            class="text-[10px] font-light leading-relaxed max-w-[260px] transition-colors"
                        >
                            Bespoke arrangements, curated luxury giftings, and concierge dispatch across Kenya.
                        </p>
                    </div>
                </div>

                {{-- Bottom footer --}}
                <div class="space-y-4">
                    <div :class="theme === 'alabaster' ? 'bg-neutral-300' : 'bg-neutral-850'" class="w-16 h-px transition-colors"></div>
                    <div class="flex items-center gap-6">
                        <span 
                            :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-700'"
                            class="text-[8px] font-mono tracking-[0.3em] uppercase transition-colors"
                        >Nairobi</span>
                        <span :class="theme === 'alabaster' ? 'text-neutral-400' : 'text-neutral-800'" class="text-[8px] transition-colors">&bull;</span>
                        <span 
                            :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-700'"
                            class="text-[8px] font-mono tracking-[0.3em] uppercase transition-colors"
                        >Kiambu</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="flex-1 flex flex-col min-h-screen">

            {{-- Mobile brand header — visible only on small screens --}}
            <div class="lg:hidden px-6 pt-10 pb-6 text-center space-y-2">
                <span class="text-[8px] font-mono tracking-[0.4em] text-[#D4AF37]/50 uppercase block">Atelier</span>
                <h1 
                    :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white/90'"
                    class="text-sm font-semibold uppercase tracking-[0.3em] transition-colors"
                >Noir &amp; Bloom</h1>
                <div :class="theme === 'alabaster' ? 'bg-neutral-300' : 'bg-neutral-800'" class="w-8 h-px mx-auto mt-3 transition-colors"></div>
            </div>

            {{-- Centered form slot --}}
            <div class="flex-1 flex items-center justify-center px-6 py-8 lg:py-12">
                <div class="w-full max-w-[420px]">
                    {{ $slot }}
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="px-6 pb-6 text-center">
                <p 
                    :class="theme === 'alabaster' ? 'text-neutral-500' : 'text-neutral-700'"
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
