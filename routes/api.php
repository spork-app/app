<?php

use App\Core\Models\FeatureList;
use App\Finance\Models\Account;
use App\Finance\Services\PlaidService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api', 'auth:sanctum'])->get('/user', fn () => auth()->user());

Route::middleware(['api', 'auth:sanctum'])->get('weather', function (\Spork\Weather\Contracts\Services\WeatherServiceContract $weatherService) {
    $propertu = \App\Core\Models\Property::first();

    return $weatherService->query($propertu->address);
});
Route::middleware(['api', 'auth:sanctum'])->get('news', function (\Spork\News\Contracts\Service\NewsServiceContract $service) {
    return $service->headlines('', request()->get('category', null));
});

