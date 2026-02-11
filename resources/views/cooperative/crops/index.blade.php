<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crops</h2>
            <a href="{{ route('cooperative.crops.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Crop</a>
        </div>
    </x-slot>
    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($crops->isEmpty())
            <p class="text-gray-600">No crops yet.</p>
            <a href="{{ route('cooperative.crops.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Crop</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Crop</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Season</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Planting</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Area</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($crops as $c)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $c->crop_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->crop_type ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->season ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->planting_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->land_area_used !== null ? number_format($c->land_area_used, 2) . ' ' . ($c->area_unit ?? '') : '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($c->crop_status ?? '—') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.crops.edit', $c) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.crops.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete?');">
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
</x-tenant-layout>
