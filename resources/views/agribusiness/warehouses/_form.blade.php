@props(['warehouse' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" value="Warehouse Name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $warehouse?->name)" required placeholder="e.g. Main Store, Cold Room" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="location" value="Location" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $warehouse?->location)" placeholder="e.g. Building A, Site 2" />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $warehouse?->description)" placeholder="Optional notes" />
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
