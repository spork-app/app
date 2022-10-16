<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterIn implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereIn($property, $value);
    }
}
