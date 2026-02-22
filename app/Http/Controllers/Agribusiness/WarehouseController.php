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
        $user = auth()->user();
        $warehouses = $user->agribusinessWarehouses()->withCount('inventory')->latest()->get();

        $totalCapacity = $warehouses->sum('capacity');
        $totalInventoryItems = $warehouses->sum('inventory_count');
        $activeWarehouses = $warehouses->where('status', 'active')->count();

        $kpis = [
            [
                'label' => 'Warehouses',
                'value' => $warehouses->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Capacity',
                'value' => number_format($totalCapacity, 0),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Inventory Items',
                'value' => $totalInventoryItems,
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeWarehouses,
                'color' => 'border-yellow-500',
            ],
        ];

        return view('agribusiness.warehouses.index', compact('warehouses', 'kpis'));
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
