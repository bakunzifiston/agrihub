<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\FarmProfilePlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CropController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $crops = $user->crops()->with('plots')->latest()->get();

        $kpis = [
            [
                'label' => 'Total Crops',
                'value' => $crops->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Planted',
                'value' => $crops->where('crop_status', 'planted')->count(),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Growing',
                'value' => $crops->where('crop_status', 'growing')->count(),
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Harvested',
                'value' => $crops->where('crop_status', 'harvested')->count(),
                'color' => 'border-primary',
            ],
        ];

        return view('farmer.crops.index', compact('crops', 'kpis'));
    }

    public function create(): View
    {
        $plots = FarmProfilePlot::whereIn('farm_profile_id', auth()->user()->farmProfiles()->pluck('id'))
            ->with('farmProfile')
            ->orderBy('farm_profile_id')
            ->orderBy('sort_order')
            ->get();

        return view('farmer.crops.create', compact('plots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plot_ids' => ['nullable', 'array'],
            'plot_ids.*' => [
                Rule::exists('farm_profile_plots', 'id')->whereIn('farm_profile_id', auth()->user()->farmProfiles()->pluck('id')),
            ],
            'crop_type' => ['required', 'string', 'max:100'],
            'crop_name' => ['required', 'string', 'max:255'],
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
        $plotIds = $validated['plot_ids'] ?? [];

        $crop = Crop::create(collect($validated)->except('plot_ids')->all());
        $crop->plots()->sync($plotIds);

        return redirect()->route('farmer.crops.index')->with('success', 'Crop added successfully.');
    }

    public function edit(Crop $crop): View|RedirectResponse
    {
        if ($crop->farmer_id !== auth()->id()) {
            abort(403);
        }

        $plots = FarmProfilePlot::whereIn('farm_profile_id', auth()->user()->farmProfiles()->pluck('id'))
            ->with('farmProfile')
            ->orderBy('farm_profile_id')
            ->orderBy('sort_order')
            ->get();

        return view('farmer.crops.edit', compact('crop', 'plots'));
    }

    public function update(Request $request, Crop $crop): RedirectResponse
    {
        if ($crop->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'plot_ids' => ['nullable', 'array'],
            'plot_ids.*' => [
                Rule::exists('farm_profile_plots', 'id')->whereIn('farm_profile_id', auth()->user()->farmProfiles()->pluck('id')),
            ],
            'crop_type' => ['required', 'string', 'max:100'],
            'crop_name' => ['required', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:50'],
            'planting_date' => ['nullable', 'date'],
            'expected_harvest_date' => ['nullable', 'date'],
            'land_area_used' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['nullable', 'string', 'max:20'],
            'expected_yield' => ['nullable', 'numeric', 'min:0'],
            'yield_unit' => ['nullable', 'string', 'max:20'],
            'crop_status' => ['nullable', 'string', 'in:planted,growing,harvested'],
        ]);

        $crop->update(collect($validated)->except('plot_ids')->all());
        $crop->plots()->sync($validated['plot_ids'] ?? []);

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
