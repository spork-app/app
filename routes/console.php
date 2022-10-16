<?php

use GuzzleHttp\Client;
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

Artisan::command('install', function () {
    // Install new composer deps
    $this->info('Thank you for installing Spork! Let us now install some dependencies to give this framework legs!');
    //guzzle fetch packagist tag
    $client = new Client;
    $response = $client->get('https://packagist.org/search.json?tags=spork-plugins');
    $data = json_decode($response->getBody()->getContents());

    if ($data->total == 0) {
        $this->error('No packages found. Please try again.');

        return;
    }

    $this->info('Found '.$data->total.' packages.');

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
