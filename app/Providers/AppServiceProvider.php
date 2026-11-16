<?php

namespace App\Providers;

use App\Models\PlayerApplication;
use App\Models\SiteVisit;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('admin.layout', function ($view) {
            $view->with('visitorsToday', SiteVisit::todayCount());
            $view->with('visitorsTotal', SiteVisit::totalCount());
            $view->with('applicationsCount', PlayerApplication::count());
        });
    }
}
