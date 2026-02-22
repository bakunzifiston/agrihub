<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\ProductionRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionRecordController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $records = $user->productionRecords()->latest()->get();

        $totalProduced = $records->sum('quantity_produced');
        $totalLosses = $records->sum('losses_quantity');
        $cropRecords = $records->where('product_type', 'crop')->count();
        $livestockRecords = $records->where('product_type', 'livestock')->count();

        $kpis = [
            [
                'label' => 'Total Records',
                'value' => $records->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Total Produced',
                'value' => number_format($totalProduced, 0),
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Total Losses',
                'value' => number_format($totalLosses, 0),
                'color' => $totalLosses > 0 ? 'border-red-500' : 'border-gray-400',
            ],
            [
                'label' => 'Crop Records',
                'value' => $cropRecords,
                'color' => 'border-yellow-500',
            ],
        ];

        return view('farmer.production-records.index', compact('records', 'kpis'));
    }

    public function create(): View
    {
        return view('farmer.production-records.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_type' => ['required', 'string', 'in:crop,livestock'],
            'product_name' => ['required', 'string', 'max:255'],
            'production_date' => ['required', 'date'],
            'quantity_produced' => ['required', 'numeric', 'min:0'],
            'quantity_unit' => ['required', 'string', 'max:50'],
            'quality_grade' => ['nullable', 'string', 'max:50'],
            'losses_quantity' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['losses_quantity'] = $validated['losses_quantity'] ?? 0;

        ProductionRecord::create($validated);

        return redirect()->route('farmer.production-records.index')->with('success', 'Production record added.');
    }

    public function edit(ProductionRecord $productionRecord): View|RedirectResponse
    {
        if ($productionRecord->farmer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.production-records.edit', compact('productionRecord'));
    }

    public function update(Request $request, ProductionRecord $productionRecord): RedirectResponse
    {
        if ($productionRecord->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'product_type' => ['required', 'string', 'in:crop,livestock'],
            'product_name' => ['required', 'string', 'max:255'],
            'production_date' => ['required', 'date'],
            'quantity_produced' => ['required', 'numeric', 'min:0'],
            'quantity_unit' => ['required', 'string', 'max:50'],
            'quality_grade' => ['nullable', 'string', 'max:50'],
            'losses_quantity' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        $productionRecord->update($validated);

        return redirect()->route('farmer.production-records.index')->with('success', 'Production record updated.');
    }

    public function destroy(ProductionRecord $productionRecord): RedirectResponse
    {
        if ($productionRecord->farmer_id !== auth()->id()) {
            abort(403);
        }
        $productionRecord->delete();

        return redirect()->route('farmer.production-records.index')->with('success', 'Production record deleted.');
    }
}
