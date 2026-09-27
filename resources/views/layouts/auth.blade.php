<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi Warga') — Habar Etam</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logo-habar-etam.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @php
    $isProduction = app()->environment('production');
    $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
    @php
    $manifest = json_decode(file_get_contents($manifestPath), true);
    @endphp
    <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endif
</head>

<body class="h-full w-full bg-slate-950 font-sans antialiased overflow-hidden selection:bg-brand-gold selection:text-black" data-lenis-prevent>
    <main class="h-full w-full">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>