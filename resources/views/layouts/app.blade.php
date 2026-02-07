<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}@auth - {{ ucfirst(auth()->user()->tenant_type) }}@endauth</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @include('layouts.partials.vite-assets')
    </head>
    <body class="font-sans antialiased bg-gray-100">
        @php
            $useTenantLayout = auth()->user()
                && in_array(auth()->user()->tenant_type, ['farmer', 'cooperative', 'agribusiness'])
                && request()->routeIs('farmer.*', 'cooperative.*', 'agribusiness.*');
        @endphp

        @if ($useTenantLayout)
            {{-- Tenant dashboard layout (Sufee style) --}}
            <div class="min-h-screen flex">
                @include('layouts.partials.tenant-sidebar')

                <div class="flex-1 pl-56 sm:pl-64 min-h-screen flex flex-col">
                    <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                        <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-500 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                        <span class="sr-only">Open user menu</span>
                                        <svg class="h-8 w-8 rounded-full bg-primary-100 text-primary flex items-center justify-center" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                    @if(auth()->user()->tenant_type === 'farmer')
                                        <x-dropdown-link :href="route('farmer.register')">Register another farmer</x-dropdown-link>
                                    @endif
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
        @else
            {{-- Breeze default layout --}}
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        @endif

        @stack('scripts')
    </body>
</html>
