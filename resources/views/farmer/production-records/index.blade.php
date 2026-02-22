<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Production Records</h2>
            <a href="{{ route('farmer.production-records.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Record</a>
        </div>
    </x-slot>

    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($records->isEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <p class="text-gray-600">No production records yet.</p>
            <a href="{{ route('farmer.production-records.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Record</a>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($records as $record)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $record->product_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($record->product_type) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $record->production_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $record->quantity_produced }} {{ $record->quantity_unit }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('farmer.production-records.edit', $record) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                <form method="POST" action="{{ route('farmer.production-records.destroy', $record) }}" class="inline" onsubmit="return confirm('Delete this production record?');">
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
