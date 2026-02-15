<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\PreOrderListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    /**
     * List active pre-order listings for WooCommerce to sync as products.
     */
    public function listings(): JsonResponse
    {
        $listings = PreOrderListing::query()
            ->where('is_active', true)
            ->with(['farmer:id,name,farm_name,location', 'crop:id,crop_name,expected_harvest_date', 'farmOutput:id,product_name,harvest_date'])
            ->orderBy('expected_harvest_date')
            ->get();

        $data = $listings->map(fn (PreOrderListing $l) => $this->listingToProduct($l));

        return response()->json(['data' => $data]);
    }

    /**
     * Single listing by ID (for product detail sync).
     */
    public function showListing(PreOrderListing $listing): JsonResponse
    {
        if (! $listing->is_active) {
            return response()->json(['message' => 'Listing not available'], 404);
        }

        $listing->load(['farmer:id,name,farm_name,location', 'crop', 'farmOutput']);

        return response()->json(['data' => $this->listingToProduct($listing)]);
    }

    /**
     * Webhook: create a pre-order when WooCommerce order is placed.
     * Body: listing_id (or woocommerce_product_id), quantity, woocommerce_order_id, customer_name, customer_email, customer_address, notes
     */
    public function storeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'listing_id' => ['required_without:woocommerce_product_id', 'nullable', 'integer', 'exists:pre_order_listings,id'],
            'woocommerce_product_id' => ['required_without:listing_id', 'nullable', 'string'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'woocommerce_order_id' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $listing = null;
        if (! empty($validated['listing_id'])) {
            $listing = PreOrderListing::where('id', $validated['listing_id'])->where('is_active', true)->first();
        }
        if (! $listing && ! empty($validated['woocommerce_product_id'])) {
            $listing = PreOrderListing::where('woocommerce_product_id', $validated['woocommerce_product_id'])->where('is_active', true)->first();
        }

        if (! $listing) {
            return response()->json(['message' => 'Listing not found or inactive'], 404);
        }

        $quantity = (float) $validated['quantity'];
        $available = $listing->available_to_sell;
        if ($quantity > $available) {
            return response()->json([
                'message' => "Insufficient quantity. Available: {$available} {$listing->unit}",
            ], 422);
        }

        $preOrder = PreOrder::create([
            'pre_order_listing_id' => $listing->id,
            'quantity' => $quantity,
            'status' => PreOrder::STATUS_PENDING,
            'woocommerce_order_id' => $validated['woocommerce_order_id'] ?? null,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pre-order created',
            'data' => [
                'id' => $preOrder->id,
                'listing_id' => $listing->id,
                'quantity' => $preOrder->quantity,
                'status' => $preOrder->status,
            ],
        ], 201);
    }

    private function listingToProduct(PreOrderListing $l): array
    {
        $harvestDate = $l->expected_harvest_date
            ?? $l->crop?->expected_harvest_date
            ?? $l->farmOutput?->harvest_date;

        return [
            'id' => $l->id,
            'woocommerce_product_id' => $l->woocommerce_product_id,
            'title' => $l->product_name,
            'quantity_available' => (float) $l->quantity_available,
            'available_to_sell' => $l->available_to_sell,
            'unit' => $l->unit,
            'price_per_unit' => $l->price_per_unit ? (float) $l->price_per_unit : null,
            'expected_harvest_date' => $harvestDate?->format('Y-m-d'),
            'farmer' => [
                'id' => $l->farmer->id,
                'name' => $l->farmer->name,
                'farm_name' => $l->farmer->farm_name,
                'location' => $l->farmer->location,
            ],
        ];
    }
}
