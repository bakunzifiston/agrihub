<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - {{ ucfirst(auth()->user()->tenant_type) }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            {{-- Sidebar - Sufee style: dark, full height, section headers --}}
            <aside class="fixed inset-y-0 left-0 w-56 sm:w-64 bg-gray-900 z-30 flex flex-col">
                {{-- Sidebar header --}}
                <div class="h-14 flex items-center justify-between px-4 border-b border-gray-800">
                    <a href="{{ route(auth()->user()->tenant_type . '.dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-6 w-auto fill-current text-white" />
                        <span class="font-semibold text-white text-sm">{{ ucfirst(auth()->user()->tenant_type) }} Admin</span>
                    </a>
                    <div class="w-9 h-9 flex items-center justify-center bg-red-600 rounded">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                </div>
                <nav class="flex-1 overflow-y-auto py-4">
                    {{-- Dashboard --}}
                    @php $isDashboard = request()->routeIs(auth()->user()->tenant_type . '.dashboard'); @endphp
                    <a href="{{ route(auth()->user()->tenant_type . '.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded text-sm font-medium transition-colors {{ $isDashboard ? 'bg-gray-800 text-white border-l-4 border-l-red-600 -ml-0.5 pl-3.5' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    {{-- Menu sections --}}
                    @foreach ($sidebarMenu ?? [] as $featureKey => $feature)
                        <div class="mt-4">
                            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $feature['label'] }}</p>
                            <ul class="mt-1">
                                @foreach ($feature['tables'] ?? [] as $routeName => $tableLabel)
                                    @php $isActive = request()->routeIs(str_replace('.index', '.*', $routeName)) || request()->routeIs($routeName); @endphp
                                    <li>
                                        <a href="{{ \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#' }}"
                                           class="flex items-center justify-between gap-3 px-4 py-2.5 mx-2 rounded text-sm font-medium transition-colors {{ $isActive ? 'bg-gray-800 text-white border-l-4 border-l-red-600 -ml-0.5 pl-3.5' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                            <span class="flex items-center gap-3">
                                                <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                {{ $tableLabel }}
                                            </span>
                                            <svg class="w-4 h-4 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>
            </aside>

            {{-- Main content area --}}
            <div class="flex-1 pl-56 sm:pl-64 min-h-screen flex flex-col">
                {{-- Top bar - light grey --}}
                <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                    <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 hidden sm:inline">{{ auth()->user()->name }}</span>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <span class="sr-only">Open user menu</span>
                                    <svg class="h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                {{-- Page content --}}
                <main class="flex-1 bg-gray-50 p-4 sm:p-6 lg:p-8">
                    @isset($header)
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
