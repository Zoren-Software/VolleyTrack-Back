<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class TeamCategory extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\TeamCategoryFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'min_age',
        'max_age',
    ];

    /**
     * @return HasMany<Team, TeamCategory>
     */
    public function teams(): HasMany
    {
        /** @phpstan-ignore-next-line */
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

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<TeamCategory>
     */
    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param  Builder<TeamCategory>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<TeamCategory>
     */
    public function scopeFilterSearch(Builder $query, array $args): Builder
    {
        /** @var string|null $search */
        $search = $args['search'] ?? null;

        return $query->when(is_string($search), function ($query) use ($search) {
            $query->where('team_categories.name', 'like', '%' . $search . '%');
        });
    }

    /**
     * @param  Builder<TeamCategory>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['ignore']), function ($query) use ($args) {
            $query->whereNotIn('team_categories.id', $args['ignore']);
        });
    }
}
