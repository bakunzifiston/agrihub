<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Inventory Item</h2>
            <a href="{{ route('cooperative.inventory.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Inventory</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('cooperative.inventory.update', $inventory) }}">
            @csrf
            @method('PATCH')
            @include('cooperative.inventory._form', ['inventory' => $inventory, 'warehouses' => $warehouses])
            <div class="mt-6 flex gap-4">
                <x-primary-button>Update Item</x-primary-button>
                <a href="{{ route('cooperative.inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
