<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\CooperativePayment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = auth()->user()->cooperativePayments()->with('farmer')->latest()->get();

        return view('cooperative.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $farmers = User::where('tenant_type', 'farmer')->orderBy('name')->get();

        return view('cooperative.payments.create', compact('farmers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'farmer_id' => ['required', 'exists:users,id'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'payment_date' => ['required', 'date'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['cooperative_id'] = auth()->id();
        CooperativePayment::create($validated);

        return redirect()->route('cooperative.payments.index')->with('success', 'Payment recorded.');
    }

    public function edit(CooperativePayment $payment): View|RedirectResponse
    {
        if ($payment->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $farmers = User::where('tenant_type', 'farmer')->orderBy('name')->get();

        return view('cooperative.payments.edit', compact('payment', 'farmers'));
    }

    public function update(Request $request, CooperativePayment $payment): RedirectResponse
    {
        if ($payment->cooperative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'farmer_id' => ['required', 'exists:users,id'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'payment_date' => ['required', 'date'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'remarks' => ['nullable', 'string'],
        ]);

        $payment->update($validated);

        return redirect()->route('cooperative.payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(CooperativePayment $payment): RedirectResponse
    {
        if ($payment->cooperative_id !== auth()->id()) {
            abort(403);
        }
        $payment->delete();

        return redirect()->route('cooperative.payments.index')->with('success', 'Payment removed.');
    }
}
