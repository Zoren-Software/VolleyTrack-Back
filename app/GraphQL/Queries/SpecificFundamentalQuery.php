<?php

namespace App\GraphQL\Queries;

use App\Models\SpecificFundamental;

class SpecificFundamentalQuery
{
    /**
     * @codeCoverageIgnore
     *
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $specificFundamental = new SpecificFundamental();

        return $specificFundamental->list($args);
    }
}
