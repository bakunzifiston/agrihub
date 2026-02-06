<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sales & Financial Reports</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Revenue</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($revenue ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">From active contracts (qty × price)</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Cost of Goods Sold</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($cogs ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Processing costs</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Profit Margin</h3>
                <p class="text-2xl font-semibold {{ ($profitMargin ?? 0) >= 0 ? 'text-primary' : 'text-red-600' }}">{{ $profitMargin !== null ? number_format($profitMargin, 1) . '%' : '-' }}</p>
                <p class="text-xs text-gray-500 mt-1">(Revenue − COGS) / Revenue</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Inventory Turnover</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $inventoryTurnover !== null ? number_format($inventoryTurnover, 2) : '-' }}</p>
                <p class="text-xs text-gray-500 mt-1">Dispatched / avg stock</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Supplier Performance</h3>
            @if ($supplierPerformance->isEmpty())
                <p class="text-gray-500">No supplier data yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contract Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($supplierPerformance as $sp)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $sp->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $sp->rating ? number_format($sp->rating, 1) : '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ ucfirst($sp->contract_status ?? '-') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-tenant-layout>
