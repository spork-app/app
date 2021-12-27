<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Fortify;

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
    // protected $namespace = 'Spork\\Http\\Controllers';

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

            Fortify::createUsersUsing(CreateNewUser::class);
            Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
            Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
            Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

            RateLimiter::for('login', fn (Request $request) => Limit::perMinute(600)->by($request->email.$request->ip()));
            RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(600)->by($request->session()->get('login.id')));

            Fortify::loginView(fn() => view('auth.login'));
            Fortify::registerView(fn() => view('auth.register'));
            Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.email'));
            Fortify::resetPasswordView(fn() => view('auth.passwords.reset'));
            Fortify::confirmPasswordView(fn() => view('auth.passwords.confirm'));

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
