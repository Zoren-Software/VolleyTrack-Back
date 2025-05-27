<?php

namespace App\GraphQL\Queries;

use App\Models\Training;

class TrainingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $training = new Training;

        return $training->list($args);
    }
}
