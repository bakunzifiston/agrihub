<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativeClient;
use App\Models\CooperativeInventory;
use App\Models\CooperativeOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $orders = $user->cooperativeOrders()->with(['inventory'])->latest('order_date')->latest()->get();

        $totalValue = $orders->sum('total_price');
        $pendingOrders = $orders->where('order_status', 'pending')->count();
        $completedOrders = $orders->whereIn('order_status', ['completed', 'delivered'])->count();

        $kpis = [
            [
                'label' => 'Total Orders',
                'value' => $orders->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Value',
                'value' => number_format($totalValue, 0),
                'format' => 'currency',
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Pending',
                'value' => $pendingOrders,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Completed',
                'value' => $completedOrders,
                'color' => 'border-purple-500',
            ],
        ];

        return view('cooperative.orders.index', compact('orders', 'kpis'));
    }

    public function create(): View
    {
        $clients = auth()->user()->cooperativeClients()->orderBy('name')->get();
        $inventoryItems = auth()->user()->cooperativeInventory()->with('warehouse')->orderBy('product_name')->get();
        return view('cooperative.orders.create', compact('clients', 'inventoryItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:cooperative_clients,id'],
            'inventory_id' => ['nullable', 'integer', 'exists:cooperative_inventory,id'],
            'customer_name' => ['required_without:client_id', 'nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:pending,confirmed,fulfilled,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['cooperative_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'pending';
        if (! empty($validated['client_id'])) {
            $client = CooperativeClient::where('id', $validated['client_id'])->where('cooperative_id', auth()->id())->first();
            if ($client) {
                $validated['customer_name'] = $client->name;
                $validated['customer_phone'] = $validated['customer_phone'] ?: $client->phone;
                $validated['customer_email'] = $validated['customer_email'] ?: $client->email;
                $validated['customer_address'] = $validated['customer_address'] ?: $client->address;
            }
        } else {
            $validated['client_id'] = null;
        }
        if (! empty($validated['inventory_id'])) {
            $inv = CooperativeInventory::where('id', $validated['inventory_id'])->where('cooperative_id', auth()->id())->first();
            if ($inv) {
                $validated['product_name'] = $inv->product_name;
                $validated['unit'] = $validated['unit'] ?: $inv->unit;
            }
        } else {
            $validated['inventory_id'] = null;
        }
        if (empty($validated['total_amount']) && ! empty($validated['unit_price'])) {
            $validated['total_amount'] = (float) $validated['quantity'] * (float) $validated['unit_price'];
        }
        CooperativeOrder::create($validated);
        return redirect()->route('cooperative.orders.index')->with('success', 'Order added.');
    }

    public function edit(CooperativeOrder $order): View|RedirectResponse
    {
        if ((int) $order->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $clients = auth()->user()->cooperativeClients()->orderBy('name')->get();
        $inventoryItems = auth()->user()->cooperativeInventory()->with('warehouse')->orderBy('product_name')->get();
        return view('cooperative.orders.edit', compact('order', 'clients', 'inventoryItems'));
    }

    public function update(Request $request, CooperativeOrder $order): RedirectResponse
    {
        if ((int) $order->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:cooperative_clients,id'],
            'inventory_id' => ['nullable', 'integer', 'exists:cooperative_inventory,id'],
            'customer_name' => ['required_without:client_id', 'nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:pending,confirmed,fulfilled,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        if (! empty($validated['client_id'])) {
            $client = CooperativeClient::where('id', $validated['client_id'])->where('cooperative_id', auth()->id())->first();
            if ($client) {
                $validated['customer_name'] = $client->name;
                $validated['customer_phone'] = $validated['customer_phone'] ?: $client->phone;
                $validated['customer_email'] = $validated['customer_email'] ?: $client->email;
                $validated['customer_address'] = $validated['customer_address'] ?: $client->address;
            }
        } else {
            $validated['client_id'] = null;
        }
        if (! empty($validated['inventory_id'])) {
            $inv = CooperativeInventory::where('id', $validated['inventory_id'])->where('cooperative_id', auth()->id())->first();
            if ($inv) {
                $validated['product_name'] = $inv->product_name;
                $validated['unit'] = $validated['unit'] ?: $inv->unit;
            }
        } else {
            $validated['inventory_id'] = null;
        }
        if (empty($validated['total_amount']) && ! empty($validated['unit_price'])) {
            $validated['total_amount'] = (float) $validated['quantity'] * (float) $validated['unit_price'];
        }
        $order->update($validated);
        return redirect()->route('cooperative.orders.index')->with('success', 'Order updated.');
    }

    public function destroy(CooperativeOrder $order): RedirectResponse
    {
        if ((int) $order->cooperative_id !== (int) auth()->id()) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('cooperative.orders.index')->with('success', 'Order removed.');
    }
}
