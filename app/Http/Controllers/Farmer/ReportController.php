<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Period filter: month, quarter, year, all
        $period = $request->get('period', 'all');
        $productionQuery = $user->productionRecords();
        $salesQuery = $user->farmSales();
        $inputQuery = $user->farmInputs();

        if ($period !== 'all') {
            $dates = $this->getPeriodDates($period);
            if ($dates) {
                $productionQuery = $productionQuery->whereBetween('production_date', $dates);
                $salesQuery = $salesQuery->whereBetween('sale_date', $dates);
                $inputQuery = $inputQuery->whereBetween('purchase_date', $dates);
            }
        }

        $productionTotal = $productionQuery->sum('quantity_produced');
        $salesTotal = $salesQuery->sum('total_amount');
        $inputCost = $inputQuery->sum('total_cost');

        // Yield comparison per season (crops: expected vs production records by season)
        $yieldBySeason = $user->crops()
            ->select('season', DB::raw('SUM(expected_yield) as expected_total'))
            ->whereNotNull('season')
            ->where('season', '!=', '')
            ->groupBy('season')
            ->get()
            ->keyBy('season');

        $productionByProduct = $user->productionRecords()
            ->select('product_name', DB::raw('SUM(quantity_produced) as produced'))
            ->groupBy('product_name')
            ->get()
            ->keyBy('product_name');

        // Map crop season to production (use product_name from production to match crops)
        $yieldComparison = [];
        foreach ($yieldBySeason as $season => $row) {
            $cropsInSeason = $user->crops()->where('season', $season)->get();
            $expectedTotal = $cropsInSeason->sum('expected_yield');
            $actualTotal = 0;
            foreach ($cropsInSeason as $crop) {
                $actualTotal += $productionByProduct->get($crop->crop_name)?->produced ?? 0;
            }
            $yieldComparison[] = [
                'season' => $season,
                'expected' => $expectedTotal,
                'actual' => $actualTotal,
            ];
        }

        // If no season-based data, show production by product as fallback
        if (empty($yieldComparison)) {
            $yieldComparison = $user->productionRecords()
                ->select('product_name', DB::raw('SUM(quantity_produced) as produced'))
                ->groupBy('product_name')
                ->get()
                ->map(fn ($r) => [
                    'season' => $r->product_name,
                    'expected' => 0,
                    'actual' => $r->produced,
                ])
                ->values()
                ->all();
        }

        return view('farmer.reports.index', [
            'productionTotal' => $productionTotal,
            'salesTotal' => $salesTotal,
            'inputCost' => $inputCost,
            'outputRevenue' => $salesTotal, // Output = sales revenue
            'yieldComparison' => $yieldComparison,
            'period' => $period,
        ]);
    }

    private function getPeriodDates(string $period): ?array
    {
        $now = now();
        return match ($period) {
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'quarter' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => null,
        };
    }
}
