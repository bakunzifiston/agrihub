<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Customer</h2>
            <a href="{{ route('agribusiness.customers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Customers</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('agribusiness.customers.update', $customer) }}">
            @csrf
            @method('PATCH')
            @include('agribusiness.customers._form', ['customer' => $customer])
            <div class="mt-6 flex gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Update Customer</button>
                <a href="{{ route('agribusiness.customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
