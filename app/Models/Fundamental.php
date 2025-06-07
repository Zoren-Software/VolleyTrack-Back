<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fundamental extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\FundamentalFactory>
     */
    use HasFactory;

    use LogsActivity;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo<User, Fundamental>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<SpecificFundamental, Fundamental>
     */
    public function specificFundamental(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(SpecificFundamental::class);
    }

    /**
     * @return BelongsToMany<Training, Fundamental, FundamentalsTrainings>
     */
    public function trainings(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(Training::class, 'fundamentals_trainings')
            ->using(FundamentalsTrainings::class)
            ->as('trainings')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(
                [
                    'updated_at',
                    'created_at',
                    'deleted_at',
                ]
            )
            ->dontSubmitEmptyLogs();
    }

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<Fundamental>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterUser($args);
    }

    /**
     * @param  Builder<Fundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        if (
            is_array($filter) &&
            array_key_exists('search', $filter) &&
            is_string($filter['search'])
        ) {
            /** @var string $search */
            $search = $filter['search'];

            $query
                ->filterName($search)
                ->orWhere(function (Builder $query) use ($search): void {
                    $query->filterUserName($search);
                });
        }
    }

    /**
     * @param  Builder<Fundamental>  $query
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('fundamentals.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<Fundamental>  $query
     */
    public function scopeFilterUserName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('user', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param  Builder<Fundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterUser(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        if (
            is_array($filter) &&
            array_key_exists('usersIds', $filter) &&
            is_array($filter['usersIds']) &&
            !empty($filter['usersIds'])
        ) {
            /** @var array<int> $usersIds */
            $usersIds = $filter['usersIds'];

            $query->whereHas('user', function ($query) use ($usersIds) {
                // @phpstan-ignore-next-line
                $query->filterIds($usersIds);
            });
        }
    }

    /**
     * @param  Builder<Fundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) &&
            array_key_exists('ignoreIds', $filter) &&
            is_array($filter['ignoreIds']) &&
            !empty($filter['ignoreIds']),
            function (Builder $query) use ($filter): void {
                /** @phpstan-var array<int> $filter */
                /** @var array<int> $ignoreIds */
                $ignoreIds = $filter['ignoreIds'];
                $query->whereNotIn('fundamentals.id', $ignoreIds);
            }
        );
    }

    /**
     * @param  Builder<Fundamental>  $query
     * @param  array<string>  $ids
     */
    public function scopeFilterIds(Builder $query, array $ids): void
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('fundamentals.id', $ids);
        });
    }
}
