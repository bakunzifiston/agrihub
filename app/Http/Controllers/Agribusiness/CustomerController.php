<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\AgribusinessCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = auth()->user()->agribusinessCustomers()->orderBy('name')->get();
        return view('agribusiness.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('agribusiness.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'customer_type' => ['required', 'string', 'in:individual,retailer,wholesaler,other'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['agribusiness_id'] = auth()->id();
        AgribusinessCustomer::create($validated);
        return redirect()->route('agribusiness.customers.index')->with('success', 'Customer added.');
    }

    public function edit(AgribusinessCustomer $customer): View|RedirectResponse
    {
        if ((int) $customer->agribusiness_id !== (int) auth()->id()) {
            abort(403);
        }
        return view('agribusiness.customers.edit', compact('customer'));
    }

    public function update(Request $request, AgribusinessCustomer $customer): RedirectResponse
    {
        if ((int) $customer->agribusiness_id !== (int) auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'customer_type' => ['required', 'string', 'in:individual,retailer,wholesaler,other'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $customer->update($validated);
        return redirect()->route('agribusiness.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(AgribusinessCustomer $customer): RedirectResponse
    {
        if ((int) $customer->agribusiness_id !== (int) auth()->id()) {
            abort(403);
        }
        $customer->delete();
        return redirect()->route('agribusiness.customers.index')->with('success', 'Customer removed.');
    }
}
