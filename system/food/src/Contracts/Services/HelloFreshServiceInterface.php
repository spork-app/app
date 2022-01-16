<?php
declare(strict_types=1);

namespace Spork\Food\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

interface HelloFreshServiceInterface
{
    public const HELLO_FRESH_API_URL = 'https://gw.hellofresh.com/api/recipes/search?locale=en-US&country=us';
    public const HELLO_FRESH_WINES_API_URL = 'https://gw.hellofresh.com/wineclub/bottles';

    public function findAll(string $token, int $page = 1, int $limit = 10): LengthAwarePaginatorContract;
    public function findAllWine(string $token, int $page = 1, int $limit = 10): LengthAwarePaginatorContract;
}
