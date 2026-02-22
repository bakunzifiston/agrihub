<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmOutput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FarmOutputController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $outputs = $user->farmOutputs()->latest()->get();

        $totalQuantity = $outputs->sum('quantity_available');
        $expiringCount = $outputs->filter(fn ($o) => $o->expiry_date && $o->expiry_date->lte(now()->addDays(30)))->count();

        $kpis = [
            [
                'label' => 'Total Products',
                'value' => $outputs->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'In Storage',
                'value' => $outputs->whereNotNull('storage_location')->count(),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Total Quantity',
                'value' => number_format($totalQuantity, 0),
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Expiring Soon',
                'value' => $expiringCount,
                'color' => $expiringCount > 0 ? 'border-red-500' : 'border-gray-400',
            ],
        ];

        return view('farmer.outputs.index', compact('outputs', 'kpis'));
    }

    public function create(): View
    {
        return view('farmer.outputs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_available' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'harvest_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        $validated['farmer_id'] = auth()->id();

        FarmOutput::create($validated);

        return redirect()->route('farmer.outputs.index')->with('success', 'Output added.');
    }

    public function edit(FarmOutput $farmOutput): View|RedirectResponse
    {
        if ((int) $farmOutput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }

        return view('farmer.outputs.edit', compact('farmOutput'));
    }

    public function update(Request $request, FarmOutput $farmOutput): RedirectResponse
    {
        if ((int) $farmOutput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_available' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'harvest_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        $farmOutput->update($validated);

        return redirect()->route('farmer.outputs.index')->with('success', 'Output updated.');
    }

    public function destroy(FarmOutput $farmOutput): RedirectResponse
    {
        if ((int) $farmOutput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        $farmOutput->delete();

        return redirect()->route('farmer.outputs.index')->with('success', 'Output deleted.');
    }
}
