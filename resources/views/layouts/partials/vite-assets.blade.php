@php
    $manifestPath = public_path('build/manifest.json');
@endphp
@if (file_exists($manifestPath))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Fallback when Vite build is missing (cPanel): Tailwind CDN + inline theme so it always works --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" />
    <style>
    :root{--primary:#1D293D;--primary-50:#e8eaee;--primary-100:#d1d5dd;--primary-700:#151d2e;--brand:#0F172B;}
    .bg-primary,.bg-primary-600{background-color:var(--primary)!important;}.bg-primary-50,.bg-primary-100{background-color:var(--primary-50)!important;}
    .bg-primary-700{background-color:var(--primary-700)!important;}.text-primary,.text-primary-700{color:var(--primary)!important;}
    .text-brand{color:var(--brand)!important;}.hover\:bg-primary-700:hover{background-color:var(--primary-700)!important;}
    .hover\:text-primary:hover,.hover\:text-primary-700:hover,.hover\:text-brand:hover{color:var(--primary)!important;}
    a.bg-primary,button.bg-primary,.inline-flex.bg-primary{background-color:var(--primary)!important;color:#fff!important;}
    a.bg-primary:hover,button.bg-primary:hover{background-color:var(--primary-700)!important;color:#fff!important;}
    .border-primary,.focus\:border-primary:focus{border-color:var(--primary)!important;}
    .focus\:ring-primary:focus,.ring-primary{--tw-ring-color:var(--primary)!important;box-shadow:0 0 0 3px rgba(29,41,61,0.3)!important;}
    .border-l-white\/60{border-left-color:rgba(255,255,255,.6)!important;}
    input:focus,select:focus,textarea:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(29,41,61,0.2);}
    .shadow-brand{box-shadow:0 20px 25px -5px rgba(15,23,43,.08),0 8px 10px -6px rgba(15,23,43,.05)!important;}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>window.Chart=window.Chart||window.chartJs?.Chart||null;</script>
@endif
