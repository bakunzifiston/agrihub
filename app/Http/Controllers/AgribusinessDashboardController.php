<?php

namespace App\Http\Controllers;

use App\Services\FeatureService;
use Illuminate\View\View;

class AgribusinessDashboardController extends Controller
{
    public function __construct(
        protected FeatureService $featureService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $kpis = [];
        $charts = [];

        if ($this->featureService->isEnabled($user, 'supplier_management')) {
            $kpis['suppliers'] = [
                'label' => 'Suppliers',
                'value' => $user->suppliers()->count(),
            ];
        }

        if ($this->featureService->isEnabled($user, 'procurement_contracts')) {
            $activeContracts = $user->contracts()->where('contract_status', 'active')->count();
            $contractValue = $user->contracts()->where('contract_status', 'active')->get()
                ->sum(fn ($c) => ($c->contract_quantity ?? 0) * ($c->price_per_unit ?? 0));
            $kpis['active_contracts'] = [
                'label' => 'Active Contracts',
                'value' => $activeContracts,
            ];
            $kpis['contract_value'] = [
                'label' => 'Contract Value',
                'value' => number_format($contractValue, 2),
                'format' => 'currency',
            ];
        }

        $last6MonthLabels = collect();
        for ($i = 5; $i >= 0; $i--) {
            $last6MonthLabels->put(now()->subMonths($i)->format('M Y'), 0);
        }

        if ($this->featureService->isEnabled($user, 'inventory_distribution')) {
            $kpis['inventory_items'] = [
                'label' => 'Inventory Items',
                'value' => $user->agribusinessInventory()->count(),
            ];
            $kpis['distributions'] = [
                'label' => 'Distributions',
                'value' => $user->distributions()->count(),
            ];
            $distByMonth = $user->distributions()
                ->where('dispatch_date', '>=', now()->subMonths(6)->startOfMonth())
                ->get()
                ->groupBy(fn ($d) => $d->dispatch_date->format('M Y'))
                ->map(fn ($g) => (float) $g->sum('quantity_dispatched'));
            $distByMonth = $last6MonthLabels->merge($distByMonth)->sortKeys();
            $charts[] = [
                'id' => 'chart-distributions',
                'title' => 'Dispatched Quantity (Last 6 Months)',
                'type' => 'bar',
                'labels' => $distByMonth->keys()->all(),
                'datasets' => [
                    ['label' => 'Quantity', 'data' => $distByMonth->values()->all(), 'backgroundColor' => '#1D293D'],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'processing_production')) {
            $processingCost = $user->processingRecords()->sum('processing_cost');
            $kpis['processing_records'] = [
                'label' => 'Processing Records',
                'value' => $user->processingRecords()->count(),
            ];
            $kpis['processing_cost'] = [
                'label' => 'Total Processing Cost',
                'value' => number_format($processingCost, 2),
                'format' => 'currency',
            ];
            $processingByMonth = $user->processingRecords()
                ->where('processing_date', '>=', now()->subMonths(6)->startOfMonth())
                ->get()
                ->groupBy(fn ($r) => $r->processing_date->format('M Y'))
                ->map(fn ($g) => (float) $g->sum('processing_cost'));
            $processingByMonth = (clone $last6MonthLabels)->merge($processingByMonth)->sortKeys();
            $charts[] = [
                'id' => 'chart-processing',
                'title' => 'Processing Cost (Last 6 Months)',
                'type' => 'line',
                'labels' => $processingByMonth->keys()->all(),
                'datasets' => [
                    ['label' => 'Cost', 'data' => $processingByMonth->values()->all(), 'borderColor' => '#1D293D', 'fill' => true, 'tension' => 0.3],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'sales_financial_reports')) {
            $revenue = $user->contracts()->where('contract_status', 'active')->get()
                ->sum(fn ($c) => ($c->contract_quantity ?? 0) * ($c->price_per_unit ?? 0));
            $cogs = $user->processingRecords()->sum('processing_cost');
            $profitMargin = $revenue > 0 ? round((($revenue - $cogs) / $revenue) * 100, 1) : null;

            $kpis['revenue'] = [
                'label' => 'Revenue',
                'value' => number_format($revenue, 2),
                'format' => 'currency',
            ];
            $kpis['profit_margin'] = [
                'label' => 'Profit Margin',
                'value' => $profitMargin !== null ? $profitMargin . '%' : '-',
            ];
        }

        return view('dashboards.agribusiness', compact('kpis', 'charts'));
    }
}
