@props(['supplier' => null, 'products' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" value="Supplier name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $supplier?->name)" required placeholder="e.g. AgriSupply Co." />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="contact_phone" value="Phone" />
        <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $supplier?->contact_phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
    </div>
    <div>
        <x-input-label for="contact_email" value="Email" />
        <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email', $supplier?->contact_email)" />
        <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
    </div>
    <div>
        <x-input-label for="address" value="Address" />
        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $supplier?->address)" />
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>
    @if ($products->isNotEmpty())
        <div class="md:col-span-2">
            <x-input-label value="Products this supplier supplies" />
            <p class="text-xs text-gray-500 mt-0.5 mb-2">Select the seeds/products this supplier provides (optional)</p>
            <div class="space-y-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 p-3 bg-gray-50">
                @foreach ($products as $product)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="rounded border-gray-300 text-primary focus:ring-primary"
                            {{ in_array($product->id, old('product_ids', $supplier?->products->pluck('id')->all() ?? [])) ? 'checked' : '' }} />
                        <span class="text-sm text-gray-800">{{ $product->name }}{{ $product->product_type ? ' (' . ucfirst($product->product_type) . ')' : '' }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('product_ids')" />
        </div>
    @endif
</div>
