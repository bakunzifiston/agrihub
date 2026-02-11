<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Livestock</h2>
        <a href="{{ route('cooperative.livestock.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back</a>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-3xl">
        <form method="POST" action="{{ route('cooperative.livestock.store') }}">
            @csrf
            @include('cooperative.livestock._form', ['livestock' => null])
            <div class="mt-6 flex gap-4">
                <x-primary-button>Add Livestock</x-primary-button>
                <a href="{{ route('cooperative.livestock.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
