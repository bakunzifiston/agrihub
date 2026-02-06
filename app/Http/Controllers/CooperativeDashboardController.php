<?php

namespace App\Http\Controllers;

use App\Services\FeatureService;
use Illuminate\View\View;

class CooperativeDashboardController extends Controller
{
    public function __construct(
        protected FeatureService $featureService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $kpis = [];
        $charts = [];

        if ($this->featureService->isEnabled($user, 'member_management')) {
            $kpis['members'] = [
                'label' => 'Active Members',
                'value' => $user->cooperativeMembers()->where('status', 'active')->count(),
            ];
        }

        $last6MonthLabels = collect();
        for ($i = 5; $i >= 0; $i--) {
            $last6MonthLabels->put(now()->subMonths($i)->format('M Y'), 0);
        }

        if ($this->featureService->isEnabled($user, 'collection_aggregation')) {
            $totalCollected = $user->produceCollections()->sum('total_value');
            $kpis['total_collected'] = [
                'label' => 'Total Produce Collected',
                'value' => number_format($totalCollected, 2),
                'format' => 'currency',
            ];
            $collectionsByMonth = $user->produceCollections()
                ->where('collection_date', '>=', now()->subMonths(6)->startOfMonth())
                ->get()
                ->groupBy(fn ($c) => $c->collection_date->format('M Y'))
                ->map(fn ($g) => (float) $g->sum(fn ($c) => (float) ($c->total_value ?? ($c->quantity_collected * ($c->price_per_unit ?? 0)))));
            $collectionsByMonth = $last6MonthLabels->merge($collectionsByMonth)->sortKeys();
            $charts[] = [
                'id' => 'chart-collections',
                'title' => 'Collections (Last 6 Months)',
                'type' => 'bar',
                'labels' => $collectionsByMonth->keys()->all(),
                'datasets' => [
                    ['label' => 'Value', 'data' => $collectionsByMonth->values()->all(), 'backgroundColor' => '#1D293D'],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'cooperative_inventory')) {
            $kpis['inventory_items'] = [
                'label' => 'Inventory Items',
                'value' => $user->cooperativeInventory()->count(),
            ];
        }

        if ($this->featureService->isEnabled($user, 'payments_to_farmers')) {
            $totalPayouts = $user->cooperativePayments()->sum('amount_paid');
            $kpis['total_payouts'] = [
                'label' => 'Total Payouts',
                'value' => number_format($totalPayouts, 2),
                'format' => 'currency',
            ];
            $paymentsByMonth = $user->cooperativePayments()
                ->where('payment_date', '>=', now()->subMonths(6)->startOfMonth())
                ->get()
                ->groupBy(fn ($p) => $p->payment_date->format('M Y'))
                ->map(fn ($g) => (float) $g->sum('amount_paid'));
            $paymentsByMonth = (clone $last6MonthLabels)->merge($paymentsByMonth)->sortKeys();
            $charts[] = [
                'id' => 'chart-payments',
                'title' => 'Payments to Farmers (Last 6 Months)',
                'type' => 'line',
                'labels' => $paymentsByMonth->keys()->all(),
                'datasets' => [
                    ['label' => 'Amount', 'data' => $paymentsByMonth->values()->all(), 'borderColor' => '#1D293D', 'fill' => true, 'tension' => 0.3],
                ],
            ];
        }

        if ($this->featureService->isEnabled($user, 'performance_analytics')) {
            $collections = $user->produceCollections()->where('collection_date', '>=', now()->subMonths(6))->get();
            $revenueTrend = $collections->groupBy(fn ($c) => $c->collection_date->format('Y-m'))
                ->map(fn ($g) => $g->sum(fn ($c) => $c->total_value ?? ($c->quantity_collected * ($c->price_per_unit ?? 0))))
                ->sortKeys()
                ->values();
            $avgMonthly = $revenueTrend->avg() ?: 0;
            $invCount = $user->cooperativeInventory()->count();
            $stockTurnover = $invCount > 0 && $avgMonthly > 0 ? round($avgMonthly / $invCount, 2) : null;

            $kpis['stock_turnover'] = [
                'label' => 'Stock Turnover',
                'value' => $stockTurnover !== null ? number_format($stockTurnover, 2) : '-',
            ];
        }

        return view('dashboards.cooperative', compact('kpis', 'charts'));
    }
}
