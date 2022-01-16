<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * Class Recipe
 * @package App
 * @mixin Model
 */
class Recipe extends Model
{
    use Searchable;

    public $incrementing = false;

    public $fillable = [
        'id',
        'country',
        'name',
        'seoName',
        'category',
        'slug',
        'headline',
        'description',
        'descriptionMarkdown',
        'seoDescription',
        'difficulty',
        'prepTime',
        'totalTime',
        'servingSize',
        'link',
        'imageLink',
        'cardLink',
        'videoLink',
        'clonedFrom',
        'canonical',
        'canonicalLink',
        'yields'
    ];

    public function scopeAllUsable($query)
    {
        $query->with([
                'ingredients',
                'wines',
                'steps',
                'utensils',
                'allergens',
            ])
            ->has('steps')
            ->has('ingredients');
    }

    public function wines()
    {
        return $this->belongsToMany(Wine::class, 'recipe_wines');
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'recipe_id');
    }

    public function utensils()
    {
        return $this->belongsToMany(Utensil::class, 'recipe_utensils');
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'recipe_allergens');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')->withPivot([
            'amount',
            'unit'
        ]);
    }

    public  function cuisines()
    {
        return $this->belongsToMany(Cuisine::class, 'recipe_cuisines');
    }

    public function toSearchableArray()
    {
        $this->load([
            'wines' => function ($query) {
                $query->select([
                    'displayName',
                    'classification',
                    'type'
                ]);
            },
            'utensils' => function($query) {
                $query->select('type');
            },
            'allergens' => function($query) {
                $query->select('type');
            },
        ]);
        return Arr::except(collect($this->toArray())->filter()->toArray(), ['steps']);
    }

}
