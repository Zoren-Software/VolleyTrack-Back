<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class TeamCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'min_age',
        'max_age',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
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
            ->filterIgnores($args);
    }

    public function scopeFilterSearch($query, array $args)
    {
        return $query->when(isset($args['search']), function ($query) use ($args) {
            $query->where('team_categories.name', 'like', '%' . $args['search'] . '%');
        });
    }

    public function scopeFilterIgnores($query, array $args)
    {
        $query->when(isset($args['ignore']), function ($query) use ($args) {
            $query->whereNotIn('team_categories.id', $args['ignore']);
        });
    }
}
