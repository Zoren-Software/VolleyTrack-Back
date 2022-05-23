<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

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

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $team = Team::find($args['id']);
        $team->name = $args['name'];
        $team->user_id = $args['user_id'];
        $team->save();

        return $team;
    }
}
