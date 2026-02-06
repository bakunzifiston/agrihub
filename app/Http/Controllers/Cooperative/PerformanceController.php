<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $totalCollected = $user->produceCollections()->sum('total_value');
        $memberCount = $user->cooperativeMembers()->where('status', 'active')->count();
        $totalPayouts = $user->cooperativePayments()->sum('amount_paid');

        // Revenue trends: monthly collection totals (last 6 months)
        $collections = $user->produceCollections()
            ->where('collection_date', '>=', now()->subMonths(6))
            ->get();
        $revenueTrends = $collections->groupBy(fn ($c) => $c->collection_date->format('Y-m'))
            ->map(fn ($group) => (object) [
                'month' => $group->first()->collection_date->format('Y-m'),
                'total' => $group->sum(fn ($c) => $c->total_value ?? ($c->quantity_collected * ($c->price_per_unit ?? 0))),
            ])
            ->sortBy('month')
            ->values();

        // Stock turnover: avg monthly collections / inventory items (simplified ratio)
        $inventoryCount = $user->cooperativeInventory()->count();
        $avgMonthly = $revenueTrends->avg('total') ?: 0;
        $stockTurnover = $inventoryCount > 0 && $avgMonthly > 0
            ? round($avgMonthly / $inventoryCount, 2)
            : null;

        return view('cooperative.performance.index', [
            'totalCollected' => $totalCollected,
            'memberCount' => $memberCount,
            'totalPayouts' => $totalPayouts,
            'revenueTrends' => $revenueTrends,
            'stockTurnover' => $stockTurnover,
        ]);
    }
}
