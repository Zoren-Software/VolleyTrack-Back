<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class UserMutation
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $this->user->name = $args['name'];
        $this->user->email = $args['email'];
        $this->user->password = $this->makePassword($args['password']);
        //dd($this->user->password);
        $this->user->save();

        //$this->user->roles()->attach($args['roleId']);

        return $this->user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $args['password'] = Hash::make($args['password']);

        $user = User::findOrFail($args['id']);
        $user->update($args);

        $user->roles()->attach($args['roleId']);

        return $user;
    }

    private function makePassword($password)
    {
        return Hash::make($password);
    }
}
