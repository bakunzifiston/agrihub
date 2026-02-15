<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pre-orders</h2>
            <a href="{{ route('farmer.pre-order-listings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Pre-order Listings</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p class="text-sm text-gray-600 mb-4">Orders from the marketplace (WooCommerce) for your pre-order listings.</p>
        @if ($preOrders->isEmpty())
            <p class="text-gray-600">No pre-orders yet. When customers order on the marketplace, they will appear here.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($preOrders as $o)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $o->preOrderListing->title }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($o->quantity, 2) }} {{ $o->preOrderListing->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $o->customer_name ?? $o->customer_email ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $o->status === 'fulfilled' ? 'bg-primary-100 text-primary' : ($o->status === 'cancelled' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-800') }}">{{ \App\Models\PreOrder::STATUSES[$o->status] ?? $o->status }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $o->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $preOrders->links() }}</div>
        @endif
    </div>
</x-tenant-layout>
