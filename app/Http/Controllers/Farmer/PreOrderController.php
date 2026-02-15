<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use Illuminate\View\View;

class PreOrderController extends Controller
{
    /**
     * List pre-orders for the farmer's listings (from marketplace/WooCommerce).
     */
    public function index(): View
    {
        $preOrders = PreOrder::query()
            ->whereHas('preOrderListing', fn ($q) => $q->where('farmer_id', auth()->id()))
            ->with('preOrderListing:id,title,unit,farmer_id')
            ->latest()
            ->paginate(20);

        return view('farmer.pre-orders.index', compact('preOrders'));
    }
}
