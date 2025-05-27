<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Position extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
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

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterTeam($args);
    }

    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('positions.id', $args['filter']['ignoreIds']);
        });
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) &&
            isset($args['filter']['search']), function ($query) use ($args) {
                // @phpstan-ignore-next-line
                $query->filterName($args['filter']['search']);
            });
    }

    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('positions.name', 'like', $search);
        });
    }

    public function scopeFilterIds(Builder $query, array $ids)
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('positions.id', $ids);
        });
    }

    public function scopeFilterTeam(Builder $query, array $args)
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
