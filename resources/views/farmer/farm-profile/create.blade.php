<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Farm Profile
            </h2>
            <a href="{{ route('farmer.farm-profile.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Farm Profiles</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('farmer.farm-profile.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('farmer.farm-profile._form', ['profile' => null])
                    <div class="mt-6 flex gap-4">
                        <x-primary-button>Create Farm Profile</x-primary-button>
                        <a href="{{ route('farmer.farm-profile.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-tenant-layout>
