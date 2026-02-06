<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CropController extends Controller
{
    public function index(): View
    {
        $crops = auth()->user()->crops()->latest()->get();

        return view('farmer.crops.index', compact('crops'));
    }

    public function create(): View
    {
        $registeredCrops = auth()->user()->registeredCrops()->orderBy('crop_name')->orderBy('crop_type')->get();

        return view('farmer.crops.create', compact('registeredCrops'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crop_name' => ['required', 'string', 'max:255'],
            'crop_type' => ['nullable', 'string', 'max:100'],
            'season' => ['nullable', 'string', 'max:50'],
            'planting_date' => ['nullable', 'date'],
            'expected_harvest_date' => ['nullable', 'date'],
            'land_area_used' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['nullable', 'string', 'max:20'],
            'expected_yield' => ['nullable', 'numeric', 'min:0'],
            'yield_unit' => ['nullable', 'string', 'max:20'],
            'crop_status' => ['nullable', 'string', 'in:planted,growing,harvested'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['crop_status'] = $validated['crop_status'] ?? 'planted';

        Crop::create($validated);

        return redirect()->route('farmer.crops.index')->with('success', 'Crop added successfully.');
    }

    public function edit(Crop $crop): View|RedirectResponse
    {
        if ($crop->farmer_id !== auth()->id()) {
            abort(403);
        }

        $registeredCrops = auth()->user()->registeredCrops()->orderBy('crop_name')->orderBy('crop_type')->get();

        return view('farmer.crops.edit', compact('crop', 'registeredCrops'));
    }

    public function update(Request $request, Crop $crop): RedirectResponse
    {
        if ($crop->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'crop_name' => ['required', 'string', 'max:255'],
            'crop_type' => ['nullable', 'string', 'max:100'],
            'season' => ['nullable', 'string', 'max:50'],
            'planting_date' => ['nullable', 'date'],
            'expected_harvest_date' => ['nullable', 'date'],
            'land_area_used' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['nullable', 'string', 'max:20'],
            'expected_yield' => ['nullable', 'numeric', 'min:0'],
            'yield_unit' => ['nullable', 'string', 'max:20'],
            'crop_status' => ['nullable', 'string', 'in:planted,growing,harvested'],
        ]);

        $crop->update($validated);

        return redirect()->route('farmer.crops.index')->with('success', 'Crop updated successfully.');
    }

    public function destroy(Crop $crop): RedirectResponse
    {
        if ($crop->farmer_id !== auth()->id()) {
            abort(403);
        }
        $crop->delete();

        return redirect()->route('farmer.crops.index')->with('success', 'Crop deleted.');
    }
}
