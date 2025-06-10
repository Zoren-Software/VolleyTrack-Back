<?php

namespace App\GraphQL\Queries;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

class TeamQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<Team>
     */
    public function list($_, array $args): Builder
    {
        $team = new Team;

        return $team->list($args);
    }
}
