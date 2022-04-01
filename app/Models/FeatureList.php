<?php

namespace App\Models;

use App\Events\FeatureCreated;
use App\Events\FeatureDeleted;
use App\Events\FeatureUpdated;
use Spork\Calendar\Traits\Repeatable;
use Spork\Finance\Models\Account;
use Spork\Maintenance\Traits\Workable;
use Spork\Research\Models\ResearchNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Kregel\LaravelAbstract\AbstractEloquentModel;
use Kregel\LaravelAbstract\AbstractModelTrait;
use Spatie\Tags\HasTags;

/**
 * Class FeatureList
 * @package App\Models
 * @mixin Model
 */
class FeatureList extends Model implements AbstractEloquentModel
{
    use HasFactory, HasTags, Workable, AbstractModelTrait, Repeatable;

    public const FEATURE_RESEARCH = 'research';
    public const FEATURE_FINANCE = 'finance';
    public const FEATURE_PLANNING = 'planning';
    public const FEATURE_MAINTENANCE = 'maintenance';
    public const FEATURE_SHOPPING = 'shopping';
    public const FEATURE_NEWS = 'news';
    public const FEATURE_WEATHER = 'weather';
    public const FEATURE_PROPERTY = 'property';
    public const FEATURE_CALENDAR = 'calendar';
    public const FEATURE_DEVELOPMENT = 'development';
    public const FEATURE_GARAGE = 'garage';

    public const ALL = [
        self::FEATURE_RESEARCH,
        self::FEATURE_FINANCE,
        self::FEATURE_PLANNING,
        self::FEATURE_MAINTENANCE,
        self::FEATURE_SHOPPING,
        self::FEATURE_NEWS,
        self::FEATURE_WEATHER,
        self::FEATURE_PROPERTY,
        self::FEATURE_CALENDAR,
        self::FEATURE_DEVELOPMENT,
        self::FEATURE_GARAGE,
    ];

    protected $fillable = [
        'name',
        'feature',
        'settings',
    ];

    protected $appends = ['slug'];

    protected $casts = [
        'settings' => 'json',
    ];

    protected $hidden = [    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($item) {
            if (auth()->check()) {
                $item->user_id = auth()->id();
            }
        });

        static::created(function ($item) {
            event(new FeatureCreated($item));
        });

        static::updated(function ($item) {
            event(new FeatureUpdated($item));
        });

        static::deleted(function ($item) {
            event(new FeatureDeleted($item));
        });

        static::addGlobalScope('user', function (Builder $builder) {
            if (!auth()->check()) {
                return;
            }

            $builder->where('user_id', auth()->id())
                ->orWhereHas('users', function (Builder $builder) {
                    $builder->where('user_id', auth()->id());
                });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'feature_list_users',)->withPivot(['role']);
    }

    public function notes()
    {
        return $this->hasMany(ResearchNote::class);
    }

    public static function forFeature(string $feature): Builder
    {
        return static::query()
            ->where('feature', $feature);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function getValidationCreateRules(): array
    {
        return [
            'name' => 'required|string',
            'feature' => [
                'required',
                'string',
                Rule::in(static::ALL)
            ],
            'settings' => 'nullable|array',
            'settings.body' => 'nullable|string',
            'settings.links' => 'array|min:0'
        ];
    }

    public function getValidationUpdateRules(): array
    {
        return [
            'name' => 'string',
            'settings' => 'array',
        ];
    }

    public function getAbstractAllowedFilters(): array
    {
        return [
            'feature',
        ];
    }

    public function getAbstractAllowedRelationships(): array
    {
        return ['repeatable.users.user', 'notes', 'accounts'];
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

    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }
}
