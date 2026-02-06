<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use App\Models\ProcessingRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcessingController extends Controller
{
    public function index(): View
    {
        $records = auth()->user()->processingRecords()->latest()->get();

        return view('agribusiness.processing.index', compact('records'));
    }

    public function create(): View
    {
        return view('agribusiness.processing.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'raw_material' => ['required', 'string', 'max:255'],
            'quantity_input' => ['required', 'numeric', 'min:0'],
            'input_unit' => ['required', 'string', 'max:50'],
            'quantity_output' => ['required', 'numeric', 'min:0'],
            'output_unit' => ['required', 'string', 'max:50'],
            'processing_date' => ['required', 'date'],
            'processing_cost' => ['nullable', 'numeric', 'min:0'],
            'wastage_quantity' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['agribusiness_id'] = auth()->id();
        ProcessingRecord::create($validated);

        return redirect()->route('agribusiness.processing.index')->with('success', 'Processing record added.');
    }

    public function edit(ProcessingRecord $processing): View|RedirectResponse
    {
        if ($processing->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        return view('agribusiness.processing.edit', compact('processing'));
    }

    public function update(Request $request, ProcessingRecord $processing): RedirectResponse
    {
        if ($processing->agribusiness_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'raw_material' => ['required', 'string', 'max:255'],
            'quantity_input' => ['required', 'numeric', 'min:0'],
            'input_unit' => ['required', 'string', 'max:50'],
            'quantity_output' => ['required', 'numeric', 'min:0'],
            'output_unit' => ['required', 'string', 'max:50'],
            'processing_date' => ['required', 'date'],
            'processing_cost' => ['nullable', 'numeric', 'min:0'],
            'wastage_quantity' => ['nullable', 'numeric', 'min:0'],
        ]);

        $processing->update($validated);

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
