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
        $user = auth()->user();
        $listings = $user->preOrderListings()->with(['crop', 'farmOutput', 'preOrders'])->orderBy('expected_harvest_date')->get();

        $totalValue = $listings->sum(fn ($l) => $l->quantity_available * ($l->price_per_unit ?? 0));
        $activeListings = $listings->where('listing_status', 'active')->count();
        $totalOrders = $listings->sum(fn ($l) => $l->preOrders->count());

        $kpis = [
            [
                'label' => 'Total Listings',
                'value' => $listings->count(),
                'color' => 'border-green-500',
            ],
            [
                'label' => 'Active',
                'value' => $activeListings,
                'color' => 'border-blue-500',
            ],
            [
                'label' => 'Pre-Orders',
                'value' => $totalOrders,
                'color' => 'border-purple-500',
            ],
            [
                'label' => 'Est. Value',
                'value' => number_format($totalValue, 0),
                'format' => 'currency',
                'color' => 'border-yellow-500',
            ],
        ];

        return view('farmer.pre-order-listings.index', compact('listings', 'kpis'));
    }

    public function create(): View
    {
        $crops = auth()->user()->crops()->orderBy('crop_name')->get();
        $outputs = auth()->user()->farmOutputs()->orderBy('product_name')->get();
        return view('farmer.pre-order-listings.create', compact('crops', 'outputs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source' => ['required', 'string', 'in:crop,output,manual'],
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
        } elseif ($validated['source'] === 'manual') {
            $validated['crop_id'] = null;
            $validated['farm_output_id'] = null;
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
