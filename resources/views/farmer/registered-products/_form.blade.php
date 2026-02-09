@props(['product' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" value="Product name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product?->name)" required placeholder="e.g. Urea, NPK 17-17-17, Maize seed" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="product_type" value="Product type" />
        <select id="product_type" name="product_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Optional —</option>
            <option value="seed" @selected(old('product_type', $product?->product_type) === 'seed')>Seed</option>
            <option value="fertilizer" @selected(old('product_type', $product?->product_type) === 'fertilizer')>Fertilizer</option>
            <option value="pesticide" @selected(old('product_type', $product?->product_type) === 'pesticide')>Pesticide</option>
            <option value="herbicide" @selected(old('product_type', $product?->product_type) === 'herbicide')>Herbicide</option>
            <option value="other" @selected(old('product_type', $product?->product_type) === 'other')>Other</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('product_type')" />
    </div>
</div>
