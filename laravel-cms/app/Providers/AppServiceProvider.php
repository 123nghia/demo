<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('site.layouts.app', function ($view) {
            $siteSettings = SiteSetting::defaults();
            $siteMenuItems = collect();
            $aboutContent = SiteSetting::aboutContentDefaults();
            $homeContent = SiteSetting::homeContentDefaults();

            try {
                $siteSettings = SiteSetting::allAsArray();
            } catch (\Throwable $exception) {
                // Keep defaults if DB is not ready.
            }

            try {
                $aboutContent = SiteSetting::aboutContent();
            } catch (\Throwable $exception) {
                // Keep default about content if DB is not ready.
            }

            try {
                $homeContent = SiteSetting::homeContent();
            } catch (\Throwable $exception) {
                // Keep default home content if DB is not ready.
            }

            try {
                $siteMenuItems = MenuItem::query()
                    ->active()
                    ->inZone(MenuItem::ZONE_MAIN)
                    ->topLevel()
                    ->ordered()
                    ->get();
            } catch (\Throwable $exception) {
                // Keep an empty menu and let Blade fallback render defaults.
            }

            $view
                ->with('siteSettings', $siteSettings)
                ->with('siteMenuItems', $siteMenuItems)
                ->with('aboutContent', $aboutContent)
                ->with('homeContent', $homeContent);
        });
    }
}
