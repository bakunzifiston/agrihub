<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Applications (Fertilizers, Chemicals)</h2>
            <a href="{{ route('farmer.input-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Record Application</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($applications->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">No input applications recorded yet.</p>
                    <a href="{{ route('farmer.input-applications.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Record Application</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Input / Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farm / Plot</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Crop</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied by</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($applications as $app)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $app->application_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-medium text-gray-900">{{ $app->input_name }}</span>
                                        <span class="text-gray-500">({{ \App\Models\FarmInputApplication::getInputTypeLabel($app->input_type) }})</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $app->farmProfile?->farm_name ?? '-' }} / {{ $app->plot?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $app->crop?->crop_name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $app->quantity_used }} {{ $app->unit }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $app->applied_by ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('farmer.input-applications.edit', $app) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                        <form method="POST" action="{{ route('farmer.input-applications.destroy', $app) }}" class="inline" onsubmit="return confirm('Delete this application record?');">
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
