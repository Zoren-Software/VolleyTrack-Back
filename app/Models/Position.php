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
     * @return BelongsToMany
     * @phpstan-ignore-next-line
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'positions_users')
            ->using(PositionsUsers::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @return LogOptions
     */
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
     * @param array<string, mixed> $args
     * 
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
     * @param Builder<Position> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('positions.id', $args['filter']['ignoreIds']);
        });
    }

    /**
     * @param Builder<Position> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) &&
            isset($args['filter']['search']), function ($query) use ($args) {
                $query->filterName($args['filter']['search']);
            });
    }

    /**
     * @param Builder<Position> $query
     * @param string $search
     * 
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('positions.name', 'like', $search);
        });
    }

    /**
     * @param Builder<Position> $query
     * @param array<string> $ids
     * 
     * @return void
     */
    public function scopeFilterIds(Builder $query, array $ids): void
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('positions.id', $ids);
        });
    }

    /**
     * @param Builder<Position> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterTeam(Builder $query, array $args): void
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['teamsIds']) &&
            !empty($args['filter']['teamsIds']
            ),
            function ($query) use ($args) {
                $query->whereHas('users', function ($query) use ($args) {
                    $query->whereHas('teams', function ($query) use ($args) {
                        // @phpstan-ignore-next-line
                        $query->filterIds($args['filter']['teamsIds']);
                    });
                });
            }
        );
    }
}
