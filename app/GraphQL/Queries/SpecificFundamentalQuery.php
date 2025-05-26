<?php

namespace App\GraphQL\Queries;

use App\Models\SpecificFundamental;

class SpecificFundamentalQuery
{
    /**
     * @param  mixed  $rootValue
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $specificFundamental = new SpecificFundamental;

        return $specificFundamental->list($args);
    }
}
