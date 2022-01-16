<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Spork\Food\Models\Recipe;

Route::get('/recipes', function (Request $request) {
    return \Spork\Food\Models\Recipe::allUsable()
        ->inRandomOrder()
        ->paginate(16);
});

Route::post('/search', function (Request $request) {
    if (!$request->hasAny(['query', 'allergies', 'pairs', 'ingredients', 'difficulty', 'prepTime',])) {
        return response()->redirectTo('/api/recipes');
    }

    $httpQuery = $request->get('query') ?? $request->get('prepTime') ?? 'difficulty ' . $request->get('difficulty');

    $query = Recipe::search($httpQuery);

    $results = $query->get();

    /** @var Builder $eloquentQuery */
    $eloquentQuery = Recipe::allUsable()->inRandomOrder();

    if ($results->isNotEmpty()) {
        $eloquentQuery->whereIn('id', $results->pluck('id'));
    }

    if ($request->has('pairs')) {
        $eloquentQuery->whereHas('wines.attributes', function (Builder $query) use ($request): void {
            $query->whereIn('name', $request->get('pairs', []));
        });
    }

    if ($request->has('allergies')) {
        $eloquentQuery->whereHas('allergens', function (Builder $query) use ($request): void {
            $query->whereNotIn('type', $request->get('allergies', []));
        });
    }

    if ($request->has('difficulty')) {
        $eloquentQuery->where('difficulty', $request->get('difficulty'));
    }

    if ($request->has('prepTime')) {
        $eloquentQuery->whereIn('prepTime', $request->get('prepTime'));
    }
    if ($request->has('ingredients')) {
        $eloquentQuery->whereHas('ingredients.family', function (Builder $query) use ($request): void {
            $query->whereIn('type', $request->get('ingredients', []));
        });
    }

    return $eloquentQuery->paginate(16);
});

Route::get('recipes', function () {
    return Recipe::allUsable()
        ->inRandomOrder()
        ->paginate(8);
});
Route::get('alergens', function () {
    return Allergen::all();
});
Route::get('wine-attributes', function () {        
    return Attribute::all();
});

Route::get('ingredient-families', function () {        
    return Family::all();
});

Route::get('prep-times', function () {
    /** @var array $items */
    $items = Recipe::selectRaw('prepTime as time')
        ->distinct()
        ->whereNotNull('prepTime')
        ->get()
        ->map
        ->time
        ->map(function ($item) {
            return str_replace('-', ' ', $item);
        })
        ->toArray();

    natsort($items);

    return Collection::make(array_values($items))
        ->map(function ($item) {
            return str_replace(' ', '-', $item);
        });
});