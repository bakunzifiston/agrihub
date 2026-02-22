<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FarmInputController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $inputs = $user->farmInputs()->latest()->get();

        $totalCost = $inputs->sum('total_cost');
        $seedCount = $inputs->where('input_category', 'seed')->count();
        $fertilizerCount = $inputs->where('input_category', 'fertilizer')->count();
        $feedCount = $inputs->where('input_category', 'feed')->count();

        $kpis = [
            [
                'label' => 'Total Inputs',
                'value' => $inputs->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Cost',
                'value' => number_format($totalCost, 0),
                'format' => 'currency',
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Seeds',
                'value' => $seedCount,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Fertilizers',
                'value' => $fertilizerCount,
                'color' => 'border-purple-500',
            ],
        ];

        return view('farmer.inputs.index', compact('inputs', 'kpis'));
    }

    public function create(): View
    {
        $farmProfiles = auth()->user()->farmProfiles()->orderBy('farm_name')->get();

        return view('farmer.inputs.create', compact('farmProfiles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'input_name' => ['required', 'string', 'max:255'],
            'input_category' => ['required', 'string', 'in:' . implode(',', array_keys(config('agricultural-inputs')))],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'purchase_date' => ['nullable', 'date'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['farmer_id'] = auth()->id();

        FarmInput::create($validated);

        return redirect()->route('farmer.inputs.index')->with('success', 'Input added.');
    }

    public function edit(FarmInput $farmInput): View|RedirectResponse
    {
        if ((int) $farmInput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }

        $farmProfiles = auth()->user()->farmProfiles()->orderBy('farm_name')->get();

        return view('farmer.inputs.edit', compact('farmInput', 'farmProfiles'));
    }

    public function update(Request $request, FarmInput $farmInput): RedirectResponse
    {
        if ((int) $farmInput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'input_name' => ['required', 'string', 'max:255'],
            'input_category' => ['required', 'string', 'in:' . implode(',', array_keys(config('agricultural-inputs')))],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'purchase_date' => ['nullable', 'date'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $farmInput->update($validated);

        return redirect()->route('farmer.inputs.index')->with('success', 'Input updated.');
    }

    public function destroy(FarmInput $farmInput): RedirectResponse
    {
        if ((int) $farmInput->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        $farmInput->delete();

        return redirect()->route('farmer.inputs.index')->with('success', 'Input deleted.');
    }
}
