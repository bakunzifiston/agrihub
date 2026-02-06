@props(['payment' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="farmer_id" value="Farmer *" />
        <select id="farmer_id" name="farmer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">Select farmer</option>
            @foreach ($farmers as $f)
                <option value="{{ $f->id }}" @selected(old('farmer_id', $payment?->farmer_id) == $f->id)>{{ $f->name }} ({{ $f->email }})</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('farmer_id')" />
    </div>
    <div>
        <x-input-label for="amount_paid" value="Amount Paid *" />
        <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('amount_paid', $payment?->amount_paid)" required />
        <x-input-error class="mt-2" :messages="$errors->get('amount_paid')" />
    </div>
    <div>
        <x-input-label for="payment_method" value="Payment Method" />
        <x-text-input id="payment_method" name="payment_method" type="text" class="mt-1 block w-full" :value="old('payment_method', $payment?->payment_method)" placeholder="e.g. Bank transfer, Cash, M-Pesa" />
        <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
    </div>
    <div>
        <x-input-label for="reference_number" value="Reference Number" />
        <x-text-input id="reference_number" name="reference_number" type="text" class="mt-1 block w-full" :value="old('reference_number', $payment?->reference_number)" />
        <x-input-error class="mt-2" :messages="$errors->get('reference_number')" />
    </div>
    <div>
        <x-input-label for="payment_date" value="Payment Date *" />
        <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', $payment?->payment_date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('payment_date')" />
    </div>
    <div>
        <x-input-label for="payment_status" value="Payment Status" />
        <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">Select status</option>
            <option value="completed" @selected(old('payment_status', $payment?->payment_status) === 'completed')>Completed</option>
            <option value="pending" @selected(old('payment_status', $payment?->payment_status) === 'pending')>Pending</option>
            <option value="failed" @selected(old('payment_status', $payment?->payment_status) === 'failed')>Failed</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('payment_status')" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="remarks" value="Remarks" />
        <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('remarks', $payment?->remarks) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
    </div>
</div>
