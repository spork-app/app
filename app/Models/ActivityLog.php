<?php

namespace App\Models;

use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\AbstractModelTrait;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity implements AbstractEloquentModel
{
    use AbstractModelTrait;

    public function getValidationCreateRules(): array
    {
        return [];
    }

    public function getValidationUpdateRules(): array
    {
        return [];
    }

    public function getAbstractAllowedFilters(): array
    {
        return [
            'id',
            'log_name',
            'description',
            'subject_type',
            'subject_id',
            'causer_type',
            'causer_id',
            'properties',
            'created_at',
            'updated_at',
            'event',
            'batch_uuid',
        ];
    }

    public function getAbstractAllowedRelationships(): array
    {
        return [
            'subject',
            'causer',
        ];
    }

    public function getAbstractAllowedSorts(): array
    {
        return [];
    }

    public function getAbstractAllowedFields(): array
    {
        return [];
    }

    public function getAbstractSearchableFields(): array
    {
        return [];
    }

}