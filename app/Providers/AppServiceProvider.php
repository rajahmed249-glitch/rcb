<?php

namespace App\Providers;

use App\Models\PlayerApplication;
use App\Models\VisitorLog;
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
            $view->with('visitorsToday', VisitorLog::todayCount());
            $view->with('visitorsTotal', VisitorLog::totalCount());
            $view->with('applicationsCount', PlayerApplication::count());
        });
    }
}
