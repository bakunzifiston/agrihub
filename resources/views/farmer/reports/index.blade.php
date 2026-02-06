<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Basic Reports</h2>
            <form method="GET" action="{{ route('farmer.reports.index') }}" class="flex gap-2">
                <select name="period" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm">
                    <option value="all" @selected(($period ?? 'all') === 'all')>All Time</option>
                    <option value="month" @selected(($period ?? '') === 'month')>This Month</option>
                    <option value="quarter" @selected(($period ?? '') === 'quarter')>This Quarter</option>
                    <option value="year" @selected(($period ?? '') === 'year')>This Year</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Production (per period)</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($productionTotal ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Quantity produced</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Sales</h3>
            <p class="text-2xl font-semibold text-primary">{{ number_format($salesTotal ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Revenue from sales</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Input Costs</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($inputCost ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Total cost of inputs</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profit Estimation</h3>
            <p class="text-2xl font-semibold {{ ($salesTotal ?? 0) - ($inputCost ?? 0) >= 0 ? 'text-primary' : 'text-red-600' }}">
                {{ number_format(($salesTotal ?? 0) - ($inputCost ?? 0), 2) }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Sales − Input costs</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Input vs Output Cost</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Input cost (spent):</span>
                    <span class="font-medium">{{ number_format($inputCost ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Output revenue (sales):</span>
                    <span class="font-medium text-primary">{{ number_format($salesTotal ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2 border-t">
                    <span class="text-gray-700 font-medium">Net:</span>
                    <span class="font-semibold {{ ($salesTotal ?? 0) - ($inputCost ?? 0) >= 0 ? 'text-primary' : 'text-red-600' }}">
                        {{ number_format(($salesTotal ?? 0) - ($inputCost ?? 0), 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($yieldComparison))
    <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Yield Comparison per Season</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Season / Product</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Expected</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actual</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Variance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($yieldComparison as $row)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $row['season'] }}</td>
                        <td class="px-4 py-2 text-sm text-right">{{ number_format($row['expected'], 2) }}</td>
                        <td class="px-4 py-2 text-sm text-right">{{ number_format($row['actual'], 2) }}</td>
                        <td class="px-4 py-2 text-sm text-right {{ ($row['actual'] - $row['expected']) >= 0 ? 'text-primary' : 'text-red-600' }}">
                            {{ $row['expected'] > 0 ? number_format((($row['actual'] - $row['expected']) / $row['expected']) * 100, 1) . '%' : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</x-tenant-layout>
