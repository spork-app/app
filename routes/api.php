<?php

use App\Events\SetupCompleted;
use App\Models\FeatureList;
use App\Finance\Models\Account;
use App\Finance\Services\PlaidService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spork\Planning\Models\Status;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api', 'auth:sanctum'])->get('/user', function () {
    $user = auth()->user();
    $user->load('unreadNotifications');
    return $user;
});

Route::middleware(['api', 'auth:sanctum'])->get('weather', function (\Spork\Weather\Contracts\Services\WeatherServiceContract $weatherService) {
    $propertu = \App\Core\Models\Property::first();

    return $weatherService->query($propertu->address);
});
Route::middleware(['api', 'auth:sanctum'])->get('news', function (\Spork\News\Contracts\Service\NewsServiceContract $service) {
    return $service->headlines('', request()->get('category', null));
});

Route::middleware(['api', 'auth:sanctum'])->post('/initial-setup', function () {
    // abort_if(file_exists(storage_path('app/setup.json')), 404, 'Setup has already been run.');

    $dumper = new Nette\PhpGenerator\Dumper;
    $from = require base_path('config/spork.php');

    $env = [];

    foreach (request()->all() as $key => $value) {
        $enabled = $from[$key]['enabled'] = $value['enabled'] ?? false;

        if (!$enabled) {
            continue;
        }

        $data = request()->all();

        if ($key === 'news') {
            $env['NEWS_API_KEY'] = Arr::get($data, 'news.news_api_key');
        }

        if ($key === 'finance') {
            $env['PLAID_CLIENT_ID'] = Arr::get($data, 'finance.plaid_client_id');
            $env['PLAID_CLIENT_SECRET'] = Arr::get($data, 'finance.plaid_client_secret');
            $env['PLAID_ENVIRONMENT'] = Arr::get($data, 'finance.plaid_env');
        }
        
        if ($key === 'research') {
            $env['GOOGLE_SEARCH_API_KEY'] = Arr::get($data, 'research.google_search_api_key');
        }
    }

        /// Development
        if (Arr::get($data, 'development.enabled') && $projects = Arr::get($data, 'development.projects', null)) {
            foreach ($projects as $project) {
                $feature = FeatureList::forFeature('development')->firstWhere('name', $project['name']);
                if (empty($feature) && Arr::get($data, 'development.import', false)) {
                    FeatureList::create([
                        'name' => $project['name'],
                        'feature' => 'development',
                        'settings' => $project['settings'],
                    ]);
                }
            }
        }

        // Calendar
        if (Arr::get($data, 'calendar.enabled') && $calendar = Arr::get($data,'calendar.name', null)) {
            $feature = FeatureList::forFeature('calendar')->firstWhere('name', $calendar);
            if (empty($feature)) {
                FeatureList::create([
                    'name' => $calendar,
                    'feature' => 'calendar',
                    'settings' => Arr::get($data,'calendar.settings', null)
                ]);
            }
        }
        // Researching
        if (Arr::get($data, 'research.enabled') && $topics = Arr::get($data,'research.topics', [])) {
            foreach ($topics as $topic) {
                $feature = FeatureList::forFeature('research')->firstWhere('name', $topic);
                if (empty($feature)) {
                    FeatureList::create([
                        'name' => $topic,
                        'feature' => 'research',
                        'settings' => [],
                    ]);
                }
            }
        }

        // Planning
        if (Arr::get($data, 'planning.enabled') && $topics = Arr::get($data,'planning.statuses', [])) {
            foreach ($topics as $topic) {
                $feature = auth()->user()->statuses()->firstWhere('title', $topic);
                if (empty($feature)) {
                    auth()->user()->statuses()->create([
                        'title' => $topic,
                        'slug' => Str::slug($topic),
                        'order' => 0,
                    ]);
                }
            }
        }

        if (Arr::get($data, 'properties.enabled') && $address = Arr::get($data, 'properties.settings.address', null)) {
            FeatureList::create([
                'name' => Arr::get($data, 'properties.settings', 'home'),
                'settings' => [
                    'address' => $address,
                ]
            ]);
        }

    file_put_contents(config_path('spork.php'), '<?php' . "\n\nreturn " .$dumper->dump($from).';');

    $envFile = file_get_contents(base_path('.env'));
    $split = explode("\n", $envFile);

    foreach ($env as $envName => $value) {
        foreach ($split as $key => $line) {
            if (str_contains($line, $envName)) {
                $splitAgain = explode("=", $line, 2);
                $splitAgain[1] = $value;

                $split[$key] = implode('=', $splitAgain);
            }
        }
    }

    file_put_contents(base_path('.env'), implode("\n", $split));
    
    file_put_contents(storage_path('app/setup.json'), json_encode(request()->all()));

    event(new SetupCompleted);

    return response()->json([
        'message' => 'Setup complete.',
    ]);
});

Route::middleware(['api', 'auth:sanctum'])->post('/notifications/{notificationId}/mark-as-read', function ($notificationId) {
    $notification = auth()->user()->notifications()->find($notificationId);
    $notification->markAsRead();
    return $notification;
});