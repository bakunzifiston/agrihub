@props(['order' => null, 'clients' => collect(), 'inventoryItems' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($order)
        <div class="md:col-span-2">
            <p class="text-sm text-gray-500">Order: <span class="font-medium text-gray-700">{{ $order->order_id }}</span></p>
        </div>
    @endif
    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1">
        <p class="text-sm font-medium text-gray-700">Customer</p>
    </div>
    @if ($clients->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="client_id" value="Select customer (optional)" />
            <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary order-client-select">
                <option value="">— Or enter customer manually —</option>
                @foreach ($clients as $c)
                    <option value="{{ $c->id }}"
                            data-name="{{ e($c->name) }}"
                            data-phone="{{ e($c->phone ?? '') }}"
                            data-email="{{ e($c->email ?? '') }}"
                            data-address="{{ e($c->address ?? '') }}"
                            @selected(old('client_id', $order?->client_id) == $c->id)>{{ $c->name }} ({{ \App\Models\CooperativeClient::TYPES[$c->client_type] ?? $c->client_type }})</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
        </div>
    @endif
    <div class="md:col-span-2">
        <x-input-label for="customer_name" value="Customer name *" />
        <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" :value="old('customer_name', $order?->customer_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('customer_name')" />
    </div>
    <div>
        <x-input-label for="customer_phone" value="Phone" />
        <x-text-input id="customer_phone" name="customer_phone" type="text" class="mt-1 block w-full" :value="old('customer_phone', $order?->customer_phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('customer_phone')" />
    </div>
    <div>
        <x-input-label for="customer_email" value="Email" />
        <x-text-input id="customer_email" name="customer_email" type="email" class="mt-1 block w-full" :value="old('customer_email', $order?->customer_email)" />
        <x-input-error class="mt-2" :messages="$errors->get('customer_email')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="customer_address" value="Address" />
        <textarea id="customer_address" name="customer_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('customer_address', $order?->customer_address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('customer_address')" />
    </div>
    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1 mt-2">
        <p class="text-sm font-medium text-gray-700">Order details</p>
    </div>
    @if ($inventoryItems->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="inventory_id" value="Product from inventory (optional)" />
            <select id="inventory_id" name="inventory_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary order-inventory-select">
                <option value="">— Or enter product manually —</option>
                @foreach ($inventoryItems as $inv)
                    <option value="{{ $inv->id }}"
                            data-product-name="{{ e($inv->product_name) }}"
                            data-unit="{{ e($inv->unit) }}"
                            data-stock="{{ $inv->quantity_in_stock }}"
                            @selected(old('inventory_id', $order?->inventory_id) == $inv->id)>{{ $inv->product_name }} — {{ number_format($inv->quantity_in_stock, 2) }} {{ $inv->unit }}{{ $inv->warehouse ? ' (' . $inv->warehouse->name . ')' : '' }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('inventory_id')" />
        </div>
        <div class="md:col-span-2 order-stock-info text-sm text-gray-600" id="order-stock-info" style="display: none;">
            <span id="order-stock-label">In stock: <strong id="order-in-stock-value">—</strong></span>
            <span class="ml-4">Remaining after order: <strong id="order-remaining-value">—</strong></span>
        </div>
        @if ($order && $order->inventory)
            <div class="md:col-span-2 text-sm text-gray-600">
                Linked to inventory: In stock <strong>{{ number_format($order->in_stock_quantity, 2) }} {{ $order->unit }}</strong>,
                remaining after this order: <strong class="{{ $order->remaining_stock < 0 ? 'text-red-600' : '' }}">{{ number_format($order->remaining_stock, 2) }} {{ $order->unit }}</strong>
            </div>
        @endif
    @endif
    <div>
        <x-input-label for="product_name" value="Product *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $order?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    <div>
        <x-input-label for="quantity" value="Quantity *" />
        <x-text-input id="quantity" name="quantity" type="number" step="0.01" min="0.01" class="mt-1 block w-full order-quantity-input" :value="old('quantity', $order?->quantity)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $order?->unit)" placeholder="e.g. kg, bags" />
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="unit_price" value="Unit price" />
        <x-text-input id="unit_price" name="unit_price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('unit_price', $order?->unit_price)" />
        <x-input-error class="mt-2" :messages="$errors->get('unit_price')" />
    </div>
    <div>
        <x-input-label for="total_amount" value="Total amount" />
        <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('total_amount', $order?->total_amount)" />
        <p class="text-xs text-gray-500 mt-1">Leave blank to auto-calculate from quantity × unit price</p>
        <x-input-error class="mt-2" :messages="$errors->get('total_amount')" />
    </div>
    <div>
        <x-input-label for="order_date" value="Order date *" />
        <x-text-input id="order_date" name="order_date" type="date" class="mt-1 block w-full" :value="old('order_date', $order?->order_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('order_date')" />
    </div>
    <div>
        <x-input-label for="delivery_date" value="Delivery date" />
        <x-text-input id="delivery_date" name="delivery_date" type="date" class="mt-1 block w-full" :value="old('delivery_date', $order?->delivery_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('delivery_date')" />
    </div>
    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="pending" @selected(old('status', $order?->status ?? 'pending') === 'pending')>Pending</option>
            <option value="confirmed" @selected(old('status', $order?->status) === 'confirmed')>Confirmed</option>
            <option value="fulfilled" @selected(old('status', $order?->status) === 'fulfilled')>Fulfilled</option>
            <option value="cancelled" @selected(old('status', $order?->status) === 'cancelled')>Cancelled</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes', $order?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
@if ($clients->isNotEmpty())
<script>
(function () {
    var sel = document.querySelector('.order-client-select');
    if (!sel) return;
    function fillFromClient() {
        var opt = sel.options[sel.selectedIndex];
        var nameEl = document.getElementById('customer_name');
        var phoneEl = document.getElementById('customer_phone');
        var emailEl = document.getElementById('customer_email');
        var addressEl = document.getElementById('customer_address');
        if (!opt || !opt.value) return;
        if (nameEl) nameEl.value = opt.getAttribute('data-name') || '';
        if (phoneEl) phoneEl.value = opt.getAttribute('data-phone') || '';
        if (emailEl) emailEl.value = opt.getAttribute('data-email') || '';
        if (addressEl) addressEl.value = opt.getAttribute('data-address') || '';
    }
    sel.addEventListener('change', fillFromClient);
    if (sel.value) fillFromClient();
})();
</script>
@endif
@if ($inventoryItems->isNotEmpty())
<script>
(function () {
    var invSel = document.querySelector('.order-inventory-select');
    var stockInfo = document.getElementById('order-stock-info');
    var inStockEl = document.getElementById('order-in-stock-value');
    var remainingEl = document.getElementById('order-remaining-value');
    var quantityInput = document.querySelector('.order-quantity-input');
    if (!invSel) return;
    function updateStockDisplay() {
        var opt = invSel.options[invSel.selectedIndex];
        if (!opt || !opt.value) {
            if (stockInfo) stockInfo.style.display = 'none';
            return;
        }
        var stock = parseFloat(opt.getAttribute('data-stock')) || 0;
        var qty = parseFloat(quantityInput && quantityInput.value ? quantityInput.value : 0) || 0;
        var unit = opt.getAttribute('data-unit') || '';
        if (inStockEl) inStockEl.textContent = stock + ' ' + unit;
        if (remainingEl) remainingEl.textContent = Math.max(0, stock - qty) + ' ' + unit;
        if (stockInfo) stockInfo.style.display = 'block';
    }
    function fillFromInventory() {
        var opt = invSel.options[invSel.selectedIndex];
        var productEl = document.getElementById('product_name');
        var unitEl = document.getElementById('unit');
        if (!opt || !opt.value) return;
        if (productEl) productEl.value = opt.getAttribute('data-product-name') || '';
        if (unitEl) unitEl.value = opt.getAttribute('data-unit') || '';
        updateStockDisplay();
    }
    invSel.addEventListener('change', fillFromInventory);
    if (quantityInput) quantityInput.addEventListener('input', updateStockDisplay);
    if (invSel.value) fillFromInventory();
})();
</script>
@endif
