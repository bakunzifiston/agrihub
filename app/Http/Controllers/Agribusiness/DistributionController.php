<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\AgribusinessInventory;
use App\Models\Distribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistributionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $distributions = $user->distributions()->with('inventory.warehouse', 'customer')->latest()->get();

        $totalDispatched = $distributions->sum('quantity_dispatched');
        $pendingDistributions = $distributions->where('delivery_status', 'pending')->count();
        $deliveredDistributions = $distributions->where('delivery_status', 'delivered')->count();

        $kpis = [
            [
                'label' => 'Distributions',
                'value' => $distributions->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Dispatched',
                'value' => number_format($totalDispatched, 0),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Pending',
                'value' => $pendingDistributions,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Delivered',
                'value' => $deliveredDistributions,
                'color' => 'border-purple-500',
            ],
        ];

        return view('agribusiness.distributions.index', compact('distributions', 'kpis'));
    }

    public function create(): View
    {
        $inventoryItems = auth()->user()->agribusinessInventory()->with('warehouse')->orderBy('product_name')->get();
        $customers = auth()->user()->agribusinessCustomers()->orderBy('name')->get();
        return view('agribusiness.distributions.create', compact('inventoryItems', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inventory_id' => ['nullable', 'integer', 'exists:agribusiness_inventory,id'],
            'customer_id' => ['nullable', 'integer', 'exists:agribusiness_customers,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_dispatched' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'dispatch_date' => ['required', 'date'],
            'delivery_status' => ['nullable', 'string', 'in:dispatched,in_transit,delivered,pending'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        $customerId = $validated['customer_id'] ?? null;
        if ($customerId) {
            $customer = \App\Models\AgribusinessCustomer::where('id', $customerId)->where('agribusiness_id', auth()->id())->first();
            if ($customer) {
                $validated['customer_name'] = $customer->name;
                $validated['customer_id'] = $customer->id;
            } else {
                $validated['customer_id'] = null;
            }
        } else {
            $validated['customer_id'] = null;
        }
        if (! empty($validated['inventory_id'])) {
            $inv = AgribusinessInventory::where('id', $validated['inventory_id'])->where('agribusiness_id', auth()->id())->first();
            if ($inv) {
                $validated['product_name'] = $inv->product_name;
                $validated['unit'] = $validated['unit'] ?: $inv->unit;
            }
        } else {
            $validated['inventory_id'] = null;
        }

        $distribution = Distribution::create($validated);

        if ($distribution->inventory_id) {
            $inv = AgribusinessInventory::where('id', $distribution->inventory_id)->where('agribusiness_id', auth()->id())->first();
            if ($inv) {
                $inv->decrement('quantity_in_stock', $distribution->quantity_dispatched);
            }
        }

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution recorded.');
    }

    public function edit(Distribution $distribution): View|RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $inventoryItems = auth()->user()->agribusinessInventory()->with('warehouse')->orderBy('product_name')->get();
        $customers = auth()->user()->agribusinessCustomers()->orderBy('name')->get();
        return view('agribusiness.distributions.edit', compact('distribution', 'inventoryItems', 'customers'));
    }

    public function update(Request $request, Distribution $distribution): RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'inventory_id' => ['nullable', 'integer', 'exists:agribusiness_inventory,id'],
            'customer_id' => ['nullable', 'integer', 'exists:agribusiness_customers,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_dispatched' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'dispatch_date' => ['required', 'date'],
            'delivery_status' => ['nullable', 'string', 'in:dispatched,in_transit,delivered,pending'],
        ]);

        $customerId = $validated['customer_id'] ?? null;
        if ($customerId) {
            $customer = \App\Models\AgribusinessCustomer::where('id', $customerId)->where('agribusiness_id', auth()->id())->first();
            if ($customer) {
                $validated['customer_name'] = $customer->name;
                $validated['customer_id'] = $customer->id;
            } else {
                $validated['customer_id'] = null;
            }
        } else {
            $validated['customer_id'] = null;
        }

        $oldInventoryId = $distribution->inventory_id;
        $oldQuantity = (float) $distribution->quantity_dispatched;

        if (! empty($validated['inventory_id'])) {
            $inv = AgribusinessInventory::where('id', $validated['inventory_id'])->where('agribusiness_id', auth()->id())->first();
            if ($inv) {
                $validated['product_name'] = $inv->product_name;
                $validated['unit'] = $validated['unit'] ?: $inv->unit;
            }
        } else {
            $validated['inventory_id'] = null;
        }

        $distribution->update($validated);

        if ($oldInventoryId) {
            $oldInv = AgribusinessInventory::where('id', $oldInventoryId)->where('agribusiness_id', auth()->id())->first();
            if ($oldInv) {
                $oldInv->increment('quantity_in_stock', $oldQuantity);
            }
        }
        if ($distribution->inventory_id) {
            $newInv = AgribusinessInventory::where('id', $distribution->inventory_id)->where('agribusiness_id', auth()->id())->first();
            if ($newInv) {
                $newInv->decrement('quantity_in_stock', $distribution->quantity_dispatched);
            }
        }

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution updated.');
    }

    public function destroy(Distribution $distribution): RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        if ($distribution->inventory_id) {
            $inv = AgribusinessInventory::where('id', $distribution->inventory_id)->where('agribusiness_id', auth()->id())->first();
            if ($inv) {
                $inv->increment('quantity_in_stock', $distribution->quantity_dispatched);
            }
        }
        $distribution->delete();

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution removed.');
    }
}
