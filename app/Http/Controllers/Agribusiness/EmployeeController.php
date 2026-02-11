<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\AgribusinessEmployee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = auth()->user()->agribusinessEmployees()->orderBy('name')->get();
        return view('agribusiness.employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('agribusiness.employees.create');
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
        $validated['agribusiness_id'] = auth()->id();
        AgribusinessEmployee::create($validated);
        return redirect()->route('agribusiness.employees.index')->with('success', 'Employee added.');
    }

    public function edit(AgribusinessEmployee $employee): View|RedirectResponse
    {
        if ((int) $employee->agribusiness_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('agribusiness.employees.edit', compact('employee'));
    }

    public function update(Request $request, AgribusinessEmployee $employee): RedirectResponse
    {
        if ((int) $employee->agribusiness_id !== (int) auth()->id()) {
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
        return redirect()->route('agribusiness.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(AgribusinessEmployee $employee): RedirectResponse
    {
        if ((int) $employee->agribusiness_id !== (int) auth()->id()) {
            abort(403);
        }
        $employee->delete();
        return redirect()->route('agribusiness.employees.index')->with('success', 'Employee removed.');
    }
}
