<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeCrop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CropController extends Controller
{
    public function index(): View
    {
        $crops = auth()->user()->cooperativeCrops()->latest()->get();
        return view('cooperative.crops.index', compact('crops'));
    }

    public function create(): View
    {
        return view('cooperative.crops.create');
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
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['cooperative_id'] = auth()->id();
        $validated['crop_status'] = $validated['crop_status'] ?? 'planted';
        CooperativeCrop::create($validated);
        return redirect()->route('cooperative.crops.index')->with('success', 'Crop added.');
    }

    public function edit(CooperativeCrop $crop): View|RedirectResponse
    {
        if ((int) $crop->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('cooperative.crops.edit', compact('crop'));
    }

    public function update(Request $request, CooperativeCrop $crop): RedirectResponse
    {
        if ((int) $crop->cooperative_id !== (int) auth()->id()) {
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
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $crop->update($validated);
        return redirect()->route('cooperative.crops.index')->with('success', 'Crop updated.');
    }

    public function destroy(CooperativeCrop $crop): RedirectResponse
    {
        if ((int) $crop->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $crop->delete();
        return redirect()->route('cooperative.crops.index')->with('success', 'Crop removed.');
    }
}
