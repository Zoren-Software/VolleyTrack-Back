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
     * @return Builder<User>
     */
    public function me($_, array $args): Builder
    {
        return User::query()->me();
    }
}
