<?php

namespace App\Phone\Models;

use App\Core\Models\AbstractModel;

class Website extends AbstractModel
{
    protected $table = 'contact_websites';

    public $fillable = [
        'contact_id',
        'site'
    ];
}
