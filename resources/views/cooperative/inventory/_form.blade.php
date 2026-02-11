@props(['inventory' => null, 'warehouses' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($warehouses->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="warehouse_id" value="Warehouse *" />
            <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                <option value="">Select warehouse</option>
                @foreach ($warehouses as $wh)
                    <option value="{{ $wh->id }}" @selected(old('warehouse_id', $inventory?->warehouse_id) == $wh->id)>{{ $wh->warehouse_id ?? $wh->name }} — {{ $wh->name }}{{ $wh->city || $wh->district ? ' (' . implode(', ', array_filter([$wh->city, $wh->district])) . ')' : '' }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Inventory is stored per warehouse. Add warehouses first if the list is empty.</p>
            <x-input-error class="mt-2" :messages="$errors->get('warehouse_id')" />
        </div>
    @endif
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $inventory?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="category" value="Category" />
        <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category', $inventory?->category)" placeholder="e.g. Grains, Vegetables" />
        <x-input-error class="mt-2" :messages="$errors->get('category')" />
    </div>
    <div>
        <x-input-label for="quantity_in_stock" value="Quantity in Stock *" />
        <x-text-input id="quantity_in_stock" name="quantity_in_stock" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_in_stock', $inventory?->quantity_in_stock)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_in_stock')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $inventory?->unit)" placeholder="e.g. kg, tons, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="storage_location" value="Storage Location" />
        <x-text-input id="storage_location" name="storage_location" type="text" class="mt-1 block w-full" :value="old('storage_location', $inventory?->storage_location)" />
        <x-input-error class="mt-2" :messages="$errors->get('storage_location')" />
    </div>
</div>
