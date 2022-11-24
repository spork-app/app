<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['web'])->get('/login', fn () => \Socialite::driver('laravelpassport')->scopes(['openid', 'profile', 'email'])->redirect())->name('login');
Route::middleware(['web'])->get('/redirect', function () {
    if (request()->get('error')) {
        return response()->json([
            'error' => request()->get('error'),
        ], 400);
    }

    try {
        $user = \Socialite::driver('laravelpassport')->stateless()->user();

        $client = new GuzzleHttp\Client;
        $userInfo = json_decode($client->get(env('LARAVELPASSPORT_HOST').'/api/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer '.$user->token,
            ],
        ])->getBody()->getContents(), true);
        $user->map($userInfo);
        $user->map(['avatar' => $userInfo['photo_url']]);
        $user->setRaw($userInfo);
    } catch (InvalidStateException $exception) {
        dd($exception);
    }
    $localUser = User::firstWhere('socialite_id', $user->id);

    if (empty($localUser)) {
        $localUser = User::create([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'socialite_id' => $user->id,
            'password' => 'unused',
        ]);
    }

    auth()->login($localUser);

    return redirect('dashboard');
})->name('verification.verify');

Route::middleware(['web'])->get('/logout', function () {
    auth()->logout();

    return redirect('/login');
});
Route::middleware(['web'])->get('/setup', fn () => view('setup'));

Route::get('/{uri?}/{subPath?}', fn () => view('welcome'))
    ->middleware('auth:sanctum')
    ->where('uri', '^((?!(api|public)).)*')
    ->where('subPath', '.*');
