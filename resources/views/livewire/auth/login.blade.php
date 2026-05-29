<div>
    <div class="space-y-10">

        {{-- Header --}}
        <div class="space-y-4">
            <div class="flex items-baseline gap-2">
                <span class="text-[8px] font-mono tracking-[0.4em] text-[#D4AF37]/50 uppercase">Atelier</span>
                <span 
                    :class="theme === 'alabaster' ? 'text-neutral-800/80' : 'text-white/80'"
                    class="text-[10px] font-semibold uppercase tracking-[0.3em] transition-colors"
                >Noir & Bloom</span>
            </div>

            <div class="space-y-2">
                <h2 
                    :class="theme === 'alabaster' ? 'text-neutral-900' : 'text-white'"
                    class="text-[22px] font-[Instrument_Serif] font-normal leading-tight transition-colors"
                >
                    Welcome back
                </h2>
                <p class="text-[11px] font-light text-neutral-500 tracking-wide">
                    Sign in to your atelier account to continue.
                </p>
            </div>

            <div :class="theme === 'alabaster' ? 'bg-neutral-200' : 'bg-neutral-800'" class="w-10 h-px transition-colors"></div>
        </div>

        {{-- Global error --}}
        @if ($errors->has('email'))
            <div class="border border-rose-900/50 bg-rose-950/20 rounded-sm px-4 py-3 space-y-1">
                @foreach ($errors->get('email') as $error)
                    <p class="text-[10px] font-mono text-rose-400 tracking-wide">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form wire:submit.prevent="authenticate" class="space-y-6">

            {{-- Email --}}
            <div class="space-y-2">
                <label 
                    for="email" 
                    :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
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
                    :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('email')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2" x-data="{ showPassword: false }">
                <div class="flex items-center justify-between">
                    <label 
                        for="password" 
                        :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Password
                    </label>
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :class="theme === 'alabaster' ? 'text-neutral-500 hover:text-neutral-800' : 'text-neutral-600 hover:text-neutral-400'"
                        class="text-[9px] font-mono tracking-wider transition-colors cursor-pointer uppercase"
                        x-text="showPassword ? 'Hide' : 'Show'"
                    ></button>
                </div>
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    wire:model="password"
                    placeholder="••••••••••"
                    autocomplete="current-password"
                    :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
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
                        :class="theme === 'alabaster' ? 'border-neutral-300 bg-white text-black' : 'border-neutral-700 bg-[#0F0F12] text-white'"
                        class="w-3.5 h-3.5 rounded-sm focus:ring-0 focus:ring-offset-0 cursor-pointer accent-current"
                    >
                    <span 
                        :class="theme === 'alabaster' ? 'text-neutral-500 group-hover:text-neutral-800' : 'text-neutral-500 group-hover:text-neutral-400'"
                        class="text-[10px] font-light transition-colors"
                    >
                        Keep me signed in
                    </span>
                </label>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="authenticate"
                :class="theme === 'alabaster' ? 'bg-neutral-950 text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <span wire:loading wire:target="authenticate" class="animate-spin rounded-full h-3 w-3 border-2 border-neutral-400 border-t-transparent inline-block"></span>
                <span wire:loading.remove wire:target="authenticate">Sign In</span>
                <span wire:loading wire:target="authenticate">Authenticating&hellip;</span>
            </button>

        </form>

        {{-- Footer link --}}
        <div class="text-center pt-2">
            <p :class="theme === 'alabaster' ? 'text-neutral-500' : 'text-neutral-600'" class="text-[11px] font-light transition-colors">
                Don&rsquo;t have an account?
                <a 
                    href="/register" 
                    wire:navigate 
                    :class="theme === 'alabaster' ? 'text-neutral-900 hover:text-black decoration-neutral-300 hover:decoration-neutral-500' : 'text-white/70 hover:text-white decoration-neutral-700 hover:decoration-neutral-500'"
                    class="underline underline-offset-4 transition-colors"
                >
                    Create one
                </a>
            </p>
        </div>

    </div>
</div>
