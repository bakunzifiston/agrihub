<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Livestock</h2>
            <a href="{{ route('farmer.livestock.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Livestock</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($livestock->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">No livestock recorded yet.</p>
                    <a href="{{ route('farmer.livestock.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Livestock</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Breed</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Health</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($livestock as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($item->livestock_type) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->breed ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($item->purpose ?? '-') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->health_status ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('farmer.livestock.edit', $item) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.livestock.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete this livestock record?');">
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
