<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory</h2>
            <a href="{{ route('agribusiness.inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Item</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($inventory->isEmpty())
            <p class="text-gray-600">No inventory yet.</p>
            <a href="{{ route('agribusiness.inventory.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Item</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Storage</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($inventory as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->warehouse?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->category ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->quantity_in_stock, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->storage_location ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->batch_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->expiry_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.inventory.edit', $item) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.inventory.destroy', $item) }}" class="inline" onsubmit="return confirm('Remove this item?');">
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
