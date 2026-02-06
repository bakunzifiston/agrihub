<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Feature Toggles
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-gray-600">Enable or disable features per tenant type. These are defaults for all tenants of that type.</p>

            @foreach ($tenantTypes as $tenantType => $data)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $data['label'] }} Features</h3>
                        <div class="space-y-3">
                            @foreach ($data['features'] as $key => $label)
                                @php
                                    $setting = $settings->get("{$key}:{$tenantType}:default");
                                    $enabled = $setting ? $setting->enabled : true;
                                @endphp
                                <form action="{{ route('admin.feature-toggles.update') }}" method="POST" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    @csrf
                                    <input type="hidden" name="feature_key" value="{{ $key }}">
                                    <input type="hidden" name="tenant_type" value="{{ $tenantType }}">
                                    <label class="flex-1 text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ $enabled ? 'Enabled' : 'Disabled' }}</span>
                                        <input type="hidden" name="enabled" value="{{ $enabled ? '0' : '1' }}">
                                        <button type="submit" class="px-3 py-1 text-sm rounded {{ $enabled ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                            {{ $enabled ? 'Disable' : 'Enable' }}
                                        </button>
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
