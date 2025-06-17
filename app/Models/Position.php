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

class Position extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PositionFactory>
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
    ];

    /**
     * @return BelongsTo<User, Position>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<User, Position>
     */
    public function users(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(User::class, 'positions_users')
            ->using(PositionsUsers::class)
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
     * @return Builder<Position>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterTeam($args);
    }

    /**
     * @param  Builder<Position>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        if (
            is_array($filter) &&
            array_key_exists('ignoreIds', $filter) &&
            is_array($filter['ignoreIds'])
        ) {
            /** @var array<int> $ignoreIds */
            $ignoreIds = $filter['ignoreIds'];

            $query->whereNotIn('positions.id', $ignoreIds);
        }
    }

    /**
     * @param  Builder<Position>  $query
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
            $query->filterName($search);
        }
    }

    /**
     * @param  Builder<Position>  $query
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('positions.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<Position>  $query
     * @param  array<string>  $ids
     */
    public function scopeFilterIds(Builder $query, array $ids): void
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('positions.id', $ids);
        });
    }

    /**
     * @param  Builder<Position>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterTeam(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        /** @var array<int>|null $teamsIds */
        $teamsIds = (is_array($filter) && isset($filter['teamsIds']) && is_array($filter['teamsIds']))
            ? $filter['teamsIds']
            : null;

        $query->when($teamsIds !== null && !empty($teamsIds), function ($query) use ($teamsIds): void {
            $query->whereHas('users', function ($query) use ($teamsIds) {
                $query->whereHas('teams', function ($query) use ($teamsIds) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($teamsIds);
                });
            });
        });
    }
}
