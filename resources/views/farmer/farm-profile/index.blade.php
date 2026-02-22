<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Farm Profile
            </h2>
            <a href="{{ route('farmer.farm-profile.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                Add Farm Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-kpis :kpis="$kpis ?? []" />

            @if (session('success'))
                <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($profiles->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">You haven't created a farm profile yet.</p>
                    <a href="{{ route('farmer.farm-profile.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">
                        Create Farm Profile
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farm Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farm Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plots</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inputs availability</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $profile->full_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $profile->farm_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($profile->farm_type) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $profile->plots->isNotEmpty() ? $profile->plots->pluck('name')->implode(', ') : ($profile->plot_count !== null ? $profile->plot_count . ' plot(s)' : '-') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $profile->inputs_availability ? implode(', ', array_map('ucfirst', $profile->inputs_availability)) : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $profile->location_district ?? $profile->location_country ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $profile->status === 'active' ? 'bg-primary-100 text-primary' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($profile->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('farmer.farm-profile.edit', $profile) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.farm-profile.destroy', $profile) }}" class="inline" onsubmit="return confirm('Delete this farm profile?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
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
</x-tenant-layout>
