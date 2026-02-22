<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Warehouses</h2>
            <a href="{{ route('cooperative.warehouses.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Warehouse</a>
        </div>
    </x-slot>

    <x-module-kpis :kpis="$kpis ?? []" />

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($warehouses->isEmpty())
            <p class="text-gray-600">No warehouses yet. Add one to assign inventory to locations.</p>
            <a href="{{ route('cooperative.warehouses.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Warehouse</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City / District / Sector</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inventory</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($warehouses as $wh)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $wh->warehouse_id }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $wh->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ implode(' / ', array_filter([$wh->city, $wh->district, $wh->sector])) ?: '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $wh->country ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $wh->manager_display_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $wh->inventory_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.warehouses.edit', $wh) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.warehouses.destroy', $wh) }}" class="inline" onsubmit="return confirm('Remove this warehouse? Inventory items will be unassigned.');">
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
