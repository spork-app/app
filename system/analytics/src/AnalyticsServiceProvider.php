<?php

namespace Spork\Analytics;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [],
    ];


    public function boot()
    {

    }

    public function register() 
    {
        Route::group(__DIR__.'/../routes/web.php');
    }
}