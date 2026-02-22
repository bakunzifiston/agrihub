<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerEmployee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $employees = $user->farmerEmployees()->with('farmProfile')->latest()->get();

        $activeEmployees = $employees->where('status', 'active')->count();
        $fullTimeCount = $employees->where('employment_type', 'full_time')->count();
        $partTimeCount = $employees->where('employment_type', 'part_time')->count();
        $seasonalCount = $employees->where('employment_type', 'seasonal')->count();

        $kpis = [
            [
                'label' => 'Total Employees',
                'value' => $employees->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeEmployees,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Full Time',
                'value' => $fullTimeCount,
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Seasonal',
                'value' => $seasonalCount,
                'color' => 'border-yellow-500',
            ],
        ];

        return view('farmer.employees.index', compact('employees', 'kpis'));
    }

    public function create(): View
    {
        $farmProfiles = auth()->user()->farmProfiles()->orderBy('farm_name')->get();

        return view('farmer.employees.create', compact('farmProfiles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farm_profile_id' => ['nullable', 'exists:farm_profiles,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', 'string', 'in:full_time,part_time,seasonal,contract'],
            'hire_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'salary_period' => ['nullable', 'string', 'in:hourly,daily,weekly,monthly,yearly'],
            'country' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'cell' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'string', 'in:active,inactive,terminated'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'active';

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        FarmerEmployee::create($validated);

        return redirect()->route('farmer.employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(FarmerEmployee $employee): View|RedirectResponse
    {
        if ($employee->farmer_id !== auth()->id()) {
            abort(403);
        }

        $farmProfiles = auth()->user()->farmProfiles()->orderBy('farm_name')->get();

        return view('farmer.employees.edit', compact('employee', 'farmProfiles'));
    }

    public function update(Request $request, FarmerEmployee $employee): RedirectResponse
    {
        if ($employee->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'farm_profile_id' => ['nullable', 'exists:farm_profiles,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', 'string', 'in:full_time,part_time,seasonal,contract'],
            'hire_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'salary_period' => ['nullable', 'string', 'in:hourly,daily,weekly,monthly,yearly'],
            'country' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'cell' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'string', 'in:active,inactive,terminated'],
        ]);

        if ($request->boolean('remove_photo') && $employee->photo) {
            Storage::disk('public')->delete($employee->photo);
            $validated['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        unset($validated['remove_photo']);
        $employee->update($validated);

        return redirect()->route('farmer.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(FarmerEmployee $employee): RedirectResponse
    {
        if ($employee->farmer_id !== auth()->id()) {
            abort(403);
        }

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('farmer.employees.index')->with('success', 'Employee deleted.');
    }
}
