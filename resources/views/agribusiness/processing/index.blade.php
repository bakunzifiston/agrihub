<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Processing & Production</h2>
            <a href="{{ route('agribusiness.processing.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Record</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($records->isEmpty())
            <p class="text-gray-600">No processing records yet.</p>
            <a href="{{ route('agribusiness.processing.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Record</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raw Material</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Input</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Output</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wastage</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($records as $r)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $r->raw_material }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($r->quantity_input, 2) }} {{ $r->input_unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($r->quantity_output, 2) }} {{ $r->output_unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $r->processing_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $r->processing_cost ? number_format($r->processing_cost, 2) : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $r->wastage_quantity ? number_format($r->wastage_quantity, 2) : '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.processing.edit', $r) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.processing.destroy', $r) }}" class="inline" onsubmit="return confirm('Remove this record?');">
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
