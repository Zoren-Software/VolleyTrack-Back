<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamLevel extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['search']), function ($query) use ($args) {
            $query->where('team_levels.name', 'like', '%' . $args['search'] . '%');
        });

    }

    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['ignore']), function ($query) use ($args) {
            $query->whereNotIn('team_levels.id', $args['ignore']);
        });
    }
}
