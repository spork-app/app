<?php

namespace App\Phone\Models;

use App\Core\Models\AbstractModel;

class Email extends AbstractModel
{
    protected $table = 'contact_emails';

    public $fillable = [
        'contact_id',
        'email',
        'label',
    ];
}
