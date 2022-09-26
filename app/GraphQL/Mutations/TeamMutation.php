<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TeamMutation
{
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $this->team = $this->team->create($args);

        if (isset($args['player_id']) && $this->team->players()) {
            $this->team->players()->syncWithoutDetaching($args['player_id']);
        }

        return $this->team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->team = $this->team->find($args['id']);
        $this->team->update($args);

        if (isset($args['player_id']) && $this->team->players()) {
            $this->team->players()->syncWithoutDetaching($args['player_id']);
        }

        return $this->team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $teams = [];
        foreach ($args['id'] as $id) {
            $this->team = $this->team->deleteTeam($id);
            $teams[] = $this->team;
        }

        return $teams;
    }
}
