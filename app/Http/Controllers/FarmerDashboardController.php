<?php

namespace App\Http\Controllers;

use App\Services\FeatureService;
use Illuminate\View\View;

class FarmerDashboardController extends Controller
{
    public function __construct(
        protected FeatureService $featureService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $kpis = [];
        $charts = [];

        $farmProfile = null;
        if ($this->featureService->isEnabled($user, 'farm_profile')) {
            $kpis['farm_profile'] = [
                'label' => 'Farm Profiles',
                'value' => $user->farmProfiles()->count(),
            ];
            $farmProfile = $user->farmProfiles()->with('plots')->latest()->first();
        }

        if ($this->featureService->isEnabled($user, 'crop_livestock_tracking')) {
            $kpis['crops'] = [
                'label' => 'Crops',
                'value' => $user->crops()->count(),
            ];
            $kpis['livestock'] = [
                'label' => 'Livestock',
                'value' => $user->livestock()->count(),
            ];
            $cropsCount = $user->crops()->count();
            $livestockCount = $user->livestock()->count();
            $charts[] = [
                'id' => 'chart-crops-livestock',
                'title' => 'Crops vs Livestock',
                'type' => 'doughnut',
                'labels' => ['Crops', 'Livestock'],
                'datasets' => [
                    ['data' => [$cropsCount, $livestockCount], 'backgroundColor' => ['#1D293D', '#4ade80']],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'production_records')) {
            $kpis['production_records'] = [
                'label' => 'Production Records',
                'value' => $user->productionRecords()->count(),
            ];
        }

        if ($this->featureService->isEnabled($user, 'inventory')) {
            $kpis['inputs'] = [
                'label' => 'Input Items',
                'value' => $user->farmInputs()->count(),
            ];
            $kpis['outputs'] = [
                'label' => 'Output Items',
                'value' => $user->farmOutputs()->count(),
            ];
        }

        if ($this->featureService->isEnabled($user, 'sales_income')) {
            $totalSales = $user->farmSales()->sum('total_amount');
            $kpis['total_sales'] = [
                'label' => 'Total Sales',
                'value' => number_format($totalSales, 2),
                'format' => 'currency',
            ];
            $last6Months = collect();
            for ($i = 5; $i >= 0; $i--) {
                $last6Months->put(now()->subMonths($i)->format('M Y'), 0);
            }
            $salesByMonth = $user->farmSales()
                ->where('sale_date', '>=', now()->subMonths(6)->startOfMonth())
                ->get()
                ->groupBy(fn ($s) => $s->sale_date->format('M Y'))
                ->map(fn ($g) => (float) $g->sum('total_amount'));
            $salesByMonth = $last6Months->merge($salesByMonth)->sortKeys();
            $charts[] = [
                'id' => 'chart-sales',
                'title' => 'Sales (Last 6 Months)',
                'type' => 'bar',
                'labels' => $salesByMonth->keys()->all(),
                'datasets' => [
                    ['label' => 'Sales', 'data' => $salesByMonth->values()->all(), 'backgroundColor' => '#1D293D'],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'reports')) {
            $totalIncome = $user->farmSales()->sum('total_amount');
            $kpis['total_income'] = [
                'label' => 'Total Income',
                'value' => number_format($totalIncome, 2),
                'format' => 'currency',
            ];
        }

        return view('dashboards.farmer', compact('kpis', 'charts', 'farmProfile'));
    }
}
