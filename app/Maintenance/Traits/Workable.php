<?php
declare(strict_types=1);

namespace App\Maintenance\Traits;

use App\Maintenance\Models\WorkOrder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use RRule\RRule;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Workable
{
    public function workOrders()
    {
        return $this->morphMany(WorkOrder::class, 'workable');
    }
}

