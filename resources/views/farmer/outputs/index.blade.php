<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Outputs</h2>
            <a href="{{ route('farmer.outputs.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium text-sm">Add Output</a>
        </div>
    </x-slot>

    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($outputs->isEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <p class="text-gray-600">No outputs recorded yet.</p>
            <a href="{{ route('farmer.outputs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Add Output</a>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Storage</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($outputs as $output)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $output->product_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $output->quantity_available }} {{ $output->unit }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $output->storage_location ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('farmer.outputs.edit', $output) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                <form method="POST" action="{{ route('farmer.outputs.destroy', $output) }}" class="inline" onsubmit="return confirm('Delete this output?');">
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
