@props(['crop' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <x-input-label for="crop_name" value="Crop name *" />
        <x-text-input id="crop_name" name="crop_name" type="text" class="mt-1 block w-full" :value="old('crop_name', $crop?->crop_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('crop_name')" />
    </div>
    <div>
        <x-input-label for="crop_type" value="Crop type" />
        <x-text-input id="crop_type" name="crop_type" type="text" class="mt-1 block w-full" :value="old('crop_type', $crop?->crop_type)" placeholder="e.g. cereal, vegetable" />
        <x-input-error class="mt-2" :messages="$errors->get('crop_type')" />
    </div>
    <div>
        <x-input-label for="season" value="Season" />
        <x-text-input id="season" name="season" type="text" class="mt-1 block w-full" :value="old('season', $crop?->season)" placeholder="e.g. 2026 A" />
        <x-input-error class="mt-2" :messages="$errors->get('season')" />
    </div>
    <div>
        <x-input-label for="planting_date" value="Planting date" />
        <x-text-input id="planting_date" name="planting_date" type="date" class="mt-1 block w-full" :value="old('planting_date', $crop?->planting_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('planting_date')" />
    </div>
    <div>
        <x-input-label for="expected_harvest_date" value="Expected harvest date" />
        <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full" :value="old('expected_harvest_date', $crop?->expected_harvest_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('expected_harvest_date')" />
    </div>
    <div>
        <x-input-label for="land_area_used" value="Land area used" />
        <x-text-input id="land_area_used" name="land_area_used" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('land_area_used', $crop?->land_area_used)" />
        <x-input-error class="mt-2" :messages="$errors->get('land_area_used')" />
    </div>
    <div>
        <x-input-label for="area_unit" value="Area unit" />
        <x-text-input id="area_unit" name="area_unit" type="text" class="mt-1 block w-full" :value="old('area_unit', $crop?->area_unit)" placeholder="e.g. hectares, acres" />
        <x-input-error class="mt-2" :messages="$errors->get('area_unit')" />
    </div>
    <div>
        <x-input-label for="expected_yield" value="Expected yield" />
        <x-text-input id="expected_yield" name="expected_yield" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('expected_yield', $crop?->expected_yield)" />
        <x-input-error class="mt-2" :messages="$errors->get('expected_yield')" />
    </div>
    <div>
        <x-input-label for="yield_unit" value="Yield unit" />
        <x-text-input id="yield_unit" name="yield_unit" type="text" class="mt-1 block w-full" :value="old('yield_unit', $crop?->yield_unit)" placeholder="e.g. kg, tons" />
        <x-input-error class="mt-2" :messages="$errors->get('yield_unit')" />
    </div>
    <div>
        <x-input-label for="crop_status" value="Status" />
        <select id="crop_status" name="crop_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="planted" @selected(old('crop_status', $crop?->crop_status ?? 'planted') === 'planted')>Planted</option>
            <option value="growing" @selected(old('crop_status', $crop?->crop_status) === 'growing')>Growing</option>
            <option value="harvested" @selected(old('crop_status', $crop?->crop_status) === 'harvested')>Harvested</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('crop_status')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes', $crop?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
