<?php

namespace App\Http\Controllers\Agribusiness;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Revenue: sum of contract values (quantity * price) for active contracts + distributions (simplified)
        $contractRevenue = $user->contracts()
            ->where('contract_status', 'active')
            ->get()
            ->sum(fn ($c) => ($c->contract_quantity ?? 0) * ($c->price_per_unit ?? 0));

        // Cost of goods: processing costs
        $cogs = $user->processingRecords()->sum('processing_cost');

        // Profit margin: (revenue - cogs) / revenue * 100
        $revenue = $contractRevenue;
        $profitMargin = $revenue > 0 ? round((($revenue - $cogs) / $revenue) * 100, 2) : null;

        // Supplier performance: rating, contract status
        $supplierPerformance = $user->suppliers()->get()->map(fn ($s) => (object) [
            'name' => $s->supplier_name,
            'rating' => $s->rating,
            'contract_status' => $s->contract_status,
        ]);

        // Inventory turnover total distributions / avg inventory (simplified)
        $totalDispatched = $user->distributions()->sum('quantity_dispatched');
        $avgInventory = $user->agribusinessInventory()->avg('quantity_in_stock') ?: 0;
        $inventoryTurnover = $avgInventory > 0 ? round($totalDispatched / $avgInventory, 2) : null;

        return view('agribusiness.reports.index', [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'profitMargin' => $profitMargin,
            'supplierPerformance' => $supplierPerformance,
            'inventoryTurnover' => $inventoryTurnover,
        ]);
    }
}
