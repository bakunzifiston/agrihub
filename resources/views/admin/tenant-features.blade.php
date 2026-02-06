<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Feature Overrides: {{ $tenant->name }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-gray-600 mb-6">Override features for this specific tenant. When not set, the tenant type default applies.</p>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach ($features as $key => $label)
                            @php
                                $override = $overrides->get($key);
                                $enabled = $override !== null ? $override->enabled : null; // null = use type default
                            @endphp
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <label class="flex-1 text-sm font-medium text-gray-700">{{ $label }}</label>
                                <div class="flex items-center gap-2">
                                    @if ($enabled === true)
                                        <span class="text-xs text-green-600">Enabled (override)</span>
                                        <form action="{{ route('admin.tenants.features.update', $tenant) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="feature_key" value="{{ $key }}">
                                            <input type="hidden" name="enabled" value="0">
                                            <button type="submit" class="px-3 py-1 text-sm rounded bg-amber-100 text-amber-800 hover:bg-amber-200">Disable</button>
                                        </form>
                                    @elseif ($enabled === false)
                                        <span class="text-xs text-red-600">Disabled (override)</span>
                                        <form action="{{ route('admin.tenants.features.update', $tenant) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="feature_key" value="{{ $key }}">
                                            <input type="hidden" name="enabled" value="1">
                                            <button type="submit" class="px-3 py-1 text-sm rounded bg-green-100 text-green-800 hover:bg-green-200">Enable</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-500">Uses type default</span>
                                        <form action="{{ route('admin.tenants.features.update', $tenant) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="feature_key" value="{{ $key }}">
                                            <input type="hidden" name="enabled" value="1">
                                            <button type="submit" class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 hover:bg-green-200">Enable</button>
                                        </form>
                                        <form action="{{ route('admin.tenants.features.update', $tenant) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="feature_key" value="{{ $key }}">
                                            <input type="hidden" name="enabled" value="0">
                                            <button type="submit" class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-800 hover:bg-amber-200">Disable</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
