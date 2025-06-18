<?php

namespace App\GraphQL\Queries;

use App\Models\ScoutFundamentalTraining;
use Illuminate\Database\Eloquent\Builder;

class ScoutFundamentalTrainingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<ScoutFundamentalTraining>
     */
    public function list($_, array $args): Builder
    {
        $scoutFundamentalTraining = new ScoutFundamentalTraining;

        return $scoutFundamentalTraining->list($args);
    }
}
