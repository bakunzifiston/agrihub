<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Procurement & Contracts</h2>
            <a href="{{ route('agribusiness.contracts.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Contract</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($contracts->isEmpty())
            <p class="text-gray-600">No contracts yet.</p>
            <a href="{{ route('agribusiness.suppliers.create') }}" class="mt-4 inline-block text-primary hover:text-primary-700 text-sm font-medium">Add a supplier first</a> · 
            <a href="{{ route('agribusiness.contracts.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Contract</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($contracts as $c)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $c->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->supplier?->supplier_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($c->contract_quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->price_per_unit ? number_format($c->price_per_unit, 2) : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->start_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->end_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $c->contract_status === 'active' ? 'bg-primary-100 text-primary' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($c->contract_status ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.contracts.edit', $c) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.contracts.destroy', $c) }}" class="inline" onsubmit="return confirm('Remove this contract?');">
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
