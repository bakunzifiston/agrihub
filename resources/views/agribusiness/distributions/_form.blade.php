@props(['distribution' => null, 'inventoryItems' => collect(), 'customers' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($inventoryItems->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="inventory_id" value="Product from inventory (optional)" />
            <select id="inventory_id" name="inventory_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dist-inventory-select">
                <option value="">— Or enter product manually —</option>
                @foreach ($inventoryItems as $inv)
                    <option value="{{ $inv->id }}"
                            data-product-name="{{ e($inv->product_name) }}"
                            data-unit="{{ e($inv->unit) }}"
                            data-stock="{{ $inv->quantity_in_stock }}"
                            @selected(old('inventory_id', $distribution?->inventory_id) == $inv->id)>{{ $inv->product_name }} — {{ number_format($inv->quantity_in_stock, 2) }} {{ $inv->unit }}{{ $inv->warehouse ? ' (' . $inv->warehouse->name . ')' : '' }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Linking to inventory will deduct the quantity dispatched from stock.</p>
            <x-input-error class="mt-2" :messages="$errors->get('inventory_id')" />
        </div>
        <div class="md:col-span-2 dist-stock-info text-sm text-gray-600" id="dist-stock-info" style="display: none;">
            In stock: <strong id="dist-in-stock-value">—</strong>
            <span class="ml-4">Remaining after dispatch: <strong id="dist-remaining-value">—</strong></span>
        </div>
        @if ($distribution && $distribution->inventory)
            <div class="md:col-span-2 text-sm text-gray-600">
                From inventory: <strong>{{ $distribution->inventory->product_name }}</strong>
                @if ($distribution->inventory->warehouse)
                    (Warehouse: {{ $distribution->inventory->warehouse->name }})
                @endif
                — In stock: <strong>{{ number_format($distribution->in_stock_quantity, 2) }} {{ $distribution->unit }}</strong>,
                remaining after dispatch: <strong class="{{ $distribution->remaining_stock < 0 ? 'text-red-600' : '' }}">{{ number_format($distribution->remaining_stock, 2) }} {{ $distribution->unit }}</strong>
            </div>
        @endif
    @endif
    @if ($customers->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="customer_id" value="Select customer (optional)" />
            <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dist-customer-select">
                <option value="">— Or enter customer name manually —</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}"
                            data-name="{{ e($c->name) }}"
                            data-phone="{{ e($c->phone ?? '') }}"
                            data-email="{{ e($c->email ?? '') }}"
                            data-address="{{ e($c->address ?? '') }}"
                            @selected(old('customer_id', $distribution?->customer_id) == $c->id)>{{ $c->name }}{{ $c->phone ? ' — ' . $c->phone : '' }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
        </div>
        <div class="md:col-span-2 dist-customer-info text-sm text-gray-600 bg-gray-50 rounded-md p-3 border border-gray-200" id="dist-customer-info" style="display: none;">
            <p class="font-medium text-gray-800 mb-1">Customer info</p>
            <p id="dist-customer-phone"></p>
            <p id="dist-customer-email"></p>
            <p id="dist-customer-address"></p>
        </div>
    @endif
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
        <x-text-input id="quantity_dispatched" name="quantity_dispatched" type="number" step="0.01" min="0" class="mt-1 block w-full dist-qty-input" :value="old('quantity_dispatched', $distribution?->quantity_dispatched)" required />
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

@if ($customers->isNotEmpty())
<script>
(function () {
    var customerSel = document.getElementById('customer_id');
    var customerNameInput = document.getElementById('customer_name');
    var infoBlock = document.getElementById('dist-customer-info');
    var phoneEl = document.getElementById('dist-customer-phone');
    var emailEl = document.getElementById('dist-customer-email');
    var addressEl = document.getElementById('dist-customer-address');
    if (customerSel && customerNameInput) {
        function fillFromCustomer() {
            var opt = customerSel.options[customerSel.selectedIndex];
            if (!opt || !opt.value) {
                if (infoBlock) infoBlock.style.display = 'none';
                return;
            }
            var name = opt.getAttribute('data-name') || '';
            var phone = opt.getAttribute('data-phone') || '';
            var email = opt.getAttribute('data-email') || '';
            var address = opt.getAttribute('data-address') || '';
            customerNameInput.value = name;
            if (infoBlock) {
                infoBlock.style.display = (phone || email || address) ? 'block' : 'none';
                if (phoneEl) phoneEl.textContent = phone ? 'Phone: ' + phone : '';
                if (emailEl) emailEl.textContent = email ? 'Email: ' + email : '';
                if (addressEl) addressEl.textContent = address ? 'Address: ' + address : '';
            }
        }
        customerSel.addEventListener('change', fillFromCustomer);
        fillFromCustomer();
    }
})();
</script>
@endif
@if ($inventoryItems->isNotEmpty())
<script>
(function () {
    var sel = document.querySelector('.dist-inventory-select');
    var stockInfo = document.getElementById('dist-stock-info');
    var inStockEl = document.getElementById('dist-in-stock-value');
    var remainingEl = document.getElementById('dist-remaining-value');
    var qtyInput = document.querySelector('.dist-qty-input');
    if (!sel) return;
    function updateStockDisplay() {
        var opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) {
            if (stockInfo) stockInfo.style.display = 'none';
            return;
        }
        var stock = parseFloat(opt.getAttribute('data-stock')) || 0;
        var qty = parseFloat(qtyInput && qtyInput.value ? qtyInput.value : 0) || 0;
        var unit = opt.getAttribute('data-unit') || '';
        if (inStockEl) inStockEl.textContent = stock + ' ' + unit;
        if (remainingEl) remainingEl.textContent = Math.max(0, stock - qty) + ' ' + unit;
        if (stockInfo) stockInfo.style.display = 'block';
    }
    function fillFromInventory() {
        var opt = sel.options[sel.selectedIndex];
        var productEl = document.getElementById('product_name');
        var unitEl = document.getElementById('unit');
        if (!opt || !opt.value) return;
        if (productEl) productEl.value = opt.getAttribute('data-product-name') || '';
        if (unitEl) unitEl.value = opt.getAttribute('data-unit') || '';
        updateStockDisplay();
    }
    sel.addEventListener('change', fillFromInventory);
    if (qtyInput) qtyInput.addEventListener('input', updateStockDisplay);
    if (sel.value) fillFromInventory();
})();
</script>
@endif
