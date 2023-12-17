<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fundamental extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specificFundamental()
    {
        return $this->hasMany(SpecificFundamental::class);
    }

    public function trainings()
    {
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

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterUser($args);
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['search']), function ($query) use ($args) {
            $query
                ->filterName($args['filter']['search'])
                ->orWhere(function ($query) use ($args) {
                    $query
                        ->filterUserName($args['filter']['search']);
                });
        });
    }

    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->where('fundamentals.name', 'like', $search);
        });
    }

    public function scopeFilterUserName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('user', function ($query) use ($search) {
                $query->filterName($search);
            });
        });
    }

    public function scopeFilterUser(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['usersIds']) &&
            !empty($args['filter']['usersIds']),
            function ($query) use ($args) {
                $query->whereHas('user', function ($query) use ($args) {
                    $query->filterIds($args['filter']['usersIds']);
                });
            }
        );
    }
}
