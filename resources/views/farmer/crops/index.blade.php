<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crops</h2>
            <a href="{{ route('farmer.crops.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Crop</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($crops->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">No crops recorded yet.</p>
                    <a href="{{ route('farmer.crops.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Crop</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Crop Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Season</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Planting</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($crops as $crop)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $crop->crop_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $crop->crop_type ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $crop->season ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary">{{ ucfirst($crop->crop_status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $crop->planting_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('farmer.crops.edit', $crop) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.crops.destroy', $crop) }}" class="inline" onsubmit="return confirm('Delete this crop?');">
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
