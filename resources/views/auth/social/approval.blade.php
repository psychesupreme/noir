<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier Curation Gateway | OAuth Authorization</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            const storedTheme = localStorage.getItem('nb_theme');
            const theme = (storedTheme === 'onyx' || storedTheme === 'dark') ? 'dark' : 'light';
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body x-data="{ theme: (localStorage.getItem('nb_theme') === 'dark' || localStorage.getItem('nb_theme') === 'light' || localStorage.getItem('nb_theme') === 'onyx') ? (localStorage.getItem('nb_theme') === 'light' ? 'light' : 'dark') : 'light' }" class="bg-bg-base text-text-primary antialiased font-sans transition-colors duration-500 min-h-screen flex items-center justify-center p-6">

    <div 
        :class="{
            'bg-[#0C0C0E]/90 border-neutral-900 shadow-[0_0_50px_rgba(0,0,0,0.8)]': theme === 'dark',
            'bg-white border-neutral-250/70 shadow-[0_0_40px_rgba(0,0,0,0.03)]': theme === 'light'
        }"
        class="max-w-[480px] w-full border backdrop-blur-md rounded-[32px] p-8 space-y-8 relative overflow-hidden"
    >
        {{-- Header --}}
        <div class="space-y-4 text-center">
            <div class="flex items-center justify-center gap-2">
                <span class="text-[10px] font-mono tracking-[0.4em] text-[#D4AF37] uppercase">Atelier</span>
                <span :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" class="text-xs font-serif font-bold uppercase tracking-[0.3em] transition-colors">Noir &amp; Bloom</span>
            </div>

            <div class="space-y-2">
                <h2 :class="theme === 'light' ? 'text-neutral-950' : 'text-white'" class="text-2xl font-serif italic font-normal transition-colors">
                    OAuth Gateway Connection
                </h2>
                <p class="text-[11px] font-light text-neutral-500 tracking-wide max-w-[340px] mx-auto leading-relaxed">
                    Authorize authentication handshake for your <strong>{{ $name }}</strong> credentials.
                </p>
            </div>
            <div :class="theme === 'light' ? 'bg-neutral-200' : 'bg-neutral-850'" class="w-16 h-[1px] mx-auto transition-colors"></div>
        </div>

        {{-- Gateway Info Box --}}
        <div 
            :class="{
                'bg-[#0A0A0E] border-neutral-900/60 text-neutral-400': theme === 'dark',
                'bg-neutral-50 border-neutral-200 text-neutral-600': theme === 'light'
            }"
            class="p-5 border rounded-2xl text-[11px] space-y-4 leading-relaxed font-light font-sans"
        >
            <div class="flex items-center gap-3">
                <span class="p-2 bg-[#D4AF37]/15 rounded-xl text-[#D4AF37] shrink-0">
                    @if($provider === 'google')
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-5.136 4.114-3.555 0-6.437-2.883-6.437-6.437s2.882-6.437 6.437-6.437c1.558 0 2.978.558 4.093 1.487l3.08-3.081C19.167 2.155 15.897 1 12.24 1c-6.075 0-11 4.925-11 11s4.925 11 11 11c5.962 0 10.217-4.195 10.217-10.222 0-.616-.055-1.189-.164-1.592H12.24z"/>
                        </svg>
                    @elseif($provider === 'apple')
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.21.67-2.93 1.49-.62.69-1.16 1.84-1.01 2.96 1.12.09 2.27-.56 2.95-1.39z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" viewBox="0 0 23 23" fill="currentColor">
                            <path d="M0 0h11v11H0z" fill="#f25022"/><path d="M12 0h11v11H12z" fill="#7fba00"/><path d="M0 12h11v11H0z" fill="#00a4ef"/><path d="M12 12h11v11H12z" fill="#ffb900"/>
                        </svg>
                    @endif
                </span>
                <div class="leading-normal">
                    <span :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" class="font-semibold block uppercase font-mono text-[9px] tracking-wider">{{ $name }} Authorization</span>
                    <span class="text-[10px] text-neutral-500">Developer/Staging Sandbox Mode</span>
                </div>
            </div>

            <p class="font-sans">
                The application redirected you to this secure gateway because no production keys were found in the server environment. 
            </p>

            <div class="border-t border-neutral-500/10 pt-3 space-y-2">
                <span :class="theme === 'light' ? 'text-neutral-900' : 'text-white'" class="font-semibold block text-[10px] uppercase font-mono tracking-wider">How to enable live OAuth:</span>
                <p>
                    Add the following credentials to your server environment or Fly secrets:
                </p>
                <div class="bg-black/10 dark:bg-black/45 p-3 rounded-lg font-mono text-[10px] space-y-3 select-all overflow-x-auto text-[#D4AF37]">
                    <div>
                        <span class="text-neutral-500 font-bold block text-[8px] uppercase tracking-wider mb-0.5">Google OAuth:</span>
                        <div>GOOGLE_CLIENT_ID="your_google_id"</div>
                        <div>GOOGLE_CLIENT_SECRET="your_google_secret"</div>
                    </div>
                    <div class="border-t border-neutral-500/10 pt-2">
                        <span class="text-neutral-500 font-bold block text-[8px] uppercase tracking-wider mb-0.5">Apple OAuth:</span>
                        <div>APPLE_CLIENT_ID="your_apple_id"</div>
                        <div>APPLE_CLIENT_SECRET="your_apple_secret"</div>
                    </div>
                    <div class="border-t border-neutral-500/10 pt-2">
                        <span class="text-neutral-500 font-bold block text-[8px] uppercase tracking-wider mb-0.5">Microsoft OAuth:</span>
                        <div>MICROSOFT_CLIENT_ID="your_microsoft_id"</div>
                        <div>MICROSOFT_CLIENT_SECRET="your_microsoft_secret"</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-3">
            <form action="{{ route('social.callback', ['provider' => $provider]) }}" method="GET">
                <input type="hidden" name="simulated" value="1">
                <button 
                    type="submit"
                    :class="theme === 'light' ? 'bg-[#1C1917] hover:bg-[#2e2b27] text-white shadow-sm' : 'bg-white hover:bg-neutral-200 text-black shadow-sm'"
                    class="w-full py-3.5 rounded-xl text-xs font-semibold uppercase tracking-[0.2em] font-mono cursor-pointer transition-all hover:-translate-y-0.5 block text-center"
                >
                    Simulate Handshake &amp; Login
                </button>
            </form>

            <a 
                href="{{ route('login') }}" 
                :class="theme === 'light' ? 'border-neutral-250 text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100/50' : 'border-neutral-850 text-neutral-400 hover:text-white hover:bg-white/5'"
                class="w-full py-3.5 border rounded-xl text-xs font-mono uppercase tracking-[0.2em] cursor-pointer transition-all block text-center"
            >
                Cancel &amp; Return
            </a>
        </div>
    </div>

</body>
</html>
