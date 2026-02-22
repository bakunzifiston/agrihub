<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employee Training Records</h2>
            <a href="{{ route('farmer.trainings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Training</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-module-kpis :kpis="$kpis ?? []" />

            @if (session('success'))
                <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($trainings->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">No training records yet.</p>
                    <a href="{{ route('farmer.trainings.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Training</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Training</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($trainings as $training)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $training->employee?->full_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ $training->training_name }}</div>
                                        @if ($training->duration_hours)
                                            <div class="text-xs text-gray-500">{{ $training->duration_hours }} hours</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $training->training_type_label }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $training->provider ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        @if ($training->start_date)
                                            {{ $training->start_date->format('M d, Y') }}
                                            @if ($training->end_date)
                                                <br><span class="text-xs">to {{ $training->end_date->format('M d, Y') }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$training->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $training->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        @if ($training->certificate_number)
                                            <div class="text-xs">{{ $training->certificate_number }}</div>
                                            @if ($training->certificate_expiry)
                                                <div class="text-xs {{ $training->certificate_expiry->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                                                    Exp: {{ $training->certificate_expiry->format('M d, Y') }}
                                                </div>
                                            @endif
                                            @if ($training->certificate_file)
                                                <a href="{{ Storage::url($training->certificate_file) }}" target="_blank" class="text-primary hover:underline text-xs">View</a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('farmer.trainings.edit', $training) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.trainings.destroy', $training) }}" class="inline" onsubmit="return confirm('Delete this training record?');">
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
