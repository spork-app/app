<?php

namespace App\Providers;

use Google\Client;
use Google\Service\CustomSearchAPI;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function () {
            $client = new Client();
            $client->setApplicationName('APP_NAME');
            $client->setDeveloperKey(env('GOOGLE_SEARCH_API_KEY'));
            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('*', function ($eventName, array $data) {
            if (in_array($eventName, [
                'Illuminate\\Log\\Events\\MessageLogged',
                'Illuminate\\Redis\\Events\\CommandExecuted',
                'Illuminate\Cache\Events\CacheHit',
                'Illuminate\Cache\Events\KeyWritten',
                'Illuminate\Database\Events\StatementPrepared',
                'Illuminate\Database\Events\QueryExecuted',
            ])) {
                return;
            }

            if (Str::startsWith($eventName, [
                'Illuminate\\Cache',
                'Illuminate\\Database', 
                'Illuminate\\Redis', 
                'Illuminate\\Queue', 
                'bootstrapped', 
                'Illuminate\\Auth', 
                'Illuminate\\Routing', 
                'composing', 
                'creating', 
                'composed',
                'eloquent.',
                "Illuminate\\Foundation",
                'Laravel\\Horizon',
                'BeyondCode\LaravelWebSockets',
            ])) {
                return;
            }

            // if ($eventName === 'Illuminate\Foundation\Http\Events\RequestHandled') {
                info(sprintf('Event: [%s]', $eventName), $data);        
            // }

        });
        
    }
}
