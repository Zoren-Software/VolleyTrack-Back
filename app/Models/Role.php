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
    * @return void
    */
    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            /**
             * verify if auth()->user() has permission in role for list-role-administrador
             * if not, remove role id 1 from query (Administrador)
             */
            $builder->when(!auth()->user()->hasPermissionsViaRoles('list-role-administrador', auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray()), function (Builder $builder) {
                $builder->whereNot('id', 1);
            })

            /**
             * verify if auth()->user() has permission in role for list-role-technician
             * if not, remove role id 2 from query (Técnico)
             */
            ->when(!auth()->user()->hasPermissionsViaRoles('list-role-technician', auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray()), function (Builder $builder) {
                $builder->whereNot('id', 2);
            })
            /**
             * verify if auth()->user() has permission in role for list-role-player
             * if not, remove role id 3 from query (Jogador)
             */
            ->when(!auth()->user()->hasPermissionsViaRoles('list-role-player', auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray()), function (Builder $builder) {
                $builder->whereNot('id', 3);
            });

            return $builder;
        });
    }
}
