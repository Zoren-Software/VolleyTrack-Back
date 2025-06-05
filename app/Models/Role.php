<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * The "booted" method of the model.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            $user = auth()->user();

            if (! $user) {
                return $builder->where('id', '<', 0); // Garante query vazia se não houver usuário
            }

            if ($user->hasPermissionTo('view-role-admin')) {
                return $builder;
            }

            if ($user->hasPermissionTo('view-role-technician')) {
                return $builder->whereNotIn('id', [1]); // Remove admin
            }

            if ($user->hasPermissionTo('view-role-player')) {
                return $builder->whereNotIn('id', [1, 2]); // Remove admin e técnico
            }

            return $builder->where('id', '<', 0); // Caso o usuário não tenha permissão alguma
        });
    }


    /**
     * @param array<string, mixed> $args
     * 
     * @return Builder<Role>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param Builder<Role> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('roles.id', $args['filter']['ignoreIds']);
        });
    }

    /**
     * @param Builder<Role> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) && isset($args['filter']['search']), function ($query) use ($args) {
            $query->filterName($args['filter']['search']);
        });
    }

    /**
     * @param Builder<Role> $query
     * @param string $search
     * 
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('roles.name', 'like', $search);
        });
    }

    /**
     * @return Attribute<string, string>
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => trans('RoleRegister.' . $value)
        );
    }
}
