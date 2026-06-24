@php
    $initialTheme = 'light';
    if (auth()->check()) {
        $pref = auth()->user()->settings['preferred_theme'] ?? 'light';
        $initialTheme = ($pref === 'onyx' || $pref === 'dark') ? 'dark' : 'light';
    }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $initialTheme }}" data-theme="{{ $initialTheme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Noir & Bloom | ERP Administration' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            @auth
                const pref = '{{ auth()->user()->settings["preferred_theme"] ?? "" }}';
                let theme = (pref === 'onyx' || pref === 'dark') ? 'dark' : 'light';
            @else
                let theme = localStorage.getItem('nb_theme') || 'light';
                if (theme === 'onyx' || theme === 'dark') {
                    theme = 'dark';
                } else {
                    theme = 'light';
                }
            @endauth
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body class="bg-bg-base text-text-primary antialiased font-sans transition-colors duration-500 selection:bg-rose-950 selection:text-rose-200">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

        {{-- ─── Mobile Overlay ─── --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
            x-cloak
        ></div>

        {{-- ─── Sidebar ─── --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-border-base bg-bg-card transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto"
        >
            {{-- Brand Mark --}}
            <div class="px-6 pt-8 pb-6 border-b border-border-base/50">
                <p class="text-[10px] font-light tracking-[0.35em] uppercase text-text-secondary mb-1">Atelier</p>
                <h1 class="text-sm font-medium tracking-[0.2em] uppercase text-text-primary">Noir & Bloom</h1>
                <p class="text-[10px] tracking-[0.25em] uppercase text-text-secondary mt-1" style="font-family: ui-monospace, SFMono-Regular, monospace;">ERP Operations</p>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
                @php
                    $navItems = [
                        ['icon' => '◈', 'label' => 'Dashboard',     'route' => '/admin',           'active' => request()->is('admin'), 'future' => false],
                        ['icon' => '☰', 'label' => 'Orders',        'route' => '/admin/orders',    'active' => request()->is('admin/orders*'), 'future' => false],
                        ['icon' => '✿', 'label' => 'Products',      'route' => '/admin/products',  'active' => request()->is('admin/products*'), 'future' => false],
                        ['icon' => '◉', 'label' => 'Clients',       'route' => '/admin/clients',   'active' => request()->is('admin/clients*'), 'future' => false],
                        ['icon' => '✉', 'label' => 'Campaigns',     'route' => '/admin/campaigns', 'active' => request()->is('admin/campaigns*'), 'future' => false],
                        ['icon' => '⬡', 'label' => 'Branches',      'route' => '/admin/branches',  'active' => request()->is('admin/branches*'), 'future' => false],
                        ['icon' => '⬢', 'label' => 'Vendors',       'route' => '/admin/vendors',   'active' => request()->is('admin/vendors*'), 'future' => false],
                        ['icon' => '☷', 'label' => 'Purchase Orders','route' => '/admin/purchase-orders', 'active' => request()->is('admin/purchase-orders*'), 'future' => false],
                        ['icon' => '⚠', 'label' => 'Wastage Log',    'route' => '/admin/wastage',   'active' => request()->is('admin/wastage*'), 'future' => false],
                        ['icon' => '◎', 'label' => 'Payments',      'route' => '/admin/payments',  'active' => request()->is('admin/payments*'), 'future' => false],
                        ['icon' => '⛃', 'label' => 'AR Ledger',     'route' => '/admin/accounts-receivable', 'active' => request()->is('admin/accounts-receivable*'), 'future' => false],
                        ['icon' => '▣', 'label' => 'Tax & eTIMS',   'route' => '/admin/tax',       'active' => request()->is('admin/tax*'), 'future' => false],
                        ['icon' => '◇', 'label' => 'Reports',       'route' => '/admin/reports',   'active' => request()->is('admin/reports*'), 'future' => false],
                        ['icon' => '▤', 'label' => 'System Logs',   'route' => '/admin/system-logs','active' => request()->is('admin/system-logs*'), 'future' => false],
                        ['icon' => '⚙', 'label' => 'Settings',      'route' => '/admin/settings',  'active' => request()->is('admin/settings*'), 'future' => false],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @if (($item['label'] === 'System Logs' || $item['label'] === 'Reports') && !auth()->user()->isAdmin())
                        @continue
                    @endif
                    @if ($item['future'])
                        <span class="flex items-center space-x-3 px-4 py-2.5 text-xs tracking-wider rounded-sm text-neutral-500 opacity-40 cursor-not-allowed select-none">
                            <span class="w-5 text-center">{{ $item['icon'] }}</span>
                            <span class="uppercase">{{ $item['label'] }}</span>
                        </span>
                    @else
                        <a
                            href="{{ $item['route'] }}"
                            @click="sidebarOpen = false"
                            class="flex items-center space-x-3 px-4 py-2.5 text-xs tracking-wider rounded-sm transition-colors
                                {{ $item['active']
                                    ? 'bg-neutral-100 dark:bg-neutral-900/60 text-text-primary border-l-2 border-brand-accent font-semibold'
                                    : 'text-text-secondary hover:text-text-primary hover:bg-neutral-100/50 dark:hover:bg-neutral-900/30' }}"
                        >
                            <span class="w-5 text-center">{{ $item['icon'] }}</span>
                            <span class="uppercase">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- User Info Block --}}
            <div class="border-t border-border-base px-5 py-5">
                @auth
                    <div class="mb-3">
                        <p class="text-xs font-medium text-text-primary truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-text-secondary truncate mt-0.5" style="font-family: ui-monospace, SFMono-Regular, monospace;">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->account_tier)
                            <span class="mt-2 inline-block text-[9px] tracking-[0.2em] uppercase px-2 py-0.5 rounded-sm bg-brand-accent/10 text-brand-accent border border-brand-accent/20">
                                {{ auth()->user()->account_tier->value ?? 'Standard' }}
                            </span>
                        @endif
                    </div>
                @endauth

                <form method="POST" action="/logout">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center space-x-2 px-3 py-2 text-[10px] tracking-[0.2em] uppercase text-text-secondary hover:text-rose-500 hover:bg-neutral-100 dark:hover:bg-neutral-900/30 rounded-sm transition-colors"
                    >
                        <span>↗</span>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ─── Main Content ─── --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-border-base bg-bg-base/80 backdrop-blur-md px-6 py-4 lg:px-10">
                {{-- Left: Hamburger + Breadcrumb --}}
                <div class="flex items-center space-x-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-text-secondary hover:text-text-primary transition-colors"
                        aria-label="Toggle sidebar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div>
                        <p class="text-[10px] tracking-[0.25em] uppercase text-text-secondary" style="font-family: ui-monospace, SFMono-Regular, monospace;">Administration</p>
                        <h2 class="text-sm font-medium text-text-primary tracking-wide">{{ $title ?? 'Dashboard' }}</h2>
                    </div>
                </div>

                {{-- Right: Avatar + Storefront Link --}}
                <div class="flex items-center space-x-4">
                    <a
                        href="/"
                        class="text-[10px] tracking-[0.2em] uppercase text-text-secondary hover:text-brand-accent transition-colors"
                    >
                        ← Storefront
                    </a>

                    <div class="h-8 w-8 rounded-full bg-bg-card border border-border-base flex items-center justify-center">
                        <span class="text-[10px] font-medium text-text-secondary">
                            @auth
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @else
                                NB
                            @endauth
                        </span>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 px-6 py-8 lg:px-10 lg:py-10">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
