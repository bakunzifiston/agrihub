@props(['profile' => null])

<div class="space-y-6">
    {{-- Personal Info --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Photo Upload --}}
            <div class="md:col-span-2">
                <x-input-label for="photo" value="Farmer Photo" />
                <div class="mt-2 flex items-center gap-6">
                    <div class="shrink-0">
                        @if ($profile?->photo)
                            <img id="photo_preview" class="h-24 w-24 object-cover rounded-full border-2 border-gray-200" src="{{ Storage::url($profile->photo) }}" alt="Farmer photo" />
                        @else
                            <div id="photo_placeholder" class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <img id="photo_preview" class="h-24 w-24 object-cover rounded-full border-2 border-gray-200 hidden" src="" alt="Farmer photo" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-700 file:cursor-pointer"
                            onchange="previewPhoto(this)" />
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Max 2MB. Recommended: square image.</p>
                        @if ($profile?->photo)
                            <label class="mt-2 inline-flex items-center text-sm">
                                <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" />
                                <span class="ml-2 text-red-600">Remove current photo</span>
                            </label>
                        @endif
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>

            <div>
                <x-input-label for="first_name" value="First Name *" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $profile?->first_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div>
                <x-input-label for="last_name" value="Last Name *" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $profile?->last_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
            <div>
                <x-input-label for="national_id" value="National ID" />
                <x-text-input id="national_id" name="national_id" type="text" class="mt-1 block w-full" :value="old('national_id', $profile?->national_id)" />
                <x-input-error class="mt-2" :messages="$errors->get('national_id')" />
            </div>
            <div>
                <x-input-label for="phone_number" value="Phone Number" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $profile?->phone_number)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $profile?->email)" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="gender" value="Gender" />
                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Select</option>
                    <option value="male" @selected(old('gender', $profile?->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $profile?->gender) === 'female')>Female</option>
                    <option value="other" @selected(old('gender', $profile?->gender) === 'other')>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
            <div>
                <x-input-label for="date_of_birth" value="Date of Birth" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>
        </div>
    </div>

    {{-- Farm Info --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Farm Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="farm_name" value="Farm Name *" />
                <x-text-input id="farm_name" name="farm_name" type="text" class="mt-1 block w-full" :value="old('farm_name', $profile?->farm_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('farm_name')" />
            </div>
            <div>
                <x-input-label for="farm_type" value="Farm Type *" />
                <select id="farm_type" name="farm_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select</option>
                    <option value="crop" @selected(old('farm_type', $profile?->farm_type) === 'crop')>Crop</option>
                    <option value="livestock" @selected(old('farm_type', $profile?->farm_type) === 'livestock')>Livestock</option>
                    <option value="mixed" @selected(old('farm_type', $profile?->farm_type) === 'mixed')>Mixed</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('farm_type')" />
            </div>
            <div>
                <x-input-label for="total_land_size" value="Total Land Size" />
                <x-text-input id="total_land_size" name="total_land_size" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('total_land_size', $profile?->total_land_size)" />
                <x-input-error class="mt-2" :messages="$errors->get('total_land_size')" />
            </div>
            <div>
                <x-input-label for="land_unit" value="Land Unit" />
                <select id="land_unit" name="land_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Select</option>
                    <option value="hectares" @selected(old('land_unit', $profile?->land_unit) === 'hectares')>Hectares</option>
                    <option value="acres" @selected(old('land_unit', $profile?->land_unit) === 'acres')>Acres</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('land_unit')" />
            </div>
            @php
                $initialPlotCount = (int) old('plot_count', $profile?->plot_count ?? 0);
                $initialPlotCount = max(0, $initialPlotCount);
                $existingNames = array_values(old('plot_names', $profile?->plots->pluck('name')->values()->all() ?? []));
                $initialPlotNames = [];
                for ($i = 0; $i < $initialPlotCount; $i++) {
                    $initialPlotNames[] = $existingNames[$i] ?? '';
                }
            @endphp
            <div class="md:col-span-2" x-data="{
                plotCount: {{ $initialPlotCount }},
                plotNames: {{ json_encode($initialPlotNames) }},
                syncPlotNamesToCount() {
                    let n = Math.max(0, parseInt(this.plotCount) || 0);
                    while (this.plotNames.length < n) this.plotNames.push('');
                    while (this.plotNames.length > n) this.plotNames.pop();
                    this.plotCount = n;
                }
            }" x-init="syncPlotNamesToCount()">
                <div>
                    <x-input-label for="plot_count" value="Number of plots" />
                    <input type="number" id="plot_count" min="0" step="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                        x-model.number="plotCount" @input="syncPlotNamesToCount()" placeholder="0" />
                    <input type="hidden" name="plot_count" :value="plotCount" />
                    <x-input-error class="mt-2" :messages="$errors->get('plot_count')" />
                </div>
                <div class="mt-4" x-show="plotCount > 0">
                    <x-input-label value="Plot names" />
                    <p class="text-sm text-gray-500 mt-0.5 mb-2">Enter a name for each plot (e.g. Plot 1, Plot 2, North field)</p>
                    <template x-for="(name, index) in plotNames" :key="index">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="plot_names[]" :value="name" @input="plotNames[index] = $event.target.value"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm"
                                :placeholder="'Plot ' + (index + 1)" />
                        </div>
                    </template>
                    <x-input-error class="mt-2" :messages="$errors->get('plot_names')" />
                </div>
            </div>
            <div>
                <x-input-label for="registration_date" value="Registration Date" />
                <x-text-input id="registration_date" name="registration_date" type="date" class="mt-1 block w-full" :value="old('registration_date', $profile?->registration_date?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('registration_date')" />
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="active" @selected(old('status', $profile?->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $profile?->status) === 'inactive')>Inactive</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>
        </div>
        @php
            $agriculturalInputs = config('agricultural-inputs');
            $selectedInputs = old('inputs_availability', $profile?->inputs_availability ?? []);
            $customInputs = old('custom_inputs', $profile?->custom_inputs ?? []);
        @endphp
        <div class="mt-4">
            <x-input-label value="Inputs Availability" />
            <p class="text-sm text-gray-500 mb-3">Select the agricultural inputs available on your farm. Click a category to expand and select specific items.</p>

            <div class="space-y-2" x-data="{ openCategories: [] }">
                @foreach ($agriculturalInputs as $categoryKey => $category)
                    @php
                        $categorySelectedCount = collect($category['items'])->keys()->filter(fn($key) => in_array($categoryKey . ':' . $key, $selectedInputs))->count();
                    @endphp
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button type="button"
                            class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left"
                            @click="openCategories.includes('{{ $categoryKey }}') ? openCategories = openCategories.filter(c => c !== '{{ $categoryKey }}') : openCategories.push('{{ $categoryKey }}')"
                        >
                            <span class="font-medium text-gray-900">
                                {{ $category['label'] }}
                                @if ($categorySelectedCount > 0)
                                    <span class="ml-2 px-2 py-0.5 text-xs bg-primary text-white rounded-full">{{ $categorySelectedCount }}</span>
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openCategories.includes('{{ $categoryKey }}') }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openCategories.includes('{{ $categoryKey }}')" x-cloak class="px-4 py-3 bg-white border-t border-gray-200">
                            <div class="flex gap-3 mb-3">
                                <button type="button" class="text-xs text-primary hover:underline" onclick="toggleCategoryInputs('{{ $categoryKey }}', true)">Select All</button>
                                <button type="button" class="text-xs text-gray-500 hover:underline" onclick="toggleCategoryInputs('{{ $categoryKey }}', false)">Deselect All</button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                @foreach ($category['items'] as $itemKey => $itemLabel)
                                    @php $inputValue = $categoryKey . ':' . $itemKey; @endphp
                                    <label class="inline-flex items-center text-sm">
                                        <input type="checkbox" name="inputs_availability[]" value="{{ $inputValue }}"
                                            class="rounded border-gray-300 text-primary focus:ring-primary input-category-{{ $categoryKey }}"
                                            {{ in_array($inputValue, $selectedInputs) ? 'checked' : '' }} />
                                        <span class="ml-2 text-gray-700">{{ $itemLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Custom Inputs Section --}}
                <div class="border border-dashed border-gray-300 rounded-lg overflow-hidden" x-data="{
                    open: {{ count($customInputs) > 0 ? 'true' : 'false' }},
                    customInputs: {{ json_encode(array_values($customInputs)) }},
                    newCategory: '',
                    newItem: '',
                    addCustomInput() {
                        if (this.newCategory.trim() && this.newItem.trim()) {
                            this.customInputs.push({
                                category: this.newCategory.trim(),
                                item: this.newItem.trim()
                            });
                            this.newCategory = '';
                            this.newItem = '';
                        }
                    },
                    removeCustomInput(index) {
                        this.customInputs.splice(index, 1);
                    }
                }">
                    <button type="button"
                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left"
                        @click="open = !open"
                    >
                        <span class="font-medium text-gray-900">
                            <svg class="w-4 h-4 inline-block mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Other / Custom Inputs
                            <template x-if="customInputs.length > 0">
                                <span class="ml-2 px-2 py-0.5 text-xs bg-gray-500 text-white rounded-full" x-text="customInputs.length"></span>
                            </template>
                        </span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="px-4 py-3 bg-white border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-3">Add custom input categories and items that aren't listed above.</p>

                        {{-- Existing custom inputs --}}
                        <template x-for="(input, index) in customInputs" :key="index">
                            <div class="flex items-center gap-2 mb-2 p-2 bg-gray-50 rounded-md">
                                <input type="hidden" :name="'custom_inputs[' + index + '][category]'" :value="input.category" />
                                <input type="hidden" :name="'custom_inputs[' + index + '][item]'" :value="input.item" />
                                <span class="flex-1 text-sm">
                                    <span class="font-medium text-gray-700" x-text="input.category"></span>:
                                    <span class="text-gray-600" x-text="input.item"></span>
                                </span>
                                <button type="button" @click="removeCustomInput(index)" class="text-red-500 hover:text-red-700 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>

                        {{-- Add new custom input --}}
                        <div class="mt-3 p-3 bg-gray-50 rounded-md">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Category name</label>
                                    <input type="text" x-model="newCategory" placeholder="e.g. Organic Fertilizers"
                                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" />
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Input item name</label>
                                    <input type="text" x-model="newItem" placeholder="e.g. Compost Manure"
                                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                        @keydown.enter.prevent="addCustomInput()" />
                                </div>
                            </div>
                            <button type="button" @click="addCustomInput()"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Custom Input
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('inputs_availability')" />
            <x-input-error class="mt-2" :messages="$errors->get('custom_inputs')" />
        </div>
    </div>

    {{-- Location --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="location_country" value="Country *" />
                <select id="location_country" name="location_country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="">Select country</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_country')" />
            </div>
            <div>
                <x-input-label for="location_district" value="District *" />
                <select id="location_district" name="location_district" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required disabled>
                    <option value="">Select district</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_district')" />
            </div>
            <div>
                <x-input-label for="location_sector" value="Sector" />
                <select id="location_sector" name="location_sector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select sector</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_sector')" />
            </div>
            <div>
                <x-input-label for="location_cell" value="Cell" />
                <select id="location_cell" name="location_cell" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select cell</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_cell')" />
            </div>
            <div>
                <x-input-label for="location_village" value="Village" />
                <select id="location_village" name="location_village" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select village</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_village')" />
            </div>
            <div>
                <x-input-label for="gps_latitude" value="GPS Latitude" />
                <x-text-input id="gps_latitude" name="gps_latitude" type="number" step="any" class="mt-1 block w-full" :value="old('gps_latitude', $profile?->gps_latitude)" />
                <x-input-error class="mt-2" :messages="$errors->get('gps_latitude')" />
            </div>
            <div>
                <x-input-label for="gps_longitude" value="GPS Longitude" />
                <x-text-input id="gps_longitude" name="gps_longitude" type="number" step="any" class="mt-1 block w-full" :value="old('gps_longitude', $profile?->gps_longitude)" />
                <x-input-error class="mt-2" :messages="$errors->get('gps_longitude')" />
            </div>
        </div>
    </div>
</div>

@php
    $oldCountry = old('location_country', $profile?->location_country);
    $oldDistrict = old('location_district', $profile?->location_district);
    $oldSector = old('location_sector', $profile?->location_sector);
    $oldCell = old('location_cell', $profile?->location_cell);
    $oldVillage = old('location_village', $profile?->location_village);
@endphp

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo_preview');
    const placeholder = document.getElementById('photo_placeholder');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleCategoryInputs(categoryKey, checked) {
    document.querySelectorAll('.input-category-' + categoryKey).forEach(function(cb) {
        cb.checked = checked;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('location_country');
    const districtSelect = document.getElementById('location_district');
    const sectorSelect = document.getElementById('location_sector');
    const cellSelect = document.getElementById('location_cell');
    const villageSelect = document.getElementById('location_village');

    const apiBase = '{{ url('/api/locations') }}';

    const oldCountry = @json($oldCountry);
    const oldDistrict = @json($oldDistrict);
    const oldSector = @json($oldSector);
    const oldCell = @json($oldCell);
    const oldVillage = @json($oldVillage);

    function resetSelect(select, placeholder, disable = true) {
        select.innerHTML = '<option value="">' + placeholder + '</option>';
        select.disabled = disable;
    }

    function populateSelect(select, data, placeholder, selectedValue = null) {
        select.innerHTML = '<option value="">' + placeholder + '</option>';
        data.forEach(function(item) {
            const opt = document.createElement('option');
            opt.value = item.name;
            opt.textContent = item.name;
            opt.dataset.id = item.id;
            if (item.name === selectedValue) opt.selected = true;
            select.appendChild(opt);
        });
        select.disabled = data.length === 0;
    }

    function getSelectedId(select) {
        const selectedOpt = select.options[select.selectedIndex];
        return selectedOpt ? selectedOpt.dataset.id : null;
    }

    // Load countries
    fetch(apiBase + '/countries')
        .then(r => r.json())
        .then(data => {
            populateSelect(countrySelect, data, 'Select country', oldCountry);
            if (oldCountry && countrySelect.value) {
                countrySelect.dispatchEvent(new Event('change'));
            }
        });

    countrySelect.addEventListener('change', function() {
        resetSelect(districtSelect, 'Select district');
        resetSelect(sectorSelect, 'Select sector');
        resetSelect(cellSelect, 'Select cell');
        resetSelect(villageSelect, 'Select village');

        const id = getSelectedId(this);
        if (!id) return;

        fetch(apiBase + '/districts?country_id=' + id)
            .then(r => r.json())
            .then(data => {
                populateSelect(districtSelect, data, 'Select district', oldDistrict);
                if (oldDistrict && districtSelect.value) {
                    districtSelect.dispatchEvent(new Event('change'));
                }
            });
    });

    districtSelect.addEventListener('change', function() {
        resetSelect(sectorSelect, 'Select sector');
        resetSelect(cellSelect, 'Select cell');
        resetSelect(villageSelect, 'Select village');

        const id = getSelectedId(this);
        if (!id) return;

        fetch(apiBase + '/sectors?district_id=' + id)
            .then(r => r.json())
            .then(data => {
                populateSelect(sectorSelect, data, 'Select sector', oldSector);
                if (oldSector && sectorSelect.value) {
                    sectorSelect.dispatchEvent(new Event('change'));
                }
            });
    });

    sectorSelect.addEventListener('change', function() {
        resetSelect(cellSelect, 'Select cell');
        resetSelect(villageSelect, 'Select village');

        const id = getSelectedId(this);
        if (!id) return;

        fetch(apiBase + '/cells?sector_id=' + id)
            .then(r => r.json())
            .then(data => {
                populateSelect(cellSelect, data, 'Select cell', oldCell);
                if (oldCell && cellSelect.value) {
                    cellSelect.dispatchEvent(new Event('change'));
                }
            });
    });

    cellSelect.addEventListener('change', function() {
        resetSelect(villageSelect, 'Select village');

        const id = getSelectedId(this);
        if (!id) return;

        fetch(apiBase + '/villages?cell_id=' + id)
            .then(r => r.json())
            .then(data => {
                populateSelect(villageSelect, data, 'Select village', oldVillage);
            });
    });
});
</script>
@endpush
