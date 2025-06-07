<?php

namespace App\GraphQL\Queries;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class RoleQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<Role>
     */
    public function list($_, array $args): Builder
    {
        $role = new Role;

        return $role->list($args);
    }
}
