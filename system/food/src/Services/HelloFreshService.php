<?php

namespace Spork\Food\Services;

use Spork\Food\Contracts\Services\HelloFreshServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

class HelloFreshService implements HelloFreshServiceInterface
{
    public function findAll(string $token, int $page = 1, int $limit = 10): LengthAwarePaginatorContract
    {
        $result = cache()->rememberForever('hello-fresh.' . $page . '.' . $limit, function () use ($token, $page, $limit) {
            $client = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ]
            ]);

            $response = $client->get(static::HELLO_FRESH_API_URL . '&offset=' . ($page * $limit) . '&limit=' . $limit);

            return json_decode($response->getBody()->getContents());
        });

        $paginator = new LengthAwarePaginator(
            $result->items,
            $result->total,
            $limit,
            $page
        );

        return $paginator;
    }

    public function findAllWine(string $token, int $page = 1, int $limit = 10): LengthAwarePaginatorContract
    {
        $result = cache()->rememberForever('hello-fresh.wines.' . $page . '.' . $limit, function () use ($token, $page, $limit) {
            $client = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ]
            ]);

            $response = $client->get(static::HELLO_FRESH_WINES_API_URL);

            return json_decode($response->getBody()->getContents());
        });

        $paginator = new LengthAwarePaginator(
            $result,
            count($result),
            $limit,
            $page
        );

        return $paginator;
    }
}
