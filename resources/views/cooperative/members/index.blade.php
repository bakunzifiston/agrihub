<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Members</h2>
            <a href="{{ route('cooperative.members.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Add Member</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if ($members->isEmpty())
            <p class="text-gray-600">No members yet.</p>
            <a href="{{ route('cooperative.members.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700">Add Member</a>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membership #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Join Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contribution</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($members as $m)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $m->membership_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $m->display_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $m->join_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($m->contribution_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $m->role === 'leader' ? 'bg-primary-100 text-primary' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($m->role) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $m->status === 'active' ? 'bg-primary-100 text-primary' : ($m->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">{{ ucfirst($m->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cooperative.members.edit', $m) }}" class="text-primary hover:text-primary-700 text-sm font-medium mr-3">Edit</a>
                                    <form method="POST" action="{{ route('cooperative.members.destroy', $m) }}" class="inline" onsubmit="return confirm('Remove this member?');">
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
