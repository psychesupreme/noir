<div x-data="{ 
         errorsCount: {{ count($errors) }}, 
         showPassword: false, 
         showConfirm: false, 
         accountType: @entangle('account_type'),
         get sliderClass() {
             return this.accountType === 'retail' ? 'translate-x-0' : 'translate-x-full';
         },
         get sliderColor() {
             if (this.theme === 'light') {
                 return this.accountType === 'retail' ? 'bg-[#1C1917] text-white shadow-sm' : 'bg-[#B59A7A] text-white shadow-sm';
             }
             return this.accountType === 'retail' ? 'bg-white text-black shadow-sm' : 'bg-[#C5A880] text-black shadow-sm';
         }
     }"
     class="space-y-6"
>
    {{-- CSS styling for shake animation, custom noise overlay, and glow borders --}}
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15%, 45%, 75% { transform: translateX(-6px); }
            30%, 60%, 90% { transform: translateX(6px); }
        }
        .animate-shake {
            animation: shake 0.45s cubic-bezier(.36,.07,.19,.97) both;
        }
        .noise-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }
    </style>

    {{-- Header --}}
    <div class="space-y-3">
        <div class="flex items-baseline gap-2">
            <span class="text-[8px] font-mono tracking-[0.4em] text-[#D4AF37]/50 uppercase">Atelier</span>
            <span 
                :class="theme === 'light' ? 'text-neutral-800/80' : 'text-white/80'"
                class="text-[10px] font-semibold uppercase tracking-[0.3em] transition-colors"
            >Noir & Bloom</span>
        </div>

        <div class="space-y-1">
            <h2 
                :class="theme === 'light' ? 'text-neutral-900' : 'text-white'"
                class="text-[24px] font-[Instrument_Serif] font-normal leading-tight transition-colors"
            >
                Create your account
            </h2>
            <p class="text-[11px] font-light text-neutral-500 tracking-wide">
                Join our exclusive floral &amp; gifting concierge.
            </p>
        </div>
        <div :class="theme === 'light' ? 'bg-neutral-200' : 'bg-neutral-800'" class="w-10 h-px transition-colors"></div>
    </div>

    {{-- Main registration card with glassmorphism, texture, and edges --}}
    <div 
        :class="{
            'bg-[#0C0C0E]/70 border-neutral-900/60 shadow-[0_0_50px_rgba(0,0,0,0.8)]': theme === 'dark',
            'bg-white/70 border-neutral-200/80 shadow-[0_0_40px_rgba(28,25,23,0.03)]': theme === 'light',
            'animate-shake': errorsCount > 0
        }"
        class="border backdrop-blur-md rounded-[28px] p-6 lg:p-8 space-y-6 transition-all duration-500 relative overflow-hidden noise-card text-left"
    >
        {{-- Social Sign-In buttons --}}
        <div class="space-y-3.5">
            <div class="flex items-center justify-between">
                <div class="w-full h-px bg-neutral-500/10"></div>
                <span class="px-3 text-[9px] font-mono tracking-widest text-neutral-500 uppercase whitespace-nowrap">Automatic Sign Up</span>
                <div class="w-full h-px bg-neutral-500/10"></div>
            </div>
            
            <div class="grid grid-cols-3 gap-3">
                <button
                    type="button"
                    wire:click="socialLogin('google')"
                    :class="theme === 'light' ? 'border-neutral-250 bg-neutral-50/50 hover:bg-neutral-100 text-neutral-800' : 'border-neutral-800 bg-[#0F0F12]/60 hover:bg-[#121215] text-white'"
                    class="border rounded-xl py-3 flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5 cursor-pointer shadow-sm z-10"
                    title="Register automatically with Google"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-5.136 4.114-3.555 0-6.437-2.883-6.437-6.437s2.882-6.437 6.437-6.437c1.558 0 2.978.558 4.093 1.487l3.08-3.081C19.167 2.155 15.897 1 12.24 1c-6.075 0-11 4.925-11 11s4.925 11 11 11c5.962 0 10.217-4.195 10.217-10.222 0-.616-.055-1.189-.164-1.592H12.24z"/>
                    </svg>
                </button>
                
                <button
                    type="button"
                    wire:click="socialLogin('apple')"
                    :class="theme === 'light' ? 'border-neutral-250 bg-neutral-50/50 hover:bg-neutral-100 text-neutral-800' : 'border-neutral-800 bg-[#0F0F12]/60 hover:bg-[#121215] text-white'"
                    class="border rounded-xl py-3 flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5 cursor-pointer shadow-sm z-10"
                    title="Register automatically with Apple"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.21.67-2.93 1.49-.62.69-1.16 1.84-1.01 2.96 1.12.09 2.27-.56 2.95-1.39z"/>
                    </svg>
                </button>
                
                <button
                    type="button"
                    wire:click="socialLogin('microsoft')"
                    :class="theme === 'light' ? 'border-neutral-250 bg-neutral-50/50 hover:bg-neutral-100 text-neutral-800' : 'border-neutral-800 bg-[#0F0F12]/60 hover:bg-[#121215] text-white'"
                    class="border rounded-xl py-3 flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5 cursor-pointer shadow-sm z-10"
                    title="Register automatically with Microsoft"
                >
                    <svg class="w-4 h-4" viewBox="0 0 23 23" fill="currentColor">
                        <path d="M0 0h11v11H0z" fill="#f25022"/><path d="M12 0h11v11H12z" fill="#7fba00"/><path d="M0 12h11v11H0z" fill="#00a4ef"/><path d="M12 12h11v11H12z" fill="#ffb900"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Divider --}}
        <div class="flex items-center justify-between">
            <div class="w-full h-px bg-neutral-500/10"></div>
            <span class="px-3 text-[9px] font-mono tracking-widest text-neutral-500 uppercase whitespace-nowrap">Or Fill Details</span>
            <div class="w-full h-px bg-neutral-500/10"></div>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="register" class="space-y-4 relative z-10">

            {{-- Account Type Toggle --}}
            <div class="space-y-1.5">
                <label 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Account Type <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                </label>
                <div 
                    :class="theme === 'light' ? 'bg-neutral-200/50 border-neutral-300' : 'bg-[#0A0A0A] border-neutral-850'"
                    class="border p-1 rounded-full grid grid-cols-2 text-center text-[9px] font-mono uppercase tracking-wider transition-colors relative h-10 overflow-hidden select-none"
                >
                    {{-- Sliding Pill background --}}
                    <div 
                        class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] rounded-full transition-all duration-300 ease-out z-0"
                        :class="sliderColor + ' ' + sliderClass"
                    ></div>

                    <button
                        type="button"
                        @click="accountType = 'retail'"
                        :class="accountType === 'retail' ? 'text-current font-bold' : 'text-neutral-500 hover:text-neutral-400'"
                        class="py-2 rounded-full cursor-pointer transition-all duration-300 z-10 font-mono"
                    >
                        Personal
                    </button>
                    <button
                        type="button"
                        @click="accountType = 'corporate'"
                        :class="accountType === 'corporate' ? 'text-current font-bold' : 'text-neutral-500 hover:text-neutral-400'"
                        class="py-2 rounded-full cursor-pointer transition-all duration-300 z-10 font-mono"
                    >
                        Business / Corporate
                    </button>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label 
                        for="name" 
                        :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Full Name <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                    </label>
                    @error('name')
                        <span class="text-[9px] font-mono text-rose-500">Required</span>
                    @enderror
                </div>
                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    placeholder="{{ $account_type === 'corporate' ? 'Company or Contact Name' : 'Your full name' }}"
                    autocomplete="name"
                    :class="{
                        'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500': theme === 'light' && !@json($errors->has('name')),
                        'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600': theme !== 'light' && !@json($errors->has('name')),
                        'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/5 text-rose-500': @json($errors->has('name'))
                    }"
                    class="border rounded-xl px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('name')
                    <p class="text-[10px] font-mono text-rose-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label 
                        for="reg-email" 
                        :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Email Address <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                    </label>
                    @error('email')
                        <span class="text-[9px] font-mono text-rose-500">Required</span>
                    @enderror
                </div>
                <input
                    id="reg-email"
                    type="email"
                    wire:model="email"
                    placeholder="you@company.co.ke"
                    autocomplete="email"
                    :class="{
                        'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500': theme === 'light' && !@json($errors->has('email')),
                        'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600': theme !== 'light' && !@json($errors->has('email')),
                        'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/5 text-rose-500': @json($errors->has('email'))
                    }"
                    class="border rounded-xl px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('email')
                    <p class="text-[10px] font-mono text-rose-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label 
                        for="phone" 
                        :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Phone Number <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                    </label>
                    @error('phone_number')
                        <span class="text-[9px] font-mono text-rose-500">Required</span>
                    @enderror
                </div>
                <div class="relative flex items-center">
                    <span 
                        :class="theme === 'light' ? 'text-neutral-400' : 'text-neutral-600'"
                        class="absolute left-4 text-[11px] font-mono select-none pointer-events-none transition-colors"
                    >+254</span>
                    <input
                        id="phone"
                        type="tel"
                        wire:model="phone_number"
                        placeholder="712 345 678"
                        autocomplete="tel"
                        :class="{
                            'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500': theme === 'light' && !@json($errors->has('phone_number')),
                            'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600': theme !== 'light' && !@json($errors->has('phone_number')),
                            'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/5 text-rose-500': @json($errors->has('phone_number'))
                        }"
                        class="pl-14 pr-4 py-3 text-sm focus:outline-none transition-all w-full font-light font-mono border rounded-xl"
                    >
                </div>
                @error('phone_number')
                    <p class="text-[10px] font-mono text-rose-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label 
                        for="reg-password" 
                        :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Password <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                    </label>
                    @error('password')
                        <span class="text-[9px] font-mono text-rose-500">Invalid</span>
                    @enderror
                </div>
                <div class="relative flex items-center">
                    <input
                        id="reg-password"
                        :type="showPassword ? 'text' : 'password'"
                        wire:model="password"
                        placeholder="Min 8 characters (Enforced caps, num, sym)"
                        autocomplete="new-password"
                        :class="{
                            'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500': theme === 'light' && !@json($errors->has('password')),
                            'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600': theme !== 'light' && !@json($errors->has('password')),
                            'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/5 text-rose-500': @json($errors->has('password'))
                        }"
                        class="border rounded-xl pl-4 pr-11 py-3 text-sm focus:outline-none transition-all w-full font-light"
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :class="theme === 'light' ? 'text-neutral-400 hover:text-neutral-700' : 'text-neutral-500 hover:text-neutral-300'"
                        class="absolute right-4 transition-colors cursor-pointer"
                        title="Toggle password visibility"
                    >
                        {{-- SVG Eye Icon --}}
                        <template x-if="!showPassword">
                            <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </template>
                    </button>
                </div>
                @error('password')
                    <p class="text-[10px] font-mono text-rose-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label 
                        for="password_confirmation" 
                        :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Confirm Password <span class="text-rose-500 font-sans font-bold inline-block ml-0.5 animate-pulse">&bull;</span>
                    </label>
                    @error('password_confirmation')
                        <span class="text-[9px] font-mono text-rose-500">Mismatch</span>
                    @enderror
                </div>
                <div class="relative flex items-center">
                    <input
                        id="password_confirmation"
                        :type="showConfirm ? 'text' : 'password'"
                        wire:model="password_confirmation"
                        placeholder="Re-enter your password"
                        autocomplete="new-password"
                        :class="{
                            'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500': theme === 'light' && !@json($errors->has('password_confirmation')),
                            'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600': theme !== 'light' && !@json($errors->has('password_confirmation')),
                            'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/5 text-rose-500': @json($errors->has('password_confirmation'))
                        }"
                        class="border rounded-xl pl-4 pr-11 py-3 text-sm focus:outline-none transition-all w-full font-light"
                    >
                    <button
                        type="button"
                        @click="showConfirm = !showConfirm"
                        :class="theme === 'light' ? 'text-neutral-400 hover:text-neutral-700' : 'text-neutral-500 hover:text-neutral-300'"
                        class="absolute right-4 transition-colors cursor-pointer"
                        title="Toggle confirm password visibility"
                    >
                        {{-- SVG Eye Icon --}}
                        <template x-if="!showConfirm">
                            <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </template>
                        <template x-if="showConfirm">
                            <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </template>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-[10px] font-mono text-rose-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="register"
                    :class="theme === 'light' ? 'bg-[#1C1917] text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                    class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-md"
                >
                    <span wire:loading wire:target="register" class="animate-spin rounded-full h-3 w-3 border-2 border-neutral-400 border-t-transparent inline-block"></span>
                    <span wire:loading.remove wire:target="register">Create Account</span>
                    <span wire:loading wire:target="register">Creating&hellip;</span>
                </button>
            </div>

        </form>
    </div>

    {{-- Footer link --}}
    <div class="text-center">
        <p :class="theme === 'light' ? 'text-neutral-500' : 'text-neutral-600'" class="text-[11px] font-light transition-colors">
            Already have an account?
            <a 
                href="/login" 
                wire:navigate 
                :class="theme === 'light' ? 'text-neutral-900 hover:text-black decoration-neutral-300 hover:decoration-neutral-500 font-medium' : 'text-white/70 hover:text-white decoration-neutral-700 hover:decoration-neutral-500 font-medium'"
                class="underline underline-offset-4 transition-colors"
            >
                Sign in
            </a>
        </p>
    </div>
</div>