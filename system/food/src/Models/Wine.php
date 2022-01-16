<?php

namespace Spork\Food\Models;

use Illuminate\Database\Eloquent\Model;

class Wine extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'displayName',
        'name',
        'brand',
        'tasting_note',
        'classification',
        'type',
        'country',
        'grape',
        'region',
        'pairings',
        'imageLink',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'wine_attributes');
    }
}
