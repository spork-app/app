<?php

namespace Spork\Maintenance;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class MaintenanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        Spork::addFeature('Garage', 'TruckIcon', '/maintenance/garage');
        Spork::addFeature('Properties', 'HomeIcon', '/maintenance/properties');
    }
}
