<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
            @if(auth()->user()->organization_id)
                <a href="{{ route(auth()->user()->tenant_type . '.users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add User</a>
            @endif
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">Users in your organization</p>
            @if ($users->isEmpty())
                <p class="text-gray-600">No users yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                @if(auth()->user()->organization_id)
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $u)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $u->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $u->email }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        @if(auth()->user()->organization_id)
                                            @php setPermissionsTeamId(auth()->user()->organization_id); @endphp
                                            {{ $u->getRoleNames()->implode(', ') ?: '-' }}
                                            @php setPermissionsTeamId(null); @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $u->is_approved ? 'bg-primary-100 text-primary' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $u->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->organization_id)
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route(auth()->user()->tenant_type . '.users.edit', $u) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                            @if($u->id !== auth()->id())
                                                <form method="POST" action="{{ route(auth()->user()->tenant_type . '.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-tenant-layout>
