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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});

Route::middleware('auth:sanctum')->get('shopping-search', \Spork\Shopping\Http\Controller\ItemController::class);
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


Route::middleware('auth:sanctum')->post('/finance/upload-accounts', function (Request $request) {
    $mapping = json_decode($request->get('mapping'));
    $filePath = $request->file('image')->store('local');

    $file = fopen(storage_path('app/'.$filePath), 'r');
    $line = 0;
    $headers = [];
    $accounts = [] ;
    try {
        while (!feof($file)) {
            $row = fgetcsv($file);
            if ($line === 0) {
                $line++;
                $headers = $row;
                continue;
            }

            if (empty ($headers)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $line++;
            $modelFillable = [];
            foreach ($mapping as $key => $value) {
                $modelFillable[$key] = $data[$mapping->$key] ?? null;
            }
            $modelFillable['feature_list_id'] = $request->get('feature_list_id');
            $accounts[] = Account::create($modelFillable);
        }
    } finally {

        fclose($file);
        unlink(storage_path('app/'.$filePath));
    }

    return $accounts ?? [];

});
Route::middleware('auth:sanctum')->post('/finance/upload-transactions', function (Request $request) {
    request()->validate([
        'account_id' => 'string|exists:accounts,account_id|required',
    ], $request->all());
    $mapping = json_decode($request->get('mapping'));
    $filePath = $request->file('image')->store('local');

    $file = fopen(storage_path('app/'.$filePath), 'r');
    $line = 0;
    $headers = [];
    $transactions = [] ;
    $row = null;
    try {
        while (!feof($file)) {
            $row = fgetcsv($file);
            if ($line === 0) {
                $line++;
                $headers = $row;
                continue;
            }

            if (empty ($headers) || empty($row)) {
                continue;
            }

            if (count($headers) !== count($row)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $line++;
            $modelFillable = [];
            foreach ($mapping as $key => $value) {
                $modelFillable[$key] = $data[$mapping->$key] ?? null;
            }

            request()->validate([
                'name' => 'required_if:transaction_id,null',
                'vendor_name' => 'string',
                'amount' => 'required_if:transaction_id,null',
                'date' => 'required_if:transaction_id,null|date',
                'pending' => 'boolean|in:posted,pending',
                'type' => 'string',
                'transaction_id' => 'string'
            ], $modelFillable);

            if (empty($modelFillable['transaction_id'])) {
                $modelFillable['transaction_id'] = md5(sprintf('%s.%s.%s', $modelFillable['name'], $modelFillable['amount'], $modelFillable['date']));
            }

            if (!is_bool($modelFillable['pending'])) {
                $modelFillable['pending'] = $modelFillable['pending'] === 'pending';
            }
            if (!empty($modelFillable['date'])) {
                $modelFillable['date'] = \Carbon\Carbon::parse($modelFillable['date']);
            }

            if (!empty($modelFillable['amount'])) {
                $modelFillable['amount'] = stripos($modelFillable['amount'], '--') !== false ? trim($modelFillable['amount'], '-') : $modelFillable['amount'];
            }
            if (!empty(request('account_id'))) {
                $modelFillable['account_id'] = request('account_id');
            }

            if (request()->get('invert_values')) {
                $modelFillable['amount'] = $modelFillable['amount'] > 0 ?  -abs($modelFillable['amount']) : abs($modelFillable['amount']);
            }

            $transaction = \App\Finance\Models\Transaction::firstWhere('transaction_id', $modelFillable['transaction_id']);

            if (empty($transaction)) {
                $transaction = \App\Finance\Models\Transaction::create($modelFillable);
            }

            $transactions[] = $transaction->update($modelFillable);
        }
    } catch (\Throwable $e) {
        dd($e, $row, $headers);
    } finally {

        fclose($file);
        unlink(storage_path('app/'.$filePath));
    }

    return $accounts ?? [];

});

Route::middleware('auth:sanctum')->delete('account/{account}', fn(Account $account) => $account->delete());
Route::middleware('auth:sanctum')->post('/plaid/create-link-token', fn (PlaidService $service) => response()->json($service->createLinkToken()));
Route::middleware('auth:sanctum')->post('/plaid/exchange-token', function(PlaidService $service) {
    $response = $service->exchangeLinkTokenForAccessToken(request()->get('public_token'));

    return FeatureList::create([
        'name' => $response->access_token,
        'feature' => FeatureList::FEATURE_FINANCE,
        'user_id' => auth()->user()->id,
        'settings' => [
            'access_token' => $response->access_token,
            'item_id' => $response->item_id,
            'institution_id' => request()->get('institution'),
        ]
    ]);
});

Route::post('plaid/webhook', fn () => info(request()->all()));
Route::middleware(['auth:sanctum'])->get('status', fn() =>
\Spatie\QueryBuilder\QueryBuilder::for(\Spork\Planning\Models\Status::class)
    ->allowedIncludes(['users', 'tasks', 'tasks.creator','tasks.assignee'])
    ->get()
);
Route::middleware(['auth:sanctum'])->get('users', fn() =>
\Spatie\QueryBuilder\QueryBuilder::for(\App\Models\User::class)
    ->allowedIncludes(['tasks.creator','tasks.assignee'])
    ->get()
);

Route::middleware(['auth:sanctum'])->post('assign-task', function (Request $request) {
    $task = Spork\Planning\Models\Task::findOrFail($request->get('task_id'));
    $user = App\Models\User::findOrFail($request->get('user_id'));
    $task->assignee_id = $user->id;
    $task->save();
});

Route::middleware(['auth:sanctum'])->post('tasks', function (Request $request) {
    $request->validate([
        'title' => 'required|string',
        'description' => 'nullable',
        'order' => 'required|int',
        'status_id' => 'required|exists:statuses,id',
    ]);

    /** @var \App\Core\Models\User $user */
    $user = $request->user();

    return $user->tasksCreated()->create($request->all());
});

Route::middleware(['auth:sanctum'])->put('sync', function (Request $request) {
    $request->validate([
        'columns' => ['required', 'array']
    ]);

    foreach ($request->columns as $status) {
        foreach ($status['tasks'] as $i => $task) {
            $order = $i + 1;
            if ($task['status_id'] !== $status['id'] || $task['order'] !== $order) {
                \Spork\Planning\Models\Task::find($task['id'])
                    ->update(['status_id' => $status['id'], 'order' => $order]);
            }
        }
    }

    return $request->user()->statuses()->with('tasks')->get();
});
