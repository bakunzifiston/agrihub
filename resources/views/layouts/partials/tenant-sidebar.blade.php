{{-- Tenant sidebar - Sufee style, used when on tenant routes --}}
<aside class="fixed inset-y-0 left-0 w-56 sm:w-64 bg-primary z-20 flex flex-col">
    <div class="h-14 flex items-center justify-between px-4 border-b border-white/10">
        <a href="{{ route(auth()->user()->tenant_type . '.dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-6 w-auto fill-current text-white" />
            <span class="font-semibold text-white text-sm">{{ ucfirst(auth()->user()->tenant_type) }} Admin</span>
        </a>
        <div class="w-9 h-9 flex items-center justify-center bg-white/20 rounded">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </div>
    </div>
    <nav class="flex-1 overflow-y-auto py-4">
        @php $isDashboard = request()->routeIs(auth()->user()->tenant_type . '.dashboard'); @endphp
        <a href="{{ route(auth()->user()->tenant_type . '.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded text-sm font-medium transition-colors {{ $isDashboard ? 'bg-primary-700 text-white border-l-4 border-l-white/60 -ml-0.5 pl-3.5' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Dashboard
        </a>
        @foreach ($sidebarMenu ?? [] as $featureKey => $feature)
            <div class="mt-4">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $feature['label'] }}</p>
                <ul class="mt-1">
                    @foreach ($feature['tables'] ?? [] as $routeName => $tableLabel)
                        @php $isActive = request()->routeIs(str_replace('.index', '.*', $routeName)) || request()->routeIs($routeName); @endphp
                        <li>
                            <a href="{{ \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#' }}"
                               class="flex items-center justify-between gap-3 px-4 py-2.5 mx-2 rounded text-sm font-medium transition-colors {{ $isActive ? 'bg-primary-700 text-white border-l-4 border-l-white/60 -ml-0.5 pl-3.5' : 'text-gray-300 hover:bg-primary-700 hover:text-white' }}">
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
