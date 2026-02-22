<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Suppliers</h2>
            <a href="{{ route('agribusiness.suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Supplier</a>
        </div>
    </x-slot>

    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($suppliers->isEmpty())
            <p class="text-gray-600">No suppliers yet.</p>
            <a href="{{ route('agribusiness.suppliers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Supplier</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($suppliers as $s)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $s->supplier_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($s->supplier_type) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $s->contact_person ?? $s->phone_number ?? $s->email ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $s->location ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $s->contract_status ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $s->rating ? number_format($s->rating, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.suppliers.edit', $s) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.suppliers.destroy', $s) }}" class="inline" onsubmit="return confirm('Remove this supplier?');">
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
