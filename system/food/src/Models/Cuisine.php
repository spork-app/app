<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    public $incrementing = false;

    public $fillable = [
        'id', 'name', 'type', 'slug', 'description', 'imageLink'
    ];
}
