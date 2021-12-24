<?php

namespace App\Core\Providers;

use App\Core\Models\FeatureList;
use App\Core\Models\Property;
use App\Finance\Models\Account;
use App\Finance\Models\Transaction;
use App\Garage\Models\Vehicle;
use App\Calendar\Models\Calendar;
use App\Core\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\Exceptions\ModelNotInstanceOfAbstractEloquentModel;

class AbstractRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        abstracted()
            ->bypass(true)
            ->middleware(['web'])
            ->route([
                'users' => User::class,
                'vehicles' => Vehicle::class,
                'properties' => Property::class,
                'feature-list' => FeatureList::class,
                'account' => Account::class,
                'transaction' => Transaction::class,
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
