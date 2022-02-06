<?php

namespace Spork\Greenhouse;

use App\Spork;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NetworkServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('Network', 'RssIcon', '/network');

        // Spork::actions('Network' , __DIR__ . '/Actions');
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        Route::middleware($this->app->make('config')->get('spork.network.middleware', ['auth:sanctum']))
            ->prefix('api/network')
            ->group(__DIR__ . '/../routes/web.php');
    }
}
