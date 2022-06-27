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
        $args['password'] = Hash::make($args['password']);

        $user = \App\Models\User::create($args);

        // TODO - Adicionar permissão ao usuário criado

        return $user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $args['password'] = Hash::make($args['password']);

        $user = \App\Models\User::findOrFail($args['id']);
        $user->update($args);

        return $user;
    }
}
