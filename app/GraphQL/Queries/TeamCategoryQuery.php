<?php

namespace App\GraphQL\Queries;

use App\Models\TeamCategory;
use Illuminate\Database\Eloquent\Builder;

class TeamCategoryQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * 
     * @return Builder<TeamCategory>
     */
    public function list($_, array $args): Builder
    {
        $teamCategory = new TeamCategory;

        return $teamCategory->list($args);
    }
}
