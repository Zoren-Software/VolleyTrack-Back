<?php

namespace App\GraphQL\Mutations;

use App\Models\ConfirmationTraining;

final class ConfirmationTrainingMutation
{
    private ConfirmationTraining $confirmationTraining;

    public function __construct(ConfirmationTraining $confirmationTraining)
    {
        $this->confirmationTraining = $confirmationTraining;
    }
}
