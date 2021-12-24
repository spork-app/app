<?php

namespace App\Finance;

use App\Finance\Contracts\Services\PlaidServiceContract;
use App\Finance\Services\PlaidService;
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
    }
}