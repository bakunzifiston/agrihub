<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cooperative Profile</h2>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 p-4 bg-primary-100 text-primary rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p class="text-gray-600">You haven't set up your cooperative profile yet.</p>
        <p class="mt-2 text-sm text-gray-500">Add your cooperative name, registration details, contact info, and location.</p>
        <a href="{{ route('cooperative.cooperative-profile.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">
            Create Cooperative Profile
        </a>
    </div>
</x-tenant-layout>
