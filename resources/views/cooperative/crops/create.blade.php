<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Crop</h2>
        <a href="{{ route('cooperative.crops.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back</a>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-3xl">
        <form method="POST" action="{{ route('cooperative.crops.store') }}">
            @csrf
            @include('cooperative.crops._form', ['crop' => null])
            <div class="mt-6 flex gap-4">
                <x-primary-button>Add Crop</x-primary-button>
                <a href="{{ route('cooperative.crops.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
