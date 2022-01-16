<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Wine::class, 'wine_attributes');
    }
}
