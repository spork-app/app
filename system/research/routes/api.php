<?php

use Google\Service\CustomSearchAPI;
use Google\Service\CustomSearchAPI\Search;

Route::middleware(['api', 'auth:sanctum'])->get('research', function (\Google\Client $client) {
    $page = request()->get('page', request()->get('start') / 10);

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
        'path' => sprintf('%s://%s%s?%s', $parts['scheme'], $parts['host'], $parts['path'], http_build_query(request()->except('page')))
    ]))->onEachSide(1);
});
