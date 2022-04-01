<?php

use App\Models\FeatureList;
use App\Finance\Contracts\Services\PlaidServiceContract;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator;
use Google\Service\CustomSearchAPI;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use RRule\RRule;
use Spork\Finance\Events\AccountUpdateRequested;
use Spork\Wiretap\Services\GithubNotificationService;

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

Artisan::command('test', function () {
    event(new AccountUpdateRequested(FeatureList::find(72)));
});

Artisan::command('test2', function () {
    $service = new GithubNotificationService;
    dd($service->findNotifications());
});

Artisan::command('test3', function () {
    dd(User::first()->createToken('personal_token'));
});

Artisan::command('populate', function () {
    $rule = new RRule([
        'FREQ' => 'DAILY',
        'DTSTART' => now(),
        'INTERVAL' => 1,
    ]);

    dd($rule->getOccurrencesBetween(Carbon::now()->startOfYear(), Carbon::now()->addYear()));
});