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
        $inputs = auth()->user()->farmInputs()->latest()->get();

        return view('farmer.inputs.index', compact('inputs'));
    }

    public function create(): View
    {
        return view('farmer.inputs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'input_name' => ['required', 'string', 'max:255'],
            'input_category' => ['required', 'string', 'in:seed,fertilizer,feed,medicine'],
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
        if ($farmInput->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.inputs.edit', compact('farmInput'));
    }

    public function update(Request $request, FarmInput $farmInput): RedirectResponse
    {
        if ($farmInput->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'input_name' => ['required', 'string', 'max:255'],
            'input_category' => ['required', 'string', 'in:seed,fertilizer,feed,medicine'],
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
        if ($farmInput->farmer_id !== auth()->id()) {
            abort(403);
        }
        $farmInput->delete();

        return redirect()->route('farmer.inputs.index')->with('success', 'Input deleted.');
    }
}
