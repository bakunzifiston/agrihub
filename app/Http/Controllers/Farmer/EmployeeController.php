<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerEmployee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = auth()->user()->farmerEmployees()->orderBy('name')->get();
        return view('farmer.employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('farmer.employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'in:full_time,part_time,seasonal,contract'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['farmer_id'] = auth()->id();
        FarmerEmployee::create($validated);
        return redirect()->route('farmer.employees.index')->with('success', 'Employee added.');
    }

    public function edit(FarmerEmployee $employee): View|RedirectResponse
    {
        if ((int) $employee->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('farmer.employees.edit', compact('employee'));
    }

    public function update(Request $request, FarmerEmployee $employee): RedirectResponse
    {
        if ((int) $employee->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'in:full_time,part_time,seasonal,contract'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $employee->update($validated);
        return redirect()->route('farmer.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(FarmerEmployee $employee): RedirectResponse
    {
        if ((int) $employee->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        $employee->delete();
        return redirect()->route('farmer.employees.index')->with('success', 'Employee removed.');
    }
}
