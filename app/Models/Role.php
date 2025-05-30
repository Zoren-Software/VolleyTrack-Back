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
    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            // Se o usuário for um administrador, não fazemos nenhuma restrição
            if (auth()->user()->hasPermissionTo('view-role-admin')) {
                return $builder;
            }

            // Se o usuário for um técnico, removemos a permissão de administrador da consulta
            if (auth()->user()->hasPermissionTo('view-role-technician')) {
                return $builder->whereNotIn('id', [1]); // Remove admin (id: 1)
            }

            // Se o usuário for um jogador, removemos as permissões de administrador e técnico da consulta
            if (auth()->user()->hasPermissionTo('view-role-player')) {
                return $builder->whereNotIn('id', [1, 2]); // Remove admin (id: 1) and technician (id: 2)
            }

            // Se chegamos até aqui, significa que o usuário não tem permissão para ver nenhum dos roles
            // Aqui você pode decidir o que fazer nesse caso, talvez retornar um builder que sempre retorna uma query vazia
            return $builder->where('id', '<', 0); // Isso retornará uma query vazia
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
