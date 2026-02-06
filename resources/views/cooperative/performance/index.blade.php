<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Performance Dashboard</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Produce Collected</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalCollected ?? 0, 2) }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Active Members</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $memberCount ?? 0 }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Payouts</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalPayouts ?? 0, 2) }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Stock Turnover</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $stockTurnover !== null ? number_format($stockTurnover, 2) : '-' }}</p>
                <p class="text-xs text-gray-500 mt-1">Avg monthly value per inventory item</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Revenue Trends (Last 6 Months)</h3>
            @if ($revenueTrends->isEmpty())
                <p class="text-gray-500">No collection data for the last 6 months.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($revenueTrends as $rt)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $rt->month)->format('F Y') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600 text-right">{{ number_format($rt->total ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-tenant-layout>
