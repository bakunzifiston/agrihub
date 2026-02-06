<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmSale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FarmSaleController extends Controller
{
    public function index(): View
    {
        $sales = auth()->user()->farmSales()->latest()->get();

        return view('farmer.sales.index', compact('sales'));
    }

    public function create(): View
    {
        return view('farmer.sales.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buyer_type' => ['required', 'string', 'in:individual,cooperative,agribusiness'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_sold' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,mobile,bank'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'sale_date' => ['required', 'date'],
        ]);

        $validated['farmer_id'] = auth()->id();

        FarmSale::create($validated);

        return redirect()->route('farmer.sales.index')->with('success', 'Sale recorded.');
    }

    public function edit(FarmSale $farmSale): View|RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.sales.edit', compact('farmSale'));
    }

    public function update(Request $request, FarmSale $farmSale): RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'buyer_type' => ['required', 'string', 'in:individual,cooperative,agribusiness'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_sold' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,mobile,bank'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'sale_date' => ['required', 'date'],
        ]);

        $farmSale->update($validated);

        return redirect()->route('farmer.sales.index')->with('success', 'Sale updated.');
    }

    public function destroy(FarmSale $farmSale): RedirectResponse
    {
        if ($farmSale->farmer_id !== auth()->id()) {
            abort(403);
        }
        $farmSale->delete();

        return redirect()->route('farmer.sales.index')->with('success', 'Sale deleted.');
    }
}
