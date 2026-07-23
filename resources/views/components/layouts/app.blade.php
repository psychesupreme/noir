<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full bg-zinc-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#09090b">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- SEO & Social Media Metadata Section -->
    @hasSection('meta')
        @yield('meta')
    @else
        <meta name="description" content="Atelier Noir & Bloom is a premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from volcanic Rift Valley growers.">
        <meta name="keywords" content="luxury flowers Nairobi, bespoke gifting Kenya, premium florist Kiambu, Rift Valley stems, corporate flower subscriptions, eTIMS compliance flowers, M-Pesa florist">
        <meta name="author" content="Atelier Noir & Bloom">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph (Facebook / Pinterest / LinkedIn) -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? 'Atelier Noir & Bloom | Premium Floral & Gifting' }}">
        <meta property="og:description" content="Premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from Rift Valley growers.">
        <meta property="og:image" content="{{ asset('media/og-image-default.jpg') }}">
        <meta property="og:site_name" content="Atelier Noir & Bloom">
        <meta property="og:locale" content="en_KE">

        <!-- Twitter / X Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $title ?? 'Atelier Noir & Bloom | Premium Floral & Gifting' }}">
        <meta name="twitter:description" content="Premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from Rift Valley growers.">
        <meta name="twitter:image" content="{{ asset('media/og-image-default.jpg') }}">
        <meta name="twitter:site" content="@NoirAndBloom">
    @endif

    <title>{{ $title ?? 'Atelier Noir & Bloom | Premium Floral & Gifting' }}</title>

    <!-- Google Search Engine Structured Data Schema (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@type": "Organization",
          "@id": "{{ url('/') }}/#organization",
          "name": "Atelier Noir & Bloom",
          "url": "{{ url('/') }}",
          "logo": "{{ asset('media/logo.png') }}",
          "sameAs": [
            "https://twitter.com/NoirAndBloom",
            "https://instagram.com/noirandbloom",
            "https://pinterest.com/noirandbloom"
          ],
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+254-712-345-678",
            "contactType": "customer service",
            "areaServed": "KE",
            "availableLanguage": "en"
          }
        },
        {
          "@type": "WebSite",
          "@id": "{{ url('/') }}/#website",
          "url": "{{ url('/') }}",
          "name": "Atelier Noir & Bloom",
          "description": "Premium Floral Curation & Bespoke Gifting Atelier",
          "publisher": {
            "@id": "{{ url('/') }}/#organization"
          }
        }
      ]
    }
    </script>

    <!-- Fonts & Metadata -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500|outfit:300,400,600,700" rel="stylesheet" />

    <!-- Leaflet Map CSS & JS CDN (100% Free OpenStreetMap client) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Global Theme Persistence (localStorage) -->
    <script>
      if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
      } else {
        document.documentElement.classList.add('dark');
      }
    </script>

    <!-- Tailwind CSS v4 Build Layer & Livewire Asset Core -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen selection:bg-amber-900 selection:text-amber-100">

    <div class="min-h-screen flex flex-col bg-zinc-950 text-zinc-100">
        <!-- Uniform Navbar Component -->
        <x-navbar />

        <!-- Windows 11 Style Floating Toast Notifications -->
        <div x-data="{ show: false, message: '', type: 'info' }"
             x-on:notify.window="
               show = true; 
               message = $event.detail.message; 
               type = $event.detail.type || 'info'; 
               setTimeout(() => show = false, 3500)
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-6 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-6 scale-95"
             class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] min-w-[280px] max-w-md px-5 py-3 rounded-2xl shadow-2xl border border-amber-500/30 bg-zinc-900/90 backdrop-blur-xl text-zinc-100 flex items-center space-x-3"
             style="display: none;">
          <div class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></div>
          <span x-text="message" class="text-sm font-sans font-medium"></span>
        </div>

        <main class="flex-1 w-full">
            {{ $slot }}
        </main>

        <!-- Uniform Footer Component -->
        <x-footer />
    </div>

    @livewireScripts
</body>
</html>