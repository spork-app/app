<?php

namespace Spork\Maintenance;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class MaintenanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('Garage', 'TruckIcon', '/maintenance/garage');
        Spork::addFeature('Properties', 'HomeIcon', '/maintenance/properties');
    }
}
