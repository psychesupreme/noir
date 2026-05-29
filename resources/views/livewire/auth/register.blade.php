<div>
    <div class="space-y-8">

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
                    Create your account
                </h2>
                <p class="text-[11px] font-light text-neutral-500 tracking-wide">
                    Join our exclusive floral &amp; gifting concierge.
                </p>
            </div>

            <div :class="theme === 'alabaster' ? 'bg-neutral-200' : 'bg-neutral-800'" class="w-10 h-px transition-colors"></div>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="register" class="space-y-5">

            {{-- Account Type Toggle --}}
            <div class="space-y-2">
                <label 
                    :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Account Type
                </label>
                <div 
                    :class="theme === 'alabaster' ? 'bg-neutral-200/50 border-neutral-300' : 'bg-[#0A0A0A] border-neutral-800'"
                    class="border p-1 rounded-full grid grid-cols-2 text-center text-[9px] font-mono uppercase tracking-wider transition-colors"
                >
                    <button
                        type="button"
                        wire:click="$set('account_type', 'retail')"
                        :class="{
                            'bg-neutral-950 text-white font-bold shadow-sm': $account_type === 'retail' && theme === 'alabaster',
                            'bg-white text-black font-bold shadow-sm': $account_type === 'retail' && theme !== 'alabaster',
                            'text-neutral-500 hover:text-neutral-800': $account_type !== 'retail' && theme === 'alabaster',
                            'text-neutral-500 hover:text-neutral-400': $account_type !== 'retail' && theme !== 'alabaster'
                        }"
                        class="py-2 rounded-full cursor-pointer transition-all duration-300"
                    >
                        Personal
                    </button>
                    <button
                        type="button"
                        wire:click="$set('account_type', 'corporate')"
                        :class="{
                            'bg-neutral-950 text-white font-bold shadow-sm': $account_type === 'corporate' && theme === 'alabaster',
                            'bg-white text-black font-bold shadow-sm': $account_type === 'corporate' && theme !== 'alabaster',
                            'text-neutral-500 hover:text-neutral-800': $account_type !== 'corporate' && theme === 'alabaster',
                            'text-neutral-500 hover:text-neutral-400': $account_type !== 'corporate' && theme !== 'alabaster'
                        }"
                        class="py-2 rounded-full cursor-pointer transition-all duration-300"
                    >
                        Business / Corporate
                    </button>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="space-y-2">
                <label 
                    for="name" 
                    :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Full Name
                </label>
                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    placeholder="{{ $account_type === 'corporate' ? 'Company or Contact Name' : 'Your full name' }}"
                    autocomplete="name"
                    :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('name')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label 
                    for="reg-email" 
                    :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Email Address
                </label>
                <input
                    id="reg-email"
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

            {{-- Phone --}}
            <div class="space-y-2">
                <label 
                    for="phone" 
                    :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                    class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                >
                    Phone Number
                </label>
                <div class="relative flex items-center">
                    <span 
                        :class="theme === 'alabaster' ? 'text-neutral-400' : 'text-neutral-600'"
                        class="absolute left-4 text-[11px] font-mono select-none pointer-events-none transition-colors"
                    >+254</span>
                    <input
                        id="phone"
                        type="tel"
                        wire:model="phone_number"
                        placeholder="712 345 678"
                        autocomplete="tel"
                        :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                        class="pl-14 pr-4 py-3 text-sm focus:outline-none transition-all w-full font-light font-mono border rounded-sm"
                    >
                </div>
                @error('phone_number')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2" x-data="{ showPassword: false }">
                <div class="flex items-center justify-between">
                    <label 
                        for="reg-password" 
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
                    id="reg-password"
                    :type="showPassword ? 'text' : 'password'"
                    wire:model="password"
                    placeholder="Minimum 8 characters"
                    autocomplete="new-password"
                    :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('password')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2" x-data="{ showConfirm: false }">
                <div class="flex items-center justify-between">
                    <label 
                        for="password_confirmation" 
                        :class="theme === 'alabaster' ? 'text-neutral-600' : 'text-neutral-500'"
                        class="text-[10px] uppercase tracking-[0.2em] font-mono block transition-colors"
                    >
                        Confirm Password
                    </label>
                    <button
                        type="button"
                        @click="showConfirm = !showConfirm"
                        :class="theme === 'alabaster' ? 'text-neutral-500 hover:text-neutral-800' : 'text-neutral-600 hover:text-neutral-400'"
                        class="text-[9px] font-mono tracking-wider transition-colors cursor-pointer uppercase"
                        x-text="showConfirm ? 'Hide' : 'Show'"
                    ></button>
                </div>
                <input
                    id="password_confirmation"
                    :type="showConfirm ? 'text' : 'password'"
                    wire:model="password_confirmation"
                    placeholder="Re-enter your password"
                    autocomplete="new-password"
                    :class="theme === 'alabaster' ? 'bg-white border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-neutral-500' : 'bg-[#0F0F12] border-neutral-800 text-white placeholder-neutral-600 focus:border-neutral-600'"
                    class="border rounded-sm px-4 py-3 text-sm focus:outline-none transition-all w-full font-light"
                >
                @error('password_confirmation')
                    <p class="text-[10px] font-mono text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="register"
                    :class="theme === 'alabaster' ? 'bg-neutral-950 text-white hover:bg-black' : 'bg-white text-black hover:bg-neutral-200'"
                    class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <span wire:loading wire:target="register" class="animate-spin rounded-full h-3 w-3 border-2 border-neutral-400 border-t-transparent inline-block"></span>
                    <span wire:loading.remove wire:target="register">Create Account</span>
                    <span wire:loading wire:target="register">Creating&hellip;</span>
                </button>
            </div>

        </form>

        {{-- Footer link --}}
        <div class="text-center">
            <p :class="theme === 'alabaster' ? 'text-neutral-500' : 'text-neutral-600'" class="text-[11px] font-light transition-colors">
                Already have an account?
                <a 
                    href="/login" 
                    wire:navigate 
                    :class="theme === 'alabaster' ? 'text-neutral-900 hover:text-black decoration-neutral-300 hover:decoration-neutral-500' : 'text-white/70 hover:text-white decoration-neutral-700 hover:decoration-neutral-500'"
                    class="underline underline-offset-4 transition-colors"
                >
                    Sign in
                </a>
            </p>
        </div>

    </div>
</div>
