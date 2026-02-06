<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FarmProfileController extends Controller
{
    public function index(): View
    {
        $profiles = auth()->user()->farmProfiles()->latest()->get();

        return view('farmer.farm-profile.index', compact('profiles'));
    }

    public function create(): View
    {
        return view('farmer.farm-profile.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'farm_name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'string', 'in:crop,livestock,mixed'],
            'total_land_size' => ['nullable', 'numeric', 'min:0'],
            'land_unit' => ['nullable', 'string', 'in:hectares,acres'],
            'location_country' => ['nullable', 'string', 'max:100'],
            'location_district' => ['nullable', 'string', 'max:100'],
            'location_sector' => ['nullable', 'string', 'max:100'],
            'location_cell' => ['nullable', 'string', 'max:100'],
            'location_village' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['registration_date'] = $validated['registration_date'] ?? now();

        FarmProfile::create($validated);

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
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'farm_name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'string', 'in:crop,livestock,mixed'],
            'total_land_size' => ['nullable', 'numeric', 'min:0'],
            'land_unit' => ['nullable', 'string', 'in:hectares,acres'],
            'location_country' => ['nullable', 'string', 'max:100'],
            'location_district' => ['nullable', 'string', 'max:100'],
            'location_sector' => ['nullable', 'string', 'max:100'],
            'location_cell' => ['nullable', 'string', 'max:100'],
            'location_village' => ['nullable', 'string', 'max:100'],
            'gps_latitude' => ['nullable', 'numeric'],
            'gps_longitude' => ['nullable', 'numeric'],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $farmProfile->update($validated);

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
}
