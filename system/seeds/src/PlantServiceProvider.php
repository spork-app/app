<?php

namespace Spork\Seeds;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class PlantServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('Seeds', 'SparklesIcon', '/seeds');
    }
}
