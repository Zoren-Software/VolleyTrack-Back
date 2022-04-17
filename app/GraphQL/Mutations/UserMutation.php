<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        if (strlen($args['password']) < 6) {
            throw new \Exception('Password must be at least 6 characters');
        }

        $args['password'] = Hash::make($args['password']);

        $user = \App\Models\User::create($args);

        return $user;
    }
}
