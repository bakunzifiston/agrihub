<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permissions</h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">Available permissions for your tenant type</p>
            @if ($permissions->isEmpty())
                <p class="text-gray-600">No permissions defined yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($permissions as $perm)
                        <div class="px-4 py-2 bg-gray-50 rounded-lg text-sm text-gray-700">
                            {{ $perm->name }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-tenant-layout>
