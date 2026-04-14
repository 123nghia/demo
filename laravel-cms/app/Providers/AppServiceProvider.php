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
            $siteAboutMenuTree = collect();
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

            try {
                $siteAboutMenuTree = MenuItem::query()
                    ->active()
                    ->inZone(MenuItem::ZONE_ABOUT_US)
                    ->topLevel()
                    ->with([
                        'children' => function ($query) {
                            $query->active()->ordered();
                        },
                    ])
                    ->ordered()
                    ->get();
            } catch (\Throwable $exception) {
                // Keep empty tree and let About Us page fallback to static menu.
            }

            $view
                ->with('siteSettings', $siteSettings)
                ->with('siteMenuItems', $siteMenuItems)
                ->with('siteAboutMenuTree', $siteAboutMenuTree)
                ->with('aboutContent', $aboutContent)
                ->with('homeContent', $homeContent);
        });
    }
}
