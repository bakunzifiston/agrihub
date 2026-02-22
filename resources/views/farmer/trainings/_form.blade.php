@props(['training' => null, 'employees' => collect()])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <x-input-label for="employee_id" value="Employee *" />
        <select id="employee_id" name="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">— Select Employee —</option>
            @foreach ($employees as $emp)
                <option value="{{ $emp->id }}" @selected(old('employee_id', $training?->employee_id) == $emp->id)>{{ $emp->full_name }} {{ $emp->job_title ? '(' . $emp->job_title . ')' : '' }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
    </div>

    <div>
        <x-input-label for="training_name" value="Training Name *" />
        <x-text-input id="training_name" name="training_name" type="text" class="mt-1 block w-full" :value="old('training_name', $training?->training_name)" required placeholder="e.g. Farm Safety Training" />
        <x-input-error class="mt-2" :messages="$errors->get('training_name')" />
    </div>

    <div>
        <x-input-label for="training_type" value="Training Type" />
        <select id="training_type" name="training_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            <option value="">— Select Type —</option>
            @foreach (\App\Models\EmployeeTraining::TRAINING_TYPES as $key => $label)
                <option value="{{ $key }}" @selected(old('training_type', $training?->training_type) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('training_type')" />
    </div>

    <div>
        <x-input-label for="provider" value="Training Provider" />
        <x-text-input id="provider" name="provider" type="text" class="mt-1 block w-full" :value="old('provider', $training?->provider)" placeholder="e.g. Rwanda Agriculture Board" />
        <x-input-error class="mt-2" :messages="$errors->get('provider')" />
    </div>

    <div>
        <x-input-label for="location" value="Location" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $training?->location)" placeholder="e.g. Kigali, On-site" />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>

    <div>
        <x-input-label for="start_date" value="Start Date" />
        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $training?->start_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
    </div>

    <div>
        <x-input-label for="end_date" value="End Date" />
        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $training?->end_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
    </div>

    <div>
        <x-input-label for="duration_hours" value="Duration (hours)" />
        <x-text-input id="duration_hours" name="duration_hours" type="number" min="1" class="mt-1 block w-full" :value="old('duration_hours', $training?->duration_hours)" placeholder="e.g. 8" />
        <x-input-error class="mt-2" :messages="$errors->get('duration_hours')" />
    </div>

    <div>
        <x-input-label for="status" value="Status *" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            @foreach (\App\Models\EmployeeTraining::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $training?->status ?? 'scheduled') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="cost" value="Cost" />
        <x-text-input id="cost" name="cost" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('cost', $training?->cost)" placeholder="0.00" />
        <x-input-error class="mt-2" :messages="$errors->get('cost')" />
    </div>

    <div class="md:col-span-2 border-t pt-4 mt-2">
        <h4 class="font-medium text-gray-900 mb-3">Certificate Information</h4>
    </div>

    <div>
        <x-input-label for="certificate_number" value="Certificate Number" />
        <x-text-input id="certificate_number" name="certificate_number" type="text" class="mt-1 block w-full" :value="old('certificate_number', $training?->certificate_number)" placeholder="e.g. CERT-2026-001" />
        <x-input-error class="mt-2" :messages="$errors->get('certificate_number')" />
    </div>

    <div>
        <x-input-label for="certificate_expiry" value="Certificate Expiry Date" />
        <x-text-input id="certificate_expiry" name="certificate_expiry" type="date" class="mt-1 block w-full" :value="old('certificate_expiry', $training?->certificate_expiry?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('certificate_expiry')" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="certificate_file" value="Certificate File (PDF, JPG, PNG - max 5MB)" />
        @if ($training?->certificate_file)
            <div class="mt-1 flex items-center gap-4">
                <a href="{{ Storage::url($training->certificate_file) }}" target="_blank" class="text-primary hover:underline text-sm">View current certificate</a>
                <label class="inline-flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remove_certificate" value="1" class="rounded border-gray-300 text-primary focus:ring-primary mr-1">
                    Remove certificate
                </label>
            </div>
        @endif
        <input type="file" id="certificate_file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary hover:file:bg-primary-100" />
        <x-input-error class="mt-2" :messages="$errors->get('certificate_file')" />
    </div>

    <div class="md:col-span-2 border-t pt-4 mt-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Training objectives and content...">{{ old('description', $training?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Additional notes...">{{ old('notes', $training?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
