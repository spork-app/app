<?php

use aalfiann\MyAnimeList;
use App\Core\Models\FeatureList;
use App\Finance\Contracts\Services\PlaidServiceContract;
use Carbon\Carbon;
use Faker\Generator;
use Google\Service\CustomSearchAPI;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test', function () {
    $feature = FeatureList::find(2);
    $getTransactions = app(PlaidServiceContract::class)->getTransactions($feature, now()->subDays(30), now());

    dd($getTransactions);
});
Artisan::command('test2', function () {
    $feature = FeatureList::with('accounts')->find(2);

    dd($feature->accounts);
});
