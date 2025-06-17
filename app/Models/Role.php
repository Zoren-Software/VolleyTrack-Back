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
     */
    protected static function booted(): void
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            $user = auth()->user();

            /** @var User|null $user */
            if ($user === null) {
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
     * @param  array<string, mixed>  $args
     * @return Builder<Role>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param  Builder<Role>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) &&
            array_key_exists('ignoreIds', $filter) &&
            is_array($filter['ignoreIds']),
            function (Builder $query) use ($filter): void {
                assert(isset($filter['ignoreIds']) && is_array($filter['ignoreIds']));
                $query->whereNotIn('roles.id', $filter['ignoreIds']);
            }
        );
    }

    /**
     * @param  Builder<Role>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        if (is_array($filter) && isset($filter['search']) && is_string($filter['search'])) {
            $query->filterName($filter['search']);
        }
    }

    /**
     * @param  Builder<Role>  $query
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
            get: function (mixed $value, array $attributes): string {
                return trans('RoleRegister.' . (is_scalar($value) ? (string) $value : 'unknown'));
            }
        );
    }
}
