@props(['member' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2 border-b border-gray-200 pb-3 mb-1">
        <p class="text-sm font-medium text-gray-700">Enter member details (name is required)</p>
    </div>
    <div class="md:col-span-2">
        <x-input-label for="full_name" value="Full name *" />
        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $member?->full_name)" placeholder="Enter farmer/member name" required />
        <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
    </div>
    <div>
        <x-input-label for="national_id" value="National ID / Registration number" />
        <x-text-input id="national_id" name="national_id" type="text" class="mt-1 block w-full" :value="old('national_id', $member?->national_id)" />
        <x-input-error class="mt-2" :messages="$errors->get('national_id')" />
    </div>
    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $member?->phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $member?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="address" value="Address" />
        <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('address', $member?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>
    <div>
        <x-input-label for="membership_number" value="Membership Number" />
        <x-text-input id="membership_number" name="membership_number" type="text" class="mt-1 block w-full" :value="old('membership_number', $member?->membership_number)" />
        <x-input-error class="mt-2" :messages="$errors->get('membership_number')" />
    </div>
    <div>
        <x-input-label for="join_date" value="Join Date" />
        <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full" :value="old('join_date', $member?->join_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('join_date')" />
    </div>
    <div>
        <x-input-label for="contribution_amount" value="Contribution Amount" />
        <x-text-input id="contribution_amount" name="contribution_amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('contribution_amount', $member?->contribution_amount ?? 0)" />
        <x-input-error class="mt-2" :messages="$errors->get('contribution_amount')" />
    </div>
    <div>
        <x-input-label for="role" value="Role *" />
        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="member" @selected(old('role', $member?->role ?? 'member') === 'member')>Member</option>
            <option value="leader" @selected(old('role', $member?->role) === 'leader')>Leader</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('role')" />
    </div>
    <div>
        <x-input-label for="status" value="Status *" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="active" @selected(old('status', $member?->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $member?->status) === 'inactive')>Inactive</option>
            <option value="suspended" @selected(old('status', $member?->status) === 'suspended')>Suspended</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>
    <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
        <x-input-label for="farmer_id" value="Link to existing farmer account (optional)" />
        <select id="farmer_id" name="farmer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— No link —</option>
            @foreach ($farmers as $f)
                <option value="{{ $f->id }}" @selected(old('farmer_id', $member?->farmer_id) == $f->id)>{{ $f->name }} ({{ $f->email }})</option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Only if this member has a farmer account in the system</p>
        <x-input-error class="mt-2" :messages="$errors->get('farmer_id')" />
    </div>
</div>
