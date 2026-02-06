<?php

namespace App\Providers;

use App\Models\User;
use App\Services\FeatureService;
use App\Services\TenantSidebarService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FeatureService::class, fn () => new FeatureService);
        $this->app->singleton(TenantSidebarService::class, fn () => new TenantSidebarService);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('feature', function (string $featureKey) {
            $user = auth()->user();
            if (! $user) {
                return false;
            }
            return app(FeatureService::class)->isEnabled($user, $featureKey);
        });

        View::composer(['layouts.app', 'layouts.partials.tenant-sidebar'], function ($view) {
            $user = auth()->user();
            if ($user && in_array($user->tenant_type, [User::TENANT_FARMER, User::TENANT_COOPERATIVE, User::TENANT_AGRIBUSINESS])) {
                $view->with('sidebarMenu', app(TenantSidebarService::class)->getSidebarForUser($user));
            } else {
                $view->with('sidebarMenu', []);
            }
        });
    }
}
