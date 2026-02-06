@props(['processing' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="raw_material" value="Raw Material *" />
        <x-text-input id="raw_material" name="raw_material" type="text" class="mt-1 block w-full" :value="old('raw_material', $processing?->raw_material)" required />
        <x-input-error class="mt-2" :messages="$errors->get('raw_material')" />
    </div>
    <div>
        <x-input-label for="processing_date" value="Processing Date *" />
        <x-text-input id="processing_date" name="processing_date" type="date" class="mt-1 block w-full" :value="old('processing_date', $processing?->processing_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('processing_date')" />
    </div>
    <div>
        <x-input-label for="quantity_input" value="Quantity Input *" />
        <x-text-input id="quantity_input" name="quantity_input" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('quantity_input', $processing?->quantity_input)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity_input')" />
    </div>
    <div>
        <x-input-label for="input_unit" value="Input Unit *" />
        <x-text-input id="input_unit" name="input_unit" type="text" class="mt-1 block w-full" :value="old('input_unit', $processing?->input_unit)" placeholder="e.g. kg, tons" required />
        <x-input-error class="mt-2" :messages="$errors->get('input_unit')" />
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
