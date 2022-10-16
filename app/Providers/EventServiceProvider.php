<?php

namespace App\Providers;

use Spork\Core\Events;
use Spork\Core\Events\FeatureCreated;
use Spork\Core\Events\FeatureDeleted;
use Spork\Core\Events\FeatureUpdated;
use Spork\Core\Events\Spork\ActionRegistered;
use Spork\Core\Events\Spork\AssetPublished;
use Spork\Core\Events\Spork\FeatureRegistered;
use Spork\Core\Models\FeatureList;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use BeyondCode\LaravelWebSockets\Events\NewConnection;
use BeyondCode\LaravelWebSockets\Events\WebSocketMessageReceived;
use BeyondCode\LaravelWebSockets\Events\ConnectionPonged;
use BeyondCode\LaravelWebSockets\Events\ConnectionClosed;

class EventServiceProvider extends ServiceProvider
{
    public const ELOQUENT_UPDATED = 'eloquent.updated: ';

    public const ELOQUENT_UPDATING = 'eloquent.updating: ';

    public const ELOQUENT_CREATED = 'eloquent.created: ';

    public const ELOQUENT_CREATING = 'eloquent.creating: ';

    public const ELOQUENT_DELETED = 'eloquent.deleted: ';

    public const ELOQUENT_DELETING = 'eloquent.deleting: ';

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AccountUpdateRequested::class => [
            SyncTransactionsForAccessTokenListener::class,
        ],

        // Spork Events
        FeatureRegistered::class => [],
        AssetPublished::class => [],
        ActionRegistered::class => [],

        FeatureCreated::class => [
            //
        ],
        FeatureUpdated::class => [
            //
        ],
        FeatureDeleted::class => [
            //
        ],
        // Websocket
        ConnectionClosed::class => [],
        NewConnection::class => [],
        WebSocketMessageReceived::class => [],
        ConnectionPonged::class => [],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
