<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
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
        return $this->makeUser($args);
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        return $this->makeUser($args);
    }

    private function makeUser($args)
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

        if (isset($args['positionId']) && $this->user->positions()) {
            $this->user->positions()->syncWithoutDetaching($args['positionId']);
        }

        if (isset($args['team_id']) && $this->user->teams()) {
            $this->user->teams()->syncWithoutDetaching($args['team_id']);
        }

        $this->user->positions;

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
            $this->user = $this->user->deleteUser($id);
            $users[] = $this->user;
        }

        return $users;
    }
}
