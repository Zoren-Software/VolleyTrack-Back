<?php

namespace App\GraphQL\Queries;

use App\Models\Fundamental;
use Illuminate\Database\Eloquent\Builder;

class FundamentalQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<Fundamental>
     */
    public function list($_, array $args): Builder
    {
        $fundamental = new Fundamental;

        return $fundamental->list($args);
    }
}
