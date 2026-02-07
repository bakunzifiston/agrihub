<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgriHub')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @include('layouts.partials.vite-assets')
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans">
    @yield('content')
    @stack('scripts')
</body>
</html>
