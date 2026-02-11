@props(['employee' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1">
        <p class="text-sm font-medium text-gray-700">Employee details</p>
    </div>
    <div class="md:col-span-2">
        <x-input-label for="name" value="Name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $employee?->name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="role" value="Role / Position" />
        <x-text-input id="role" name="role" type="text" class="mt-1 block w-full" :value="old('role', $employee?->role)" placeholder="e.g. Warehouse staff, Driver" />
        <x-input-error class="mt-2" :messages="$errors->get('role')" />
    </div>
    <div>
        <x-input-label for="employment_type" value="Employment type" />
        <select id="employment_type" name="employment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">Select type</option>
            @foreach (\App\Models\AgribusinessEmployee::EMPLOYMENT_TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('employment_type', $employee?->employment_type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('employment_type')" />
    </div>
    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $employee?->phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $employee?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div>
        <x-input-label for="hire_date" value="Hire date" />
        <x-text-input id="hire_date" name="hire_date" type="date" class="mt-1 block w-full" :value="old('hire_date', $employee?->hire_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('hire_date')" />
    </div>
    <div>
        <x-input-label for="id_number" value="ID / National ID" />
        <x-text-input id="id_number" name="id_number" type="text" class="mt-1 block w-full" :value="old('id_number', $employee?->id_number)" />
        <x-input-error class="mt-2" :messages="$errors->get('id_number')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="address" value="Address" />
        <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('address', $employee?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes', $employee?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
