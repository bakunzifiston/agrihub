<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $suppliers = $user->suppliers()->latest()->get();

        $activeSuppliers = $suppliers->where('status', 'active')->count();
        $farmersCount = $suppliers->where('supplier_type', 'farmer')->count();
        $cooperativeCount = $suppliers->where('supplier_type', 'cooperative')->count();

        $kpis = [
            [
                'label' => 'Total Suppliers',
                'value' => $suppliers->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeSuppliers,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Farmers',
                'value' => $farmersCount,
                'color' => 'border-yellow-500',
            ],
            [
                'label' => 'Cooperatives',
                'value' => $cooperativeCount,
                'color' => 'border-purple-500',
            ],
        ];

        return view('agribusiness.suppliers.index', compact('suppliers', 'kpis'));
    }

    public function create(): View
    {
        return view('agribusiness.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_type' => ['required', 'string', 'in:farmer,cooperative'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contract_status' => ['nullable', 'string', 'max:50'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        Supplier::create($validated);

        return redirect()->route('agribusiness.suppliers.index')->with('success', 'Supplier added.');
    }

    public function edit(Supplier $supplier): View|RedirectResponse
    {
        if ($supplier->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        return view('agribusiness.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        if ($supplier->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'supplier_type' => ['required', 'string', 'in:farmer,cooperative'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contract_status' => ['nullable', 'string', 'max:50'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        $supplier->update($validated);

        return redirect()->route('agribusiness.suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $supplier->delete();

        return redirect()->route('agribusiness.suppliers.index')->with('success', 'Supplier removed.');
    }
}
