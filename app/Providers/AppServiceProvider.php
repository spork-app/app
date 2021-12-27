<?php

namespace App\Providers;

use Google\Client;
use Google\Service\CustomSearchAPI;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
            $client->setSporklicationName('APP_NAME');
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
        //
    }
}
