<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeLivestock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LivestockController extends Controller
{
    public function index(): View
    {
        $livestock = auth()->user()->cooperativeLivestock()->latest()->get();
        return view('cooperative.livestock.index', compact('livestock'));
    }

    public function create(): View
    {
        return view('cooperative.livestock.create');
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
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['cooperative_id'] = auth()->id();
        CooperativeLivestock::create($validated);
        return redirect()->route('cooperative.livestock.index')->with('success', 'Livestock added.');
    }

    public function edit(CooperativeLivestock $livestock): View|RedirectResponse
    {
        if ((int) $livestock->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('cooperative.livestock.edit', compact('livestock'));
    }

    public function update(Request $request, CooperativeLivestock $livestock): RedirectResponse
    {
        if ((int) $livestock->cooperative_id !== (int) auth()->id()) {
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
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $livestock->update($validated);
        return redirect()->route('cooperative.livestock.index')->with('success', 'Livestock updated.');
    }

    public function destroy(CooperativeLivestock $livestock): RedirectResponse
    {
        if ((int) $livestock->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $livestock->delete();
        return redirect()->route('cooperative.livestock.index')->with('success', 'Livestock removed.');
    }
}
