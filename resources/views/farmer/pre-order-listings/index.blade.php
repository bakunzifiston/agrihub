<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pre-order Listings</h2>
            <a href="{{ route('farmer.pre-order-listings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Listing</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p class="text-sm text-gray-600 mb-4">List crops or harvests for pre-order on the marketplace. Sync these with your WordPress WooCommerce store so customers can pre-order.</p>
        @if ($listings->isEmpty())
            <p class="text-gray-600">No pre-order listings yet. Add a listing from your crops, harvest outputs, or create a manual listing.</p>
            <a href="{{ route('farmer.pre-order-listings.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Listing</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity / Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected harvest</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($listings as $l)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $l->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($l->quantity_available, 2) }} {{ $l->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($l->available_to_sell, 2) }} {{ $l->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $l->expected_harvest_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if ($l->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('farmer.pre-orders.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium mr-3">Orders</a>
                                    <a href="{{ route('farmer.pre-order-listings.edit', $l) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('farmer.pre-order-listings.destroy', $l) }}" class="inline" onsubmit="return confirm('Remove this listing?');">
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
