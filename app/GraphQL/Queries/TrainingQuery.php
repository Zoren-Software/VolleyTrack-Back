<?php

namespace App\GraphQL\Queries;

use App\Models\Training;
use Illuminate\Database\Eloquent\Builder;

class TrainingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<Training>
     */
    public function list($_, array $args): Builder
    {
        $training = new Training;

        return $training->list($args);
    }
}
