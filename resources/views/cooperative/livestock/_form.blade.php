@props(['livestock' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="livestock_type" value="Livestock type *" />
        <input id="livestock_type" name="livestock_type" type="text" list="livestock-types" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" value="{{ old('livestock_type', $livestock?->livestock_type) }}" required placeholder="e.g. cattle, poultry, goat" />
        <datalist id="livestock-types">
            <option value="cattle">
            <option value="poultry">
            <option value="goat">
            <option value="sheep">
            <option value="pig">
            <option value="rabbit">
            <option value="bee">
        </datalist>
        <x-input-error class="mt-2" :messages="$errors->get('livestock_type')" />
    </div>
    <div>
        <x-input-label for="breed" value="Breed" />
        <x-text-input id="breed" name="breed" type="text" class="mt-1 block w-full" :value="old('breed', $livestock?->breed)" />
        <x-input-error class="mt-2" :messages="$errors->get('breed')" />
    </div>
    <div>
        <x-input-label for="quantity" value="Quantity *" />
        <x-text-input id="quantity" name="quantity" type="number" min="0" class="mt-1 block w-full" :value="old('quantity', $livestock?->quantity ?? 0)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
    </div>
    <div>
        <x-input-label for="purpose" value="Purpose" />
        <select id="purpose" name="purpose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">Select</option>
            <option value="milk" @selected(old('purpose', $livestock?->purpose) === 'milk')>Milk</option>
            <option value="meat" @selected(old('purpose', $livestock?->purpose) === 'meat')>Meat</option>
            <option value="eggs" @selected(old('purpose', $livestock?->purpose) === 'eggs')>Eggs</option>
            <option value="breeding" @selected(old('purpose', $livestock?->purpose) === 'breeding')>Breeding</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('purpose')" />
    </div>
    <div>
        <x-input-label for="acquisition_date" value="Acquisition date" />
        <x-text-input id="acquisition_date" name="acquisition_date" type="date" class="mt-1 block w-full" :value="old('acquisition_date', $livestock?->acquisition_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('acquisition_date')" />
    </div>
    <div>
        <x-input-label for="health_status" value="Health status" />
        <x-text-input id="health_status" name="health_status" type="text" class="mt-1 block w-full" :value="old('health_status', $livestock?->health_status)" />
        <x-input-error class="mt-2" :messages="$errors->get('health_status')" />
    </div>
    <div>
        <x-input-label for="vaccination_status" value="Vaccination status" />
        <x-text-input id="vaccination_status" name="vaccination_status" type="text" class="mt-1 block w-full" :value="old('vaccination_status', $livestock?->vaccination_status)" />
        <x-input-error class="mt-2" :messages="$errors->get('vaccination_status')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes', $livestock?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
