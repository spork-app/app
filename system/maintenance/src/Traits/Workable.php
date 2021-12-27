<?php
declare(strict_types=1);

namespace Spork\Maintenance\Traits;

use Spork\Maintenance\Models\WorkOrder;
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

