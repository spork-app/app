<?php

namespace App\Finance\Models;

use App\Core\Models\AbstractModel;
use App\Core\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kregel\LaravelAbstract\AbstractModelTrait;
use Spatie\QueryBuilder\AllowedFilter;

class Transaction extends AbstractModel
{
    use HasFactory, AbstractModelTrait;

    protected $fillable = [
        'id',
        'name',
        'amount',
        'account_id',
        'date',
        'pending',
        'category_id',
        'transaction_id',
    ];

    public function scopeForAccounts(Builder $query, $value)
    {
        $query->with('account');
        return $query->whereIn('account_id', auth()->user()->features->map->accounts->reduce(function ($items, Collection $item) {
            return $item->map->account_id->concat($items)->unique()->toArray();
        }, []));
    }

    public function getAbstractAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('for_accounts')
        ];
    }

    public function getAbstractAllowedSorts(): array
    {
        return [
            'date'
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
}
