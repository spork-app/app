<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $fillable = [
        'index',
        'instructionsMarkdown',
        'instructions',
        'timers',
        'images',
        'videos',
        'recipe_id',
    ];

    public function setVideosAttribute($value)
    {
        return json_encode($value);
    }

    public function setImagessAttribute($value)
    {
        return json_encode($value);
    }

    public function setTimersAttribute($value)
    {
        return json_encode($value);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'step_ingredients');
    }

    public function utensils()
    {
        return $this->belongsToMany(Utensil::class, 'step_utensils');
    }
}
