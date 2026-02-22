<div class="space-y-6">
    {{-- Personal Information --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" value="First Name *" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $employee?->first_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
            <div>
                <x-input-label for="last_name" value="Last Name *" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $employee?->last_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
            <div>
                <x-input-label for="national_id" value="National ID" />
                <x-text-input id="national_id" name="national_id" type="text" class="mt-1 block w-full" :value="old('national_id', $employee?->national_id)" />
                <x-input-error class="mt-2" :messages="$errors->get('national_id')" />
            </div>
            <div>
                <x-input-label for="phone_number" value="Phone Number" />
                <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number', $employee?->phone_number)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $employee?->email)" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="gender" value="Gender" />
                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Select gender</option>
                    <option value="male" @selected(old('gender', $employee?->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $employee?->gender) === 'female')>Female</option>
                    <option value="other" @selected(old('gender', $employee?->gender) === 'other')>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
            <div>
                <x-input-label for="date_of_birth" value="Date of Birth" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $employee?->date_of_birth?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>
            <div class="md:col-span-2">
                <x-input-label for="photo" value="Photo" />
                <div class="mt-2 flex items-center gap-6">
                    <div class="shrink-0">
                        @if ($employee?->photo)
                            <img id="photo_preview" class="h-20 w-20 object-cover rounded-full border-2 border-gray-200" src="{{ Storage::url($employee->photo) }}" alt="Employee photo" />
                        @else
                            <div id="photo_placeholder" class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <img id="photo_preview" class="h-20 w-20 object-cover rounded-full border-2 border-gray-200 hidden" src="" alt="Employee photo" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-700 file:cursor-pointer"
                            onchange="previewPhoto(this)" />
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Max 2MB.</p>
                        @if ($employee?->photo)
                            <label class="mt-2 inline-flex items-center text-sm">
                                <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" />
                                <span class="ml-2 text-red-600">Remove current photo</span>
                            </label>
                        @endif
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>
        </div>
    </div>

    {{-- Employment Details --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="farm_profile_id" value="Assigned Farm" />
                <select id="farm_profile_id" name="farm_profile_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Select farm (optional)</option>
                    @foreach ($farmProfiles as $farm)
                        <option value="{{ $farm->id }}" @selected(old('farm_profile_id', $employee?->farm_profile_id) == $farm->id)>{{ $farm->farm_name }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('farm_profile_id')" />
            </div>
            <div>
                <x-input-label for="job_title" value="Job Title" />
                <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title', $employee?->job_title)" placeholder="e.g. Farm Manager, Field Worker" />
                <x-input-error class="mt-2" :messages="$errors->get('job_title')" />
            </div>
            <div>
                <x-input-label for="department" value="Department" />
                <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $employee?->department)" placeholder="e.g. Operations, Livestock" />
                <x-input-error class="mt-2" :messages="$errors->get('department')" />
            </div>
            <div>
                <x-input-label for="employment_type" value="Employment Type *" />
                <select id="employment_type" name="employment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    <option value="full_time" @selected(old('employment_type', $employee?->employment_type ?? 'full_time') === 'full_time')>Full Time</option>
                    <option value="part_time" @selected(old('employment_type', $employee?->employment_type) === 'part_time')>Part Time</option>
                    <option value="seasonal" @selected(old('employment_type', $employee?->employment_type) === 'seasonal')>Seasonal</option>
                    <option value="contract" @selected(old('employment_type', $employee?->employment_type) === 'contract')>Contract</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('employment_type')" />
            </div>
            <div>
                <x-input-label for="hire_date" value="Hire Date" />
                <x-text-input id="hire_date" name="hire_date" type="date" class="mt-1 block w-full" :value="old('hire_date', $employee?->hire_date?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('hire_date')" />
            </div>
            <div>
                <x-input-label for="end_date" value="End Date" />
                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $employee?->end_date?->format('Y-m-d'))" />
                <p class="mt-1 text-xs text-gray-500">Leave empty for ongoing employment</p>
                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
            </div>
            <div>
                <x-input-label for="salary" value="Salary" />
                <x-text-input id="salary" name="salary" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('salary', $employee?->salary)" />
                <x-input-error class="mt-2" :messages="$errors->get('salary')" />
            </div>
            <div>
                <x-input-label for="salary_period" value="Salary Period" />
                <select id="salary_period" name="salary_period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="monthly" @selected(old('salary_period', $employee?->salary_period ?? 'monthly') === 'monthly')>Monthly</option>
                    <option value="hourly" @selected(old('salary_period', $employee?->salary_period) === 'hourly')>Hourly</option>
                    <option value="daily" @selected(old('salary_period', $employee?->salary_period) === 'daily')>Daily</option>
                    <option value="weekly" @selected(old('salary_period', $employee?->salary_period) === 'weekly')>Weekly</option>
                    <option value="yearly" @selected(old('salary_period', $employee?->salary_period) === 'yearly')>Yearly</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('salary_period')" />
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="active" @selected(old('status', $employee?->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $employee?->status) === 'inactive')>Inactive</option>
                    <option value="terminated" @selected(old('status', $employee?->status) === 'terminated')>Terminated</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>
            <div class="md:col-span-2">
                <x-input-label for="skills" value="Skills" />
                <textarea id="skills" name="skills" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="e.g. Tractor operation, irrigation management, crop spraying">{{ old('skills', $employee?->skills) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            </div>
        </div>
    </div>

    {{-- Location --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Address / Location</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="country" value="Country" />
                <select id="country" name="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Select country</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>
            <div>
                <x-input-label for="district" value="District" />
                <select id="district" name="district" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select district</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('district')" />
            </div>
            <div>
                <x-input-label for="sector" value="Sector" />
                <select id="sector" name="sector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select sector</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('sector')" />
            </div>
            <div>
                <x-input-label for="cell" value="Cell" />
                <select id="cell" name="cell" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select cell</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('cell')" />
            </div>
            <div>
                <x-input-label for="village" value="Village" />
                <select id="village" name="village" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" disabled>
                    <option value="">Select village</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('village')" />
            </div>
        </div>
    </div>

    {{-- Emergency Contact --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contact</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="emergency_contact_name" value="Contact Name" />
                <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" :value="old('emergency_contact_name', $employee?->emergency_contact_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_name')" />
            </div>
            <div>
                <x-input-label for="emergency_contact_phone" value="Contact Phone" />
                <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" class="mt-1 block w-full" :value="old('emergency_contact_phone', $employee?->emergency_contact_phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_phone')" />
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div>
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Any additional notes about this employee">{{ old('notes', $employee?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>

    <div class="flex justify-end gap-3 pt-4">
        <a href="{{ route('farmer.employees.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
        <x-primary-button>{{ $employee ? 'Update Employee' : 'Add Employee' }}</x-primary-button>
    </div>
</div>

@php
    $oldCountry = old('country', $employee?->country);
    $oldDistrict = old('district', $employee?->district);
    $oldSector = old('sector', $employee?->sector);
    $oldCell = old('cell', $employee?->cell);
    $oldVillage = old('village', $employee?->village);
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

document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country');
    const districtSelect = document.getElementById('district');
    const sectorSelect = document.getElementById('sector');
    const cellSelect = document.getElementById('cell');
    const villageSelect = document.getElementById('village');

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
