<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
            <a href="{{ route('farmer.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Employee</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-kpis :kpis="$kpis ?? []" />

            @if (session('success'))
                <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($employees->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">No employees recorded yet.</p>
                    <a href="{{ route('farmer.employees.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Employee</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Title</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farm</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hire Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            @if ($employee->photo)
                                                <img class="h-8 w-8 rounded-full object-cover mr-3" src="{{ Storage::url($employee->photo) }}" alt="{{ $employee->full_name }}" />
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</div>
                                                @if ($employee->email)
                                                    <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->job_title ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->farmProfile?->farm_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->employment_type_label }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->phone_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->hire_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-gray-100 text-gray-800',
                                                'terminated' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$employee->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('farmer.employees.edit', $employee) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Delete this employee?');">
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
    </div>
</x-tenant-layout>
