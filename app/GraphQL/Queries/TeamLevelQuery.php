<?php

namespace App\GraphQL\Queries;

use App\Models\TeamLevel;

class TeamLevelQuery
{
    /**
     * @param  mixed  $rootValue
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $teamLevel = new TeamLevel;

        return $teamLevel->list($args);
    }
}
