@props(['processing' => null, 'contracts' => collect(), 'suppliers' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <x-input-label for="contract_id" value="Contract (optional)" />
        <select id="contract_id" name="contract_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— None —</option>
            @foreach ($contracts as $c)
                <option value="{{ $c->id }}" @selected(old('contract_id', $processing?->contract_id) == $c->id)>
                    {{ $c->product_name }} — {{ $c->supplier?->supplier_name ?? 'N/A' }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('contract_id')" />
    </div>

    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1 mt-2">
        <p class="text-sm font-medium text-gray-700">Raw materials</p>
        <p class="text-xs text-gray-500">Add one or more raw materials; optionally select which supplier supplied each.</p>
    </div>

    <div class="md:col-span-2" id="raw-materials-container">
        @php
            $defaultRawMaterials = [['raw_material' => '', 'quantity_input' => '', 'input_unit' => '', 'supplier_id' => '']];
            if ($processing && $processing->rawMaterials->isNotEmpty()) {
                $defaultRawMaterials = $processing->rawMaterials->map(fn ($rm) => ['raw_material' => $rm->raw_material, 'quantity_input' => $rm->quantity_input, 'input_unit' => $rm->input_unit, 'supplier_id' => $rm->supplier_id])->toArray();
            }
            $rawMaterials = old('raw_materials', $defaultRawMaterials);
            if (empty($rawMaterials)) {
                $rawMaterials = [['raw_material' => '', 'quantity_input' => '', 'input_unit' => '', 'supplier_id' => '']];
            }
        @endphp
        @foreach ($rawMaterials as $index => $rm)
            @php
                $rawId = 'raw_material_' . $index;
                $qtyId = 'quantity_input_' . $index;
                $unitId = 'input_unit_' . $index;
                $suppId = 'supplier_id_' . $index;
                $rawName = 'raw_materials[' . $index . '][raw_material]';
                $qtyName = 'raw_materials[' . $index . '][quantity_input]';
                $unitName = 'raw_materials[' . $index . '][input_unit]';
                $suppName = 'raw_materials[' . $index . '][supplier_id]';
            @endphp
            <div class="raw-material-row flex flex-wrap gap-3 items-end mb-3 p-3 bg-gray-50 rounded-md" data-index="{{ $index }}">
                <div class="flex-1 min-w-[120px]">
                    <x-input-label :for="$rawId" value="Raw material *" />
                    <x-text-input :id="$rawId" :name="$rawName" type="text" class="mt-1 block w-full" :value="data_get($rm, 'raw_material', '')" />
                </div>
                <div class="w-24">
                    <x-input-label :for="$qtyId" value="Qty *" />
                    <x-text-input :id="$qtyId" :name="$qtyName" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="data_get($rm, 'quantity_input', '')" />
                </div>
                <div class="w-24">
                    <x-input-label :for="$unitId" value="Unit *" />
                    <x-text-input :id="$unitId" :name="$unitName" type="text" class="mt-1 block w-full" :value="data_get($rm, 'input_unit', '')" placeholder="kg" />
                </div>
                <div class="flex-1 min-w-[140px]">
                    <x-input-label :for="$suppId" value="Supplier" />
                    <select :id="$suppId" :name="$suppName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">— None —</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(data_get($rm, 'supplier_id') == $s->id)>{{ $s->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button" class="remove-raw-material px-3 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 text-sm" title="Remove">Remove</button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="md:col-span-2">
        <button type="button" id="add-raw-material" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">+ Add raw material</button>
    </div>

    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1 mt-2">
        <p class="text-sm font-medium text-gray-700">Output &amp; costs</p>
    </div>
    <div>
        <x-input-label for="processing_date" value="Processing Date *" />
        <x-text-input id="processing_date" name="processing_date" type="date" class="mt-1 block w-full" :value="old('processing_date', $processing?->processing_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('processing_date')" />
    </div>
    <div>
        <x-input-label for="quantity_output" value="Quantity Output *" />
        <x-text-input id="quantity_output" name="quantity_output" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_output', $processing?->quantity_output)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_output')" />
    </div>
    <div>
        <x-input-label for="output_unit" value="Output Unit *" />
        <x-text-input id="output_unit" name="output_unit" type="text" class="mt-1 block w-full" :value="old('output_unit', $processing?->output_unit)" placeholder="e.g. kg, bags" required />
        <x-input-error class="mt-2" :messages="$errors->get('output_unit')" />
    </div>
    <div>
        <x-input-label for="processing_cost" value="Processing Cost" />
        <x-text-input id="processing_cost" name="processing_cost" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('processing_cost', $processing?->processing_cost)" />
        <x-input-error class="mt-2" :messages="$errors->get('processing_cost')" />
    </div>
    <div>
        <x-input-label for="wastage_quantity" value="Wastage Quantity" />
        <x-text-input id="wastage_quantity" name="wastage_quantity" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('wastage_quantity', $processing?->wastage_quantity)" />
        <x-input-error class="mt-2" :messages="$errors->get('wastage_quantity')" />
    </div>
</div>

<template id="raw-material-template">
    <div class="raw-material-row flex flex-wrap gap-3 items-end mb-3 p-3 bg-gray-50 rounded-md" data-index="{{ 'INDEX' }}">
        <div class="flex-1 min-w-[120px]">
            <label class="block text-sm font-medium text-gray-700">Raw material *</label>
            <input type="text" name="raw_materials[INDEX][raw_material]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" />
        </div>
        <div class="w-24">
            <label class="block text-sm font-medium text-gray-700">Qty *</label>
            <input type="number" step="0.01" min="0" name="raw_materials[INDEX][quantity_input]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" />
        </div>
        <div class="w-24">
            <label class="block text-sm font-medium text-gray-700">Unit *</label>
            <input type="text" name="raw_materials[INDEX][input_unit]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="kg" />
        </div>
        <div class="flex-1 min-w-[140px]">
            <label class="block text-sm font-medium text-gray-700">Supplier</label>
            <select name="raw_materials[INDEX][supplier_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="">— None —</option>
                @foreach ($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->supplier_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="button" class="remove-raw-material px-3 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 text-sm">Remove</button>
        </div>
    </div>
</template>

<script>
(function () {
    var container = document.getElementById('raw-materials-container');
    var template = document.getElementById('raw-material-template');
    var addBtn = document.getElementById('add-raw-material');
    if (!container || !template || !addBtn) return;

    var nextIndex = container.querySelectorAll('.raw-material-row').length;

    addBtn.addEventListener('click', function () {
        var html = template.innerHTML.replace(/\bINDEX\b/g, nextIndex);
        container.insertAdjacentHTML('beforeend', html);
        nextIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-raw-material')) {
            var row = e.target.closest('.raw-material-row');
            var rows = container.querySelectorAll('.raw-material-row');
            if (rows.length > 1) row.remove();
        }
    });
})();
</script>
