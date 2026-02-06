@props(['profile' => null])

<div class="space-y-6">
    {{-- Personal Info --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="full_name" value="Full Name *" />
                <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $profile?->full_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
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
    </div>

    {{-- Location --}}
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="location_country" value="Country" />
                <x-text-input id="location_country" name="location_country" type="text" class="mt-1 block w-full" :value="old('location_country', $profile?->location_country)" />
                <x-input-error class="mt-2" :messages="$errors->get('location_country')" />
            </div>
            <div>
                <x-input-label for="location_district" value="District" />
                <x-text-input id="location_district" name="location_district" type="text" class="mt-1 block w-full" :value="old('location_district', $profile?->location_district)" />
                <x-input-error class="mt-2" :messages="$errors->get('location_district')" />
            </div>
            <div>
                <x-input-label for="location_sector" value="Sector" />
                <x-text-input id="location_sector" name="location_sector" type="text" class="mt-1 block w-full" :value="old('location_sector', $profile?->location_sector)" />
                <x-input-error class="mt-2" :messages="$errors->get('location_sector')" />
            </div>
            <div>
                <x-input-label for="location_cell" value="Cell" />
                <x-text-input id="location_cell" name="location_cell" type="text" class="mt-1 block w-full" :value="old('location_cell', $profile?->location_cell)" />
                <x-input-error class="mt-2" :messages="$errors->get('location_cell')" />
            </div>
            <div>
                <x-input-label for="location_village" value="Village" />
                <x-text-input id="location_village" name="location_village" type="text" class="mt-1 block w-full" :value="old('location_village', $profile?->location_village)" />
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
