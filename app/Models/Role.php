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
            $builder->when(auth()->user()->hasRole('Administrador'), function (Builder $builder) {
                return $builder;
            })
            ->when(auth()->user()->hasRole('Técnico'), function (Builder $builder) {
                return $builder->whereNot('id', 1);
            })
            ->when(auth()->user()->hasRole('Jogador'), function (Builder $builder) {
                return $builder->whereNotIn('id', [1, 2]);
            });
        });
    }
}
