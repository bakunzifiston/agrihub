<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\FarmOutput;
use App\Models\PreOrderListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreOrderListingController extends Controller
{
    public function index(): View
    {
        $listings = auth()->user()->preOrderListings()->with(['crop', 'farmOutput', 'preOrders'])->orderBy('expected_harvest_date')->get();
        return view('farmer.pre-order-listings.index', compact('listings'));
    }

    public function create(): View
    {
        $crops = auth()->user()->crops()->whereIn('crop_status', ['planted', 'growing'])->orderBy('crop_name')->get();
        $outputs = auth()->user()->farmOutputs()->orderBy('product_name')->get();
        return view('farmer.pre-order-listings.create', compact('crops', 'outputs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source' => ['required', 'string', 'in:crop,output'],
            'crop_id' => ['required_if:source,crop', 'nullable', 'integer', 'exists:crops,id'],
            'farm_output_id' => ['required_if:source,output', 'nullable', 'integer', 'exists:farm_outputs,id'],
            'title' => ['required', 'string', 'max:255'],
            'quantity_available' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:50'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
            'expected_harvest_date' => ['nullable', 'date'],
        ]);

        $validated['farmer_id'] = auth()->id();
        if ($validated['source'] === 'crop') {
            $crop = Crop::where('id', $validated['crop_id'])->where('farmer_id', auth()->id())->firstOrFail();
            $validated['crop_id'] = $crop->id;
            $validated['farm_output_id'] = null;
            $validated['expected_harvest_date'] = $validated['expected_harvest_date'] ?? $crop->expected_harvest_date;
            if (empty($validated['title'])) {
                $validated['title'] = $crop->crop_name . ($crop->crop_type ? " ({$crop->crop_type})" : '');
            }
        } else {
            $output = FarmOutput::where('id', $validated['farm_output_id'])->where('farmer_id', auth()->id())->firstOrFail();
            $validated['farm_output_id'] = $output->id;
            $validated['crop_id'] = null;
            $validated['expected_harvest_date'] = $validated['expected_harvest_date'] ?? $output->harvest_date;
            if (empty($validated['title'])) {
                $validated['title'] = $output->product_name;
            }
        }

        unset($validated['source']);
        PreOrderListing::create($validated);

        return redirect()->route('farmer.pre-order-listings.index')->with('success', 'Pre-order listing added. It will appear on the marketplace when synced.');
    }

    public function edit(PreOrderListing $preOrderListing): View|RedirectResponse
    {
        if ((int) $preOrderListing->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        $preOrderListing->load(['crop', 'farmOutput']);
        return view('farmer.pre-order-listings.edit', compact('preOrderListing'));
    }

    public function update(Request $request, PreOrderListing $preOrderListing): RedirectResponse
    {
        if ((int) $preOrderListing->farmer_id !== (int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'quantity_available' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price_per_unit' => ['nullable', 'numeric', 'min:0'],
            'expected_harvest_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $preOrderListing->update($validated);

        return redirect()->route('farmer.pre-order-listings.index')->with('success', 'Listing updated.');
    }

    public function destroy(PreOrderListing $preOrderListing): RedirectResponse
    {
        if ((int) $preOrderListing->farmer_id !== (int) auth()->id()) {
            abort(403);
        }
        if ($preOrderListing->preOrders()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return redirect()->route('farmer.pre-order-listings.index')->with('error', 'Cannot delete: there are active pre-orders. Deactivate the listing instead.');
        }
        $preOrderListing->delete();
        return redirect()->route('farmer.pre-order-listings.index')->with('success', 'Listing removed.');
    }
}
