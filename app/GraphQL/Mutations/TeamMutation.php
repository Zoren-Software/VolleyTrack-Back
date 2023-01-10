<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TeamMutation
{
    private $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        if (isset($args['id'])) {
            $this->team = $this->team->find($args['id']);
            $this->team->update($args);
        } else {
            $this->team = $this->team->create($args);
        }

        if (isset($args['player_id']) && count($args['player_id']) > 0) {
            $players = [];
            $technicians = [];

            foreach ($args['player_id'] as $playerId) {
                User::find($playerId)->hasRole('Técnico') ||
                User::find($playerId)->hasRole('Administrador') ||
                User::find($playerId)->hasRole('Suporte')
                    ? $technicians[] = $playerId
                    : $players[] = $playerId ;
            }

            $this->team->players()->syncWithPivotValues($players, ['role' => 'player']);
            $this->team->players()->syncWithPivotValues($technicians, ['role' => 'technician']);
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
