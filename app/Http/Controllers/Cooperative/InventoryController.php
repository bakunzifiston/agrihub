<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeInventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $inventory = auth()->user()->cooperativeInventory()->with('warehouse')->latest()->get();

        return view('cooperative.inventory.index', compact('inventory'));
    }

    public function create(): View
    {
        $warehouses = auth()->user()->cooperativeWarehouses()->orderBy('name')->get();

        return view('cooperative.inventory.create', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:cooperative_warehouses,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
        ]);
        if (isset($validated['warehouse_id']) && (int) \App\Models\CooperativeWarehouse::find($validated['warehouse_id'])->cooperative_id !== (int) auth()->id()) {
            return back()->withErrors(['warehouse_id' => 'Invalid warehouse.']);
        }

        $validated['cooperative_id'] = auth()->id();
        $validated['last_updated'] = now();
        CooperativeInventory::create($validated);

        return redirect()->route('cooperative.inventory.index')->with('success', 'Inventory item added.');
    }

    public function edit(CooperativeInventory $inventory): View|RedirectResponse
    {
        if ($inventory->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $warehouses = auth()->user()->cooperativeWarehouses()->orderBy('name')->get();

        return view('cooperative.inventory.edit', compact('inventory', 'warehouses'));
    }

    public function update(Request $request, CooperativeInventory $inventory): RedirectResponse
    {
        if ($inventory->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:cooperative_warehouses,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
        ]);
        if (isset($validated['warehouse_id']) && (int) \App\Models\CooperativeWarehouse::find($validated['warehouse_id'])->cooperative_id !== (int) auth()->id()) {
            return back()->withErrors(['warehouse_id' => 'Invalid warehouse.']);
        }

        $validated['last_updated'] = now();
        $inventory->update($validated);

        return redirect()->route('cooperative.inventory.index')->with('success', 'Inventory updated.');
    }

    public function destroy(CooperativeInventory $inventory): RedirectResponse
    {
        if ($inventory->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $inventory->delete();

        return redirect()->route('cooperative.inventory.index')->with('success', 'Inventory item removed.');
    }
}
