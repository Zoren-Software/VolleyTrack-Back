<?php

namespace App\GraphQL\Queries;

use App\Models\Fundamental;

class FundamentalQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $fundamental = new Fundamental();

        return $fundamental->list($args);
    }
}
