@props(['input' => null, 'farmProfiles' => collect()])

@php
    $inputCategories = config('agricultural-inputs');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <x-input-label for="farm_profile_id" value="Select Farm Profile (to load available inputs)" />
        <select id="farm_profile_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Select farm to load available inputs —</option>
            @foreach ($farmProfiles as $fp)
                <option value="{{ $fp->id }}">{{ $fp->farm_name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Select a farm to see inputs configured in its "Inputs Availability"</p>
    </div>

    <div class="md:col-span-2">
        <x-input-label for="available_input_select" value="Select from available inputs" />
        <select id="available_input_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
            <option value="">— Select a farm first, or enter manually below —</option>
        </select>
    </div>

    <div>
        <x-input-label for="input_name" value="Input Name *" />
        <x-text-input id="input_name" name="input_name" type="text" class="mt-1 block w-full" :value="old('input_name', $input?->input_name)" required placeholder="e.g. Urea 46%, DAP 18-46-0" />
        <x-input-error class="mt-2" :messages="$errors->get('input_name')" />
    </div>
    <div>
        <x-input-label for="input_category" value="Category *" />
        <select id="input_category" name="input_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select category —</option>
            @foreach ($inputCategories as $catKey => $cat)
                <option value="{{ $catKey }}" @selected(old('input_category', $input?->input_category) === $catKey)>{{ $cat['label'] }}</option>
            @endforeach
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
        <select id="unit" name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select —</option>
            <option value="kg" @selected(old('unit', $input?->unit) === 'kg')>Kg</option>
            <option value="L" @selected(old('unit', $input?->unit) === 'L')>Liters (L)</option>
            <option value="bags" @selected(old('unit', $input?->unit) === 'bags')>Bags</option>
            <option value="bottles" @selected(old('unit', $input?->unit) === 'bottles')>Bottles</option>
            <option value="units" @selected(old('unit', $input?->unit) === 'units')>Units</option>
        </select>
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

@push('scripts')
<script>
(function() {
    var farmProfileSelect = document.getElementById('farm_profile_id');
    var availableInputSelect = document.getElementById('available_input_select');
    var inputNameField = document.getElementById('input_name');
    var inputCategorySelect = document.getElementById('input_category');

    function loadAvailableInputs(farmProfileId) {
        if (!farmProfileId) {
            availableInputSelect.innerHTML = '<option value="">— Select a farm first, or enter manually below —</option>';
            availableInputSelect.disabled = true;
            return;
        }

        availableInputSelect.innerHTML = '<option value="">Loading...</option>';
        availableInputSelect.disabled = true;

        fetch('/farmer/farm-profile/' + farmProfileId + '/available-inputs')
            .then(function(response) { return response.json(); })
            .then(function(inputs) {
                if (!inputs || inputs.length === 0) {
                    availableInputSelect.innerHTML = '<option value="">— No inputs configured for this farm (configure in Farm Profile) —</option>';
                    availableInputSelect.disabled = true;
                    return;
                }

                var html = '<option value="">— Select an input —</option>';
                var currentCategory = '';
                for (var i = 0; i < inputs.length; i++) {
                    var input = inputs[i];
                    if (input.category_label !== currentCategory) {
                        if (currentCategory !== '') {
                            html += '</optgroup>';
                        }
                        currentCategory = input.category_label;
                        html += '<optgroup label="' + currentCategory + '">';
                    }
                    html += '<option value="' + input.value + '" data-category="' + input.category_key + '" data-name="' + input.item_label + '">' + input.item_label + '</option>';
                }
                if (currentCategory !== '') {
                    html += '</optgroup>';
                }
                availableInputSelect.innerHTML = html;
                availableInputSelect.disabled = false;
            })
            .catch(function() {
                availableInputSelect.innerHTML = '<option value="">— Error loading inputs —</option>';
                availableInputSelect.disabled = true;
            });
    }

    if (farmProfileSelect) {
        farmProfileSelect.addEventListener('change', function() {
            loadAvailableInputs(this.value);
        });
    }

    if (availableInputSelect) {
        availableInputSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.value) {
                var category = selectedOption.getAttribute('data-category');
                var name = selectedOption.getAttribute('data-name');
                if (category && inputCategorySelect) {
                    inputCategorySelect.value = category;
                }
                if (name && inputNameField) {
                    inputNameField.value = name;
                }
            }
        });
    }
})();
</script>
@endpush
