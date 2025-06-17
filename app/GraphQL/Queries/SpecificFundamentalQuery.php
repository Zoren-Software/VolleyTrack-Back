<?php

namespace App\GraphQL\Queries;

use App\Models\SpecificFundamental;
use Illuminate\Database\Eloquent\Builder;

class SpecificFundamentalQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<SpecificFundamental>
     */
    public function list($_, array $args): Builder
    {
        $specificFundamental = new SpecificFundamental;

        return $specificFundamental->list($args);
    }
}
