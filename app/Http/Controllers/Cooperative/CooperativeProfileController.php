<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CooperativeProfileController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $profile = auth()->user()->cooperativeProfile;

        if ($profile) {
            return view('cooperative.cooperative-profile.show', compact('profile'));
        }

        return view('cooperative.cooperative-profile.index');
    }

    public function create(): View
    {
        return view('cooperative.cooperative-profile.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'focus' => ['nullable', 'string', 'in:' . implode(',', array_keys(CooperativeProfile::FOCUS_OPTIONS))],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'registration_date' => ['nullable', 'date'],
        ]);

        $validated['cooperative_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'active';
        CooperativeProfile::create($validated);

        return redirect()->route('cooperative.cooperative-profile.index')->with('success', 'Cooperative profile created.');
    }

    public function edit(CooperativeProfile $cooperativeProfile): View|RedirectResponse
    {
        if ((int) $cooperativeProfile->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('cooperative.cooperative-profile.edit', compact('cooperativeProfile'));
    }

    public function update(Request $request, CooperativeProfile $cooperativeProfile): RedirectResponse
    {
        if ((int) $cooperativeProfile->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'district' => ['nullable', 'string', 'max:100'],
            'sector' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'focus' => ['nullable', 'string', 'in:' . implode(',', array_keys(CooperativeProfile::FOCUS_OPTIONS))],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'registration_date' => ['nullable', 'date'],
        ]);
        $cooperativeProfile->update($validated);

        return redirect()->route('cooperative.cooperative-profile.index')->with('success', 'Cooperative profile updated.');
    }

    public function destroy(CooperativeProfile $cooperativeProfile): RedirectResponse
    {
        if ((int) $cooperativeProfile->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $cooperativeProfile->delete();
        return redirect()->route('cooperative.cooperative-profile.index')->with('success', 'Cooperative profile removed.');
    }
}
