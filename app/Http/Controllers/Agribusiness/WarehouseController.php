<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\AgribusinessWarehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = auth()->user()->agribusinessWarehouses()->withCount('inventory')->latest()->get();

        return view('agribusiness.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('agribusiness.warehouses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        AgribusinessWarehouse::create($validated);

        return redirect()->route('agribusiness.warehouses.index')->with('success', 'Warehouse added.');
    }

    public function edit(AgribusinessWarehouse $warehouse): View|RedirectResponse
    {
        if ($warehouse->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        return view('agribusiness.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, AgribusinessWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $warehouse->update($validated);

        return redirect()->route('agribusiness.warehouses.index')->with('success', 'Warehouse updated.');
    }

    public function destroy(AgribusinessWarehouse $warehouse): RedirectResponse
    {
        if ($warehouse->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $warehouse->delete();

        return redirect()->route('agribusiness.warehouses.index')->with('success', 'Warehouse removed.');
    }
}
