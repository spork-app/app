<?php

namespace Spork\Finance;

use App\Spork;
use Spork\Finance\Contracts\Services\PlaidServiceContract;
use Spork\Finance\Services\PlaidService;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PlaidServiceContract::class, PlaidService::class);
        Spork::addFeature('finance', 'LibraryIcon', '/finance');
    }
}