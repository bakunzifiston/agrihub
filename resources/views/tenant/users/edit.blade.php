<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
            <a href="{{ route(auth()->user()->tenant_type . '.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Users</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-lg">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route(auth()->user()->tenant_type . '.users.update', $editUser) }}">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                <div>
                    <x-input-label for="name" value="Name *" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $editUser->name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="email" value="Email *" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $editUser->email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
                <div>
                    <x-input-label for="password" value="New Password (leave blank to keep current)" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirm New Password" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                </div>
                <div>
                    <x-input-label for="role" value="Role *" />
                    <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role', $userRole) === $role->name)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                </div>
                <div>
                    <x-input-label value="Additional Permissions" />
                    <p class="text-xs text-gray-500 mb-2">Select permissions to grant</p>
                    <div class="mt-2 space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50">
                        @foreach ($permissions as $perm)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions', $userPermissions)) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-gray-700">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <x-primary-button>Update User</x-primary-button>
                <a href="{{ route(auth()->user()->tenant_type . '.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
