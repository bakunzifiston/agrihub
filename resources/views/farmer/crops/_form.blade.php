@props(['crop' => null, 'plots' => collect()])

@php
    $cropTypes = config('crop-types');
    $oldCropType = old('crop_type', $crop?->crop_type);
    $oldCropName = old('crop_name', $crop?->crop_name);
@endphp

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

    <div>
        <x-input-label for="crop_type" value="Crop Type *" />
        <select id="crop_type_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select crop type —</option>
            @foreach ($cropTypes as $typeKey => $typeData)
                <option value="{{ $typeKey }}" @selected($oldCropType === $typeKey)>{{ $typeData['label'] }}</option>
            @endforeach
        </select>
        <input type="hidden" id="crop_type" name="crop_type" value="{{ $oldCropType }}" />
        <div id="crop_type_custom_wrapper" class="mt-2 hidden">
            <x-text-input id="crop_type_custom" type="text" class="block w-full" placeholder="Enter custom crop type" :value="old('crop_type_custom', $oldCropType === 'other' ? '' : '')" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('crop_type')" />
    </div>

    <div>
        <x-input-label for="crop_name" value="Crop Name *" />
        <select id="crop_name_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary disabled:bg-gray-100 disabled:cursor-not-allowed" disabled required>
            <option value="">— Select crop type first —</option>
        </select>
        <input type="hidden" id="crop_name" name="crop_name" value="{{ $oldCropName }}" />
        <div id="crop_name_custom_wrapper" class="mt-2 hidden">
            <x-text-input id="crop_name_custom" type="text" class="block w-full" placeholder="Enter custom crop name" :value="old('crop_name_custom', '')" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('crop_name')" />
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

@push('scripts')
<script>
(function() {
    var cropTypesData = @json($cropTypes);
    var initialCropType = @json($oldCropType);
    var initialCropName = @json($oldCropName);

    var cropTypeSelect = document.getElementById('crop_type_select');
    var cropTypeHidden = document.getElementById('crop_type');
    var cropTypeCustomWrapper = document.getElementById('crop_type_custom_wrapper');
    var cropTypeCustomInput = document.getElementById('crop_type_custom');

    var cropNameSelect = document.getElementById('crop_name_select');
    var cropNameHidden = document.getElementById('crop_name');
    var cropNameCustomWrapper = document.getElementById('crop_name_custom_wrapper');
    var cropNameCustomInput = document.getElementById('crop_name_custom');

    function isOtherType(typeKey) {
        return typeKey === 'other' || (cropTypesData[typeKey] && cropTypesData[typeKey].allow_custom);
    }

    function populateCropNames(selectedType, preselectedName) {
        if (!cropNameSelect) return;

        cropNameSelect.innerHTML = '';

        if (!selectedType) {
            cropNameSelect.innerHTML = '<option value="">— Select crop type first —</option>';
            cropNameSelect.disabled = true;
            cropNameCustomWrapper.classList.add('hidden');
            cropNameHidden.value = '';
            return;
        }

        if (isOtherType(selectedType)) {
            cropNameSelect.innerHTML = '<option value="">— Enter manually below —</option>';
            cropNameSelect.disabled = true;
            cropNameCustomWrapper.classList.remove('hidden');
            cropNameCustomInput.required = true;
            if (preselectedName) {
                cropNameCustomInput.value = preselectedName;
                cropNameHidden.value = preselectedName;
            }
            return;
        }

        if (!cropTypesData[selectedType]) {
            cropNameSelect.innerHTML = '<option value="">— Select crop type first —</option>';
            cropNameSelect.disabled = true;
            cropNameCustomWrapper.classList.add('hidden');
            return;
        }

        var crops = cropTypesData[selectedType].crops;

        cropNameSelect.innerHTML = '<option value="">— Select crop name —</option>';

        for (var key in crops) {
            if (crops.hasOwnProperty(key)) {
                var option = document.createElement('option');
                option.value = crops[key];
                option.textContent = crops[key];
                if (preselectedName && crops[key] === preselectedName) {
                    option.selected = true;
                }
                cropNameSelect.appendChild(option);
            }
        }

        var otherOption = document.createElement('option');
        otherOption.value = '__other__';
        otherOption.textContent = '— Other (specify manually) —';
        cropNameSelect.appendChild(otherOption);

        cropNameSelect.disabled = false;
        cropNameCustomWrapper.classList.add('hidden');
        cropNameCustomInput.required = false;

        if (preselectedName && !isInCropList(selectedType, preselectedName)) {
            cropNameSelect.value = '__other__';
            cropNameCustomWrapper.classList.remove('hidden');
            cropNameCustomInput.value = preselectedName;
            cropNameCustomInput.required = true;
            cropNameHidden.value = preselectedName;
        }
    }

    function isInCropList(typeKey, cropName) {
        if (!cropTypesData[typeKey] || !cropTypesData[typeKey].crops) return false;
        var crops = cropTypesData[typeKey].crops;
        for (var key in crops) {
            if (crops[key] === cropName) return true;
        }
        return false;
    }

    function updateCropTypeHidden() {
        if (cropTypeSelect.value === 'other') {
            cropTypeCustomWrapper.classList.remove('hidden');
            cropTypeCustomInput.required = true;
            cropTypeHidden.value = cropTypeCustomInput.value || 'other';
        } else {
            cropTypeCustomWrapper.classList.add('hidden');
            cropTypeCustomInput.required = false;
            cropTypeHidden.value = cropTypeSelect.value;
        }
    }

    function updateCropNameHidden() {
        if (cropNameSelect.value === '__other__' || isOtherType(cropTypeSelect.value)) {
            cropNameHidden.value = cropNameCustomInput.value;
        } else {
            cropNameHidden.value = cropNameSelect.value;
        }
    }

    if (cropTypeSelect) {
        cropTypeSelect.addEventListener('change', function() {
            updateCropTypeHidden();
            populateCropNames(this.value, null);
            updateCropNameHidden();
        });

        if (cropTypeCustomInput) {
            cropTypeCustomInput.addEventListener('input', function() {
                cropTypeHidden.value = this.value || 'other';
            });
        }
    }

    if (cropNameSelect) {
        cropNameSelect.addEventListener('change', function() {
            if (this.value === '__other__') {
                cropNameCustomWrapper.classList.remove('hidden');
                cropNameCustomInput.required = true;
                cropNameCustomInput.value = '';
                cropNameHidden.value = '';
            } else {
                cropNameCustomWrapper.classList.add('hidden');
                cropNameCustomInput.required = false;
                cropNameHidden.value = this.value;
            }
        });
    }

    if (cropNameCustomInput) {
        cropNameCustomInput.addEventListener('input', function() {
            cropNameHidden.value = this.value;
        });
    }

    // Initialize on page load
    if (initialCropType) {
        updateCropTypeHidden();
        populateCropNames(initialCropType, initialCropName);

        if (initialCropType === 'other' && initialCropName) {
            cropTypeCustomInput.value = initialCropName;
        }
    }
})();
</script>
@endpush
