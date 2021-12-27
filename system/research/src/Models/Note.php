<?php

namespace Spork\Research\Models;

use App\Models\FeatureList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\AbstractModelTrait;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Note extends Model implements AbstractEloquentModel
{
    use HasFactory, AbstractModelTrait, HasTags, Searchable;

    protected $table = 'research_notes';

    public function topic()
    {
        return $this->belongsTo(FeatureList::class);
    }

    public function getValidationCreateRules(): array
    {
        return [
            'name' => 'string',
            'body' => 'required|string',
            'url' => 'url|string'
        ];
    }

    public function getValidationUpdateRules(): array
    {
        return [
            'name' => 'string',
            'body' => 'string',
            'url' => 'url|string'
        ];
    }

    public function getAbstractAllowedFilters(): array
    {
        return [];
    }

    public function getAbstractAllowedRelationships(): array
    {
        return ['topics'];
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
