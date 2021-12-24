<?php

namespace App\Phone\Models;

use App\Core\Models\AbstractModel;

class Number extends AbstractModel
{
    public $table = 'contact_numbers';

    public $fillable = [
        'contact_id',
        'normalized',
        'number',
        'label'
    ];
}
