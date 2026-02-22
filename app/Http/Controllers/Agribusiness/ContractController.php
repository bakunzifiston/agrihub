<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $contracts = $user->contracts()->with('supplier')->latest()->get();

        $activeContracts = $contracts->where('contract_status', 'active');
        $totalValue = $activeContracts->sum(fn ($c) => ($c->contract_quantity ?? 0) * ($c->price_per_unit ?? 0));
        $pendingContracts = $contracts->where('contract_status', 'pending')->count();

        $kpis = [
            [
                'label' => 'Total Contracts',
                'value' => $contracts->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeContracts->count(),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Total Value',
                'value' => number_format($totalValue, 0),
                'format' => 'currency',
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Pending',
                'value' => $pendingContracts,
                'color' => 'border-yellow-500',
            ],
        ];

        return view('agribusiness.contracts.index', compact('contracts', 'kpis'));
    }

    public function create(): View|RedirectResponse
    {
        $suppliers = auth()->user()->suppliers()->orderBy('supplier_name')->get();
        if ($suppliers->isEmpty()) {
            return redirect()->route('agribusiness.contracts.index')
                ->with('error', 'Add at least one supplier before creating a contract.');
        }

        return view('agribusiness.contracts.create', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validSupplierIds = auth()->user()->suppliers()->pluck('id')->toArray();
        $validated = $request->validate([
            'supplier_id' => ['required', 'in:'.implode(',', $validSupplierIds ?: [0])],
            'product_name' => ['required', 'string', 'max:255'],
            'contract_quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'delivery_schedule' => ['nullable', 'string'],
            'contract_status' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        Contract::create($validated);

        return redirect()->route('agribusiness.contracts.index')->with('success', 'Contract added.');
    }

    public function edit(Contract $contract): View|RedirectResponse
    {
        if ($contract->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $suppliers = auth()->user()->suppliers()->orderBy('supplier_name')->get();

        return view('agribusiness.contracts.edit', compact('contract', 'suppliers'));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        if ($contract->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validSupplierIds = auth()->user()->suppliers()->pluck('id')->toArray();
        $validated = $request->validate([
            'supplier_id' => ['required', 'in:'.implode(',', $validSupplierIds ?: [0])],
            'product_name' => ['required', 'string', 'max:255'],
            'contract_quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'delivery_schedule' => ['nullable', 'string'],
            'contract_status' => ['nullable', 'string', 'max:50'],
        ]);

        $contract->update($validated);

        return redirect()->route('agribusiness.contracts.index')->with('success', 'Contract updated.');
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        if ($contract->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $contract->delete();

        return redirect()->route('agribusiness.contracts.index')->with('success', 'Contract removed.');
    }
}
