<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Distribution</h2>
            <a href="{{ route('agribusiness.distributions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Distribution</a>
        </div>
    </x-slot>

    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($distributions->isEmpty())
            <p class="text-gray-600">No distributions yet.</p>
            <a href="{{ route('agribusiness.distributions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Distribution</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">From warehouse</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dispatch Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($distributions as $d)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $d->customer_display_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $d->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $d->inventory && $d->inventory->warehouse ? $d->inventory->warehouse->name : '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($d->quantity_dispatched, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $d->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $d->dispatch_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $d->delivery_status === 'delivered' ? 'bg-primary-100 text-primary' : ($d->delivery_status === 'in_transit' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">{{ ucfirst($d->delivery_status ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.distributions.edit', $d) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.distributions.destroy', $d) }}" class="inline" onsubmit="return confirm('Remove this distribution?');">
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
