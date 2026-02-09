@props(['application' => null, 'farmProfiles' => collect(), 'crops' => collect(), 'inputNameOptions' => collect(), 'supplierNameOptions' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="farm_profile_id" value="Farm *" />
        <select id="farm_profile_id" name="farm_profile_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select farm —</option>
            @foreach ($farmProfiles as $fp)
                <option value="{{ $fp->id }}" @selected(old('farm_profile_id', $application?->farm_profile_id) == $fp->id)>{{ $fp->farm_name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('farm_profile_id')" />
    </div>
    <div>
        <x-input-label for="farm_profile_plot_id" value="Plot (where applied)" />
        <select id="farm_profile_plot_id" name="farm_profile_plot_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Optional —</option>
            @foreach ($farmProfiles as $fp)
                @if ($fp->plots->isNotEmpty())
                    <optgroup label="{{ $fp->farm_name }}">
                        @foreach ($fp->plots as $plot)
                            <option value="{{ $plot->id }}" data-farm="{{ $fp->id }}" @selected(old('farm_profile_plot_id', $application?->farm_profile_plot_id) == $plot->id)>{{ $plot->name }}</option>
                        @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('farm_profile_plot_id')" />
    </div>
    <div>
        <x-input-label for="crop_id" value="Crop (optional)" />
        <select id="crop_id" name="crop_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Optional —</option>
            @foreach ($crops as $c)
                <option value="{{ $c->id }}" @selected(old('crop_id', $application?->crop_id) == $c->id)>{{ $c->crop_name }}{{ $c->crop_type ? ' - ' . $c->crop_type : '' }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('crop_id')" />
    </div>
    @if ($inputNameOptions->isNotEmpty())
    <div class="md:col-span-2">
            <x-input-label for="input_name_select" value="Select from your inputs (optional)" />
            <select id="input_name_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="">— Select or type below —</option>
                @foreach ($inputNameOptions as $name)
                    <option value="{{ e($name) }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div>
        <x-input-label for="input_name" value="Input name *" />
        <x-text-input id="input_name" name="input_name" type="text" class="mt-1 block w-full" :value="old('input_name', $application?->input_name)" required placeholder="e.g. Urea, Roundup" />
        <x-input-error class="mt-2" :messages="$errors->get('input_name')" />
    </div>
    <div>
        <x-input-label for="input_type" value="Input type *" />
        <select id="input_type" name="input_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="fertilizer" @selected(old('input_type', $application?->input_type) === 'fertilizer')>Fertilizer</option>
            <option value="pesticide" @selected(old('input_type', $application?->input_type) === 'pesticide')>Pesticide</option>
            <option value="herbicide" @selected(old('input_type', $application?->input_type) === 'herbicide')>Herbicide</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('input_type')" />
    </div>
    <div>
        <x-input-label for="batch_number" value="Batch number" />
        <x-text-input id="batch_number" name="batch_number" type="text" class="mt-1 block w-full" :value="old('batch_number', $application?->batch_number)" placeholder="Manufacturer batch for traceability" />
        <x-input-error class="mt-2" :messages="$errors->get('batch_number')" />
    </div>
    @if ($supplierNameOptions->isNotEmpty())
    <div>
            <x-input-label for="supplier_select" value="Select supplier from your inputs (optional)" />
            <select id="supplier_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="">— Select or type below —</option>
                @foreach ($supplierNameOptions as $name)
                    <option value="{{ e($name) }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div>
        <x-input-label for="supplier" value="Supplier name" />
        <x-text-input id="supplier" name="supplier" type="text" class="mt-1 block w-full" :value="old('supplier', $application?->supplier)" placeholder="Type or select above" />
        <x-input-error class="mt-2" :messages="$errors->get('supplier')" />
    </div>
    <div>
        <x-input-label for="application_date" value="Application date *" />
        <x-text-input id="application_date" name="application_date" type="date" class="mt-1 block w-full" :value="old('application_date', $application?->application_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('application_date')" />
    </div>
    <div>
        <x-input-label for="quantity_used" value="Quantity used *" />
        <x-text-input id="quantity_used" name="quantity_used" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_used', $application?->quantity_used)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_used')" />
    </div>
    <div>
        <x-input-label for="unit" value="Unit *" />
        <select id="unit" name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select —</option>
            <option value="Kg" @selected(old('unit', $application?->unit) === 'Kg')>Kg</option>
            <option value="L" @selected(old('unit', $application?->unit) === 'L')>L</option>
            <option value="Bag" @selected(old('unit', $application?->unit) === 'Bag')>Bag</option>
            <option value="Bottle" @selected(old('unit', $application?->unit) === 'Bottle')>Bottle</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
    </div>
    <div>
        <x-input-label for="applied_by" value="Applied by" />
        <x-text-input id="applied_by" name="applied_by" type="text" class="mt-1 block w-full" :value="old('applied_by', $application?->applied_by)" placeholder="Farmer / agronomist / staff" />
        <x-input-error class="mt-2" :messages="$errors->get('applied_by')" />
    </div>
    <div>
        <x-input-label for="phi_days" value="Pre-harvest interval (days)" />
        <x-text-input id="phi_days" name="phi_days" type="number" min="0" class="mt-1 block w-full" :value="old('phi_days', $application?->phi_days)" placeholder="Waiting period for safety" />
        <x-input-error class="mt-2" :messages="$errors->get('phi_days')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Observations / reason">{{ old('notes', $application?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>

@if ($inputNameOptions->isNotEmpty() || $supplierNameOptions->isNotEmpty())
<script>
(function() {
    var inputNameSelect = document.getElementById('input_name_select');
    var inputName = document.getElementById('input_name');
    if (inputNameSelect && inputName) {
        inputNameSelect.addEventListener('change', function() {
            if (this.value) inputName.value = this.value;
        });
    }
    var supplierSelect = document.getElementById('supplier_select');
    var supplierInput = document.getElementById('supplier');
    if (supplierSelect && supplierInput) {
        supplierSelect.addEventListener('change', function() {
            if (this.value) supplierInput.value = this.value;
        });
    }
})();
</script>
@endif
