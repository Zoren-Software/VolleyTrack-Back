<?php

namespace App\GraphQL\Queries;

use App\Models\Team;

class TeamQuery
{
    /**
     * @param  mixed  $rootValue
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $team = new Team;

        return $team->list($args);
    }
}
