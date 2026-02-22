<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Input</h2>
            <a href="{{ route('farmer.inputs.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('farmer.inputs.update', $farmInput) }}">
            @csrf
            @method('PATCH')
            @include('farmer.inputs._form', ['input' => $farmInput, 'farmProfiles' => $farmProfiles])
            <div class="mt-6 flex gap-4">
                <x-primary-button>Update Input</x-primary-button>
                <a href="{{ route('farmer.inputs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
