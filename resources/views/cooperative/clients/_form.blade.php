@props(['client' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1">
        <p class="text-sm font-medium text-gray-700">Client details</p>
    </div>
    <div class="md:col-span-2">
        <x-input-label for="name" value="Name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client?->name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="client_type" value="Type *" />
        <select id="client_type" name="client_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            @foreach (\App\Models\CooperativeClient::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('client_type', $client?->client_type ?? 'individual') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('client_type')" />
    </div>
    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $client?->phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $client?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div>
        <x-input-label for="contact_person" value="Contact person" />
        <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person', $client?->contact_person)" placeholder="For shop/company" />
        <x-input-error class="mt-2" :messages="$errors->get('contact_person')" />
    </div>
    <div>
        <x-input-label for="tax_id" value="Tax ID / Registration" />
        <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1 block w-full" :value="old('tax_id', $client?->tax_id)" />
        <x-input-error class="mt-2" :messages="$errors->get('tax_id')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="address" value="Address" />
        <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('address', $client?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes', $client?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
