@extends('layouts.landing')

@section('title', 'AgriHub — Connect Farmers, Cooperatives & Agribusiness')

@section('content')
<div class="min-h-screen flex flex-col bg-gradient-to-b from-slate-50 via-white to-slate-50">
    {{-- Header --}}
    <header class="sticky top-0 z-50 py-5 px-6 shadow-md" style="background-color: #1D293D;">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <span class="text-2xl font-bold tracking-tight text-white">AgriHub</span>
            </a>
            <a href="{{ route('admin.login') }}" class="text-sm font-medium transition-colors flex items-center gap-2" style="color: #ffffff;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Admin
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <main class="flex-1 flex items-center px-6 py-16 lg:py-24">
        <div class="max-w-5xl w-full mx-auto">
            <div class="text-center mb-14">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-5">
                    <span class="text-brand">AgriHub</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 max-w-xl mx-auto leading-relaxed">
                    Connect farmers, cooperatives, and agribusinesses. Choose your portal to get started.
                </p>
            </div>

            {{-- Portal cards --}}
            <div class="grid md:grid-cols-3 gap-5 lg:gap-6">
                <a href="{{ route('farmer.login') }}" class="group block">
                    <div class="h-full bg-white rounded-2xl p-7 shadow-sm border border-slate-200/70 hover:border-brand/30 hover:shadow-brand transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0h.5a2.5 2.5 0 002.5-2.5V3.935M12 12a4 4 0 01-4-4v-1a2 2 0 012-2h2a2 2 0 012 2v1a4 4 0 01-4 4z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-brand mb-1.5">Farmer</h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-5">Sign in or register here if you are a farmer managing your farm.</p>
                        <span class="inline-flex items-center gap-2 text-brand font-medium text-sm group-hover:gap-3 transition-all">
                            Enter
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </div>
                </a>

                <a href="{{ route('cooperative.login') }}" class="group block">
                    <div class="h-full bg-white rounded-2xl p-7 shadow-sm border border-slate-200/70 hover:border-brand/30 hover:shadow-brand transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-5 group-hover:bg-emerald-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-brand mb-1.5">Cooperative</h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-5">Sign in or register here if you represent a cooperative.</p>
                        <span class="inline-flex items-center gap-2 text-brand font-medium text-sm group-hover:gap-3 transition-all">
                            Enter
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </div>
                </a>

                <a href="{{ route('agribusiness.login') }}" class="group block">
                    <div class="h-full bg-white rounded-2xl p-7 shadow-sm border border-slate-200/70 hover:border-brand/30 hover:shadow-brand transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center mb-5 group-hover:bg-sky-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-brand mb-1.5">Agribusiness</h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-5">Sign in or register here if you represent an agribusiness.</p>
                        <span class="inline-flex items-center gap-2 text-brand font-medium text-sm group-hover:gap-3 transition-all">
                            Enter
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </div>
                </a>
            </div>

            <p class="text-center text-slate-400 text-sm mt-10">
                Already have an account? Click your portal above to sign in.
            </p>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-5 px-6 border-t border-slate-200/60 bg-white/60">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-sm text-slate-500">© {{ date('Y') }} <span class="text-brand font-medium">AgriHub</span></span>
            <a href="{{ route('admin.login') }}" class="text-sm text-slate-500 hover:text-brand transition-colors font-medium">Admin</a>
        </div>
    </footer>
</div>
@endsection
