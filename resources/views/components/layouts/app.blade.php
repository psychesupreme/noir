<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO & Social Media Metadata Section -->
    @hasSection('meta')
        @yield('meta')
    @else
        <meta name="description" content="Noir & Bloom is a premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from volcanic Rift Valley growers.">
        <meta name="keywords" content="luxury flowers Nairobi, bespoke gifting Kenya, premium florist Kiambu, Rift Valley stems, corporate flower subscriptions, eTIMS compliance flowers, M-Pesa florist">
        <meta name="author" content="Noir & Bloom Atelier">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph (Facebook / Pinterest / LinkedIn) -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? 'Noir & Bloom | Premium Floral & Gifting Atelier' }}">
        <meta property="og:description" content="Premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from Rift Valley growers.">
        <meta property="og:image" content="{{ asset('media/og-image-default.jpg') }}">
        <meta property="og:site_name" content="Noir & Bloom">
        <meta property="og:locale" content="en_KE">

        <!-- Twitter / X Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $title ?? 'Noir & Bloom | Premium Floral & Gifting Atelier' }}">
        <meta name="twitter:description" content="Premium luxury floral curation atelier, bespoke gifting suite, and elite event design concierge. Sourcing Grade-A stems directly from Rift Valley growers.">
        <meta name="twitter:image" content="{{ asset('media/og-image-default.jpg') }}">
        <meta name="twitter:site" content="@NoirAndBloom">
    @endif

    <title>{{ $title ?? 'Noir & Bloom | Premium Floral & Gifting Atelier' }}</title>

    <!-- Google Search Engine Structured Data Schema (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "{{ url('/') }}/#organization",
          "name": "Noir & Bloom",
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
          "name": "Noir & Bloom",
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

    <!-- Tailwind CSS v4 Build Layer & Livewire Asset Core -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            const theme = localStorage.getItem('nb_theme') || 'onyx';
            document.documentElement.className = theme;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body class="bg-bg-base text-text-primary antialiased font-sans transition-colors duration-500 selection:bg-rose-950 selection:text-rose-200">

    <!-- Livewire 3 injects your full-page component directly here -->
    {{ $slot }}

    @livewireScripts
</body>
</html>