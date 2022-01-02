<?php

namespace App\Providers;

use App\Models\FeatureList;
use Spork\Finance\Models\Account;
use Spork\Finance\Models\Transaction;
use App\Garage\Models\Vehicle;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\Exceptions\ModelNotInstanceOfAbstractEloquentModel;
use App\Models\ActivityLog;
use Spork\Greenhouse\Models\Seed;
use Spork\Greenhouse\Models\Plant;

class AbstractRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        abstracted()
            ->bypass(true)
            ->middleware(['api', 'auth:sanctum'])
            ->route([
                'users' => User::class,
                'vehicles' => Vehicle::class,
                'feature-list' => FeatureList::class,
                'account' => Account::class,
                'transaction' => Transaction::class,
                'plants' => Plant::class,
                'seeds' => Seed::class,
                'activity-logs' => ActivityLog::class,
            ]);

        Route::bind('abstract_model', abstracted()->resolveModelsUsing ?? function ($value) {
            $class = abstracted()->route($value);

            $model = new $class;

            throw_if(!$model instanceof AbstractEloquentModel, ModelNotInstanceOfAbstractEloquentModel::class);

            return $model;
        });

        Route::bind('abstract_model_id', function ($value) {
            $model = request()->route("abstract_model");

            return $model::findOrFail($value);
        });
    }

    public function map()
    {
        if (abstracted()->usingRoutes) {
            $this->mapRoutes();
        }
    }

    protected function mapRoutes()
    {
        Route::middleware(abstracted()->middlewareGroup)
            ->namespace('Kregel\LaravelAbstract\Http\Controllers')
            ->group(function () {
                Route::get('api/{abstract_model}', 'AbstractResourceController@index');
                Route::post('api/{abstract_model}', 'AbstractResourceController@store');
                Route::get('api/{abstract_model}/{abstract_model_id}', 'AbstractResourceController@show');
                // Updating
                Route::put('api/{abstract_model}/{abstract_model_id}', 'AbstractResourceController@update');
                Route::patch('api/{abstract_model}/{abstract_model_id}', 'AbstractResourceController@update');
                // Restoring
                Route::post('api/{abstract_model}/{abstract_model_id}/restore', 'AbstractResourceController@restore');
                // Soft-deleting
                Route::delete('api/{abstract_model}/{abstract_model_id}', 'AbstractResourceController@destroy');
                // Force delete
                Route::delete('api/{abstract_model}/{abstract_model_id}/force', 'AbstractResourceController@forceDestroy');
            });
    }
}
