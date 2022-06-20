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

Artisan::command('install', function () {
    // Install new composer deps
    $this->info("Thank you for installing Spork! Let us now install some dependencies to give this framework legs!");
    //guzzle fetch packagist tag
    $client = new Client;
    $response = $client->get('https://packagist.org/search.json?tags=spork%20app');
    $data = json_decode($response->getBody()->getContents());

    if ($data->total == 0) {
        $this->error("No packages found. Please try again.");
        return;
    }
    
    $this->info("Found " . $data->total . " packages.");
    
    $packages = array_map(fn ($item) => $item->name, $data->results);

    $selectedPackages = $this->choice(
        'Which packages would you like to install?', 
        $packages,
        0,
        1,
        true
    );
    
    $composer = 'composer require '.implode(' ', $selectedPackages);
    $output = shell_exec($composer);
    echo $output;
});