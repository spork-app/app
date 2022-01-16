<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Utensil extends Model
{
    public $incrementing = false;
    public $fillable = [
        'id',
        'type',
        'name',
    ];
}
