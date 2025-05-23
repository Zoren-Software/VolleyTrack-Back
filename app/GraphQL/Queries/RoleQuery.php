<?php

namespace App\GraphQL\Queries;

use App\Models\Role;

class RoleQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $role = new Role;

        return $role->list($args);
    }
}
