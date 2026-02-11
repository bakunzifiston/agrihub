<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Livestock</h2>
            <a href="{{ route('cooperative.livestock.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Livestock</a>
        </div>
    </x-slot>
    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($livestock->isEmpty())
            <p class="text-gray-600">No livestock yet.</p>
            <a href="{{ route('cooperative.livestock.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Livestock</a>
        @else
            <div class="overflow-x-auto">
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
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ ucfirst($item->livestock_type) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->breed ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($item->purpose ?? '—') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->health_status ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.livestock.edit', $item) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.livestock.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete?');">
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
