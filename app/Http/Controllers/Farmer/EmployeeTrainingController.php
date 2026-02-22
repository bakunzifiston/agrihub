<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTraining;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeTrainingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $trainings = $user->employeeTrainings()->with('employee')->latest()->get();

        $completedCount = $trainings->where('status', 'completed')->count();
        $scheduledCount = $trainings->where('status', 'scheduled')->count();
        $inProgressCount = $trainings->where('status', 'in_progress')->count();
        $totalCost = $trainings->sum('cost');

        $kpis = [
            [
                'label' => 'Total Trainings',
                'value' => $trainings->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Completed',
                'value' => $completedCount,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Scheduled',
                'value' => $scheduledCount,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Total Cost',
                'value' => number_format($totalCost, 0),
                'format' => 'currency',
                'color' => 'border-purple-500',
            ],
        ];

        return view('farmer.trainings.index', compact('trainings', 'kpis'));
    }

    public function create(): View
    {
        $employees = auth()->user()->farmerEmployees()->orderBy('first_name')->get();

        return view('farmer.trainings.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                Rule::exists('farmer_employees', 'id')->where('farmer_id', auth()->id()),
            ],
            'training_name' => ['required', 'string', 'max:255'],
            'training_type' => ['nullable', 'string', 'in:' . implode(',', array_keys(EmployeeTraining::TRAINING_TYPES))],
            'provider' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(EmployeeTraining::STATUSES))],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'certificate_expiry' => ['nullable', 'date'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['farmer_id'] = auth()->id();

        if ($request->hasFile('certificate_file')) {
            $validated['certificate_file'] = $request->file('certificate_file')->store('trainings/certificates', 'public');
        }

        EmployeeTraining::create($validated);

        return redirect()->route('farmer.trainings.index')->with('success', 'Training record added successfully.');
    }

    public function edit(EmployeeTraining $training): View|RedirectResponse
    {
        if ($training->farmer_id !== auth()->id()) {
            abort(403);
        }

        $employees = auth()->user()->farmerEmployees()->orderBy('first_name')->get();

        return view('farmer.trainings.edit', compact('training', 'employees'));
    }

    public function update(Request $request, EmployeeTraining $training): RedirectResponse
    {
        if ($training->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'employee_id' => [
                'required',
                Rule::exists('farmer_employees', 'id')->where('farmer_id', auth()->id()),
            ],
            'training_name' => ['required', 'string', 'max:255'],
            'training_type' => ['nullable', 'string', 'in:' . implode(',', array_keys(EmployeeTraining::TRAINING_TYPES))],
            'provider' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(EmployeeTraining::STATUSES))],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'certificate_expiry' => ['nullable', 'date'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'remove_certificate' => ['nullable', 'boolean'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($request->boolean('remove_certificate') && $training->certificate_file) {
            Storage::disk('public')->delete($training->certificate_file);
            $validated['certificate_file'] = null;
        } elseif ($request->hasFile('certificate_file')) {
            if ($training->certificate_file) {
                Storage::disk('public')->delete($training->certificate_file);
            }
            $validated['certificate_file'] = $request->file('certificate_file')->store('trainings/certificates', 'public');
        }

        unset($validated['remove_certificate']);
        $training->update($validated);

        return redirect()->route('farmer.trainings.index')->with('success', 'Training record updated successfully.');
    }

    public function destroy(EmployeeTraining $training): RedirectResponse
    {
        if ($training->farmer_id !== auth()->id()) {
            abort(403);
        }

        if ($training->certificate_file) {
            Storage::disk('public')->delete($training->certificate_file);
        }

        $training->delete();

        return redirect()->route('farmer.trainings.index')->with('success', 'Training record deleted successfully.');
    }
}
