<?php

namespace App\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::post('webhooks/events', function (Request $request) {
                info($request->get('status'), $request->all());

                return $request->all();
            });
            Route::post('webhooks/answer', function (Request $request) {
                return response()->json([
                    [
                        "action" => "talk",
                        "text" => "Janet here, connecting you now"
                    ],
                    [
                        "action" => "connect",
                        "from" => $request->get('from'),
                        "endpoint" => [
                            [
                                "type" => "websocket",
                                "uri" => sprintf("wss://%s/socket", 'voice.kregel.cloud'),
                                "content-type" => "audio/l16;rate=16000",
                                "headers" => $request->all(),
                            ]
                        ]
                    ]
                ]);
            });

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
