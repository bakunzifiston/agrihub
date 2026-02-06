@props(['input' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="input_name" value="Input Name *" />
        <x-text-input id="input_name" name="input_name" type="text" class="mt-1 block w-full" :value="old('input_name', $input?->input_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('input_name')" />
    </div>
    <div>
        <x-input-label for="input_category" value="Category *" />
        <select id="input_category" name="input_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="seed" @selected(old('input_category', $input?->input_category) === 'seed')>Seed</option>
            <option value="fertilizer" @selected(old('input_category', $input?->input_category) === 'fertilizer')>Fertilizer</option>
            <option value="feed" @selected(old('input_category', $input?->input_category) === 'feed')>Feed</option>
            <option value="medicine" @selected(old('input_category', $input?->input_category) === 'medicine')>Medicine</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('input_category')" />
    </div>
    <div>
        <x-input-label for="quantity" value="Quantity *" />
        <x-text-input id="quantity" name="quantity" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity', $input?->quantity)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $input?->unit)" placeholder="e.g. kg, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="purchase_date" value="Purchase Date" />
        <x-text-input id="purchase_date" name="purchase_date" type="date" class="mt-1 block w-full" :value="old('purchase_date', $input?->purchase_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('purchase_date')" />
    </div>
    <div>
        <x-input-label for="supplier_name" value="Supplier Name" />
        <x-text-input id="supplier_name" name="supplier_name" type="text" class="mt-1 block w-full" :value="old('supplier_name', $input?->supplier_name)" />
        <x-input-error class="mt-2" :messages="$errors->get('supplier_name')" />
    </div>
    <div>
        <x-input-label for="cost_per_unit" value="Cost Per Unit" />
        <x-text-input id="cost_per_unit" name="cost_per_unit" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('cost_per_unit', $input?->cost_per_unit)" />
        <x-input-error class="mt-2" :messages="$errors->get('cost_per_unit')" />
    </div>
    <div>
        <x-input-label for="total_cost" value="Total Cost" />
        <x-text-input id="total_cost" name="total_cost" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('total_cost', $input?->total_cost)" />
        <x-input-error class="mt-2" :messages="$errors->get('total_cost')" />
    </div>
</div>
