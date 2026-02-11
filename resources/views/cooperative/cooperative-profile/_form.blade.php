@props(['profile' => null])

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <x-input-label for="name" value="Cooperative name *" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $profile?->name)" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="registration_number" value="Registration number" />
            <x-text-input id="registration_number" name="registration_number" type="text" class="mt-1 block w-full" :value="old('registration_number', $profile?->registration_number)" />
            <x-input-error class="mt-2" :messages="$errors->get('registration_number')" />
        </div>
        <div>
            <x-input-label for="registration_date" value="Registration date" />
            <x-text-input id="registration_date" name="registration_date" type="date" class="mt-1 block w-full" :value="old('registration_date', $profile?->registration_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('registration_date')" />
        </div>
        <div>
            <x-input-label for="phone" value="Phone" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $profile?->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $profile?->email)" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        <div class="md:col-span-2">
            <x-input-label for="address" value="Address" />
            <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('address', $profile?->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="country" value="Country" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $profile?->country)" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>
        <div>
            <x-input-label for="district" value="District" />
            <x-text-input id="district" name="district" type="text" class="mt-1 block w-full" :value="old('district', $profile?->district)" />
            <x-input-error class="mt-2" :messages="$errors->get('district')" />
        </div>
        <div>
            <x-input-label for="sector" value="Sector" />
            <x-text-input id="sector" name="sector" type="text" class="mt-1 block w-full" :value="old('sector', $profile?->sector)" />
            <x-input-error class="mt-2" :messages="$errors->get('sector')" />
        </div>
        <div>
            <x-input-label for="focus" value="Focus" />
            <select id="focus" name="focus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="">— Select —</option>
                @foreach (\App\Models\CooperativeProfile::FOCUS_OPTIONS as $value => $label)
                    <option value="{{ $value }}" @selected(old('focus', $profile?->focus) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('focus')" />
        </div>
        <div>
            <x-input-label for="status" value="Status" />
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <option value="active" @selected(old('status', $profile?->status ?? 'active') === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $profile?->status) === 'inactive')>Inactive</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>
        <div class="md:col-span-2">
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('description', $profile?->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>
    </div>
</div>
