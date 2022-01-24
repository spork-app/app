<?php

namespace App\Providers;

use App\Events;
use App\Events\FeatureCreated;
use App\Events\FeatureDeleted;
use App\Events\FeatureUpdated;
use App\Models\FeatureList;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Spork\Finance\Events\AccountUpdateRequested;
use Spork\Finance\Events\BankLinkedEvent;
use Spork\Finance\Listeners\SyncTransactionsForAccessTokenListener;
use Spork\Finance\Models\Transaction;

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
        FeatureCreated::class => [
            SyncTransactionsForAccessTokenListener::class,
        ],
        AccountUpdateRequested::class => [
            SyncTransactionsForAccessTokenListener::class,
        ],
        FeatureDeleted::class => [],
        FeatureUpdated::class => [],
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
