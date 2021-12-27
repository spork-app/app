<?php

namespace Spork\Planning;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class PlanningServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('planning', 'ViewBoardsIcon', '/planning');
    }
}
