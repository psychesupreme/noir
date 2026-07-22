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
                    New Credentials
                </h2>
                <p class="text-xs font-light text-neutral-500 tracking-wide">
                    Configure your secure account credentials.
                </p>
            </div>

            <div :class="theme === 'light' ? 'bg-neutral-350' : 'bg-neutral-850'" class="w-12 h-[1px] transition-colors"></div>
        </div>

        {{-- Error Banner --}}
        @if ($errors->any())
            <div class="border border-rose-900/50 bg-rose-950/20 rounded-sm px-4 py-3 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="text-[10px] font-mono text-rose-400 tracking-wide">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form wire:submit.prevent="resetPassword" class="space-y-6">

            <input type="hidden" wire:model="token">

            {{-- Email --}}
            <div class="space-y-2">
                <label 
                    for="email" 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Confirm Email Address
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
            </div>

            {{-- Password --}}
            <div class="space-y-2" x-data="{ showPassword: false }">
                <label 
                    for="password" 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    New Password
                </label>
                <div class="relative flex items-center">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        wire:model="password"
                        placeholder="••••••••••"
                        :class="theme === 'light' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                        class="border rounded-sm pl-4 pr-11 py-3 text-sm focus:outline-none transition-all w-full font-light"
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :class="theme === 'light' ? 'text-neutral-500 hover:text-neutral-800' : 'text-neutral-500 hover:text-neutral-300'"
                        class="absolute right-3.5 focus:outline-none transition-colors cursor-pointer select-none"
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
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <label 
                    for="password_confirmation" 
                    :class="theme === 'light' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Confirm New Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    placeholder="••••••••••"
                    :class="theme === 'light' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="resetPassword"
                :class="theme === 'light' ? 'auth-btn-light' : 'auth-btn-dark'"
                class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <span wire:loading wire:target="resetPassword" class="animate-spin rounded-full h-3 w-3 border-2 border-current border-t-transparent inline-block"></span>
                <span wire:loading.remove wire:target="resetPassword">Update Password</span>
                <span wire:loading wire:target="resetPassword">Updating&hellip;</span>
            </button>

        </form>

    </div>
</div>
