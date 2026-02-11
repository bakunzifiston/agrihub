@props(['sale' => null, 'outputs' => collect(), 'clients' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($outputs->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="farm_output_id" value="Product from inventory (optional)" />
            <select id="farm_output_id" name="farm_output_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sale-output-select">
                <option value="">— Or enter product manually —</option>
                @foreach ($outputs as $out)
                    <option value="{{ $out->id }}"
                            data-product-name="{{ e($out->product_name) }}"
                            data-unit="{{ e($out->unit) }}"
                            data-stock="{{ $out->quantity_available }}"
                            @selected(old('farm_output_id', $sale?->farm_output_id) == $out->id)>{{ $out->product_name }} — {{ number_format($out->quantity_available, 2) }} {{ $out->unit }}{{ $out->storage_location ? ' (' . $out->storage_location . ')' : '' }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Linking to inventory will deduct the quantity sold from your Outputs.</p>
            <x-input-error class="mt-2" :messages="$errors->get('farm_output_id')" />
        </div>
        <div class="md:col-span-2 sale-stock-info text-sm text-gray-600" id="sale-stock-info" style="display: none;">
            In stock: <strong id="sale-in-stock-value">—</strong>
            <span class="ml-4">Remaining after sale: <strong id="sale-remaining-value">—</strong></span>
        </div>
        @if ($sale && $sale->output)
            <div class="md:col-span-2 text-sm text-gray-600">
                Linked to inventory: In stock <strong>{{ number_format($sale->in_stock_quantity, 2) }} {{ $sale->unit }}</strong>,
                remaining after this sale: <strong class="{{ $sale->remaining_stock < 0 ? 'text-red-600' : '' }}">{{ number_format($sale->remaining_stock, 2) }} {{ $sale->unit }}</strong>
            </div>
        @endif
    @endif
    <div>
        <x-input-label for="product_name" value="Product Name *" />
        <x-text-input id="product_name" name="product_name" type="text" class="mt-1 block w-full" :value="old('product_name', $sale?->product_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('product_name')" />
    </div>
    @if ($clients->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="client_id" value="Select client (optional)" />
            <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sale-client-select">
                <option value="">— Or enter buyer manually —</option>
                @foreach ($clients as $c)
                    <option value="{{ $c->id }}" data-name="{{ e($c->name) }}" @selected(old('client_id', $sale?->client_id) == $c->id)>{{ $c->name }} ({{ \App\Models\FarmerClient::TYPES[$c->client_type] ?? $c->client_type }})</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
        </div>
    @endif
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
        <x-text-input id="quantity_sold" name="quantity_sold" type="number" step="0.01" min="0" class="mt-1 block w-full sale-quantity-input" :value="old('quantity_sold', $sale?->quantity_sold)" required />
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
        <x-input-label for="total_amount" value="Total Amount" />
        <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" min="0" class="mt-1 block w-full sale-total-input" :value="old('total_amount', $sale?->total_amount)" placeholder="Auto-calculated" />
        <p class="text-xs text-gray-500 mt-1">Auto-calculated from quantity × unit price (you can override)</p>
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
        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Select —</option>
            <option value="paid" @selected(old('payment_status', $sale?->payment_status) === 'paid')>Paid</option>
            <option value="pending" @selected(old('payment_status', $sale?->payment_status) === 'pending')>Pending</option>
            <option value="partial" @selected(old('payment_status', $sale?->payment_status) === 'partial')>Partial</option>
            <option value="overdue" @selected(old('payment_status', $sale?->payment_status) === 'overdue')>Overdue</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('payment_status')" />
    </div>
    <div>
        <x-input-label for="sale_date" value="Sale Date *" />
        <x-text-input id="sale_date" name="sale_date" type="date" class="mt-1 block w-full" :value="old('sale_date', $sale?->sale_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('sale_date')" />
    </div>
</div>
@if ($outputs->isNotEmpty())
<script>
(function () {
    var sel = document.querySelector('.sale-output-select');
    var stockInfo = document.getElementById('sale-stock-info');
    var inStockEl = document.getElementById('sale-in-stock-value');
    var remainingEl = document.getElementById('sale-remaining-value');
    var qtyInput = document.querySelector('.sale-quantity-input');
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
    function fillFromOutput() {
        var opt = sel.options[sel.selectedIndex];
        var productEl = document.getElementById('product_name');
        var unitEl = document.getElementById('unit');
        if (!opt || !opt.value) return;
        if (productEl) productEl.value = opt.getAttribute('data-product-name') || '';
        if (unitEl) unitEl.value = opt.getAttribute('data-unit') || '';
        updateStockDisplay();
    }
    sel.addEventListener('change', fillFromOutput);
    if (qtyInput) qtyInput.addEventListener('input', updateStockDisplay);
    if (sel.value) fillFromOutput();
})();
</script>
@endif
@if ($clients->isNotEmpty())
<script>
(function () {
    var sel = document.querySelector('.sale-client-select');
    if (!sel) return;
    function fillFromClient() {
        var opt = sel.options[sel.selectedIndex];
        var nameEl = document.getElementById('buyer_name');
        if (!opt || !opt.value) return;
        if (nameEl) nameEl.value = opt.getAttribute('data-name') || '';
    }
    sel.addEventListener('change', fillFromClient);
    if (sel.value) fillFromClient();
})();
</script>
@endif
<script>
(function () {
    var qty = document.querySelector('.sale-quantity-input');
    var price = document.getElementById('unit_price');
    var total = document.getElementById('total_amount');
    function updateTotal() {
        if (!total) return;
        var q = parseFloat(qty && qty.value ? qty.value : 0) || 0;
        var p = parseFloat(price && price.value ? price.value : 0) || 0;
        total.value = (q * p).toFixed(2);
    }
    if (qty) qty.addEventListener('input', updateTotal);
    if (price) price.addEventListener('input', updateTotal);
})();
</script>
