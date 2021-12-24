<?php

namespace App\Maintenance\Models;

use App\Core\Models\Property;
use App\Core\Models\User;
use App\Garage\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class WorkOrder extends Model
{
    use HasFactory, HasTags, Searchable;

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->morphedByMany(Property::class, 'workable');
    }

    public function vehicles()
    {
        return $this->morphedByMany(Vehicle::class, 'workable');
    }

    public function workable()
    {
        return $this->morphTo();
    }
}
