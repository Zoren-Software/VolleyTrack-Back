<?php

namespace App\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Hash;

class UserMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $args["password"] = Hash::make($args["password"]);

        $user = \App\Models\User::create($args);

        return $user;
    }
}
