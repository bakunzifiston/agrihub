<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeWarehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = auth()->user()->cooperativeWarehouses()->withCount('inventory')->latest()->get();

        return view('cooperative.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('cooperative.warehouses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['cooperative_id'] = auth()->id();
        CooperativeWarehouse::create($validated);

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse added.');
    }

    public function edit(CooperativeWarehouse $warehouse): View|RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }

        return view('cooperative.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, CooperativeWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $warehouse->update($validated);

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse updated.');
    }

    public function destroy(CooperativeWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $warehouse->delete();

        return redirect()->route('cooperative.warehouses.index')->with('success', 'Warehouse removed.');
    }
}
