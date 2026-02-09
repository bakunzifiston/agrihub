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

        @if (!empty($farmProfile))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Farm profile</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Farm name</dt>
                        <dd class="font-medium text-gray-900">{{ $farmProfile->farm_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Farm type</dt>
                        <dd class="font-medium text-gray-900">{{ ucfirst($farmProfile->farm_type ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Plots</dt>
                        <dd class="font-medium text-gray-900">{{ $farmProfile->plots->isNotEmpty() ? $farmProfile->plots->pluck('name')->implode(', ') : ($farmProfile->plot_count !== null ? $farmProfile->plot_count . ' plot(s)' : '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Inputs availability</dt>
                        <dd class="font-medium text-gray-900">{{ $farmProfile->inputs_availability ? implode(', ', array_map('ucfirst', $farmProfile->inputs_availability)) : '—' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('farmer.farm-profile.edit', $farmProfile) }}" class="mt-4 inline-flex items-center text-primary hover:text-primary-700 text-sm font-medium">Edit farm profile →</a>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Welcome</h3>
            <p class="text-gray-600">Use the sidebar to navigate between features and their tables. Each feature contains one or more data tables for managing your farm.</p>
        </div>
    </div>
</x-tenant-layout>
