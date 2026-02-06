<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inputs</h2>
            <a href="{{ route('farmer.inputs.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Input</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($inputs->isEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <p class="text-gray-600">No inputs recorded yet.</p>
            <a href="{{ route('farmer.inputs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Input</a>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Input Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($inputs as $input)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $input->input_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($input->input_category) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $input->quantity }} {{ $input->unit }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $input->total_cost ? number_format($input->total_cost, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('farmer.inputs.edit', $input) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                <form method="POST" action="{{ route('farmer.inputs.destroy', $input) }}" class="inline" onsubmit="return confirm('Delete this input?');">
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
