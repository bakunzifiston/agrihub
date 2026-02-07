@php
    $manifestPath = public_path('build/manifest.json');
@endphp
@if (file_exists($manifestPath))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Fallback when Vite build is missing (e.g. cPanel): Tailwind CDN + our theme --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" />
    <link rel="stylesheet" href="{{ asset('build-fallback.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>window.Chart = window.Chart || (typeof Chart !== 'undefined' ? Chart : null);</script>
@endif
