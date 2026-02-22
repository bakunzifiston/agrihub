<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmProfile;
use App\Models\FarmProfilePlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FarmProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $profiles = $user->farmProfiles()->with('plots')->latest()->get();

        $totalPlots = $profiles->sum(fn ($p) => $p->plots->count() ?: $p->plot_count ?? 0);
        $totalLand = $profiles->sum('total_land_size');
        $activeProfiles = $profiles->where('status', 'active')->count();

        $kpis = [
            [
                'label' => 'Farm Profiles',
                'value' => $profiles->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeProfiles,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Total Plots',
                'value' => $totalPlots,
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Total Land',
                'value' => number_format($totalLand, 1) . ' ha',
                'color' => 'border-yellow-500',
            ],
        ];

        return view('farmer.farm-profile.index', compact('profiles', 'kpis'));
    }

    public function create(): View
    {
        return view('farmer.farm-profile.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'farm_name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'string', 'in:crop,livestock,mixed'],
            'total_land_size' => ['nullable', 'numeric', 'min:0'],
            'land_unit' => ['nullable', 'string', 'in:hectares,acres'],
            'plot_count' => ['nullable', 'integer', 'min:0'],
            'location_country' => ['required', 'string', 'max:100'],
            'location_district' => ['required', 'string', 'max:100'],
            'location_sector' => ['nullable', 'string', 'max:100'],
            'location_cell' => ['nullable', 'string', 'max:100'],
            'location_village' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'inputs_availability' => ['nullable', 'array'],
            'inputs_availability.*' => ['string'],
            'custom_inputs' => ['nullable', 'array'],
            'custom_inputs.*.category' => ['required_with:custom_inputs', 'string', 'max:100'],
            'custom_inputs.*.item' => ['required_with:custom_inputs', 'string', 'max:255'],
            'plot_names' => ['nullable', 'array'],
            'plot_names.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['inputs_availability'] = array_values($validated['inputs_availability'] ?? []) ?: null;
        $validated['custom_inputs'] = array_values(array_filter($validated['custom_inputs'] ?? [], fn($i) => !empty($i['category']) && !empty($i['item']))) ?: null;
        $validated['registration_date'] = $validated['registration_date'] ?? now();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('farm-profiles/photos', 'public');
        }

        $profile = FarmProfile::create($validated);

        $plotNames = array_filter(array_map('trim', $validated['plot_names'] ?? []));
        foreach ($plotNames as $i => $name) {
            FarmProfilePlot::create([
                'farm_profile_id' => $profile->id,
                'name' => $name,
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('farmer.farm-profile.index')->with('success', 'Farm profile created successfully.');
    }

    public function edit(FarmProfile $farmProfile): View|RedirectResponse
    {
        if ($farmProfile->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.farm-profile.edit', compact('farmProfile'));
    }

    public function update(Request $request, FarmProfile $farmProfile): RedirectResponse
    {
        if ($farmProfile->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'farm_name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'string', 'in:crop,livestock,mixed'],
            'total_land_size' => ['nullable', 'numeric', 'min:0'],
            'land_unit' => ['nullable', 'string', 'in:hectares,acres'],
            'plot_count' => ['nullable', 'integer', 'min:0'],
            'location_country' => ['required', 'string', 'max:100'],
            'location_district' => ['required', 'string', 'max:100'],
            'location_sector' => ['nullable', 'string', 'max:100'],
            'location_cell' => ['nullable', 'string', 'max:100'],
            'location_village' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'inputs_availability' => ['nullable', 'array'],
            'inputs_availability.*' => ['string'],
            'custom_inputs' => ['nullable', 'array'],
            'custom_inputs.*.category' => ['required_with:custom_inputs', 'string', 'max:100'],
            'custom_inputs.*.item' => ['required_with:custom_inputs', 'string', 'max:255'],
            'plot_names' => ['nullable', 'array'],
            'plot_names.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['inputs_availability'] = array_values($validated['inputs_availability'] ?? []) ?: null;
        $validated['custom_inputs'] = array_values(array_filter($validated['custom_inputs'] ?? [], fn($i) => !empty($i['category']) && !empty($i['item']))) ?: null;

        if ($request->boolean('remove_photo') && $farmProfile->photo) {
            Storage::disk('public')->delete($farmProfile->photo);
            $validated['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($farmProfile->photo) {
                Storage::disk('public')->delete($farmProfile->photo);
            }
            $validated['photo'] = $request->file('photo')->store('farm-profiles/photos', 'public');
        }

        unset($validated['remove_photo']);
        $farmProfile->update($validated);

        $farmProfile->plots()->delete();
        $plotNames = array_filter(array_map('trim', $validated['plot_names'] ?? []));
        foreach ($plotNames as $i => $name) {
            FarmProfilePlot::create([
                'farm_profile_id' => $farmProfile->id,
                'name' => $name,
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('farmer.farm-profile.index')->with('success', 'Farm profile updated successfully.');
    }

    public function destroy(FarmProfile $farmProfile): RedirectResponse
    {
        if ($farmProfile->farmer_id !== auth()->id()) {
            abort(403);
        }
        $farmProfile->delete();

        return redirect()->route('farmer.farm-profile.index')->with('success', 'Farm profile deleted.');
    }

    public function availableInputs(FarmProfile $farmProfile): \Illuminate\Http\JsonResponse
    {
        if ($farmProfile->farmer_id !== auth()->id()) {
            abort(403);
        }

        $availableInputs = [];

        if (! empty($farmProfile->inputs_availability)) {
            $agriculturalInputs = config('agricultural-inputs');

            foreach ($farmProfile->inputs_availability as $inputKey) {
                $parts = explode(':', $inputKey);
                if (count($parts) !== 2) {
                    continue;
                }

                [$categoryKey, $itemKey] = $parts;

                if (! isset($agriculturalInputs[$categoryKey])) {
                    continue;
                }

                $category = $agriculturalInputs[$categoryKey];

                if (! isset($category['items'][$itemKey])) {
                    continue;
                }

                $availableInputs[] = [
                    'category_key' => $categoryKey,
                    'category_label' => $category['label'],
                    'item_key' => $itemKey,
                    'item_label' => $category['items'][$itemKey],
                    'value' => $inputKey,
                    'is_custom' => false,
                ];
            }
        }

        if (! empty($farmProfile->custom_inputs)) {
            foreach ($farmProfile->custom_inputs as $index => $customInput) {
                $availableInputs[] = [
                    'category_key' => 'custom_' . $index,
                    'category_label' => $customInput['category'],
                    'item_key' => 'custom_' . $index,
                    'item_label' => $customInput['item'],
                    'value' => 'custom:' . $index . ':' . $customInput['category'] . ':' . $customInput['item'],
                    'is_custom' => true,
                ];
            }
        }

        usort($availableInputs, fn ($a, $b) => strcmp($a['category_label'] . $a['item_label'], $b['category_label'] . $b['item_label']));

        return response()->json($availableInputs);
    }
}
