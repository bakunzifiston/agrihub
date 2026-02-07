@php
    $manifestPath = public_path('build/manifest.json');
@endphp
@if (file_exists($manifestPath))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Fallback when Vite build is missing (e.g. cPanel): run "npm run build" and upload public/build/ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
@endif
