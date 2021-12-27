<?php

namespace Spork\Analytics\Http\Controllers;

use App\Http\Controllers\Controller;
use InfluxDB\Database;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class EventController extends Controller
{
    public function __invoke()
    {
        $points = [
            new InfluxDB\Point(
                'test_metric', // name of the measurement
                null, // the measurement value
                ['host' => 'server01', 'region' => 'us-west'], // optional tags
                ['cpucount' => 10], // optional additional fields
                time() // Time precision has to be set to seconds!
            )
        ];

        return InfluxDB::writePoints($points, Database::PRECISION_MICROSECONDS);
    }
}
