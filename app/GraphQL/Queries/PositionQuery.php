<?php

namespace App\GraphQL\Queries;

use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

class PositionQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * 
     * @return Builder<Position>
     */
    public function list($_, array $args): Builder
    {
        $position = new Position;

        return $position->list($args);
    }
}
