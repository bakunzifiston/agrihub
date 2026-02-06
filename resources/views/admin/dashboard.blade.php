<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Super Admin Dashboard
            </h2>
            <a href="{{ route('admin.feature-toggles') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                Feature Toggles
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Pending Approvals (First) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Tenant Approvals</h3>
                    @if ($pendingTenants->isEmpty())
                        <p class="text-gray-500">No pending approvals at the moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pendingTenants as $tenant)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $tenant->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tenant->email }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">
                                                    {{ ucfirst($tenant->tenant_type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $tenant->created_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('admin.tenants.approve', $tenant) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-green-600 hover:text-green-800">
                                                        Approve
                                                    </button>
                                                </form>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <form action="{{ route('admin.tenants.reject', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this tenant?');">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                                        Reject
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Number of Tenants Registered (Second) - One row --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Tenants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cooperatives</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agribusinesses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereIn('tenant_type', ['farmer', 'cooperative', 'agribusiness'])->where('is_approved', true)->count() }}</td>
                            <td class="px-6 py-4 text-2xl font-semibold text-gray-900">{{ \App\Models\User::where('tenant_type', 'farmer')->where('is_approved', true)->count() }}</td>
                            <td class="px-6 py-4 text-2xl font-semibold text-gray-900">{{ \App\Models\User::where('tenant_type', 'cooperative')->where('is_approved', true)->count() }}</td>
                            <td class="px-6 py-4 text-2xl font-semibold text-gray-900">{{ \App\Models\User::where('tenant_type', 'agribusiness')->where('is_approved', true)->count() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Default Tenant Features --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Default Tenant Features</h3>
                    <p class="text-sm text-gray-600 mb-4">Control which features are enabled by default for each tenant type. New tenants of that type will get these settings.</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feature</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Farmer</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cooperative</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Agribusiness</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Enable / Disable</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $allFeatureKeys = collect($tenantTypes)->flatMap(fn ($t) => array_keys($t['features']))->unique()->values();
                                @endphp
                                @foreach ($allFeatureKeys as $featureKey)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            @php
                                                $label = $tenantTypes['farmer']['features'][$featureKey] ?? $tenantTypes['cooperative']['features'][$featureKey] ?? $tenantTypes['agribusiness']['features'][$featureKey] ?? $featureKey;
                                            @endphp
                                            {{ $label }}
                                        </td>
                                        @foreach (['farmer', 'cooperative', 'agribusiness'] as $tenantType)
                                            <td class="px-4 py-3 text-center">
                                                @if (isset($tenantTypes[$tenantType]['features'][$featureKey]))
                                                    @php
                                                        $setting = $featureSettings->get("{$featureKey}:{$tenantType}");
                                                        $enabled = $setting ? $setting->enabled : (\App\Services\FeatureService::TENANT_TYPE_DEFAULTS[$tenantType][$featureKey] ?? true);
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ $enabled ? 'Enabled' : 'Disabled' }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col gap-2">
                                                @foreach (['farmer', 'cooperative', 'agribusiness'] as $tenantType)
                                                    @if (isset($tenantTypes[$tenantType]['features'][$featureKey]))
                                                        @php
                                                            $setting = $featureSettings->get("{$featureKey}:{$tenantType}");
                                                            $enabled = $setting ? $setting->enabled : (\App\Services\FeatureService::TENANT_TYPE_DEFAULTS[$tenantType][$featureKey] ?? true);
                                                        @endphp
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-xs text-gray-500 w-20">{{ ucfirst($tenantType) }}:</span>
                                                            <form action="{{ route('admin.feature-toggles.update') }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="feature_key" value="{{ $featureKey }}">
                                                                <input type="hidden" name="tenant_type" value="{{ $tenantType }}">
                                                                <input type="hidden" name="enabled" value="1">
                                                                <button type="submit" class="px-2 py-0.5 text-xs rounded {{ $enabled ? 'bg-green-200 text-green-900 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-green-100' }}">Enable</button>
                                                            </form>
                                                            <form action="{{ route('admin.feature-toggles.update') }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="feature_key" value="{{ $featureKey }}">
                                                                <input type="hidden" name="tenant_type" value="{{ $tenantType }}">
                                                                <input type="hidden" name="enabled" value="0">
                                                                <button type="submit" class="px-2 py-0.5 text-xs rounded {{ !$enabled ? 'bg-amber-200 text-amber-900 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-amber-100' }}">Disable</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('admin.feature-toggles') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-800">View full feature toggles →</a>
                </div>
            </div>

            {{-- Approved Tenants --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Approved Tenants</h3>
                    @if ($approvedTenants->isEmpty())
                        <p class="text-gray-500">No approved tenants yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Approved</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($approvedTenants as $tenant)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $tenant->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tenant->email }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($tenant->tenant_type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $tenant->updated_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <a href="{{ route('admin.tenants.features', $tenant) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 mr-2">Features</a>
                                                <form action="{{ route('admin.tenants.reject', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Revoke approval for this tenant? They will lose dashboard access.');">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-amber-600 hover:text-amber-800">
                                                        Revoke
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $approvedTenants->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
