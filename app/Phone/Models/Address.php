<?php

namespace App\Phone\Models;

use App\Core\Models\AbstractModel;

class Address extends AbstractModel
{
    protected $table = 'contact_addresses';

    public $fillable = [
        'contact_id',
        'label',
        'street',
        'address',
        'city',
        'country',
        'region'
    ];
}
