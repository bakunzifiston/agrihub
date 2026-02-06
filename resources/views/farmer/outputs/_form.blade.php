@props(['output' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $output?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="quantity_available" value="Quantity Available *" />
        <x-text-input id="quantity_available" name="quantity_available" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_available', $output?->quantity_available)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_available')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $output?->unit)" placeholder="e.g. kg, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="storage_location" value="Storage Location" />
        <x-text-input id="storage_location" name="storage_location" type="text" class="mt-1 block w-full" :value="old('storage_location', $output?->storage_location)" />
        <x-input-error class="mt-2" :messages="$errors->get('storage_location')" />
    </div>
    <div>
        <x-input-label for="harvest_date" value="Harvest Date" />
        <x-text-input id="harvest_date" name="harvest_date" type="date" class="mt-1 block w-full" :value="old('harvest_date', $output?->harvest_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('harvest_date')" />
    </div>
    <div>
        <x-input-label for="expiry_date" value="Expiry Date" />
        <x-text-input id="expiry_date" name="expiry_date" type="date" class="mt-1 block w-full" :value="old('expiry_date', $output?->expiry_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('expiry_date')" />
    </div>
</div>
