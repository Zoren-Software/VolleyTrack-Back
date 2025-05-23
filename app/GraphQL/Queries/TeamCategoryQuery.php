<?php

namespace App\GraphQL\Queries;

use App\Models\TeamCategory;

class TeamCategoryQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $teamCategory = new TeamCategory;

        return $teamCategory->list($args);
    }
}
