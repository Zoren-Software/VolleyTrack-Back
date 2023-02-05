<?php

namespace App\GraphQL\Queries;

use App\Models\ConfirmationTraining;
use App\Models\Training;

class ConfirmationTrainingQuery
{
    /**
     * @codeCoverageIgnore
     *
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $confirmationTraining = new ConfirmationTraining();

        return $confirmationTraining->list($args);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param  Training  $training
     */
    public function metrics(Training $training)
    {
        return $training->metrics();
    }
}
