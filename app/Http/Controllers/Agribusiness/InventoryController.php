<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\AgribusinessInventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $inventory = $user->agribusinessInventory()->with('warehouse')->latest()->get();

        $totalQuantity = $inventory->sum('quantity');
        $totalValue = $inventory->sum(fn ($i) => $i->quantity * ($i->unit_cost ?? 0));
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
                'label' => 'Total Value',
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

        return view('agribusiness.inventory.index', compact('inventory', 'kpis'));
    }

    public function create(): View
    {
        $warehouses = auth()->user()->agribusinessWarehouses()->orderBy('name')->get();

        return view('agribusiness.inventory.create', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'warehouse_id' => ['nullable', 'integer', 'exists:agribusiness_warehouses,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'batch_number' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        $validated['warehouse_id'] = $request->input('warehouse_id') ?: null;
        AgribusinessInventory::create($validated);

        return redirect()->route('agribusiness.inventory.index')->with('success', 'Inventory item added.');
    }

    public function edit(AgribusinessInventory $inventory): View|RedirectResponse
    {
        if ($inventory->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $warehouses = auth()->user()->agribusinessWarehouses()->orderBy('name')->get();

        return view('agribusiness.inventory.edit', compact('inventory', 'warehouses'));
    }

    public function update(Request $request, AgribusinessInventory $inventory): RedirectResponse
    {
        if ($inventory->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'warehouse_id' => ['nullable', 'integer', 'exists:agribusiness_warehouses,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'batch_number' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        $validated['warehouse_id'] = $request->input('warehouse_id') ?: null;
        $inventory->update($validated);

        return redirect()->route('agribusiness.inventory.index')->with('success', 'Inventory updated.');
    }

    public function destroy(AgribusinessInventory $inventory): RedirectResponse
    {
        if ($inventory->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $inventory->delete();

        return redirect()->route('agribusiness.inventory.index')->with('success', 'Inventory item removed.');
    }
}
