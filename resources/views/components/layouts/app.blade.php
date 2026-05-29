<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Noir & Bloom | Premium Floral & Gifting ERP' }}</title>

    <!-- Fonts & Metadata -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500" rel="stylesheet" />

    <!-- Tailwind CSS v4 Build Layer & Livewire Asset Core -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Persistent Theme Bootstrap -->
    <script>
        (function() {
            const theme = localStorage.getItem('nb_theme') || 'midnight';
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