<?php

namespace Spork\Finance;

use App\Spork;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Spork\Finance\Contracts\Services\PlaidServiceContract;
use Spork\Finance\Services\PlaidService;

class FinanceServiceProvider extends RouteServiceProvider
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
        Spork::addFeature('finance', 'LibraryIcon', '/finance/dashboard');
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');
    }
}