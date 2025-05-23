<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class MeQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function me($_, array $args)
    {
        $user = new User();

        return $user->me($args);
    }
}
