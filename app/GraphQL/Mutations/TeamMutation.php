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
        $this->team->name = $args['name'];
        $this->team->user_id = $args['user_id'];
        $this->team->save();

        return $this->team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->team->find($args['id']);
        $this->team->name = $args['name'];
        $this->team->user_id = $args['user_id'];
        $this->team->save();

        return $this->team;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        foreach ($args['id'] as $id) {
            $this->team->find($id);
            $this->team->delete();
        }
    }
}
