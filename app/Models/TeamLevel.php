<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamLevel extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\TeamLevelFactory>
     */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return HasMany<Team, TeamLevel>
     */
    public function teams(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(Team::class);
    }

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<TeamLevel>
     */
    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param  Builder<TeamLevel>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['search']), function ($query) use ($args) {
            $query->where('team_levels.name', 'like', '%' . $args['search'] . '%');
        });

    }

    /**
     * @param  Builder<TeamLevel>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['ignore']), function ($query) use ($args) {
            $query->whereNotIn('team_levels.id', $args['ignore']);
        });
    }
}
