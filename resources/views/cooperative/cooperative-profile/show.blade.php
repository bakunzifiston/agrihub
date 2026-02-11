<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cooperative Profile</h2>
            <a href="{{ route('cooperative.cooperative-profile.edit', $profile) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Edit Profile</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Name</h3>
            <p class="mt-1 text-lg font-medium text-gray-900">{{ $profile->name }}</p>
        </div>

        @if ($profile->registration_number || $profile->registration_date)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($profile->registration_number)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Registration number</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->registration_number }}</p>
                    </div>
                @endif
                @if ($profile->registration_date)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Registration date</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->registration_date->format('F j, Y') }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if ($profile->phone || $profile->email)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($profile->phone)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Phone</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->phone }}</p>
                    </div>
                @endif
                @if ($profile->email)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->email }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if ($profile->address)
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Address</h3>
                <p class="mt-1 text-gray-900">{{ $profile->address }}</p>
            </div>
        @endif

        @if ($profile->district || $profile->sector || $profile->country)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if ($profile->country)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Country</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->country }}</p>
                    </div>
                @endif
                @if ($profile->district)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">District</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->district }}</p>
                    </div>
                @endif
                @if ($profile->sector)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sector</h3>
                        <p class="mt-1 text-gray-900">{{ $profile->sector }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if ($profile->focus)
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Focus</h3>
                <p class="mt-1 text-gray-900">{{ \App\Models\CooperativeProfile::FOCUS_OPTIONS[$profile->focus] ?? $profile->focus }}</p>
            </div>
        @endif

        @if ($profile->description)
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</h3>
                <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $profile->description }}</p>
            </div>
        @endif

        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</h3>
            <p class="mt-1">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $profile->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($profile->status) }}</span>
            </p>
        </div>
    </div>
</x-tenant-layout>
