@props(['supplier' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="supplier_type" value="Supplier Type *" />
        <select id="supplier_type" name="supplier_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="farmer" @selected(old('supplier_type', $supplier?->supplier_type ?? 'farmer') === 'farmer')>Farmer</option>
            <option value="cooperative" @selected(old('supplier_type', $supplier?->supplier_type) === 'cooperative')>Cooperative</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('supplier_type')" />
    </div>
    <div>
        <x-input-label for="supplier_name" value="Supplier Name *" />
        <x-text-input id="supplier_name" name="supplier_name" type="text" class="mt-1 block w-full" :value="old('supplier_name', $supplier?->supplier_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('supplier_name')" />
    </div>
    <div>
        <x-input-label for="contact_person" value="Contact Person" />
        <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person', $supplier?->contact_person)" />
        <x-input-error class="mt-2" :messages="$errors->get('contact_person')" />
    </div>
    <div>
        <x-input-label for="phone_number" value="Phone Number" />
        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $supplier?->phone_number)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
    </div>
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $supplier?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div>
        <x-input-label for="location" value="Location" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $supplier?->location)" />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>
    <div>
        <x-input-label for="contract_status" value="Contract Status" />
        <x-text-input id="contract_status" name="contract_status" type="text" class="mt-1 block w-full" :value="old('contract_status', $supplier?->contract_status)" placeholder="e.g. Active, Pending" />
        <x-input-error class="mt-2" :messages="$errors->get('contract_status')" />
    </div>
    <div>
        <x-input-label for="rating" value="Rating (0-5)" />
        <x-text-input id="rating" name="rating" type="number" step="0.01" min="0" max="5" class="mt-1 block w-full" :value="old('rating', $supplier?->rating)" />
        <x-input-error class="mt-2" :messages="$errors->get('rating')" />
    </div>
</div>
