<?php

namespace Spork\Analytics;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register() 
    {
        Route::group(__DIR__.'/../routes/web.php');
    }
}