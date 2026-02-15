<x-tenant-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pre-order Listing</h2>
            <a href="{{ route('farmer.pre-order-listings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Listings</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('farmer.pre-order-listings.update', $preOrderListing) }}">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <x-input-label for="title" value="Listing title *" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $preOrderListing->title)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="quantity_available" value="Quantity available *" />
                        <x-text-input id="quantity_available" name="quantity_available" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_available', $preOrderListing->quantity_available)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('quantity_available')" />
                    </div>
                    <div>
                        <x-input-label for="unit" value="Unit *" />
                        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $preOrderListing->unit)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price_per_unit" value="Price per unit (optional)" />
                        <x-text-input id="price_per_unit" name="price_per_unit" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_unit', $preOrderListing->price_per_unit)" />
                        <x-input-error class="mt-2" :messages="$errors->get('price_per_unit')" />
                    </div>
                    <div>
                        <x-input-label for="expected_harvest_date" value="Expected harvest date" />
                        <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full" :value="old('expected_harvest_date', $preOrderListing->expected_harvest_date?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('expected_harvest_date')" />
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary focus:ring-primary" @checked(old('is_active', $preOrderListing->is_active)) />
                        <span class="text-sm text-gray-700">Listing active (visible on marketplace)</span>
                    </label>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-700 font-medium text-sm">Update Listing</button>
                <a href="{{ route('farmer.pre-order-listings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</x-tenant-layout>
