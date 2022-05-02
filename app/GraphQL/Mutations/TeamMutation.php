<?php

namespace App\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Team;

final class TeamMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $team = new Team();
        $team->name = $args['name'];
        $team->user_id = $args['user_id'];
        $team->save();

        return $team;
    }
}
