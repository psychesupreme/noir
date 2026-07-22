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
                    Reset Password
                </h2>
                <p class="text-xs font-light text-neutral-500 tracking-wide">
                    Enter your email to receive a password reset link.
                </p>
            </div>

            <div :class="theme === 'light' ? 'bg-neutral-350' : 'bg-neutral-850'" class="w-12 h-[1px] transition-colors"></div>
        </div>

        {{-- Status Notification --}}
        @if (session('status'))
            <div class="p-3 border border-emerald-500/30 bg-emerald-950/20 rounded-sm text-emerald-400 text-[11px] font-mono text-center">
                <span class="block font-bold uppercase tracking-wider text-[9px] mb-1">Email Dispatched</span>
                {{ session('status') }}
            </div>
        @endif

        {{-- Error Banner --}}
        @if ($errors->has('email'))
            <div class="border border-rose-900/50 bg-rose-950/20 rounded-sm px-4 py-3 space-y-1">
                @foreach ($errors->get('email') as $error)
                    <p class="text-[10px] font-mono text-rose-400 tracking-wide">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form wire:submit.prevent="sendResetLink" class="space-y-6">

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
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="sendResetLink"
                :class="theme === 'light' ? 'auth-btn-light' : 'auth-btn-dark'"
                class="w-full font-semibold text-[10px] uppercase tracking-[0.2em] py-4 transition-all cursor-pointer rounded-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <span wire:loading wire:target="sendResetLink" class="animate-spin rounded-full h-3 w-3 border-2 border-current border-t-transparent inline-block"></span>
                <span wire:loading.remove wire:target="sendResetLink">Send Reset Link</span>
                <span wire:loading wire:target="sendResetLink">Processing&hellip;</span>
            </button>

        </form>

        {{-- Footer Link --}}
        <div class="text-center pt-2">
            <p :class="theme === 'light' ? 'text-neutral-500' : 'text-neutral-600'" class="text-[11px] font-light transition-colors">
                Remember your password?
                <a 
                    href="/login" 
                    wire:navigate 
                    :class="theme === 'light' ? 'text-neutral-900 hover:text-black decoration-neutral-300 hover:decoration-neutral-500' : 'text-white/70 hover:text-white decoration-neutral-700 hover:decoration-neutral-500'"
                    class="underline underline-offset-4 transition-colors"
                >
                    Sign In
                </a>
            </p>
        </div>

    </div>
</div>
