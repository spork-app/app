<?php

namespace Spork\Calendar;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class CalendarServiceProvider extends ServiceProvider
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
        Spork::addFeature('calendar', 'CalendarIcon', '/calendar');
    }
}