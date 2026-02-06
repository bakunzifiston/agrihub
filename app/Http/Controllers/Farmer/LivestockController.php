<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LivestockController extends Controller
{
    public function index(): View
    {
        $livestock = auth()->user()->livestock()->latest()->get();

        return view('farmer.livestock.index', compact('livestock'));
    }

    public function create(): View
    {
        return view('farmer.livestock.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'livestock_type' => ['required', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'purpose' => ['nullable', 'string', 'in:milk,meat,eggs,breeding'],
            'acquisition_date' => ['nullable', 'date'],
            'health_status' => ['nullable', 'string', 'max:100'],
            'vaccination_status' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['farmer_id'] = auth()->id();

        Livestock::create($validated);

        return redirect()->route('farmer.livestock.index')->with('success', 'Livestock added successfully.');
    }

    public function edit(Livestock $livestock): View|RedirectResponse
    {
        if ($livestock->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.livestock.edit', compact('livestock'));
    }

    public function update(Request $request, Livestock $livestock): RedirectResponse
    {
        if ($livestock->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'livestock_type' => ['required', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'purpose' => ['nullable', 'string', 'in:milk,meat,eggs,breeding'],
            'acquisition_date' => ['nullable', 'date'],
            'health_status' => ['nullable', 'string', 'max:100'],
            'vaccination_status' => ['nullable', 'string', 'max:100'],
        ]);

        $livestock->update($validated);

        return redirect()->route('farmer.livestock.index')->with('success', 'Livestock updated successfully.');
    }

    public function destroy(Livestock $livestock): RedirectResponse
    {
        if ($livestock->farmer_id !== auth()->id()) {
            abort(403);
        }
        $livestock->delete();

        return redirect()->route('farmer.livestock.index')->with('success', 'Livestock deleted.');
    }
}
