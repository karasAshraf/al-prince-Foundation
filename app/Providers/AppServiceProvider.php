<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Throw an exception when a lazy-loaded relationship is accessed in non-production.
        // This ensures N+1 query regressions are caught immediately during development.
        Model::preventLazyLoading(! app()->isProduction());

        // Share settings globally across all views and components
        try {
            $companyInfo = \App\Models\Setting::group('company_info');
            $socialLinks = \App\Models\Setting::group('social_links');
        } catch (\Throwable $e) {
            $companyInfo = [];
            $socialLinks = [];
        }

        \Illuminate\Support\Facades\View::share('companyInfo', $companyInfo);
        \Illuminate\Support\Facades\View::share('socialLinksSettings', $socialLinks);
    }
}
