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
                ! auth()->user()->hasRoleAdmin(),
                function (Builder $builder) {
                    $builder->whereNot('id', 1);
                }
            )

            /**
             * verify if auth()->user() has permission in role for view-role-technician
             * if not, remove role id 2 from query (Técnico)
             */
            ->when(
                ! auth()->user()->hasRoleTechnician(),
                function (Builder $builder) {
                    $builder->whereNot('id', 2);
                }
            )
            /**
             * verify if auth()->user() has permission in role for view-role-player
             * if not, remove role id 3 from query (Jogador)
             */
            ->when(
                ! auth()->user()->hasRolePlayer(),
                function (Builder $builder) {
                    $builder->whereNot('id', 3);
                }
            );
        });
    }
}
