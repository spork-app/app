<?php

namespace App\News\Contracts\Service;

interface NewsServiceContract
{
    public function query(string $query): array;

    public function headlines(string $query, ?string $category = null): array;
}
