@props(['warehouse' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @if ($warehouse)
        <div class="md:col-span-2">
            <p class="text-sm text-gray-500">Warehouse ID: <span class="font-medium text-gray-700">{{ $warehouse->warehouse_id }}</span> (auto-generated)</p>
        </div>
    @endif
    <div class="md:col-span-2">
        <x-input-label for="name" value="Warehouse Name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $warehouse?->name)" required placeholder="e.g. Main Store, Cold Room" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="city" value="City" />
        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $warehouse?->city)" />
        <x-input-error class="mt-2" :messages="$errors->get('city')" />
    </div>
    <div>
        <x-input-label for="district" value="District" />
        <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district', $warehouse?->district)" />
        <x-input-error class="mt-2" :messages="$errors->get('district')" />
    </div>
    <div>
        <x-input-label for="sector" value="Sector" />
        <x-text-input id="sector" name="sector" type="text" class="mt-1 block w-full" :value="old('sector', $warehouse?->sector)" />
        <x-input-error class="mt-2" :messages="$errors->get('sector')" />
    </div>
    <div>
        <x-input-label for="country" value="Country" />
        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $warehouse?->country)" />
        <x-input-error class="mt-2" :messages="$errors->get('country')" />
    </div>
    <div>
        <x-input-label for="gps_latitude" value="GPS Latitude" />
        <x-text-input id="gps_latitude" name="gps_latitude" type="number" step="any" class="mt-1 block w-full" :value="old('gps_latitude', $warehouse?->gps_latitude)" placeholder="e.g. -1.9441" />
        <x-input-error class="mt-2" :messages="$errors->get('gps_latitude')" />
    </div>
    <div>
        <x-input-label for="gps_longitude" value="GPS Longitude" />
        <x-text-input id="gps_longitude" name="gps_longitude" type="number" step="any" class="mt-1 block w-full" :value="old('gps_longitude', $warehouse?->gps_longitude)" placeholder="e.g. 29.8739" />
        <x-input-error class="mt-2" :messages="$errors->get('gps_longitude')" />
    </div>
    <div>
        <x-input-label for="phone" value="Phone Number" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $warehouse?->phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $warehouse?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div>
        <x-input-label for="manager_member_id" value="Manager / In-Charge (select member)" />
        <select id="manager_member_id" name="manager_member_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Or enter name below —</option>
            @foreach ($members as $m)
                <option value="{{ $m->id }}" @selected(old('manager_member_id', $warehouse?->manager_member_id) == $m->id)>{{ $m->display_name }}{{ $m->membership_number ? ' (' . $m->membership_number . ')' : '' }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('manager_member_id')" />
    </div>
    <div>
        <x-input-label for="manager_name" value="Or enter manager name" />
        <x-text-input id="manager_name" name="manager_name" type="text" class="mt-1 block w-full" :value="old('manager_name', $warehouse?->manager_name)" placeholder="If not selecting a member" />
        <x-input-error class="mt-2" :messages="$errors->get('manager_name')" />
    </div>
    <div>
        <x-input-label for="location" value="Location (e.g. building/site)" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $warehouse?->location)" placeholder="e.g. Building A, Site 2" />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('description', $warehouse?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
