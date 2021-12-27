<?php

namespace Spork\Research;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class ResearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('research', 'BeakerIcon', '/research');
    }
}
