<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sales</h2>
            <a href="{{ route('farmer.sales.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Sale</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($sales->isEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <p class="text-gray-600">No sales recorded yet.</p>
            <a href="{{ route('farmer.sales.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Sale</a>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buyer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->product_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $sale->buyer_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $sale->quantity_sold }} {{ $sale->unit }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('farmer.sales.edit', $sale) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                <form method="POST" action="{{ route('farmer.sales.destroy', $sale) }}" class="inline" onsubmit="return confirm('Delete this sale?');">
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
</x-tenant-layout>
