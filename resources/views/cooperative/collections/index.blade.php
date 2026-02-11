<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produce Collections</h2>
            <a href="{{ route('cooperative.collections.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Collection</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($collections->isEmpty())
            <p class="text-gray-600">No collections yet.</p>
            <a href="{{ route('cooperative.collections.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Collection</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quality</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Collection Point</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($collections as $c)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $c->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->contributor_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->collection_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($c->quantity_collected, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->quality_grade ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->collection_point ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->price_per_unit ? number_format($c->price_per_unit, 2) : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->total_value ? number_format($c->total_value, 2) : '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.collections.edit', $c) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.collections.destroy', $c) }}" class="inline" onsubmit="return confirm('Remove this collection?');">
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
