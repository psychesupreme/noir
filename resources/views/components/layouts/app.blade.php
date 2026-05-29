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
            const bgColors = {
                'midnight': '#09090B',
                'alabaster': '#F4F4F6',
                'floral': '#121A16',
                'love': '#1C0D12',
                'cute': '#24181B'
            };
            const textColors = {
                'midnight': '#FAFAFA',
                'alabaster': '#1C1C1E',
                'floral': '#EDF2EF',
                'love': '#FDF4F7',
                'cute': '#FFF5F7'
            };
            document.documentElement.style.backgroundColor = bgColors[theme];
            document.documentElement.style.color = textColors[theme];
        })();
    </script>
</head>
<body class="bg-inherit text-inherit antialiased selection:bg-rose-950 selection:text-rose-200">

    <!-- Livewire 3 injects your full-page component directly here -->
    {{ $slot }}

    @livewireScripts
</body>
</html>