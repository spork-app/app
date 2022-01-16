<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 * @package App
 * @property Collection $family
 */
class Ingredient extends Model
{
    public $incrementing = false;
    public $fillable = [
        'id',
        'type',
        'name',
        'slug',
        'description',
        'imageLink',
    ];

    public function family()
    {
        return $this->belongsToMany(Family::class, 'ingredient_families');
    }
}
