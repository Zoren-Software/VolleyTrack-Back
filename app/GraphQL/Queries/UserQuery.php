<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class UserQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $user = new User;

        return $user->list($args);
    }
}
