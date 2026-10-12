<header class="fixed top-0 inset-x-0 w-full h-24 z-50 transition-all duration-500 flex items-center shadow-md backdrop-blur-xl bg-zinc-950/80 dark:bg-zinc-950/80 border-b border-zinc-800/60 text-zinc-100">
    <div class="max-w-8xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3 sm:gap-6">
        
        <!-- Mobile Menu Trigger Button -->
        <button type="button"
                @click="$dispatch('toggle-mobile-menu')"
                class="md:hidden p-2 rounded-full border border-zinc-800 bg-zinc-900/60 text-zinc-300 hover:text-amber-400 hover:border-amber-500/40 transition cursor-pointer select-none shrink-0"
                title="Open Navigation Menu">
            <svg class="w-5 h-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- 1. Homepage link / logo -->
        <a href="/" class="shrink-0 flex items-center select-none cursor-pointer group/brand transition-transform duration-300 hover:scale-[1.02]">
            <div class="flex items-center space-x-2.5 sm:space-x-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-amber-500/30 bg-zinc-900 flex items-center justify-center text-amber-400 font-serif font-bold text-base sm:text-lg shadow-md group-hover/brand:border-amber-400 group-hover/brand:scale-105 transition-all">
                    N
                </div>
                <div class="flex flex-col text-left leading-none">
                    <span class="text-[9px] sm:text-[10px] font-mono tracking-[0.35em] uppercase font-bold text-amber-400/90">Atelier</span>
                    <span class="text-sm sm:text-base md:text-lg font-extrabold uppercase tracking-[0.18em] font-outfit mt-0.5 text-zinc-100 group-hover/brand:text-amber-400 transition-colors">Noir &amp; Bloom</span>
                </div>
            </div>
        </a>

        <!-- 2. Search bar input / trigger with Instant Live Suggestions -->
        <div class="flex-1 max-w-md mx-auto hidden md:block" 
             x-data="{
                query: '',
                results: [],
                loading: false,
                open: false,
                debounceTimer: null,
                search() {
                    clearTimeout(this.debounceTimer);
                    if (this.query.trim().length < 2) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    this.loading = true;
                    this.debounceTimer = setTimeout(() => {
                        fetch('/api/v1/products?search=' + encodeURIComponent(this.query))
                            .then(res => res.json())
                            .then(data => {
                                this.results = data.data || [];
                                this.loading = false;
                                this.open = true;
                            })
                            .catch(() => {
                                this.loading = false;
                            });
                    }, 250);
                }
             }"
             @click.away="open = false"
        >
            <form action="/" method="GET" class="relative group">
                <input 
                    type="text" 
                    name="find"
                    x-model="query"
                    @input="search()"
                    @focus="if (results.length > 0) open = true"
                    placeholder="Search fresh flowers, luxury hampers, gifts..."
                    autocomplete="off"
                    class="w-full bg-zinc-900/60 border border-zinc-800 text-zinc-100 placeholder-zinc-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 rounded-full pl-10 pr-10 py-2 text-xs font-sans focus:outline-none transition-all duration-300 shadow-sm"
                />
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none text-amber-400/80">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                    </svg>
                </div>
                <!-- Loading spinner indicator -->
                <div x-show="loading" class="absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center pointer-events-none" style="display: none;">
                    <svg class="animate-spin h-3.5 w-3.5 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </form>

            <!-- Search Auto-suggest Dropdown Results -->
            <div x-show="open && results.length > 0" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                 class="absolute mt-2 w-full max-w-md bg-zinc-900/95 border border-zinc-800 rounded-2xl shadow-2xl backdrop-blur-xl overflow-hidden z-[100]"
                 style="display: none;"
            >
                <div class="px-3 pt-2.5 pb-1 border-b border-zinc-800/60 flex items-center justify-between text-[10px] font-mono uppercase tracking-wider text-zinc-400">
                    <span>Suggested Products</span>
                    <a :href="'/?find=' + encodeURIComponent(query)" class="text-amber-400 hover:underline">View All &rarr;</a>
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-zinc-800/40">
                    <template x-for="item in results.slice(0, 5)" :key="item.id">
                        <a :href="'/?find=' + encodeURIComponent(item.name)" 
                           class="flex items-center space-x-3 p-3 hover:bg-zinc-800/60 transition-colors group cursor-pointer text-left">
                            <img :src="item.image_url || 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=100'" 
                                 :alt="item.name" 
                                 class="w-10 h-10 rounded-lg object-cover border border-zinc-800 group-hover:border-amber-500/40 shrink-0">
                            <div class="flex-1 min-w-0">
                                <span class="text-xs font-semibold text-zinc-100 group-hover:text-amber-400 transition-colors block truncate" x-text="item.name"></span>
                                <span class="text-[10px] font-mono text-zinc-400" x-text="(item.formatted_price || (item.price + ' KSH')) + ' • ' + (item.category ? item.category.toUpperCase() : '')"></span>
                            </div>
                            <span class="text-xs text-zinc-500 group-hover:text-amber-400 transition-colors">&rarr;</span>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Side Nav Items -->
        <div class="flex items-center space-x-2.5 sm:space-x-4 text-xs font-mono uppercase tracking-widest shrink-0">
            
            <!-- 3. Services & Gifts link -->
            <a href="/services-gifts" 
               class="hidden lg:flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full border border-amber-500/30 hover:border-amber-400 hover:bg-amber-500/10 text-amber-400 transition-all font-medium text-xs tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Services &amp; Gifts</span>
            </a>

            <!-- 4. Curation Studio link -->
            <a href="/curate" 
               class="hidden md:inline-block px-3.5 py-1.5 rounded-full border border-amber-500/30 hover:border-amber-400 hover:bg-amber-500/10 text-amber-400 font-medium transition-all">
               Curation Studio
            </a>

            <!-- 5. Theme toggle button -->
            <button x-data="{ mode: localStorage.getItem('nb_theme') || localStorage.getItem('theme') || 'dark' }"
                    @click="
                      mode = (mode === 'dark' ? 'light' : 'dark');
                      localStorage.setItem('theme', mode);
                      localStorage.setItem('nb_theme', mode);
                      if (mode === 'light') { document.documentElement.classList.remove('dark'); }
                      else { document.documentElement.classList.add('dark'); }
                      $dispatch('theme-changed', mode);
                      $dispatch('theme-settings-changed', mode);
                    "
                    class="p-2 rounded-full border border-zinc-800 bg-zinc-900/60 hover:bg-zinc-800 text-amber-400 transition cursor-pointer shrink-0"
                    title="Toggle Theme Mode">
                <!-- Sun Icon for Dark Mode -->
                <svg x-show="mode === 'dark'" class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="12" cy="12" r="5"></circle>
                    <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <!-- Moon Icon for Light Mode -->
                <svg x-show="mode === 'light'" style="display: none;" class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>

            <!-- 6. Notifications bell / popover -->
            <button type="button"
                    @click="$dispatch('toggle-notifications')" 
                    class="hidden sm:flex relative w-9 h-9 items-center justify-center rounded-full border border-zinc-800 bg-zinc-900/60 text-zinc-300 hover:text-amber-400 hover:border-amber-500/40 transition cursor-pointer shrink-0"
                    title="View Notifications">
                <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- 7. Wishlist button -->
            <a href="/profile-portal?tab=wishlist" 
               class="hidden sm:flex relative w-9 h-9 items-center justify-center rounded-full border border-zinc-800 bg-zinc-900/60 text-zinc-300 hover:text-amber-400 hover:border-amber-500/40 transition cursor-pointer shrink-0"
               title="View Wishlist">
                <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- 8. Cart drawer trigger (Instant client-side toggle if on storefront, redirect otherwise) -->
            <button type="button"
                    @click="
                        if (window.location.pathname === '/' || window.location.pathname === '') {
                            $dispatch('toggle-cart');
                        } else {
                            window.location.href = '/?open_cart=true';
                        }
                    " 
                    class="relative w-9 h-9 flex items-center justify-center rounded-full border border-zinc-800 bg-zinc-900/60 text-zinc-300 hover:text-amber-400 hover:border-amber-500/40 transition cursor-pointer shrink-0"
                    title="View Curation Drawer Cart">
                <svg class="w-4 h-4 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- 9. User Profile link / dropdown -->
            @auth
                <a href="/profile-portal" 
                   class="w-9 h-9 rounded-full border border-amber-500/40 bg-zinc-900 flex items-center justify-center text-amber-400 font-mono text-xs font-bold hover:border-amber-400 transition-all shadow-sm shrink-0" 
                   title="Profile Portal">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                </a>
            @else
                <a href="/login" 
                   class="px-3.5 py-1.5 rounded-full border border-amber-500/30 bg-zinc-900/80 text-amber-400 hover:bg-amber-500 hover:text-black font-sans text-xs font-semibold tracking-wider uppercase transition-all shrink-0">
                    Sign In
                </a>
            @endauth

        </div>

    </div>
</header>
