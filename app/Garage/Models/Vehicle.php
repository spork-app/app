<?php

namespace App\Garage\Models;

use App\Models\AbstractModel;
use Spork\Maintenance\Traits\Workable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Tags\HasTags;

class Vehicle extends AbstractModel
{
    use HasFactory, Workable, HasTags;

    protected $guarded = [];
    
    public function getValidationCreateRules(): array
    {
        return [
            'vin' => 'string|nullable',
            'miles' => 'integer|nullable',
            'year' => 'integer|nullable',
            'make' => 'string|nullable',
            'model' => 'string|nullable',
            'trim' => 'string|nullable',
            'body' => 'string|nullable',
            'drivetrain' => 'string|nullable',
            'transmission' => 'string|nullable',
            'city_mpg' => 'integer|nullable',
            'hwy_mpg' => 'integer|nullable',
            'front_tire_pressure' => 'integer|nullable',
            'rear_tire_pressure' => 'integer|nullable',
            'right_wiper_length' => 'integer|nullable',
            'left_wiper_length' => 'integer|nullable',
            'engine_oil_type' => 'string|nullable',
            'transmission_fluid_type' => 'string|nullable',
            'ext_color' => 'string|nullable',
            'gas_tank_percentage' => 'integer|nullable',
        ];
    }
}
