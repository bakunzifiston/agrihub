@props(['registeredCrop' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="crop_name" value="Crop Name *" />
        <x-text-input id="crop_name" name="crop_name" type="text" class="mt-1 block w-full" :value="old('crop_name', $registeredCrop?->crop_name)" required placeholder="e.g. Maize, Beans" />
        <x-input-error class="mt-2" :messages="$errors->get('crop_name')" />
    </div>
    <div>
        <x-input-label for="crop_type" value="Crop Type" />
        <x-text-input id="crop_type" name="crop_type" type="text" class="mt-1 block w-full" :value="old('crop_type', $registeredCrop?->crop_type)" placeholder="e.g. Grain, Legume" />
        <x-input-error class="mt-2" :messages="$errors->get('crop_type')" />
    </div>
</div>
