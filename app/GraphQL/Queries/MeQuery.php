<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class MeQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     *
     * @return User
     */
    public function me($_, array $args): User
    {
        return User::query()->me()->firstOrFail();
    }
}
