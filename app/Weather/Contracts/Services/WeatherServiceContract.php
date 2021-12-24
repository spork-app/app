<?php

namespace App\Weather\Contracts\Services;

use App\Weather\Models\Forecast;

interface WeatherServiceContract
{
    public function query(string $address): array;
}
