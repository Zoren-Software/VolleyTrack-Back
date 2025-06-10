<?php

namespace App\GraphQL\Queries;

use App\Models\TeamLevel;
use Illuminate\Database\Eloquent\Builder;

class TeamLevelQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<TeamLevel>
     */
    public function list($_, array $args): Builder
    {
        $teamLevel = new TeamLevel;

        return $teamLevel->list($args);
    }
}
