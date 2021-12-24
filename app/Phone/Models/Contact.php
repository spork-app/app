<?php

namespace App\Phone\Models;

use App\Core\Models\AbstractModel;
;

class Contact extends AbstractModel
{
    protected $table = 'contact_contacts';

    public $fillable = [
        'name',
        'uid'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function (Contact $model) {
            if (empty($model->uid)) {
                $model->uid = mt_rand(0, PHP_INT_MAX);
            }
        });
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function getAbstractAllowedRelationships(): array
    {
        return [
            'addresses',
            'emails',
            'numbers',
            'websites',
        ];
    }
}
