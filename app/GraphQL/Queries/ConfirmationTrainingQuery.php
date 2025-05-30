<?php

namespace App\GraphQL\Queries;

use App\Models\ConfirmationTraining;
use App\Models\Training;
use Illuminate\Database\Eloquent\Builder;

class ConfirmationTrainingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * 
     * @return Builder<ConfirmationTraining>
     */
    public function list($_, array $args): Builder
    {
        $confirmationTraining = new ConfirmationTraining;

        return $confirmationTraining->list($args);
    }

    /**
     * @param Training $training
     * 
     * @return array<string, mixed>
     */
    public function metrics(Training $training): array
    {
        return $training->metrics();
    }
}
