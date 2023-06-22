<?php

namespace App\GraphQL\Queries;

use App\Models\Position;

class PositionQuery
{
    /**
     * @codeCoverageIgnore
     *
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $position = new Position();

        return $position->list($args);
    }
}
