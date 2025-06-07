<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<User>
     */
    public function list($_, array $args): Builder
    {
        $user = new User;

        return $user->list($args);
    }
}
