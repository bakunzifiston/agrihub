<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerClient;
use App\Models\FarmOutput;
use App\Models\FarmSale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FarmSaleController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $sales = $user->farmSales()->with(['output', 'client'])->latest()->get();

        $totalRevenue = $sales->sum('total_amount');
        $thisMonthSales = $sales->filter(fn ($s) => $s->sale_date->isCurrentMonth());
        $thisMonthRevenue = $thisMonthSales->sum('total_amount');
        $paidSales = $sales->where('payment_status', 'paid')->sum('total_amount');
        $pendingSales = $sales->whereIn('payment_status', ['pending', 'partial', 'overdue'])->sum('total_amount');

        $kpis = [
            [
                'label' => 'Total Sales',
                'value' => $sales->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Revenue',
                'value' => number_format($totalRevenue, 0),
                'format' => 'currency',
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'This Month',
                'value' => number_format($thisMonthRevenue, 0),
                'format' => 'currency',
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Pending Payments',
                'value' => number_format($pendingSales, 0),
                'format' => 'currency',
                'color' => 'border-yellow-500',
            ],
        ];

        return view('farmer.sales.index', compact('sales', 'kpis'));
    }

    public function create(): View
    {
        $outputs = auth()->user()->farmOutputs()->orderBy('product_name')->get();
        $clients = auth()->user()->farmerClients()->orderBy('name')->get();
        return view('farmer.sales.create', compact('outputs', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farm_output_id' => ['nullable', 'integer', 'exists:farm_outputs,id'],
            'client_id' => ['nullable', 'integer', 'exists:farmer_clients,id'],
            'buyer_type' => ['required', 'string', 'in:individual,cooperative,agribusiness'],
            'buyer_name' => ['required_without:client_id', 'nullable', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_sold' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,mobile,bank'],
            'payment_status' => ['nullable', 'string', 'in:paid,pending,partial,overdue'],
            'sale_date' => ['required', 'date'],
        ]);

        $validated['farmer_id'] = auth()->id();
        if (empty($validated['total_amount']) && isset($validated['quantity_sold'], $validated['unit_price'])) {
            $validated['total_amount'] = (float) $validated['quantity_sold'] * (float) $validated['unit_price'];
        }
        if (! empty($validated['client_id'])) {
            $client = FarmerClient::where('id', $validated['client_id'])->where('farmer_id', auth()->id())->first();
            if ($client) {
                $validated['buyer_name'] = $client->name;
            }
        } else {
            $validated['client_id'] = null;
        }
        if (! empty($validated['farm_output_id'])) {
            $output = FarmOutput::where('id', $validated['farm_output_id'])->where('farmer_id', auth()->id())->first();
            if ($output) {
                $validated['product_name'] = $output->product_name;
                $validated['unit'] = $validated['unit'] ?: $output->unit;
            }
        } else {
            $validated['farm_output_id'] = null;
        }

        $sale = FarmSale::create($validated);

        if ($sale->farm_output_id) {
            $output = FarmOutput::where('id', $sale->farm_output_id)->where('farmer_id', auth()->id())->first();
            if ($output) {
                $output->decrement('quantity_available', $sale->quantity_sold);
            }
        }

        return redirect()->route('farmer.sales.index')->with('success', 'Sale recorded.');
    }

    public function edit(FarmSale $farmSale): View|RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }
        $outputs = auth()->user()->farmOutputs()->orderBy('product_name')->get();
        $clients = auth()->user()->farmerClients()->orderBy('name')->get();
        return view('farmer.sales.edit', compact('farmSale', 'outputs', 'clients'));
    }

    public function update(Request $request, FarmSale $farmSale): RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'farm_output_id' => ['nullable', 'integer', 'exists:farm_outputs,id'],
            'client_id' => ['nullable', 'integer', 'exists:farmer_clients,id'],
            'buyer_type' => ['required', 'string', 'in:individual,cooperative,agribusiness'],
            'buyer_name' => ['required_without:client_id', 'nullable', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_sold' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,mobile,bank'],
            'payment_status' => ['nullable', 'string', 'in:paid,pending,partial,overdue'],
            'sale_date' => ['required', 'date'],
        ]);

        if (empty($validated['total_amount']) && isset($validated['quantity_sold'], $validated['unit_price'])) {
            $validated['total_amount'] = (float) $validated['quantity_sold'] * (float) $validated['unit_price'];
        }
        if (! empty($validated['client_id'])) {
            $client = FarmerClient::where('id', $validated['client_id'])->where('farmer_id', auth()->id())->first();
            if ($client) {
                $validated['buyer_name'] = $client->name;
            }
        } else {
            $validated['client_id'] = null;
        }

        $oldOutputId = $farmSale->farm_output_id;
        $oldQuantity = (float) $farmSale->quantity_sold;

        if (! empty($validated['farm_output_id'])) {
            $output = FarmOutput::where('id', $validated['farm_output_id'])->where('farmer_id', auth()->id())->first();
            if ($output) {
                $validated['product_name'] = $output->product_name;
                $validated['unit'] = $validated['unit'] ?: $output->unit;
            }
        } else {
            $validated['farm_output_id'] = null;
        }

        $farmSale->update($validated);

        if ($oldOutputId) {
            $oldOutput = FarmOutput::where('id', $oldOutputId)->where('farmer_id', auth()->id())->first();
            if ($oldOutput) {
                $oldOutput->increment('quantity_available', $oldQuantity);
            }
        }
        if ($farmSale->farm_output_id) {
            $newOutput = FarmOutput::where('id', $farmSale->farm_output_id)->where('farmer_id', auth()->id())->first();
            if ($newOutput) {
                $newOutput->decrement('quantity_available', $farmSale->quantity_sold);
            }
        }

        return redirect()->route('farmer.sales.index')->with('success', 'Sale updated.');
    }

    public function destroy(FarmSale $farmSale): RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }
        if ($farmSale->farm_output_id) {
            $output = FarmOutput::where('id', $farmSale->farm_output_id)->where('farmer_id', auth()->id())->first();
            if ($output) {
                $output->increment('quantity_available', $farmSale->quantity_sold);
            }
        }
        $farmSale->delete();

        return redirect()->route('farmer.sales.index')->with('success', 'Sale deleted.');
    }
}
