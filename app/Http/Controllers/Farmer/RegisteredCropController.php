<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerRegisteredCrop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredCropController extends Controller
{
    public function index(): View
    {
        $registeredCrops = auth()->user()->registeredCrops()->orderBy('crop_name')->orderBy('crop_type')->get();

        return view('farmer.registered-crops.index', compact('registeredCrops'));
    }

    public function create(): View
    {
        return view('farmer.registered-crops.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crop_name' => ['required', 'string', 'max:255'],
            'crop_type' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['farmer_id'] = auth()->id();
        FarmerRegisteredCrop::create($validated);

        return redirect()->route('farmer.registered-crops.index')->with('success', 'Crop registered. You can now select it when adding crops.');
    }

    public function edit(FarmerRegisteredCrop $registeredCrop): View|RedirectResponse
    {
        if ($registeredCrop->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.registered-crops.edit', compact('registeredCrop'));
    }

    public function update(Request $request, FarmerRegisteredCrop $registeredCrop): RedirectResponse
    {
        if ($registeredCrop->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'crop_name' => ['required', 'string', 'max:255'],
            'crop_type' => ['nullable', 'string', 'max:100'],
        ]);

        $registeredCrop->update($validated);

        return redirect()->route('farmer.registered-crops.index')->with('success', 'Registered crop updated.');
    }

    public function destroy(FarmerRegisteredCrop $registeredCrop): RedirectResponse
    {
        if ($registeredCrop->farmer_id !== auth()->id()) {
            abort(403);
        }
        $registeredCrop->delete();

        return redirect()->route('farmer.registered-crops.index')->with('success', 'Registered crop removed.');
    }
}
