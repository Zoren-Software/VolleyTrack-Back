<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class UserMutation
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        if (isset($args['id'])) {
            $this->user = $this->user->findOrFail($args['id']);
        }

        $this->user->name = $args['name'];
        $this->user->email = $args['email'];

        if (isset($args['password'])) {
            $this->user->makePassword($args['password']);
        }

        $this->user->save();

        $this->user->roles()->syncWithoutDetaching($args['roleId']);

        if (isset($args['positionId'])) {
            $this->user->positions()->syncWithoutDetaching($args['positionId']);
        }

        if (isset($args['teamId'])) {
            $this->user->teams()->syncWithoutDetaching($args['teamId']);
        }

        return $this->user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $users = [];
        foreach ($args['id'] as $id) {
            $this->user = $this->user->findOrFail($id);
            $users[] = $this->user;
            $this->user->delete();
        }

        return $users;
    }
}
