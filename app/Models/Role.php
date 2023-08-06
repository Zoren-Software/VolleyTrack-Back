<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            /**
             * verify if auth()->user() has permission in role for view-role-admin
             * if not, remove role id 1 from query (admin)
             */
            return $builder->when(
                ! auth()->user()->hasPermissionRole('view-role-admin'),
                function (Builder $builder) {
                    $builder->whereNot('id', 1);
                }
            )

            /**
             * verify if auth()->user() has permission in role for view-role-technician
             * if not, remove role id 2 from query (Técnico)
             */
                ->when(
                    ! auth()->user()->hasPermissionRole('view-role-technician'),
                    function (Builder $builder) {
                        $builder->whereNot('id', 2);
                    }
                )
            /**
             * verify if auth()->user() has permission in role for view-role-player
             * if not, remove role id 3 from query (Jogador)
             */
                ->when(
                    ! auth()->user()->hasPermissionRole('view-role-player'),
                    function (Builder $builder) {
                        $builder->whereNot('id', 3);
                    }
                );
        });
    }

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('roles.id', $args['filter']['ignoreIds']);
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
            $query->where('roles.name', 'like', $search);
        });
    }

    public function getNameAttribute($value)
    {
        return trans('RoleRegister.' . $value);
    }
}
