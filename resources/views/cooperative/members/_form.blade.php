@props(['member' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="farmer_id" value="Farmer *" />
        <select id="farmer_id" name="farmer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">Select farmer</option>
            @foreach ($farmers as $f)
                <option value="{{ $f->id }}" @selected(old('farmer_id', $member?->farmer_id) == $f->id)>{{ $f->name }} ({{ $f->email }})</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('farmer_id')" />
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
</div>
