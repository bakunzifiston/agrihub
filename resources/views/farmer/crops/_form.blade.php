@props(['crop' => null, 'registeredCrops' => collect(), 'plots' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($plots->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label value="Plots" />
            <p class="text-xs text-gray-500 mt-0.5 mb-2">Select all plots where this crop is planted (optional)</p>
            <div class="space-y-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 p-3 bg-gray-50">
                @foreach ($plots as $plot)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="plot_ids[]" value="{{ $plot->id }}" class="rounded border-gray-300 text-primary focus:ring-primary"
                            {{ in_array($plot->id, old('plot_ids', $crop?->plots->pluck('id')->all() ?? [])) ? 'checked' : '' }} />
                        <span class="text-sm text-gray-800">{{ $plot->name }}{{ $plot->farmProfile ? ' — ' . $plot->farmProfile->farm_name : '' }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('plot_ids')" />
        </div>
    @endif
    @if ($registeredCrops->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label for="registered_crop_select" value="Quick select from registered crops" />
            <select id="registered_crop_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="">— Type below or select —</option>
                @foreach ($registeredCrops as $rc)
                    <option value="{{ e($rc->crop_name) }}|||{{ e($rc->crop_type ?? '') }}">{{ $rc->crop_name }}{{ $rc->crop_type ? ' - ' . $rc->crop_type : '' }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Select to fill Crop Name and Crop Type below</p>
        </div>
    @endif
    <div>
        <x-input-label for="crop_name" value="Crop Name *" />
        <x-text-input id="crop_name" name="crop_name" type="text" class="mt-1 block w-full" :value="old('crop_name', $crop?->crop_name)" required placeholder="e.g. Maize, Beans" />
        <x-input-error class="mt-2" :messages="$errors->get('crop_name')" />
    </div>
    <div>
        <x-input-label for="crop_type" value="Crop Type" />
        <x-text-input id="crop_type" name="crop_type" type="text" class="mt-1 block w-full" :value="old('crop_type', $crop?->crop_type)" placeholder="e.g. Grain, Legume" />
        <x-input-error class="mt-2" :messages="$errors->get('crop_type')" />
    </div>
    <div>
        <x-input-label for="season" value="Season" />
        <x-text-input id="season" name="season" type="text" class="mt-1 block w-full" :value="old('season', $crop?->season)" placeholder="e.g. Season A, 2024" />
        <x-input-error class="mt-2" :messages="$errors->get('season')" />
    </div>
    <div>
        <x-input-label for="crop_status" value="Crop Status" />
        <select id="crop_status" name="crop_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="planted" @selected(old('crop_status', $crop?->crop_status ?? 'planted') === 'planted')>Planted</option>
            <option value="growing" @selected(old('crop_status', $crop?->crop_status) === 'growing')>Growing</option>
            <option value="harvested" @selected(old('crop_status', $crop?->crop_status) === 'harvested')>Harvested</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('crop_status')" />
    </div>
    <div>
        <x-input-label for="planting_date" value="Planting Date" />
        <x-text-input id="planting_date" name="planting_date" type="date" class="mt-1 block w-full" :value="old('planting_date', $crop?->planting_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('planting_date')" />
    </div>
    <div>
        <x-input-label for="expected_harvest_date" value="Expected Harvest Date" />
        <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full" :value="old('expected_harvest_date', $crop?->expected_harvest_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('expected_harvest_date')" />
    </div>
    <div>
        <x-input-label for="land_area_used" value="Land Area Used" />
        <x-text-input id="land_area_used" name="land_area_used" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('land_area_used', $crop?->land_area_used)" />
        <x-input-error class="mt-2" :messages="$errors->get('land_area_used')" />
    </div>
    <div>
        <x-input-label for="area_unit" value="Area Unit" />
        <x-text-input id="area_unit" name="area_unit" type="text" class="mt-1 block w-full" :value="old('area_unit', $crop?->area_unit)" placeholder="e.g. hectares, acres" />
        <x-input-error class="mt-2" :messages="$errors->get('area_unit')" />
    </div>
    <div>
        <x-input-label for="expected_yield" value="Expected Yield" />
        <x-text-input id="expected_yield" name="expected_yield" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('expected_yield', $crop?->expected_yield)" />
        <x-input-error class="mt-2" :messages="$errors->get('expected_yield')" />
    </div>
    <div>
        <x-input-label for="yield_unit" value="Yield Unit" />
        <x-text-input id="yield_unit" name="yield_unit" type="text" class="mt-1 block w-full" :value="old('yield_unit', $crop?->yield_unit)" placeholder="e.g. kg, tons" />
        <x-input-error class="mt-2" :messages="$errors->get('yield_unit')" />
    </div>
</div>

@if ($registeredCrops->isNotEmpty())
<script>
(function() {
    function initRegisteredCropSelect() {
        var sel = document.getElementById('registered_crop_select');
        var nameInput = document.querySelector('input[name="crop_name"]');
        var typeInput = document.querySelector('input[name="crop_type"]');
        if (!sel || !nameInput || !typeInput) return;
        sel.addEventListener('change', function() {
            var v = this.value;
            if (!v) return;
            var parts = v.split('|||');
            nameInput.value = parts[0] || '';
            typeInput.value = parts[1] || '';
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRegisteredCropSelect);
    } else {
        initRegisteredCropSelect();
    }
})();
</script>
@endif
