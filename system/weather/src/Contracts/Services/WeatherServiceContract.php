<?php

namespace Spork\Weather\Contracts\Services;

use Spork\Weather\Models\Forecast;

interface WeatherServiceContract
{
    public function query(string $address): array;
}
