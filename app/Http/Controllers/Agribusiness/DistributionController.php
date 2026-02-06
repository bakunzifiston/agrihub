<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistributionController extends Controller
{
    public function index(): View
    {
        $distributions = auth()->user()->distributions()->latest()->get();

        return view('agribusiness.distributions.index', compact('distributions'));
    }

    public function create(): View
    {
        return view('agribusiness.distributions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_dispatched' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'dispatch_date' => ['required', 'date'],
            'delivery_status' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        Distribution::create($validated);

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution recorded.');
    }

    public function edit(Distribution $distribution): View|RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        return view('agribusiness.distributions.edit', compact('distribution'));
    }

    public function update(Request $request, Distribution $distribution): RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_dispatched' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'dispatch_date' => ['required', 'date'],
            'delivery_status' => ['nullable', 'string', 'max:50'],
        ]);

        $distribution->update($validated);

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution updated.');
    }

    public function destroy(Distribution $distribution): RedirectResponse
    {
        if ($distribution->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $distribution->delete();

        return redirect()->route('agribusiness.distributions.index')->with('success', 'Distribution removed.');
    }
}
