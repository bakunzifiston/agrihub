<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Farmer Dashboard
        </h2>
    </x-slot>

    <div class="space-y-6">
        @if (!empty($kpis))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($kpis as $key => $kpi)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                        <h3 class="text-sm font-medium text-gray-500 uppercase mb-1">{{ $kpi['label'] }}</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $kpi['value'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @include('partials.dashboard-charts', ['charts' => $charts ?? []])

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Welcome</h3>
            <p class="text-gray-600">Use the sidebar to navigate between features and their tables. Each feature contains one or more data tables for managing your farm.</p>
        </div>
    </div>
</x-tenant-layout>
