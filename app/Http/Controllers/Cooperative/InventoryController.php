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
        $user = auth()->user();
        $inventory = $user->cooperativeInventory()->with('warehouse')->latest()->get();

        $totalQuantity = $inventory->sum('quantity');
        $totalValue = $inventory->sum(fn ($i) => $i->quantity * ($i->unit_price ?? 0));
        $lowStockCount = $inventory->filter(fn ($i) => $i->quantity < ($i->reorder_level ?? 10))->count();

        $kpis = [
            [
                'label' => 'Inventory Items',
                'value' => $inventory->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Quantity',
                'value' => number_format($totalQuantity, 0),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Est. Value',
                'value' => number_format($totalValue, 0),
                'format' => 'currency',
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Low Stock',
                'value' => $lowStockCount,
                'color' => $lowStockCount > 0 ? 'border-red-500' : 'border-gray-400',
            ],
        ];

        return view('cooperative.inventory.index', compact('inventory', 'kpis'));
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
