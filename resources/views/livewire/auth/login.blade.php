<div>
    <div class="space-y-10">

        {{-- Header --}}
        <div class="space-y-4">
            <div class="flex items-baseline gap-2.5">
                <span class="text-[10px] font-mono tracking-[0.4em] text-[#D4AF37] uppercase">Atelier</span>
                <span 
                    :class="theme === 'light' ? 'text-neutral-900' : 'text-white'"
                    class="text-xs font-serif font-bold uppercase tracking-[0.3em] transition-colors"
                >Noir &amp; Bloom</span>
            </div>

            <div class="space-y-2">
                <h2 
                    :class="theme === 'light' ? 'text-neutral-900' : 'text-white'"
                    class="text-3xl font-serif italic font-normal leading-tight transition-colors"
                >
                    Welcome Back
                </h2>
                <p class="text-xs font-light text-neutral-500 tracking-wide">
                    Sign in to your luxury atelier dashboard.
                </p>
            </div>

            <div :class="theme === 'light' ? 'bg-neutral-350' : 'bg-neutral-850'" class="w-12 h-[1px] transition-colors"></div>
        </div>

        {{-- Session expired notification --}}
        @if(session('session_expired'))
            <div class="mb-4 p-3 border border-amber-500/30 bg-amber-950/20 rounded-xl text-amber-400 text-[11px] font-mono text-center">
                <span class="block font-bold uppercase tracking-wider text-[9px] mb-1">Session Expired</span>
                Your session has timed out for security. Please sign in again.
            </div>
        @endif

        {{-- Global error --}}
        @if ($errors->has('email'))
            <div class="border border-rose-900/50 bg-rose-950/20 rounded-sm px-4 py-3 space-y-1">
                @foreach ($errors->get('email') as $error)
                    <p class="text-[10px] font-mono text-rose-400 tracking-wide">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Social Sign-In buttons --}}
        <div class="space-y-3.5">
            <div class="flex items-center gap-3 my-2">
                <div class="w-full h-px bg-neutral-500/10"></div>
                <span class="px-3 text-[9px] font-mono tracking-widest text-neutral-500 uppercase whitespace-nowrap">Automatic Sign In</span>
                <div class="w-full h-px bg-neutral-500/10"></div>
            </div>
            
            <div>
                <a
                    href="{{ route('social.redirect', ['provider' => 'google']) }}"
                    :class="theme === 'light' ? 'border-neutral-250 bg-neutral-50/50 hover:bg-neutral-100 text-neutral-800' : 'border-neutral-800 bg-[#0F0F12]/60 hover:bg-[#121215] text-white'"
                    class="border rounded-xl py-3.5 flex items-center justify-center gap-3 transition-all duration-300 hover:-translate-y-0.5 cursor-pointer shadow-sm z-10 w-full font-mono text-[10px] uppercase tracking-wider font-medium"
                    title="Sign in automatically with Google"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-5.136 4.114-3.555 0-6.437-2.883-6.437-6.437s2.882-6.437 6.437-6.437c1.558 0 2.978.558 4.093 1.487l3.08-3.081C19.167 2.155 15.897 1 12.24 1c-6.075 0-11 4.925-11 11s4.925 11 11 11c5.962 0 10.217-4.195 10.217-10.222 0-.616-.055-1.189-.164-1.592H12.24z"/>
                    </svg>
                    Continue with Google
                </a>
            </div>
        </div>

        {{-- Divider --}}
        <div class="flex items-center justify-between">
            <div class="w-full h-px bg-neutral-500/10"></div>
            <span class="px-3 text-[9px] font-mono tracking-widest text-neutral-500 uppercase whitespace-nowrap">Or Credentials</span>
            <div class="w-full h-px bg-neutral-500/10"></div>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="authenticate" class="space-y-6">

            {{-- Email --}}
            <div class="space-y-2">
                <label 
                    for="email" 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Email Address
                </label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    placeholder="you@company.co.ke"
                    autocomplete="email"
                    :class="theme === 'light' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('email')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2" x-data="{ showPassword: false }">
                <label 
                    for="password" 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Password
                </label>
                <div class="relative flex items-center">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        wire:model="password"
                        placeholder="••••••••••"
                        autocomplete="current-password"
                        :class="theme === 'light' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                        class="border rounded-sm pl-4 pr-11 py-3 text-sm focus:outline-none transition-all w-full font-light"
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :class="theme === 'light' ? 'text-neutral-500 hover:text-neutral-800' : 'text-neutral-500 hover:text-neutral-300'"
                        class="absolute right-3.5 focus:outline-none transition-colors cursor-pointer select-none"
                        title="Toggle Password Visibility"
                    >
                        {{-- Eye Open SVG --}}
                        <svg x-show="!showPassword" class="w-4 h-4 fill-none stroke-current" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{-- Eye Closed SVG --}}
                        <svg x-show="showPassword" class="w-4 h-4 fill-none stroke-current" stroke-width="1.5" viewBox="0 0 24 24" style="display:none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 11 7 11 7a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 12s4-8 11-8c2.12 0 3.89.69 5.3 1.76M3 3l18 18" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                    <input
                        type="checkbox"
                        wire:model="remember"
                        :class="theme === 'light' ? 'border-neutral-300 bg-white text-black' : 'border-neutral-700 bg-[#0F0F12] text-white'"
                        class="w-3.5 h-3.5 rounded-sm focus:ring-0 focus:ring-offset-0 cursor-pointer accent-current"
                    >
                    <span 
                        :class="theme === 'light' ? 'text-neutral-500' : 'text-neutral-600'"
                        class="text-[10px] font-light transition-colors"
                    >
                        Keep me signed in
                    </span>
                </label>
                <a 
                    href="/forgot-password" 
                    wire:navigate 
                    :class="theme === 'light' ? 'text-neutral-500 hover:text-neutral-800' : 'text-neutral-500 hover:text-neutral-400'"
                    class="text-[10px] font-light transition-colors hover:underline cursor-pointer"
                >
                    Forgot password?
                </a>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="authenticate"
                :class="theme === 'light' ? 'auth-btn-light' : 'auth-btn-dark'"
                class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <span wire:loading wire:target="authenticate" class="animate-spin rounded-full h-3 w-3 border-2 border-current border-t-transparent inline-block"></span>
                <span wire:loading.remove wire:target="authenticate">Sign In</span>
                <span wire:loading wire:target="authenticate">Authenticating&hellip;</span>
            </button>

        </form>

        {{-- Footer link --}}
        <div class="text-center pt-2">
            <p :class="theme === 'light' ? 'text-neutral-500' : 'text-neutral-600'" class="text-[11px] font-light transition-colors">
                Don&rsquo;t have an account?
                <a 
                    href="/register" 
                    wire:navigate 
                    :class="theme === 'light' ? 'text-neutral-900 hover:text-black decoration-neutral-300 hover:decoration-neutral-500' : 'text-white/70 hover:text-white decoration-neutral-700 hover:decoration-neutral-500'"
                    class="underline underline-offset-4 transition-colors"
                >
                    Create one
                </a>
            </p>
        </div>

    </div>
</div>