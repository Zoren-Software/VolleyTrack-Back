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

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $technicians
 */
class Team extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\TeamFactory>
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
        'team_category_id',
        'team_level_id',
    ];

    /**
     * @return BelongsTo<User, Team>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ConfirmationTraining, Team>
     */
    public function confirmationsTraining(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(ConfirmationTraining::class);
    }

    /**
     * @return BelongsToMany<User, Team, TeamsUsers>
     */
    public function technicians(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('technicians')
            ->wherePivot('role', 'technician')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at', 'role');
    }

    /**
     * @return BelongsToMany<User, Team, TeamsUsers>
     */
    public function players(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('players')
            ->wherePivot('role', 'player')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at', 'role');
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
     * @return BelongsTo<TeamCategory, Team>
     */
    public function teamCategory(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(TeamCategory::class, 'team_category_id');
    }

    /**
     * @return BelongsTo<TeamLevel, Team>
     */
    public function teamLevel(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(TeamLevel::class, 'team_level_id');
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<Team>
     */
    public function scopeList(Builder $query, array $args)
    {
        return $query
            ->with([
                'teamCategory:id,name,updated_at',
                'teamLevel:id,name,updated_at',
            ])
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterPosition($args)
            ->filterByTeamPlayer($args)
            ->filterPlayers($args)
            ->filterUsers($args);
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array{ignoreIds?: array<int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['ignoreIds'])) {
            $ignoreIds = $filter['ignoreIds'];
            $query->whereNotIn('teams.id', $ignoreIds);
        }
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array{search?: string} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['search'])) {
            $query->filterName($filter['search']);
        }
    }

    /**
     * @param  Builder<Team>  $query
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('teams.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string>  $ids
     * @return void
     */
    public function scopeFilterIds(Builder $query, array $ids)
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('teams.id', $ids);
        });
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterPosition(Builder $query, array $args): void
    {
        /** @var array{positionsIds?: array<array-key, int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['positionsIds']) && !empty($filter['positionsIds'])) {
            $query->whereHas('players', function ($query) use ($filter) {
                $query->whereHas('positions', function ($query) use ($filter) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($filter['positionsIds']);
                });
            });
        }
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterByTeamPlayer(Builder $query, array $args): void
    {
        /** @var array{playersIds?: array<array-key, int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (!empty($filter['playersIds'])) {
            $query->whereHas('players', function ($query) use ($filter) {
                // @phpstan-ignore-next-line
                $query->filterIds($filter['playersIds']);
            });
        }
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterPlayers(Builder $query, array $args): void
    {
        /** @var array{playersIds?: array<array-key, int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (!empty($filter['playersIds'])) {
            $query->whereHas('players', function ($query) use ($filter) {
                // @phpstan-ignore-next-line
                $query->filterIds($filter['playersIds']);
            });
        }
    }

    /**
     * @param  Builder<Team>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterUsers(Builder $query, array $args): void
    {
        /** @var array{usersIds?: int|array<array-key, int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['usersIds'])) {
            $query->where('teams.user_id', $filter['usersIds']);
        }
    }
}
