<?php

namespace App\GraphQL\Queries;

use App\Models\Fundamental;

class FundamentalQuery
{
    /**
     * @param  mixed  $rootValue
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $fundamental = new Fundamental;

        return $fundamental->list($args);
    }
}
