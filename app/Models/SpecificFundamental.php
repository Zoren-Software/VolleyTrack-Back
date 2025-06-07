<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Fundamental[] $fundamentals
 */
class SpecificFundamental extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SpecificFundamentalFactory>
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
     * @return BelongsTo<User, SpecificFundamental>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Fundamental, SpecificFundamental>
     */
    public function fundamentals(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(Fundamental::class)
            ->using(FundamentalsSpecificFundamentals::class)
            ->as('fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @codeCoverageIgnore
     */
    public function deleteSpecificFundamental(int $id): SpecificFundamental
    {
        $specificFundamental = $this->findOrFail($id);
        $specificFundamental->delete();

        return $specificFundamental;
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
     * @return Builder<SpecificFundamental>
     */
    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterUser($args)
            ->filterFundamentals($args);
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array{search?: string} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['search'])) {
            $query
                ->filterName($filter['search'])
                ->orWhere(function ($query) use ($filter) {
                    $query->filterUserName($filter['search']);
                });
        }
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('specific_fundamentals.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @return void
     */
    public function scopeFilterUserName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('user', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterUser(Builder $query, array $args): void
    {
        /** @var array{usersIds?: array<array-key, int>}|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) && isset($filter['usersIds']) && !empty($filter['usersIds']),
            function ($query) use ($filter) {
                $query->whereHas('user', function ($query) use ($filter) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($filter['usersIds']);
                });
            }
        );
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        $filter = $args['filter'] ?? [];

        /** @var array<int>|null $ignoreIds */
        $ignoreIds = is_array($filter) && array_key_exists('ignoreIds', $filter) && is_array($filter['ignoreIds'])
            ? $filter['ignoreIds']
            : null;

        if ($ignoreIds !== null) {
            $query->whereNotIn('specific_fundamentals.id', $ignoreIds);
        }
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterFundamentals(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) &&
            array_key_exists('fundamentalsIds', $filter) &&
            is_array($filter['fundamentalsIds']),
            function (Builder $query) use ($filter): void {
                /** @var array<int> $fundamentalsIds */
                $fundamentalsIds = $filter['fundamentalsIds'] ?? [];
                $query->whereHas('fundamentals', function ($query) use ($fundamentalsIds) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($fundamentalsIds);
                });
            }
        );
    }
}
