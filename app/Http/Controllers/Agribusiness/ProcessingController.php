<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\ProcessingRawMaterial;
use App\Models\ProcessingRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcessingController extends Controller
{
    public function index(): View
    {
        $records = auth()->user()->processingRecords()->with(['contract.supplier', 'rawMaterials.supplier'])->latest()->get();

        return view('agribusiness.processing.index', compact('records'));
    }

    public function create(): View
    {
        $contracts = auth()->user()->contracts()->with('supplier')->orderBy('product_name')->get();
        $suppliers = auth()->user()->suppliers()->orderBy('supplier_name')->get();
        return view('agribusiness.processing.create', compact('contracts', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
            'raw_materials' => ['required', 'array', 'min:1'],
            'raw_materials.*.raw_material' => ['required', 'string', 'max:255'],
            'raw_materials.*.quantity_input' => ['required', 'numeric', 'min:0'],
            'raw_materials.*.input_unit' => ['required', 'string', 'max:50'],
            'raw_materials.*.supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'quantity_output' => ['required', 'numeric', 'min:0'],
            'output_unit' => ['required', 'string', 'max:50'],
            'processing_date' => ['required', 'date'],
            'processing_cost' => ['nullable', 'numeric', 'min:0'],
            'wastage_quantity' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        $validated['contract_id'] = $validated['contract_id'] ?: null;
        $record = ProcessingRecord::create([
            'agribusiness_id' => $validated['agribusiness_id'],
            'contract_id' => $validated['contract_id'],
            'quantity_output' => $validated['quantity_output'],
            'output_unit' => $validated['output_unit'],
            'processing_date' => $validated['processing_date'],
            'processing_cost' => $validated['processing_cost'] ?? null,
            'wastage_quantity' => $validated['wastage_quantity'] ?? null,
        ]);

        foreach ($validated['raw_materials'] as $rm) {
            $record->rawMaterials()->create([
                'raw_material' => $rm['raw_material'],
                'quantity_input' => $rm['quantity_input'],
                'input_unit' => $rm['input_unit'],
                'supplier_id' => ! empty($rm['supplier_id']) ? $rm['supplier_id'] : null,
            ]);
        }

        return redirect()->route('agribusiness.processing.index')->with('success', 'Processing record added.');
    }

    public function edit(ProcessingRecord $processing): View|RedirectResponse
    {
        if ($processing->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $contracts = auth()->user()->contracts()->with('supplier')->orderBy('product_name')->get();
        $suppliers = auth()->user()->suppliers()->orderBy('supplier_name')->get();
        return view('agribusiness.processing.edit', compact('processing', 'contracts', 'suppliers'));
    }

    public function update(Request $request, ProcessingRecord $processing): RedirectResponse
    {
        if ($processing->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
            'raw_materials' => ['required', 'array', 'min:1'],
            'raw_materials.*.raw_material' => ['required', 'string', 'max:255'],
            'raw_materials.*.quantity_input' => ['required', 'numeric', 'min:0'],
            'raw_materials.*.input_unit' => ['required', 'string', 'max:50'],
            'raw_materials.*.supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'quantity_output' => ['required', 'numeric', 'min:0'],
            'output_unit' => ['required', 'string', 'max:50'],
            'processing_date' => ['required', 'date'],
            'processing_cost' => ['nullable', 'numeric', 'min:0'],
            'wastage_quantity' => ['nullable', 'numeric', 'min:0'],
        ]);

        $processing->update([
            'contract_id' => $validated['contract_id'] ?: null,
            'quantity_output' => $validated['quantity_output'],
            'output_unit' => $validated['output_unit'],
            'processing_date' => $validated['processing_date'],
            'processing_cost' => $validated['processing_cost'] ?? null,
            'wastage_quantity' => $validated['wastage_quantity'] ?? null,
        ]);

        $processing->rawMaterials()->delete();
        foreach ($validated['raw_materials'] as $rm) {
            $processing->rawMaterials()->create([
                'raw_material' => $rm['raw_material'],
                'quantity_input' => $rm['quantity_input'],
                'input_unit' => $rm['input_unit'],
                'supplier_id' => ! empty($rm['supplier_id']) ? $rm['supplier_id'] : null,
            ]);
        }

        return redirect()->route('agribusiness.processing.index')->with('success', 'Processing record updated.');
    }

    public function destroy(ProcessingRecord $processing): RedirectResponse
    {
        if ($processing->agribusiness_id !== auth()->id()) {
            abort(403);
        }
        $processing->delete();

        return redirect()->route('agribusiness.processing.index')->with('success', 'Processing record removed.');
    }
}
