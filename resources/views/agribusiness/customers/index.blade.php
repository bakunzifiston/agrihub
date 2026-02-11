<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customers</h2>
            <a href="{{ route('agribusiness.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Customer</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($customers->isEmpty())
            <p class="text-gray-600">No customers yet. Add customers to select them when creating distributions.</p>
            <a href="{{ route('agribusiness.customers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Customer</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($customers as $c)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $c->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ \App\Models\AgribusinessCustomer::TYPES[$c->customer_type] ?? $c->customer_type }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->phone ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $c->email ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">{{ $c->address ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agribusiness.customers.edit', $c) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('agribusiness.customers.destroy', $c) }}" class="inline" onsubmit="return confirm('Remove this customer?');">
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
