<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerRegisteredProduct;
use App\Models\FarmerSupplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmerSupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = auth()->user()->farmerSuppliers()->with('products')->orderBy('name')->get();

        return view('farmer.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        $products = auth()->user()->registeredProducts()->orderBy('name')->get();

        return view('farmer.suppliers.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => [Rule::exists('farmer_registered_products', 'id')->where('farmer_id', auth()->id())],
        ]);

        $productIds = array_values($validated['product_ids'] ?? []);
        unset($validated['product_ids']);
        $validated['farmer_id'] = auth()->id();

        $supplier = FarmerSupplier::create($validated);
        $supplier->products()->sync($productIds);

        return redirect()->route('farmer.suppliers.index')->with('success', 'Supplier added. You can select them when recording input applications.');
    }

    public function edit(FarmerSupplier $supplier): View|RedirectResponse
    {
        if ($supplier->farmer_id !== auth()->id()) {
            abort(403);
        }

        $supplier->load('products');
        $products = auth()->user()->registeredProducts()->orderBy('name')->get();

        return view('farmer.suppliers.edit', compact('supplier', 'products'));
    }

    public function update(Request $request, FarmerSupplier $supplier): RedirectResponse
    {
        if ($supplier->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => [Rule::exists('farmer_registered_products', 'id')->where('farmer_id', auth()->id())],
        ]);

        $productIds = array_values($validated['product_ids'] ?? []);
        unset($validated['product_ids']);
        $supplier->update($validated);

        $supplier->products()->sync($productIds);

        return redirect()->route('farmer.suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(FarmerSupplier $supplier): RedirectResponse
    {
        if ($supplier->farmer_id !== auth()->id()) {
            abort(403);
        }
        $supplier->delete();

        return redirect()->route('farmer.suppliers.index')->with('success', 'Supplier removed.');
    }
}
