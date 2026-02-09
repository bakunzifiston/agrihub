<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerRegisteredProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredProductController extends Controller
{
    public function index(): View
    {
        $products = auth()->user()->registeredProducts()->orderBy('name')->get();

        return view('farmer.registered-products.index', compact('products'));
    }

    public function create(): View
    {
        return view('farmer.registered-products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'in:seed,fertilizer,pesticide,herbicide,other'],
        ]);

        $validated['farmer_id'] = auth()->id();
        FarmerRegisteredProduct::create($validated);

        return redirect()->route('farmer.registered-products.index')->with('success', 'Product registered. You can select it when recording input applications.');
    }

    public function edit(FarmerRegisteredProduct $registeredProduct): View|RedirectResponse
    {
        if ($registeredProduct->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.registered-products.edit', compact('registeredProduct'));
    }

    public function update(Request $request, FarmerRegisteredProduct $registeredProduct): RedirectResponse
    {
        if ($registeredProduct->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'in:seed,fertilizer,pesticide,herbicide,other'],
        ]);

        $registeredProduct->update($validated);

        return redirect()->route('farmer.registered-products.index')->with('success', 'Product updated.');
    }

    public function destroy(FarmerRegisteredProduct $registeredProduct): RedirectResponse
    {
        if ($registeredProduct->farmer_id !== auth()->id()) {
            abort(403);
        }
        $registeredProduct->delete();

        return redirect()->route('farmer.registered-products.index')->with('success', 'Product removed.');
    }
}
