<?php

namespace App\Core\Models;

use App\Maintenance\Traits\Workable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kregel\LaravelAbstract\AbstractModelTrait;
use Spatie\Tags\HasTags;

class Property extends AbstractModel
{
    use HasFactory, Workable, HasTags;
}
