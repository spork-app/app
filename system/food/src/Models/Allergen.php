<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    public $incrementing = false;
    public $fillable = [
        'id',
        'type',
        'name',
        'description',
        'triggersTracesOf',
        'tracesOf',
        'iconLink',
    ];
}
