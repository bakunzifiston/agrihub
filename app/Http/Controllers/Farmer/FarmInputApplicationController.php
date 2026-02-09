<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\FarmInputApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmInputApplicationController extends Controller
{
    public function index(): View
    {
        $applications = auth()->user()->farmInputApplications()
            ->with(['farmProfile', 'plot', 'crop'])
            ->latest('application_date')
            ->get();

        return view('farmer.input-applications.index', compact('applications'));
    }

    public function create(): View
    {
        $farmProfiles = auth()->user()->farmProfiles()->with('plots')->orderBy('farm_name')->get();
        $crops = auth()->user()->crops()->orderBy('crop_name')->get();
        $inputNameOptions = auth()->user()->farmInputs()->distinct()->pluck('input_name')->filter()->sort()->values();
        $supplierNameOptions = auth()->user()->farmInputs()->distinct()->pluck('supplier_name')->filter()->sort()->values();

        return view('farmer.input-applications.create', compact('farmProfiles', 'crops', 'inputNameOptions', 'supplierNameOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farm_profile_id' => [
                'required',
                Rule::exists('farm_profiles', 'id')->where('farmer_id', auth()->id()),
            ],
            'farm_profile_plot_id' => [
                'nullable',
                Rule::exists('farm_profile_plots', 'id')->where('farm_profile_id', $request->input('farm_profile_id')),
            ],
            'crop_id' => [
                'nullable',
                Rule::exists('crops', 'id')->where('farmer_id', auth()->id()),
            ],
            'input_name' => ['required', 'string', 'max:255'],
            'input_type' => ['required', 'string', 'in:fertilizer,pesticide,herbicide'],
            'batch_number' => ['nullable', 'string', 'max:100'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
            'quantity_used' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'applied_by' => ['nullable', 'string', 'max:255'],
            'phi_days' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['farmer_id'] = auth()->id();

        FarmInputApplication::create($validated);

        return redirect()->route('farmer.input-applications.index')->with('success', 'Input application recorded.');
    }

    public function edit(FarmInputApplication $farmInputApplication): View|RedirectResponse
    {
        if ($farmInputApplication->farmer_id !== auth()->id()) {
            abort(403);
        }

        $farmProfiles = auth()->user()->farmProfiles()->with('plots')->orderBy('farm_name')->get();
        $crops = auth()->user()->crops()->orderBy('crop_name')->get();
        $inputNameOptions = auth()->user()->farmInputs()->distinct()->pluck('input_name')->filter()->sort()->values();
        $supplierNameOptions = auth()->user()->farmInputs()->distinct()->pluck('supplier_name')->filter()->sort()->values();

        return view('farmer.input-applications.edit', compact('farmInputApplication', 'farmProfiles', 'crops', 'inputNameOptions', 'supplierNameOptions'));
    }

    public function update(Request $request, FarmInputApplication $farmInputApplication): RedirectResponse
    {
        if ($farmInputApplication->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'farm_profile_id' => [
                'required',
                Rule::exists('farm_profiles', 'id')->where('farmer_id', auth()->id()),
            ],
            'farm_profile_plot_id' => [
                'nullable',
                Rule::exists('farm_profile_plots', 'id')->where('farm_profile_id', $request->input('farm_profile_id')),
            ],
            'crop_id' => [
                'nullable',
                Rule::exists('crops', 'id')->where('farmer_id', auth()->id()),
            ],
            'input_name' => ['required', 'string', 'max:255'],
            'input_type' => ['required', 'string', 'in:fertilizer,pesticide,herbicide'],
            'batch_number' => ['nullable', 'string', 'max:100'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
            'quantity_used' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'applied_by' => ['nullable', 'string', 'max:255'],
            'phi_days' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $farmInputApplication->update($validated);

        return redirect()->route('farmer.input-applications.index')->with('success', 'Input application updated.');
    }

    public function destroy(FarmInputApplication $farmInputApplication): RedirectResponse
    {
        if ($farmInputApplication->farmer_id !== auth()->id()) {
            abort(403);
        }
        $farmInputApplication->delete();

        return redirect()->route('farmer.input-applications.index')->with('success', 'Input application deleted.');
    }
}
