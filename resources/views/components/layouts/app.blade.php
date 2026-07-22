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
    <meta name="theme-color" content="#0B0B0D" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#FAF7F0" media="(prefers-color-scheme: light)">

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

    <!-- Tailwind CSS v4 Build Layer & Livewire Asset Core -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            let theme = 'light';
            const storedTheme = localStorage.getItem('nb_theme');
            if (storedTheme === 'dark' || storedTheme === 'light') {
                theme = storedTheme;
            } else {
                @auth
                    const pref = '{{ auth()->user()->settings["preferred_theme"] ?? "" }}';
                    theme = (pref === 'onyx' || pref === 'dark') ? 'dark' : 'light';
                @else
                    theme = 'light';
                @endauth
            }
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
            const bgColors = { 'dark': '#0A0908', 'light': '#FAF7F0' };
            const textColors = { 'dark': '#E4E4E7', 'light': '#1C1C20' };
            if (bgColors[theme]) document.documentElement.style.backgroundColor = bgColors[theme];
            if (textColors[theme]) document.documentElement.style.color = textColors[theme];
        })();
    </script>
</head>
<body class="bg-bg-base text-text-primary antialiased font-sans transition-colors duration-500 selection:bg-rose-950 selection:text-rose-200">

    <!-- Livewire 3 injects your full-page component directly here -->
    {{ $slot }}

    @livewireScripts
</body>
</html>