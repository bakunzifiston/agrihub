@props(['sale' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $sale?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="buyer_type" value="Buyer Type *" />
        <select id="buyer_type" name="buyer_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="individual" @selected(old('buyer_type', $sale?->buyer_type) === 'individual')>Individual</option>
            <option value="cooperative" @selected(old('buyer_type', $sale?->buyer_type) === 'cooperative')>Cooperative</option>
            <option value="agribusiness" @selected(old('buyer_type', $sale?->buyer_type) === 'agribusiness')>Agribusiness</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('buyer_type')" />
    </div>
    <div>
        <x-input-label for="buyer_name" value="Buyer Name *" />
        <x-text-input id="buyer_name" name="buyer_name" type="text" class="mt-1 block w-full" :value="old('buyer_name', $sale?->buyer_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('buyer_name')" />
    </div>
    <div>
        <x-input-label for="quantity_sold" value="Quantity Sold *" />
        <x-text-input id="quantity_sold" name="quantity_sold" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_sold', $sale?->quantity_sold)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_sold')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $sale?->unit)" placeholder="e.g. kg, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="unit_price" value="Unit Price *" />
        <x-text-input id="unit_price" name="unit_price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('unit_price', $sale?->unit_price)" required />
        <x-input-error class="mt-2" :messages="$errors->get('unit_price')" />
    </div>
    <div>
        <x-input-label for="total_amount" value="Total Amount *" />
        <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('total_amount', $sale?->total_amount)" required />
        <x-input-error class="mt-2" :messages="$errors->get('total_amount')" />
    </div>
    <div>
        <x-input-label for="payment_method" value="Payment Method *" />
        <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="cash" @selected(old('payment_method', $sale?->payment_method) === 'cash')>Cash</option>
            <option value="mobile" @selected(old('payment_method', $sale?->payment_method) === 'mobile')>Mobile</option>
            <option value="bank" @selected(old('payment_method', $sale?->payment_method) === 'bank')>Bank</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
    </div>
    <div>
        <x-input-label for="payment_status" value="Payment Status" />
        <x-text-input id="payment_status" name="payment_status" type="text" class="mt-1 block w-full" :value="old('payment_status', $sale?->payment_status)" placeholder="e.g. paid, pending" />
        <x-input-error class="mt-2" :messages="$errors->get('payment_status')" />
    </div>
    <div>
        <x-input-label for="sale_date" value="Sale Date *" />
        <x-text-input id="sale_date" name="sale_date" type="date" class="mt-1 block w-full" :value="old('sale_date', $sale?->sale_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('sale_date')" />
    </div>
</div>
