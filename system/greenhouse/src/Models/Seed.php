<?php

namespace Spork\Greenhouse\Models;

use App\Models\AbstractModel;
use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\AbstractModelTrait;

class Seed extends AbstractModel
{
    public $fillable = [
        'name',
        'plant_id',
        'germinated_at',
        'planted_at',
        'expected_harvest_date',
        'harvested_at',
        'last_watered_at',
        'last_fed_at',
    ];

    public $casts = [
        'germinated_at' => 'datetime',
        'planted_at' => 'datetime',
        'expected_harvest_date' => 'datetime',
        'harvested_at' => 'datetime',
        'last_watered_at' => 'datetime',
        'last_fed_at' => 'datetime',
    ];

    
    public function getValidationCreateRules(): array
    {
        return [
            'name' => 'string|required',
            'plant_id' => 'integer|exists:plants,id',
            'germinated_at' => 'date|nullable',
            'planted_at' => 'date|nullable',
            'expected_harvest_date' => 'date|nullable',
            'harvested_at' => 'date|nullable',
            'last_watered_at' => 'date|nullable',
            'last_fed_at' => 'date|nullable',
        ];
    }
}