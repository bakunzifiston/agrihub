<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
            <a href="{{ route('farmer.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Employee</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($employees->isEmpty())
            <p class="text-gray-600">No employees yet. Add workers or staff with their contact and role details.</p>
            <a href="{{ route('farmer.employees.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Employee</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hire Date</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($employees as $e)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $e->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $e->role ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ \App\Models\FarmerEmployee::EMPLOYMENT_TYPES[$e->employment_type] ?? $e->employment_type ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $e->phone ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $e->hire_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('farmer.employees.edit', $e) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('farmer.employees.destroy', $e) }}" class="inline" onsubmit="return confirm('Remove this employee?');">
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
