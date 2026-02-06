<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">Roles and their permissions in your organization</p>
            @if ($roles->isEmpty())
                <p class="text-gray-600">No roles yet. Roles are created when you register.</p>
            @else
                <div class="space-y-4">
                    @foreach ($roles as $role)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">{{ $role->name }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($role->permissions as $perm)
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-tenant-layout>
