<?php

namespace App\Weather\Providers;

use App\Weather\Contracts\Services\WeatherServiceContract;
use App\Weather\Service\WeatherService;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(WeatherServiceContract::class, WeatherService::class);
    }
}
