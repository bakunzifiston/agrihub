@props(['contract' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="supplier_id" value="Supplier *" />
        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">Select supplier</option>
            @foreach ($suppliers as $s)
                <option value="{{ $s->id }}" @selected(old('supplier_id', $contract?->supplier_id) == $s->id)>{{ $s->supplier_name }} ({{ $s->supplier_type }})</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
    </div>
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $contract?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="contract_quantity" value="Contract Quantity *" />
        <x-text-input id="contract_quantity" name="contract_quantity" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('contract_quantity', $contract?->contract_quantity)" required />
        <x-input-error class="mt-2" :messages="$errors->get('contract_quantity')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $contract?->unit)" placeholder="e.g. kg, tons" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="price_per_unit" value="Price per Unit" />
        <x-text-input id="price_per_unit" name="price_per_unit" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_unit', $contract?->price_per_unit)" />
        <x-input-error class="mt-2" :messages="$errors->get('price_per_unit')" />
    </div>
    <div>
        <x-input-label for="start_date" value="Start Date" />
        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $contract?->start_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
    </div>
    <div>
        <x-input-label for="end_date" value="End Date" />
        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $contract?->end_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
    </div>
    <div>
        <x-input-label for="contract_status" value="Contract Status" />
        <select id="contract_status" name="contract_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">Select status</option>
            <option value="active" @selected(old('contract_status', $contract?->contract_status) === 'active')>Active</option>
            <option value="pending" @selected(old('contract_status', $contract?->contract_status) === 'pending')>Pending</option>
            <option value="expired" @selected(old('contract_status', $contract?->contract_status) === 'expired')>Expired</option>
            <option value="completed" @selected(old('contract_status', $contract?->contract_status) === 'completed')>Completed</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('contract_status')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="delivery_schedule" value="Delivery Schedule" />
        <textarea id="delivery_schedule" name="delivery_schedule" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('delivery_schedule', $contract?->delivery_schedule) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('delivery_schedule')" />
    </div>
</div>
