<?php

namespace App\GraphQL\Queries;

use App\Models\ConfirmationTraining;

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
}
