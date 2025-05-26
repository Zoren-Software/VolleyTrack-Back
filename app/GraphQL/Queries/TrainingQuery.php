<?php

namespace App\GraphQL\Queries;

use App\Models\Training;

class TrainingQuery
{
    /**
     * @param  mixed  $rootValue
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $training = new Training;

        return $training->list($args);
    }
}
