<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'team_category_id',
        'team_level_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmationsTraining()
    {
        return $this->hasMany(ConfirmationTraining::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('technicians')
            ->wherePivot('role', 'technician')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at', 'role');
    }

    public function players()
    {
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

    public function category()
    {
        return $this->belongsTo(TeamCategory::class, 'team_category_id');
    }

    public function level()
    {
        return $this->belongsTo(TeamLevel::class, 'team_level_id');
    }

    public function list(array $args)
    {
        return $this
            ->with([
                'category:id,name,updated_at',
                'level:id,name,updated_at',
            ])
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterPosition($args)
            ->filterByTeamPlayer($args)
            ->filterPlayers($args)
            ->filterUsers($args);
    }

    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('teams.id', $args['filter']['ignoreIds']);
        });
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['search']), function ($query) use ($args) {
            $query->filterName($args['filter']['search']);
        });
    }

    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->where('teams.name', 'like', $search);
        });
    }

    public function scopeFilterIds(Builder $query, array $ids)
    {
        $query->when(isset($ids) && !empty($ids), function ($query) use ($ids) {
            $query->whereIn('teams.id', $ids);
        });
    }

    public function scopeFilterPosition(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['positionsIds']) &&
            !empty($args['filter']['positionsIds']),
            function ($query) use ($args) {
                $query->whereHas('players', function ($query) use ($args) {
                    $query->whereHas('positions', function ($query) use ($args) {
                        $query->filterIds($args['filter']['positionsIds']);
                    });
                });
            }
        );
    }

    public function scopeFilterByTeamPlayer(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['playersIds']) &&
            !empty($args['filter']['playersIds']),
            function ($query) use ($args) {
                $query->whereHas('players', function ($query) use ($args) {
                    $query->filterIds($args['filter']['playersIds']);
                });
            }
        );
    }

    public function scopeFilterPlayers(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['playersIds']) &&
            !empty($args['filter']['playersIds']),
            function ($query) use ($args) {
                $query->whereHas('players', function ($query) use ($args) {
                    $query->filterIds($args['filter']['playersIds']);
                });
            }
        );
    }

    public function scopeFilterUsers(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['usersIds']) &&
            !empty($args['filter']['usersIds']),
            function ($query) use ($args) {
                $query->where('teams.user_id', $args['filter']['usersIds']);
            }
        );
    }
}
