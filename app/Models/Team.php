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
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'user_id',
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
            ->where('teams_users.role', 'technician')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('players')
            ->where('teams_users.role', 'player')
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

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterPosition($args);
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
        $query->when(isset($ids) && ! empty($ids), function ($query) use ($ids) {
            $query->whereIn('teams.id', $ids);
        });
    }

    public function scopeFilterPosition(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['positionsIds']) &&
            ! empty($args['filter']['positionsIds']),
            function ($query) use ($args) {
                $query->whereHas('players', function ($query) use ($args) {
                    $query->whereHas('positions', function ($query) use ($args) {
                        $query->filterIds($args['filter']['positionsIds']);
                    });
                });
            }
        );
    }
}
