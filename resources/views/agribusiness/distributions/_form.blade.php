@props(['distribution' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="customer_name" value="Customer Name *" />
        <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" :value="old('customer_name', $distribution?->customer_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('customer_name')" />
    </div>
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $distribution?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="quantity_dispatched" value="Quantity Dispatched *" />
        <x-text-input id="quantity_dispatched" name="quantity_dispatched" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_dispatched', $distribution?->quantity_dispatched)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_dispatched')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $distribution?->unit)" placeholder="e.g. kg, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="dispatch_date" value="Dispatch Date *" />
        <x-text-input id="dispatch_date" name="dispatch_date" type="date" class="mt-1 block w-full" :value="old('dispatch_date', $distribution?->dispatch_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('dispatch_date')" />
    </div>
    <div>
        <x-input-label for="delivery_status" value="Delivery Status" />
        <select id="delivery_status" name="delivery_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">Select status</option>
            <option value="dispatched" @selected(old('delivery_status', $distribution?->delivery_status) === 'dispatched')>Dispatched</option>
            <option value="in_transit" @selected(old('delivery_status', $distribution?->delivery_status) === 'in_transit')>In Transit</option>
            <option value="delivered" @selected(old('delivery_status', $distribution?->delivery_status) === 'delivered')>Delivered</option>
            <option value="pending" @selected(old('delivery_status', $distribution?->delivery_status) === 'pending')>Pending</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('delivery_status')" />
    </div>
</div>
