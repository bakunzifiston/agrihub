<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orders</h2>
            <a href="{{ route('cooperative.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Order</a>
        </div>
    </x-slot>
    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($orders->isEmpty())
            <p class="text-gray-600">No orders yet.</p>
            <a href="{{ route('cooperative.orders.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Order</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $o)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $o->order_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $o->customer_display_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $o->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600">{{ number_format($o->quantity, 2) }} {{ $o->unit ?? '' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    @if ($o->inventory)
                                        In stock: <strong>{{ number_format($o->in_stock_quantity, 2) }} {{ $o->unit ?? '' }}</strong>
                                        <br><span class="text-xs {{ $o->remaining_stock !== null && $o->remaining_stock < 0 ? 'text-red-600' : '' }}">Remaining: {{ number_format($o->remaining_stock, 2) }} {{ $o->unit ?? '' }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">{{ $o->total_amount !== null ? number_format($o->total_amount, 2) : '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $o->order_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium
                                        {{ $o->status === 'fulfilled' ? 'bg-green-100 text-green-800' : ($o->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($o->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.orders.edit', $o) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.orders.destroy', $o) }}" class="inline" onsubmit="return confirm('Delete this order?');">
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
