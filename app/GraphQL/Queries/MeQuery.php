<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class MeQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     */
    public function me($_, array $args): User
    {
        return User::query()->me()->firstOrFail();
    }
}
