<?php

use App\Core\Models\FeatureList;
use App\Finance\Models\Account;
use App\Finance\Services\PlaidService;
use Google\Service\CustomSearchAPI;
use Google\Service\CustomSearchAPI\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['api', 'auth:sanctum']);

Route::get('shopping-search', \Spork\Shopping\Http\Controller\ItemController::class);
Route::middleware('auth:sanctum')->get('research', function (Request $request, \Google\Client $client) {
    $page = $request->get('page', $request->get('start') / 10);

    /** @var Search $results */
    $results = cache()->remember(sprintf('%s.%s.google-search', $page, request()->get('q')), now()->addDay(), function () use ($client, $page) {
        $api = new CustomSearchAPI($client);
        return $api->cse->listCse([
            'cx' => '005170087081803266169:ipknn59okgm',
            'q' => request()->get('q'),
            'start' => $page * 10,
        ]);
    });

    $data = array_map(function (CustomSearchAPI\Result $result, int $key) use ($page) {
        return [
            'id' => $key + ($page * 10),
            'title' => $result->htmlTitle,
            'snippet' => $result->htmlSnippet,
            'link' => $result->link,
            'image' => $result->getPagemap()['cse_thumbnail'][0]['src'] ?? $result->getPagemap()['cse_image'][0]['src'] ?? 'https://via.placeholder.com/150/374151/E5E7EB/?text=x',
        ];
    }, $results->getItems(), array_keys($results->getItems()));
    $parts = parse_url(request()->url());

    return (new \Illuminate\Pagination\LengthAwarePaginator($data, (int) $results->getSearchInformation()->totalResults, 10, $page, [
        'path' => sprintf('%s://%s%s?%s', $parts['scheme'], $parts['host'], $parts['path'], http_build_query($request->except('page')))
    ]))->onEachSide(1);
});

Route::middleware('auth:sanctum')->get('weather', function (Request $request, \Spork\Weather\Contracts\Services\WeatherServiceContract $weatherService) {
    $propertu = \App\Core\Models\Property::first();

    return $weatherService->query($propertu->address);
});
Route::middleware('auth:sanctum')->get('news', function (Request $request, \Spork\News\Contracts\Service\NewsServiceContract $service) {
    return $service->headlines('');
});

